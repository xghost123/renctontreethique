<?php

namespace App\Http\Controllers;

use App\Models\Biodata;
use App\Models\Mosque;
use App\Models\MosqueMembership;
use App\Models\MosqueProposal;
use App\Models\MosqueJoinRequest;
use App\Models\User;
use App\Models\Verification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminController extends Controller
{
    /**
     * Dashboard metrics
     */
    public function dashboard()
    {
        $totalUsers = User::count();
        $totalMosques = Mosque::count();
        $activeMosques = Mosque::where('status', 'active')->count();
        $approvedMembers = MosqueMembership::where('status', 'approved')->count();
        $pendingMemberships = MosqueMembership::where('status', 'pending')->count();
        $joinRequests = MosqueJoinRequest::where('status', 'pending')->count();
        $proposals = MosqueProposal::count();
        $pendingProposals = MosqueProposal::where('status', 'pending')->count();
        $acceptedProposals = MosqueProposal::where('status', 'accepted')->count();
        $biodatas = Biodata::count();
        $approvedBiodatas = Biodata::where('is_approved', 1)->count();
        $men = Biodata::where('gender', 'male')->count();
        $women = Biodata::where('gender', 'female')->count();
        $verifications = Verification::count();
        $pendingVerifications = Verification::where('status', 'PENDING')->count();
        $paidMembers = Biodata::where('plan', 'paid')->count();

        // Users per role
        $roles = User::select('role', DB::raw('count(*) as total'))
            ->groupBy('role')->pluck('total', 'role')->toArray();

        // New users last 7 days
        $newUsers7d = User::where('created_at', '>=', now()->subDays(7))->count();
        $newUsers30d = User::where('created_at', '>=', now()->subDays(30))->count();

        // Proposals per status
        $proposalStatuses = MosqueProposal::select('status', DB::raw('count(*) as total'))
            ->groupBy('status')->pluck('total', 'status')->toArray();

        // Mosques per country
        $mosquesByCountry = Mosque::select('country', DB::raw('count(*) as total'))
            ->whereNotNull('country')->groupBy('country')->pluck('total', 'country')->toArray();

        // Recent activity
        $recentUsers = User::latest()->take(5)->get(['id', 'name', 'email', 'created_at']);
        $recentJoinRequests = MosqueJoinRequest::with(['user', 'mosque'])
            ->latest()->take(5)->get();
        $recentProposals = MosqueProposal::with(['sender', 'receiver', 'mosque'])
            ->latest()->take(5)->get();

        return response()->json([
            'metrics' => [
                'total_users' => $totalUsers,
                'total_mosques' => $totalMosques,
                'active_mosques' => $activeMosques,
                'approved_members' => $approvedMembers,
                'pending_memberships' => $pendingMemberships,
                'join_requests' => $joinRequests,
                'proposals' => $proposals,
                'pending_proposals' => $pendingProposals,
                'accepted_proposals' => $acceptedProposals,
                'biodatas' => $biodatas,
                'approved_biodatas' => $approvedBiodatas,
                'men' => $men,
                'women' => $women,
                'verifications' => $verifications,
                'pending_verifications' => $pendingVerifications,
                'paid_members' => $paidMembers,
                'new_users_7d' => $newUsers7d,
                'new_users_30d' => $newUsers30d,
                'conversations' => \App\Models\Conversation::count(),
                'pending_messages' => \App\Models\Message::where('status', 'pending')->count(),
            ],
            'roles' => $roles,
            'proposal_statuses' => $proposalStatuses,
            'mosques_by_country' => $mosquesByCountry,
            'recent' => [
                'users' => $recentUsers,
                'join_requests' => $recentJoinRequests,
                'proposals' => $recentProposals,
            ],
        ]);
    }

    /**
     * Users list (with biodata + membership info)
     */
    public function users(Request $request)
    {
        $query = User::with(['biodata', 'mosqueMemberships.mosque'])
            ->withCount('mosqueMemberships');

        // Filters
        if ($request->has('search') && $request->search) {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'like', "%{$request->search}%")
                    ->orWhere('email', 'like', "%{$request->search}%")
                    ->orWhere('mobile', 'like', "%{$request->search}%");
            });
        }
        if ($request->has('role') && $request->role && $request->role !== 'all') {
            $query->where('role', $request->role);
        }
        if ($request->has('status') && $request->status) {
            if ($request->status === 'approved') {
                $query->whereHas('biodata', fn ($q) => $q->where('is_approved', 1));
            } elseif ($request->status === 'pending') {
                $query->whereHas('biodata', fn ($q) => $q->where('is_approved', 0));
            } elseif ($request->status === 'no_biodata') {
                $query->whereDoesntHave('biodata');
            }
        }

        $users = $query->orderByDesc('created_at')->paginate(20)->through(function ($u) {
            $b = $u->biodata;
            $mosque = $u->mosqueMemberships->firstWhere('status', 'approved');
            return [
                'id' => $u->id,
                'name' => $u->name,
                'email' => $u->email,
                'mobile' => $u->mobile,
                'role' => $u->role,
                'created_at' => $u->created_at,
                'biodata' => $b ? [
                    'id' => $b->id,
                    'gender' => $b->gender,
                    'age' => $b->age,
                    'maritial_status' => $b->maritial_status,
                    'is_approved' => (bool) $b->is_approved,
                    'biodata_code' => $b->biodata_code,
                    'plan' => $b->plan ?? null,
                ] : null,
                'mosque' => $mosque ? [
                    'id' => $mosque->mosque_id,
                    'name' => $mosque->mosque->name ?? null,
                    'role' => $mosque->role,
                ] : null,
                'memberships_count' => $u->mosque_memberships_count,
            ];
        });

        return response()->json($users);
    }

    /**
     * Approve / reject a user's biodata (candidate acceptance)
     */
    public function setBiodataStatus(Request $request)
    {
        $request->validate([
            'user_id' => 'required|integer|exists:users,id',
            'status' => 'required|in:approved,rejected',
        ]);

        $biodata = Biodata::where('user_id', $request->user_id)->first();
        if (!$biodata) {
            return response()->json(['error' => 'Aucun profil trouvé pour cet utilisateur.'], 404);
        }

        $biodata->update([
            'is_approved' => $request->status === 'approved' ? 1 : 0,
            'approved_date' => $request->status === 'approved' ? now() : null,
            'pending_approve' => 0,
        ]);

        return response()->json(['success' => true, 'status' => $request->status]);
    }

    /**
     * Approve / reject a mosque join request
     */
    public function decideJoinRequest(Request $request)
    {
        $request->validate([
            'join_request_id' => 'required|integer|exists:mosque_join_requests,id',
            'decision' => 'required|in:approved,rejected',
        ]);

        $join = MosqueJoinRequest::findOrFail($request->join_request_id);

        $join->update([
            'status' => $request->decision,
            'reviewed_by' => auth()->id(),
            'reviewed_at' => now(),
        ]);

        if ($request->decision === 'approved') {
            MosqueMembership::updateOrCreate(
                ['mosque_id' => $join->mosque_id, 'user_id' => $join->user_id],
                ['role' => 'member', 'status' => 'approved', 'approved_by' => auth()->id(), 'approved_at' => now()]
            );
            Biodata::where('user_id', $join->user_id)->update(['mosque_id' => $join->mosque_id]);
        }

        return response()->json(['success' => true, 'status' => $request->decision]);
    }

    /**
     * Mosque management list
     */
    public function mosques(Request $request)
    {
        $query = Mosque::withCount([
            'memberships as approved_count' => fn ($q) => $q->where('status', 'approved'),
            'memberships as pending_count' => fn ($q) => $q->where('status', 'pending'),
            'proposals as proposals_count' => fn ($q) => $q->where('status', 'pending'),
        ]);

        if ($request->has('status') && $request->status && $request->status !== 'all') {
            $query->where('status', $request->status);
        }

        return response()->json($query->orderByDesc('created_at')->paginate(20));
    }

    /**
     * Toggle mosque status (active/suspended)
     */
    public function setMosqueStatus(Request $request)
    {
        $request->validate([
            'mosque_id' => 'required|integer|exists:mosques,id',
            'status' => 'required|in:active,suspended,pending',
        ]);

        $mosque = Mosque::findOrFail($request->mosque_id);
        $mosque->update(['status' => $request->status]);

        return response()->json(['success' => true, 'status' => $request->status]);
    }

    /**
     * Proposals management (all proposals with status filter)
     */
    public function proposals(Request $request)
    {
        $query = MosqueProposal::with(['sender', 'receiver', 'mosque']);

        if ($request->has('status') && $request->status && $request->status !== 'all') {
            $query->where('status', $request->status);
        }

        return response()->json($query->orderByDesc('created_at')->paginate(20));
    }

    /**
     * Pending join requests queue
     */
    public function joinRequests(Request $request)
    {
        $query = MosqueJoinRequest::with(['user', 'mosque']);

        if ($request->has('status') && $request->status && $request->status !== 'all') {
            $query->where('status', $request->status);
        } else {
            $query->where('status', 'pending');
        }

        return response()->json($query->orderByDesc('created_at')->paginate(20));
    }

    /**
     * Make a user moderator/imam/member
     */
    public function setRole(Request $request)
    {
        $request->validate([
            'user_id' => 'required|integer|exists:users,id',
            'role' => 'required|in:member,moderator,admin',
        ]);

        $user = User::findOrFail($request->user_id);
        $user->update(['role' => $request->role]);

        return response()->json(['success' => true, 'role' => $request->role]);
    }

    /**
     * Get pending photos for approval
     * GET /api/admin/photos/pending
     */
    public function pendingPhotos(Request $request)
    {
        try {
            $photos = \App\Models\Photo::pending()
                ->with('user', 'biodata')
                ->orderByDesc('created_at')
                ->paginate(20);

            $formatted = $photos->map(function ($photo) {
                return [
                    'id' => $photo->id,
                    'user_id' => $photo->user_id,
                    'user_name' => $photo->user->name,
                    'biodata_id' => $photo->biodata_id,
                    'path' => asset('storage/' . $photo->path),
                    'created_at' => $photo->created_at,
                    'original_filename' => $photo->original_filename,
                ];
            });

            return response()->json([
                'data' => $formatted,
                'total' => \App\Models\Photo::pending()->count(),
            ]);

        } catch (\Exception $e) {
            \Log::error('Get pending photos error: ' . $e->getMessage());
            return response()->json([
                'message' => 'Error fetching pending photos',
            ], 500);
        }
    }

    /**
     * Approve a photo
     * POST /api/admin/photos/{id}/approve
     */
    public function approvePhoto($photoId, Request $request)
    {
        try {
            $photo = \App\Models\Photo::findOrFail($photoId);
            $admin = auth()->user();

            $photo->update([
                'approved' => true,
                'approved_by' => $admin->id,
                'approved_at' => now(),
            ]);

            return response()->json([
                'message' => 'Photo approved successfully',
                'photo' => [
                    'id' => $photo->id,
                    'approved' => $photo->approved,
                    'approved_at' => $photo->approved_at,
                ],
            ]);

        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json(['message' => 'Photo not found'], 404);
        } catch (\Exception $e) {
            \Log::error('Photo approval error: ' . $e->getMessage());
            return response()->json(['message' => 'Error approving photo'], 500);
        }
    }

    /**
     * Reject/delete a photo
     * POST /api/admin/photos/{id}/reject
     */
    public function rejectPhoto($photoId)
    {
        try {
            $photo = \App\Models\Photo::findOrFail($photoId);

            // Delete file from storage
            if (\Illuminate\Support\Facades\Storage::disk('public')->exists($photo->path)) {
                \Illuminate\Support\Facades\Storage::disk('public')->delete($photo->path);
            }

            // Delete record
            $photo->delete();

            return response()->json([
                'message' => 'Photo rejected and deleted',
            ]);

        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json(['message' => 'Photo not found'], 404);
        } catch (\Exception $e) {
            \Log::error('Photo rejection error: ' . $e->getMessage());
            return response()->json(['message' => 'Error rejecting photo'], 500);
        }
    }
}
