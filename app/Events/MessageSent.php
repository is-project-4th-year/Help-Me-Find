<?php

namespace App\Events;

use App\Models\Message;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Queue\SerializesModels;

class MessageSent implements ShouldBroadcast
{
    use SerializesModels;

    public $message;
    public $chatId;

    public function __construct(Message $message, string $chatId)
    {
        $this->message = $message->load('sender');
        $this->chatId = $chatId;
    }

    // This will broadcast to the private channel named "chat.{chatId}"
    public function broadcastOn()
    {
        return new PrivateChannel('chat.' . $this->chatId);
    }

    /**
     * Get the data to broadcast.
     *
     * @return array
     */
    // This MUST be wrapped in a 'message' key to match chat.js
    public function broadcastWith()
    {
        return [
            'message' => [
                'id' => $this->message->id,
                'sender_id' => $this->message->sender_id,
                'receiver_id' => $this->message->receiver_id,
                'body' => $this->message->body,
                'created_at' => $this->message->created_at->toDateTimeString(),
                'sender' => [
                    'id' => $this->message->sender->id,
                    'firstName' => $this->message->sender->firstName ?? $this->message->sender->name,
                ],
            ]
        ];
    }

    /**
     * The event's broadcast name.
     *
     * @return string
     */
    // This MUST be 'MessageSent' to match chat.js
    public function broadcastAs()
    {
        return 'MessageSent';
    }
}
