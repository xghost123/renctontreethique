<?php

namespace App\Http\Controllers;

use App\Models\Message;
use App\Models\Conversation;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class MessageController extends Controller
{
    /**
     * Send a message in a conversation
     * POST /api/messages/send
     */
    public function send(Request $request)
    {
        try {
            $validated = $request->validate([
                'conversation_id' => 'required|integer|exists:conversations,id',
                'body' => 'required|string|min:1|max:5000',
                'language' => 'sometimes|string|in:fr,en,ar|default:fr',
            ]);

            $user = Auth::user();
            $conversation = Conversation::findOrFail($validated['conversation_id']);

            // Verify user is part of this conversation
            if ($conversation->owner_id !== $user->id && $conversation->dest_id !== $user->id) {
                return response()->json([
                    'message' => 'Unauthorized - You are not part of this conversation',
                ], 403);
            }

            // Determine sender and recipient
            $senderId = $user->id;
            $recipientId = $conversation->owner_id === $user->id 
                ? $conversation->dest_id 
                : $conversation->owner_id;

            // Create the message
            $message = Message::create([
                'conversation_id' => $validated['conversation_id'],
                'sender_id' => $senderId,
                'recipient_id' => $recipientId,
                'body' => trim($validated['body']),
                'language' => $validated['language'] ?? 'fr',
                'status' => 'sent', // New messages start as sent, will transition to delivered
            ]);

            // Update conversation's last_message
            $conversation->update([
                'last_message' => $message->body,
            ]);

            return response()->json([
                'message' => 'Message sent successfully',
                'data' => $this->formatMessageResponse($message),
            ], 201);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'message' => 'Validation failed',
                'errors' => $e->errors(),
            ], 422);
        } catch (\Exception $e) {
            \Log::error('Message send error: ' . $e->getMessage());
            return response()->json([
                'message' => 'Error sending message: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Get messages for a conversation (with pagination and polling support)
     * GET /api/messages/conversation/{conversationId}
     */
    public function getConversationMessages($conversationId, Request $request)
    {
        try {
            $conversation = Conversation::findOrFail($conversationId);
            $user = Auth::user();

            // Verify user is part of this conversation
            if ($conversation->owner_id !== $user->id && $conversation->dest_id !== $user->id) {
                return response()->json([
                    'message' => 'Unauthorized - You are not part of this conversation',
                ], 403);
            }

            $limit = $request->query('limit', 50); // Messages to fetch
            $offset = $request->query('offset', 0); // For pagination/polling
            $sinceId = $request->query('since_id'); // For polling - get only newer messages

            $query = Message::forConversation($conversationId);

            // If polling with since_id, only get messages after that ID
            if ($sinceId) {
                $query = $query->where('id', '>', $sinceId);
            }

            $messages = $query->with(['sender', 'recipient'])
                ->limit($limit)
                ->offset($offset)
                ->get()
                ->map(fn($msg) => $this->formatMessageResponse($msg, $user->id));

            // Mark all recipient's unread messages as delivered
            Message::where('conversation_id', $conversationId)
                ->where('recipient_id', $user->id)
                ->where('status', '!=', 'read')
                ->update(['status' => 'delivered']);

            return response()->json([
                'conversation_id' => $conversationId,
                'messages' => $messages,
                'count' => count($messages),
                'total' => Message::forConversation($conversationId)->count(),
            ]);

        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'message' => 'Conversation not found',
            ], 404);
        } catch (\Exception $e) {
            \Log::error('Get messages error: ' . $e->getMessage());
            return response()->json([
                'message' => 'Error fetching messages',
            ], 500);
        }
    }

    /**
     * Mark messages as read
     * POST /api/messages/mark-read
     */
    public function markAsRead(Request $request)
    {
        try {
            $validated = $request->validate([
                'message_ids' => 'required|array|min:1',
                'message_ids.*' => 'integer|exists:messages,id',
            ]);

            $user = Auth::user();

            // Update only messages where user is the recipient
            $updated = Message::whereIn('id', $validated['message_ids'])
                ->where('recipient_id', $user->id)
                ->where('status', '!=', 'read')
                ->update([
                    'status' => 'read',
                    'read_at' => now(),
                ]);

            return response()->json([
                'message' => 'Messages marked as read',
                'updated_count' => $updated,
            ]);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'message' => 'Validation failed',
                'errors' => $e->errors(),
            ], 422);
        } catch (\Exception $e) {
            \Log::error('Mark read error: ' . $e->getMessage());
            return response()->json([
                'message' => 'Error marking messages as read',
            ], 500);
        }
    }

    /**
     * Get unread message count for a user
     * GET /api/messages/unread-count
     */
    public function getUnreadCount()
    {
        try {
            $user = Auth::user();
            $unreadCount = Message::where('recipient_id', $user->id)
                ->where('status', '!=', 'read')
                ->count();

            return response()->json([
                'unread_count' => $unreadCount,
            ]);

        } catch (\Exception $e) {
            \Log::error('Get unread count error: ' . $e->getMessage());
            return response()->json([
                'message' => 'Error fetching unread count',
            ], 500);
        }
    }

    /**
     * Poll for new messages (for real-time updates without WebSocket)
     * GET /api/messages/poll/{conversationId}
     * 
     * Returns only messages created/updated after the last poll
     * Client calls this repeatedly to check for new messages
     */
    public function poll($conversationId, Request $request)
    {
        try {
            $conversation = Conversation::findOrFail($conversationId);
            $user = Auth::user();

            // Verify user is part of this conversation
            if ($conversation->owner_id !== $user->id && $conversation->dest_id !== $user->id) {
                return response()->json([
                    'message' => 'Unauthorized',
                ], 403);
            }

            $sinceTimestamp = $request->query('since'); // ISO 8601 timestamp of last poll
            $query = Message::forConversation($conversationId);

            if ($sinceTimestamp) {
                $query = $query->where('created_at', '>', $sinceTimestamp);
            }

            $messages = $query->with(['sender', 'recipient'])
                ->orderBy('created_at', 'asc')
                ->get()
                ->map(fn($msg) => $this->formatMessageResponse($msg, $user->id));

            // Auto-mark received messages as delivered
            Message::where('conversation_id', $conversationId)
                ->where('recipient_id', $user->id)
                ->where('status', 'sent')
                ->update(['status' => 'delivered']);

            return response()->json([
                'messages' => $messages,
                'timestamp' => now()->toIso8601String(),
            ]);

        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json(['message' => 'Conversation not found'], 404);
        } catch (\Exception $e) {
            \Log::error('Poll error: ' . $e->getMessage());
            return response()->json(['message' => 'Error polling messages'], 500);
        }
    }

    /**
     * Format message response with sender info and read receipts
     */
    private function formatMessageResponse($message, $currentUserId = null)
    {
        $currentUserId = $currentUserId ?? Auth::id();

        return [
            'id' => $message->id,
            'conversation_id' => $message->conversation_id,
            'sender_id' => $message->sender_id,
            'recipient_id' => $message->recipient_id,
            'body' => $message->body,
            'language' => $message->language,
            'status' => $message->status,
            'is_from_me' => $message->sender_id === $currentUserId,
            'sender' => [
                'id' => $message->sender->id,
                'name' => $message->sender->name,
            ],
            'recipient' => [
                'id' => $message->recipient->id,
                'name' => $message->recipient->name,
            ],
            'read_at' => $message->read_at?->toIso8601String(),
            'created_at' => $message->created_at->toIso8601String(),
            'updated_at' => $message->updated_at->toIso8601String(),
            'readable_status' => $message->getReadableStatus(),
        ];
    }

    /**
     * Delete a message (soft delete - only for user's own messages)
     * DELETE /api/messages/{id}
     */
    public function delete($id)
    {
        try {
            $message = Message::findOrFail($id);
            $user = Auth::user();

            // Only the sender can delete their own message
            if ($message->sender_id !== $user->id) {
                return response()->json([
                    'message' => 'Unauthorized - You can only delete your own messages',
                ], 403);
            }

            $message->delete();

            return response()->json([
                'message' => 'Message deleted successfully',
            ]);

        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'message' => 'Message not found',
            ], 404);
        } catch (\Exception $e) {
            \Log::error('Message delete error: ' . $e->getMessage());
            return response()->json([
                'message' => 'Error deleting message',
            ], 500);
        }
    }

    /**
     * Flag a message for moderation (by sender or admin)
     * POST /api/messages/{id}/flag
     */
    public function flag($id, Request $request)
    {
        try {
            $message = Message::findOrFail($id);
            $user = Auth::user();

            $validated = $request->validate([
                'reason' => 'sometimes|string|max:500',
            ]);

            // Only sender, recipient, or admin can flag
            $canFlag = $message->sender_id === $user->id || 
                       $message->recipient_id === $user->id || 
                       $user->role === 'admin';

            if (!$canFlag) {
                return response()->json([
                    'message' => 'Unauthorized - Cannot flag this message',
                ], 403);
            }

            $message->flag($validated['reason'] ?? null);

            return response()->json([
                'message' => 'Message flagged for moderation',
            ]);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'message' => 'Validation failed',
                'errors' => $e->errors(),
            ], 422);
        } catch (\Exception $e) {
            \Log::error('Flag message error: ' . $e->getMessage());
            return response()->json([
                'message' => 'Error flagging message',
            ], 500);
        }
    }
}
