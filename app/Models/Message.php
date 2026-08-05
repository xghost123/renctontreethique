<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Message extends Model
{
    use HasFactory;

    protected $fillable = [
        'conversation_id',
        'sender_id',
        'recipient_id',
        'body',
        'language',
        'status',
        'read_at',
        'is_flagged',
        'flag_reason',
        'moderation_note',
        'moderated_at',
        'moderated_by',
    ];

    protected $casts = [
        'read_at' => 'datetime',
        'moderated_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'is_flagged' => 'boolean',
    ];

    /**
     * Get the conversation this message belongs to
     */
    public function conversation(): BelongsTo
    {
        return $this->belongsTo(Conversation::class);
    }

    /**
     * Get the user who sent this message
     */
    public function sender(): BelongsTo
    {
        return $this->belongsTo(User::class, 'sender_id');
    }

    /**
     * Get the user who received this message
     */
    public function recipient(): BelongsTo
    {
        return $this->belongsTo(User::class, 'recipient_id');
    }

    /**
     * Get the admin who moderated this message
     */
    public function moderator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'moderated_by');
    }

    /**
     * Scope: Get unread messages for a user
     */
    public function scopeUnread($query)
    {
        return $query->where('status', '!=', 'read')->whereNull('read_at');
    }

    /**
     * Scope: Get messages in a conversation
     */
    public function scopeForConversation($query, $conversationId)
    {
        return $query->where('conversation_id', $conversationId)->orderBy('created_at', 'asc');
    }

    /**
     * Scope: Get messages from a specific sender
     */
    public function scopeFromSender($query, $senderId)
    {
        return $query->where('sender_id', $senderId);
    }

    /**
     * Scope: Get messages for a specific recipient
     */
    public function scopeForRecipient($query, $recipientId)
    {
        return $query->where('recipient_id', $recipientId);
    }

    /**
     * Scope: Get flagged messages
     */
    public function scopeFlagged($query)
    {
        return $query->where('is_flagged', true);
    }

    /**
     * Scope: Get unmoderated messages
     */
    public function scopeUnmoderated($query)
    {
        return $query->whereNull('moderated_at');
    }

    /**
     * Mark message as read
     */
    public function markAsRead(): void
    {
        if ($this->status !== 'read') {
            $this->update([
                'status' => 'read',
                'read_at' => now(),
            ]);
        }
    }

    /**
     * Mark message as delivered
     */
    public function markAsDelivered(): void
    {
        if ($this->status === 'sent') {
            $this->update(['status' => 'delivered']);
        }
    }

    /**
     * Flag message for moderation
     */
    public function flag(string $reason = null): void
    {
        $this->update([
            'is_flagged' => true,
            'flag_reason' => $reason,
        ]);
    }

    /**
     * Check if message is read
     */
    public function isRead(): bool
    {
        return $this->status === 'read' && $this->read_at !== null;
    }

    /**
     * Check if message is delivered
     */
    public function isDelivered(): bool
    {
        return in_array($this->status, ['delivered', 'read']);
    }

    /**
     * Check if message is pending (not yet delivered)
     */
    public function isPending(): bool
    {
        return $this->status === 'sent';
    }

    /**
     * Get human-readable status
     */
    public function getReadableStatus(): string
    {
        return match ($this->status) {
            'sent' => '✓ Envoyé',
            'delivered' => '✓ ✓ Remis',
            'read' => '✓ ✓ ✓ Lu',
            default => $this->status,
        };
    }
}
