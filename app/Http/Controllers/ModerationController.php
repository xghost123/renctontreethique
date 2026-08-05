<?php

namespace App\Http\Controllers;

use App\Models\Conversation;
use App\Models\Message;
use App\Models\MosqueMembership;
use Illuminate\Http\Request;

class ModerationController extends Controller
{
    /**
     * Pending messages queue (admin sees all; imam/moderator sees own mosque's)
     */
    public function queue(Request $request)
    {
        $user = $request->user();

        $query = Message::where('status', 'pending')
            ->with(['conversation.owner', 'conversation.dest', 'owner', 'dest']);

        // Imam/moderator: only messages in their mosque's conversations
        if ($user->role === 'moderator') {
            $mosqueIds = MosqueMembership::where('user_id', $user->id)
                ->whereIn('role', ['moderator', 'imam'])->where('status', 'approved')
                ->pluck('mosque_id');
            $convIds = Conversation::whereIn('mosque_id', $mosqueIds)->pluck('id');
            $query->whereIn('conversation_id', $convIds);
        }

        $pending = $query->orderBy('created_at')->paginate(20)->through(function (Message $m) {
            $conv = $m->conversation;
            $mosqueId = $conv->mosque_id ?? null;
            $mosqueName = $mosqueId ? (\App\Models\Mosque::find($mosqueId)?->name) : null;
            return [
                'id' => $m->id,
                'message' => $m->message,
                'sender' => $m->owner ? ['id' => $m->owner->id, 'name' => $m->owner->name] : null,
                'recipient' => $m->dest ? ['id' => $m->dest->id, 'name' => $m->dest->name] : null,
                'mosque' => $mosqueName,
                'created_at' => $m->created_at,
            ];
        });

        return response()->json([
            'pending' => $pending,
            'count' => Message::where('status', 'pending')->count(),
        ]);
    }

    /**
     * Approve a message → delivered to recipient
     */
    public function approve(Request $request)
    {
        $request->validate(['message_id' => 'required|integer|exists:messages,id']);
        $message = Message::findOrFail($request->message_id);

        if ($message->status !== 'pending') {
            return response()->json(['error' => 'Ce message n\'est pas en attente.'], 422);
        }

        $message->update([
            'status' => 'delivered',
            'moderated_by' => auth()->id(),
            'moderated_at' => now(),
        ]);

        return response()->json(['success' => true, 'status' => 'delivered']);
    }

    /**
     * Reject a message → sender is told + reason
     */
    public function reject(Request $request)
    {
        $request->validate([
            'message_id' => 'required|integer|exists:messages,id',
            'reason' => 'nullable|string|max:500',
        ]);
        $message = Message::findOrFail($request->message_id);

        if ($message->status !== 'pending') {
            return response()->json(['error' => 'Ce message n\'est pas en attente.'], 422);
        }

        $message->update([
            'status' => 'rejected',
            'rejected_reason' => $request->reason ?: 'Message refusé par un modérateur.',
            'moderated_by' => auth()->id(),
            'moderated_at' => now(),
        ]);

        return response()->json(['success' => true, 'status' => 'rejected']);
    }
}
