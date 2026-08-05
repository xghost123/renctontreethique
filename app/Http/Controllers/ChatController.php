<?php

namespace App\Http\Controllers;

use App\Models\Biodata;
use App\Models\Conversation;
use App\Models\Message;
use App\Models\MosqueMembership;
use Illuminate\Http\Request;

class ChatController extends Controller
{
    /**
     * List my conversations (only same-mosque members, both approved profiles)
     */
    public function conversations(Request $request)
    {
        $user = $request->user();
        $myMosque = MosqueMembership::approvedMosqueId($user->id);

        $conversations = Conversation::where(function ($q) use ($user) {
            $q->where('owner_id', $user->id)->orWhere('dest_id', $user->id);
        })->with(['owner', 'dest'])->orderByDesc('updated_at')->get()->map(function ($c) use ($user) {
            $other = $c->owner_id === $user->id ? $c->dest : $c->owner;
            $last = Message::where('conversation_id', $c->id)->orderByDesc('created_at')->first();
            $unread = Message::where('conversation_id', $c->id)
                ->where('dest_id', $user->id)->where('status', 'delivered')->whereNull('read_at')->count();
            return [
                'id' => $c->id,
                'other_name' => $other->name ?? null,
                'other_id' => $other->id ?? null,
                'last_message' => $last ? mb_substr($last->message, 0, 60) : null,
                'last_status' => $last->status ?? null,
                'unread' => $unread,
                'updated_at' => $c->updated_at,
            ];
        });

        return response()->json(['conversations' => $conversations]);
    }

    /**
     * Open a conversation with a same-mosque member (or create one)
     */
    public function open(Request $request)
    {
        $request->validate(['other_id' => 'required|integer|exists:users,id']);
        $user = $request->user();
        $otherId = (int) $request->other_id;

        // Same-mosque isolation check
        $myMosque = MosqueMembership::approvedMosqueId($user->id);
        $otherMosque = MosqueMembership::approvedMosqueId($otherId);
        if (!$myMosque || $myMosque !== $otherMosque) {
            return response()->json(['error' => 'Vous ne pouvez échanger qu\'avec les membres de votre mosquée.'], 403);
        }

        $conversation = Conversation::where(function ($q) use ($user, $otherId) {
            $q->where('owner_id', $user->id)->where('dest_id', $otherId);
        })->orWhere(function ($q) use ($user, $otherId) {
            $q->where('owner_id', $otherId)->where('dest_id', $user->id);
        })->first();

        if (!$conversation) {
            $conversation = Conversation::create([
                'owner_id' => $user->id,
                'dest_id' => $otherId,
                'owner_name' => $user->name,
                'dest_name' => \App\Models\User::find($otherId)?->name,
                'status' => 'active',
            ]);
        }

        return response()->json(['conversation' => $conversation]);
    }

    /**
     * Messages of a conversation (visible to the two members)
     */
    public function messages(Request $request, int $conversationId)
    {
        $user = $request->user();
        $conversation = Conversation::findOrFail($conversationId);

        // Authorization: only the two members
        if (!in_array($user->id, [$conversation->owner_id, $conversation->dest_id])) {
            return response()->json(['error' => 'Accès refusé.'], 403);
        }

        // MODERATION: recipient only sees delivered messages;
        // sender sees their own pending/rejected ones
        $messages = Message::where('conversation_id', $conversationId)
            ->where(function ($q) use ($user) {
                $q->where('status', 'delivered')
                  ->orWhere('owner_id', $user->id); // sender sees own pending/rejected
            })
            ->orderBy('created_at')->get()->map(function ($m) use ($user) {
                return [
                    'id' => $m->id,
                    'message' => $m->message,
                    'from_me' => $m->owner_id === $user->id,
                    'status' => $m->status, // pending | delivered | rejected
                    'rejected_reason' => $m->rejected_reason,
                    'created_at' => $m->created_at,
                ];
            });

        // Mark incoming as read
        Message::where('conversation_id', $conversationId)
            ->where('dest_id', $user->id)->where('status', 'delivered')
            ->update(['read_at' => now()]);

        return response()->json(['messages' => $messages]);
    }

    /**
     * Send a message — goes to MODERATION (pending) before delivery.
     * Nothing reaches the recipient until admin/imam approves it.
     */
    public function send(Request $request)
    {
        $request->validate([
            'conversation_id' => 'required|integer|exists:conversations,id',
            'message' => 'required|string|max:2000',
        ]);

        $user = $request->user();
        $conversation = Conversation::findOrFail($request->conversation_id);

        if (!in_array($user->id, [$conversation->owner_id, $conversation->dest_id])) {
            return response()->json(['error' => 'Accès refusé.'], 403);
        }

        $destId = $conversation->owner_id === $user->id ? $conversation->dest_id : $conversation->owner_id;

        $message = Message::create([
            'conversation_id' => $conversation->id,
            'message' => $request->message,
            'owner_id' => $user->id,
            'dest_id' => $destId,
            'langage' => 'fr',
            // MODERATION: always starts pending — needs admin/imam approval to be delivered
            'status' => 'pending',
        ]);

        $conversation->update(['last_message' => mb_substr($request->message, 0, 200), 'updated_at' => now()]);

        return response()->json([
            'success' => true,
            'message' => $message,
            'notice' => 'Votre message est en attente de validation par un modérateur.',
        ], 201);
    }

    /**
     * Whether the current user is a moderator (admin/imam) for a mosque
     */
    public static function isModerator($user, ?int $mosqueId = null): bool
    {
        if (!$user) return false;
        if ($user->role === 'admin') return true;
        if ($user->role !== 'moderator') return false;
        if (!$mosqueId) return true; // moderator of any mosque
        return MosqueMembership::where('user_id', $user->id)
            ->where('mosque_id', $mosqueId)
            ->whereIn('role', ['moderator', 'imam'])
            ->where('status', 'approved')
            ->exists();
    }
}
