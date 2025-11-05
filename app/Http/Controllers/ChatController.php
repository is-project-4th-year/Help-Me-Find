<?php

namespace App\Http\Controllers;

use App\Models\Chat;
use App\Models\Message;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;

class ChatController extends Controller
{
    private $jsonFile = 'uploads.json';

    private function loadData()
    {
        if (!File::exists($this->jsonFile)) {
            return [];
        }

        $json = File::get($this->jsonFile);
        return json_decode($json, true) ?? [];
    }

    /**
     * Initiates or displays a chat for a specific item.
     */
    public function showChat($item_id)
    {
        $itemData = $this->loadData()[$item_id] ?? null;

        if (!$itemData || !isset($itemData['FinderId'])) {
            return redirect()->route('lostItems')->with('error', 'Item or Finder details are incomplete.');
        }

        $seekerId = Auth::id();
        $finderId = $itemData['FinderId'];

        if ((int)$seekerId === (int)$finderId) {
            return redirect()->route('lostItems')->with('error', 'You cannot start a chat for an item you posted.');
        }

        $finder = User::find($finderId);

        if (!$finder) {
            return redirect()->route('lostItems')->with('error', 'The item poster could not be found.');
        }

        // Find existing chat or create a new one
        $chat = Chat::firstOrCreate(
            [
                'seeker_id' => $seekerId,
                'item_id' => $item_id,
            ],
            [
                'finder_id' => $finderId,
            ]
        );

        return view('chat', compact('chat', 'finder', 'item_id'));
    }

    /**
     * Fetches all messages for a chat. Used for the initial load and polling.
     */
    public function getMessages(Chat $chat)
    {
        // Security check: ensure the current user is a participant
        if (Auth::id() != $chat->seeker_id && Auth::id() != $chat->finder_id) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        // Order by created_at ascending
        $messages = $chat->messages()->orderBy('created_at')->with('user')->get()->map(function ($message) {
            return [
                'id' => $message->id,
                'content' => $message->content,
                'is_sender' => $message->user_id === Auth::id(),
                'sender_name' => $message->user->firstName,
                'time' => $message->created_at->diffForHumans(),
            ];
        });

        return response()->json($messages);
    }

    /**
     * Stores a new message in the chat.
     */
    public function sendMessage(Request $request, Chat $chat)
    {
        // Validation
        $request->validate([
            'content' => 'required|string|max:1000',
        ]);

        // Security check: ensure the current user is a participant
        if (Auth::id() != $chat->seeker_id && Auth::id() != $chat->finder_id) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $message = Message::create([
            'chat_id' => $chat->id,
            'user_id' => Auth::id(),
            'content' => $request->input('content'),
        ]);

        return response()->json([
            'id' => $message->id,
            'content' => $message->content,
            'is_sender' => true,
            'sender_name' => Auth::user()->firstName,
            'time' => $message->created_at->diffForHumans(),
        ]);
    }
}
