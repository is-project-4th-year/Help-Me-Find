<?php
namespace App\Http\Controllers;

use App\Events\MessageSent;
use App\Models\Message;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ChatController extends Controller
{
    public function index()
    {
        // list all users except current user
        $users = User::where('id', '!=', Auth::id())->get(['id','firstName','lastName','email']);
        return view('chat.ChatList', compact('users'));
    }

    public function withUser(User $user)
    {
        $me = Auth::user();
        $other = $user;

        // Compute deterministic chatId
        $ids = [$me->id, $other->id];
        sort($ids);
        $chatId = $ids[0] . '_' . $ids[1];

        // load recent messages between the two
        $messages = Message::where(function($q) use ($me, $other) {
            $q->where('sender_id', $me->id)->where('receiver_id', $other->id);
        })->orWhere(function($q) use ($me, $other) {
            $q->where('sender_id', $other->id)->where('receiver_id', $me->id);
        })->orderBy('created_at')->get();

        return view('chat.chat', compact('other','messages','chatId'));
    }

    public function send(Request $request)
    {
        $request->validate([
            'receiver_id' => 'required|exists:users,id',
            'body' => 'required|string',
            'chatId' => 'required|string'
        ]);

        $sender = Auth::id();
        $receiver = $request->input('receiver_id');

        $message = Message::create([
            'sender_id' => $sender,
            'receiver_id' => $receiver,
            'body' => $request->input('body')
        ]);

        // Build chat id server side the same way (optional)
        // $chatId = $request->chatId;

        // Fire event
        event(new MessageSent($message, $request->input('chatId')));

        return response()->json(['status' => 'ok', 'message' => $message], 200);
    }
}
