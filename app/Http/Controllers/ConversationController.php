<?php

namespace App\Http\Controllers;

use App\Models\Conversation;
use App\Models\Message;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ConversationController extends Controller
{
    /**
     * Get all conversations for the current user
     * GET /api/conversations
     */
    public function index(Request $request)
    {
        try {
            $user = Auth::user();
            
            $conversations = Conversation::where(function ($query) use ($user) {
                $query->where('owner_id', $user->id)
                      ->orWhere('dest_id', $user->id);
            })
            ->with(['owner', 'dest', 'messages'])
            ->orderBy('updated_at', 'desc')
            ->paginate($request->query('per_page', 20));

            $conversations->getCollection()->transform(function ($conversation) use ($user) {
                return $this->formatConversation($conversation, $user->id);
            });

            return response()->json([
                'conversations' => $conversations->items(),
                'pagination' => [
                    'total' => $conversations->total(),
                    'per_page' => $conversations->perPage(),
                    'current_page' => $conversations->currentPage(),
                    'last_page' => $conversations->lastPage(),
                ],
            ]);

        } catch (\Exception $e) {
            \Log::error('Get conversations error: ' . $e->getMessage());
            return response()->json([
                'message' => 'Error fetching conversations',
            ], 500);
        }
    }

    /**
     * Get a specific conversation with all messages
     * GET /api/conversations/{id}
     */
    public function show($id, Request $request)
    {
        try {
            $conversation = Conversation::findOrFail($id);
            $user = Auth::user();

            // Verify user is part of this conversation
            if ($conversation->owner_id !== $user->id && $conversation->dest_id !== $user->id) {
                return response()->json([
                    'message' => 'Unauthorized',
                ], 403);
            }

            // Load messages with pagination
            $limit = $request->query('limit', 50);
            $offset = $request->query('offset', 0);

            $messages = Message::forConversation($id)
                ->with(['sender', 'recipient'])
                ->limit($limit)
                ->offset($offset)
                ->get()
                ->map(fn($msg) => $this->formatMessage($msg, $user->id));

            // Mark messages as delivered
            Message::where('conversation_id', $id)
                ->where('recipient_id', $user->id)
                ->where('status', '!=', 'read')
                ->update(['status' => 'delivered']);

            return response()->json([
                'conversation' => $this->formatConversation($conversation, $user->id),
                'messages' => $messages,
            ]);

        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'message' => 'Conversation not found',
            ], 404);
        } catch (\Exception $e) {
            \Log::error('Get conversation error: ' . $e->getMessage());
            return response()->json([
                'message' => 'Error fetching conversation',
            ], 500);
        }
    }

    /**
     * Create a new conversation (or return existing one)
     * POST /api/conversations
     */
    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'recipient_id' => 'required|integer|exists:users,id|different:user_id',
            ]);

            $user = Auth::user();
            $recipientId = $validated['recipient_id'];

            // Check if conversation already exists
            $conversation = Conversation::where(function ($query) use ($user, $recipientId) {
                $query->where('owner_id', $user->id)
                      ->where('dest_id', $recipientId);
            })
            ->orWhere(function ($query) use ($user, $recipientId) {
                $query->where('owner_id', $recipientId)
                      ->where('dest_id', $user->id);
            })
            ->first();

            // Create new conversation if doesn't exist
            if (!$conversation) {
                $recipient = \App\Models\User::findOrFail($recipientId);

                $conversation = Conversation::create([
                    'owner_id' => $user->id,
                    'owner_name' => $user->name,
                    'dest_id' => $recipientId,
                    'dest_name' => $recipient->name,
                    'status' => 'active',
                ]);
            }

            return response()->json([
                'message' => 'Conversation created/found',
                'conversation' => $this->formatConversation($conversation, $user->id),
            ], 201);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'message' => 'Validation failed',
                'errors' => $e->errors(),
            ], 422);
        } catch (\Exception $e) {
            \Log::error('Create conversation error: ' . $e->getMessage());
            return response()->json([
                'message' => 'Error creating conversation',
            ], 500);
        }
    }

    /**
     * Close a conversation
     * PUT /api/conversations/{id}/close
     */
    public function close($id, Request $request)
    {
        try {
            $conversation = Conversation::findOrFail($id);
            $user = Auth::user();

            // Only conversation members can close it
            if ($conversation->owner_id !== $user->id && $conversation->dest_id !== $user->id) {
                return response()->json([
                    'message' => 'Unauthorized',
                ], 403);
            }

            $validated = $request->validate([
                'reason' => 'sometimes|string|max:500',
            ]);

            $conversation->update([
                'status' => 'closed',
                'closed_by' => $user->id,
                'closed_at' => now(),
                'close_reason' => $validated['reason'] ?? null,
            ]);

            return response()->json([
                'message' => 'Conversation closed',
                'conversation' => $this->formatConversation($conversation, $user->id),
            ]);

        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'message' => 'Conversation not found',
            ], 404);
        } catch (\Exception $e) {
            \Log::error('Close conversation error: ' . $e->getMessage());
            return response()->json([
                'message' => 'Error closing conversation',
            ], 500);
        }
    }

    /**
     * Get unread conversation count
     * GET /api/conversations/unread-count
     */
    public function unreadCount()
    {
        try {
            $user = Auth::user();

            $unreadCount = Conversation::where(function ($query) use ($user) {
                $query->where('owner_id', $user->id)
                      ->orWhere('dest_id', $user->id);
            })
            ->whereHas('messages', function ($query) use ($user) {
                $query->where('recipient_id', $user->id)
                      ->where('status', '!=', 'read');
            })
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
     * Search conversations
     * GET /api/conversations/search
     */
    public function search(Request $request)
    {
        try {
            $user = Auth::user();
            $query = $request->query('q', '');

            if (empty($query)) {
                return response()->json([
                    'conversations' => [],
                ]);
            }

            $conversations = Conversation::where(function ($q) use ($user) {
                $q->where('owner_id', $user->id)
                  ->orWhere('dest_id', $user->id);
            })
            ->where(function ($q) use ($query) {
                $q->where('owner_name', 'like', "%{$query}%")
                  ->orWhere('dest_name', 'like', "%{$query}%")
                  ->orWhere('last_message', 'like', "%{$query}%");
            })
            ->with(['owner', 'dest'])
            ->limit(20)
            ->get()
            ->map(fn($conv) => $this->formatConversation($conv, $user->id));

            return response()->json([
                'conversations' => $conversations,
            ]);

        } catch (\Exception $e) {
            \Log::error('Search conversations error: ' . $e->getMessage());
            return response()->json([
                'message' => 'Error searching conversations',
            ], 500);
        }
    }

    /**
     * Format conversation response
     */
    private function formatConversation($conversation, $currentUserId)
    {
        $recipientId = $conversation->owner_id === $currentUserId
            ? $conversation->dest_id
            : $conversation->owner_id;

        $recipientName = $conversation->owner_id === $currentUserId
            ? $conversation->dest_name
            : $conversation->owner_name;

        // Get unread message count
        $unreadCount = Message::where('conversation_id', $conversation->id)
            ->where('recipient_id', $currentUserId)
            ->where('status', '!=', 'read')
            ->count();

        return [
            'id' => $conversation->id,
            'owner_id' => $conversation->owner_id,
            'owner_name' => $conversation->owner_name,
            'dest_id' => $conversation->dest_id,
            'dest_name' => $conversation->dest_name,
            'recipient_id' => $recipientId,
            'recipient_name' => $recipientName,
            'last_message' => $conversation->last_message,
            'unread_count' => $unreadCount,
            'status' => $conversation->status,
            'created_at' => $conversation->created_at?->toIso8601String(),
            'updated_at' => $conversation->updated_at?->toIso8601String(),
        ];
    }

    /**
     * Format message response
     */
    private function formatMessage($message, $currentUserId)
    {
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
}
