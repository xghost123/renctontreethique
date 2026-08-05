<?php

namespace App\Http\Controllers;

use App\Models\Biodata;
use App\Models\Mosque;
use App\Models\MosqueMembership;
use App\Models\MosqueProposal;
use App\Models\MosqueJoinRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class MosqueController extends Controller
{
    /**
     * List mosques (public directory)
     */
    public function index()
    {
        $mosques = Mosque::where('status', 'active')->orderBy('name')->get();
        return response()->json(['mosques' => $mosques]);
    }

    /**
     * Show a mosque + its approved members (strict isolation:
     * only if the requester is an approved member of THIS mosque)
     */
    public function show(Request $request, string $slug)
    {
        $mosque = Mosque::where('slug', $slug)->where('status', 'active')->firstOrFail();
        $user = $request->user();

        $isMember = $user && MosqueMembership::isApprovedMember($user->id, $mosque->id);
        if (!$isMember) {
            return response()->json(['error' => 'Vous devez être membre approuvé de cette mosquée pour voir ses membres.'], 403);
        }

        // STRICT ISOLATION: only same-mosque approved members, opposite gender
        $myBiodata = Biodata::where('user_id', $user->id)->first();
        $oppositeGender = $myBiodata && $myBiodata->gender === 'male' ? 'female' : 'male';

        $members = Biodata::where('mosque_id', $mosque->id)
            ->where('gender', $oppositeGender)
            ->where('is_approved', 1)
            ->where('visible_to_mosque', 1)
            ->where('user_id', '!=', $user->id)
            ->with('user')
            ->get()
            ->map(function ($b) {
                return [
                    'id' => $b->id,
                    'biodata_code' => $b->biodata_code,
                    'age' => $b->age,
                    'maritial_status' => $b->maritial_status,
                    'permanent_country' => $b->permanent_country,
                    'height' => $b->height,
                    'madhab' => $b->madhab,
                    'prayer_level' => $b->prayer_level,
                    'bio' => Str::limit($b->bio, 200),
                    'photo_blurred' => $b->photo_blurred,
                ];
            });

        return response()->json([
            'mosque' => $mosque,
            'is_member' => true,
            'members' => $members,
        ]);
    }

    /**
     * Request to join a mosque (pending → imam approval)
     */
    public function join(Request $request)
    {
        $request->validate([
            'mosque_id' => 'required|exists:mosques,id',
            'note' => 'nullable|string|max:500',
        ]);

        $user = $request->user();

        // Already a member?
        if (MosqueMembership::isApprovedMember($user->id, $request->mosque_id)) {
            return response()->json(['error' => 'Vous êtes déjà membre de cette mosquée.'], 422);
        }

        // Already requested?
        $existing = MosqueJoinRequest::where('user_id', $user->id)
            ->where('mosque_id', $request->mosque_id)
            ->where('status', 'pending')
            ->first();
        if ($existing) {
            return response()->json(['error' => 'Demande déjà en attente.'], 422);
        }

        $join = MosqueJoinRequest::create([
            'mosque_id' => $request->mosque_id,
            'user_id' => $user->id,
            'status' => 'pending',
            'note' => $request->note,
        ]);

        return response()->json(['success' => true, 'request' => $join], 201);
    }

    /**
     * Imam/moderator: approve a join request → creates approved membership
     */
    public function approveJoin(Request $request, int $joinRequestId)
    {
        $join = MosqueJoinRequest::findOrFail($joinRequestId);
        $user = $request->user();

        // Only imam/moderator of that mosque can approve
        $isMod = MosqueMembership::where('user_id', $user->id)
            ->where('mosque_id', $join->mosque_id)
            ->whereIn('role', ['moderator', 'imam'])
            ->where('status', 'approved')
            ->exists();
        if (!$isMod) {
            return response()->json(['error' => 'Seul un imam/modérateur peut approuver.'], 403);
        }

        $join->update(['status' => 'approved', 'reviewed_by' => $user->id, 'reviewed_at' => now()]);

        MosqueMembership::updateOrCreate(
            ['mosque_id' => $join->mosque_id, 'user_id' => $join->user_id],
            ['role' => 'member', 'status' => 'approved', 'approved_by' => $user->id, 'approved_at' => now()]
        );

        // Link the user's biodata to the mosque (enables visibility)
        Biodata::where('user_id', $join->user_id)->update(['mosque_id' => $join->mosque_id]);

        return response()->json(['success' => true]);
    }

    /**
     * Imam/moderator: reject a join request
     */
    public function rejectJoin(Request $request, int $joinRequestId)
    {
        $join = MosqueJoinRequest::findOrFail($joinRequestId);
        $user = $request->user();

        $isMod = MosqueMembership::where('user_id', $user->id)
            ->where('mosque_id', $join->mosque_id)
            ->whereIn('role', ['moderator', 'imam'])
            ->where('status', 'approved')
            ->exists();
        if (!$isMod) {
            return response()->json(['error' => 'Seul un imam/modérateur peut rejeter.'], 403);
        }

        $join->update(['status' => 'rejected', 'reviewed_by' => $user->id, 'reviewed_at' => now()]);
        return response()->json(['success' => true]);
    }

    /**
     * Brother sends a proposal to a sister of the SAME mosque
     */
    public function propose(Request $request)
    {
        $request->validate([
            'receiver_id' => 'required|exists:users,id',
            'message' => 'nullable|string|max:1000',
        ]);

        $sender = $request->user();
        $receiverId = $request->receiver_id;

        // 1. Both must be approved members of the SAME mosque
        $senderMosque = MosqueMembership::approvedMosqueId($sender->id);
        $receiverMosque = MosqueMembership::approvedMosqueId($receiverId);
        if (!$senderMosque || $senderMosque !== $receiverMosque) {
            return response()->json(['error' => 'Vous devez être membres de la même mosquée.'], 403);
        }

        // 2. Sender must be male (men initiate)
        $senderBiodata = Biodata::where('user_id', $sender->id)->first();
        $receiverBiodata = Biodata::where('user_id', $receiverId)->first();
        if (!$senderBiodata || $senderBiodata->gender !== 'male') {
            return response()->json(['error' => 'Seuls les hommes peuvent initier une demande.'], 403);
        }
        if (!$receiverBiodata || $receiverBiodata->gender !== 'female') {
            return response()->json(['error' => 'Cible invalide.'], 422);
        }

        // 3. No duplicate active proposal
        if (MosqueProposal::hasActiveProposal($sender->id, $receiverId)) {
            return response()->json(['error' => 'Demande déjà envoyée.'], 422);
        }

        $proposal = MosqueProposal::create([
            'mosque_id' => $senderMosque,
            'sender_id' => $sender->id,
            'receiver_id' => $receiverId,
            'status' => 'pending',
            'message' => $request->message,
        ]);

        return response()->json(['success' => true, 'proposal' => $proposal], 201);
    }

    /**
     * Sister accepts a proposal
     */
    public function acceptProposal(Request $request, int $proposalId)
    {
        $proposal = MosqueProposal::findOrFail($proposalId);
        $user = $request->user();

        if ($proposal->receiver_id !== $user->id) {
            return response()->json(['error' => 'Non autorisé.'], 403);
        }

        $proposal->update(['status' => 'accepted', 'responded_at' => now()]);
        return response()->json(['success' => true]);
    }

    /**
     * Sister declines a proposal
     */
    public function declineProposal(Request $request, int $proposalId)
    {
        $proposal = MosqueProposal::findOrFail($proposalId);
        $user = $request->user();

        if ($proposal->receiver_id !== $user->id) {
            return response()->json(['error' => 'Non autorisé.'], 403);
        }

        $proposal->update(['status' => 'declined', 'responded_at' => now()]);
        return response()->json(['success' => true]);
    }

    /**
     * My mosque dashboard (what I see)
     */
    public function myMosque(Request $request)
    {
        $user = $request->user();
        $mosqueId = MosqueMembership::approvedMosqueId($user->id);

        if (!$mosqueId) {
            return response()->json(['mosque' => null, 'pending' => true]);
        }

        $mosque = Mosque::find($mosqueId);
        $myBiodata = Biodata::where('user_id', $user->id)->first();
        $oppositeGender = $myBiodata && $myBiodata->gender === 'male' ? 'female' : 'male';

        $members = Biodata::where('mosque_id', $mosqueId)
            ->where('gender', $oppositeGender)
            ->where('is_approved', 1)
            ->where('visible_to_mosque', 1)
            ->where('user_id', '!=', $user->id)
            ->get()
            ->map(fn ($b) => [
                'id' => $b->id,
                'user_id' => $b->user_id,
                'biodata_code' => $b->biodata_code,
                'age' => $b->age,
                'maritial_status' => $b->maritial_status,
                'height' => $b->height,
                'madhab' => $b->madhab,
                'prayer_level' => $b->prayer_level,
                'practice_religion' => $b->practice_religion,
                'permanent_country' => $b->permanent_country,
                'bio' => Str::limit($b->bio, 200),
                'has_pending_proposal' => MosqueProposal::hasActiveProposal($user->id, $b->user_id),
            ]);

        // My pending requests (for sisters) / sent (for brothers)
        $incoming = MosqueProposal::where('receiver_id', $user->id)->where('status', 'pending')->with('sender')->get();
        $sent = MosqueProposal::where('sender_id', $user->id)->where('status', 'pending')->with('receiver')->get();

        // Pending join requests (for moderators/imam)
        $isMod = MosqueMembership::where('user_id', $user->id)
            ->where('mosque_id', $mosqueId)
            ->whereIn('role', ['moderator', 'imam'])
            ->where('status', 'approved')
            ->exists();
        $joinRequests = $isMod
            ? MosqueJoinRequest::where('mosque_id', $mosqueId)->where('status', 'pending')->with('user')->get()
            : [];

        return response()->json([
            'mosque' => $mosque,
            'members' => $members,
            'incoming_proposals' => $incoming,
            'sent_proposals' => $sent,
            'join_requests' => $joinRequests,
            'is_moderator' => $isMod,
        ]);
    }
}
