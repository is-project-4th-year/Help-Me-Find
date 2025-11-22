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
        $users = User::where('id', '!=', Auth::id())->get(['id','firstName','lastName','email']);
        return view('chat.ChatList', compact('users'));
    }

    public function withUser(Request $request, User $user)
    {
        $me = Auth::user();
        $other = $user;

        $ids = [$me->id, $other->id];
        sort($ids);
        $chatId = $ids[0] . '_' . $ids[1];

        $messages = Message::where(function($q) use ($me, $other) {
            $q->where('sender_id', $me->id)->where('receiver_id', $other->id);
        })->orWhere(function($q) use ($me, $other) {
            $q->where('sender_id', $other->id)->where('receiver_id', $me->id);
        })->orderBy('created_at')->get();

        // Capture query parameters
        $defaultMessage = $request->query('message', '');
        $defaultImage = $request->query('image', null);

        return view('chat.chat', compact('other','messages','chatId', 'defaultMessage', 'defaultImage'));
    }

    public function send(Request $request)
    {
        $request->validate([
            'receiver_id' => 'required|exists:users,id',
            'body' => 'required|string', // The body now contains the text AND the img tag
            'chatId' => 'required|string'
        ]);

        $sender = Auth::id();
        $receiver = $request->input('receiver_id');

        // Create the message. We don't need to save image_path separately anymore
        // because it is embedded in the 'body' string as HTML.
        $message = Message::create([
            'sender_id' => $sender,
            'receiver_id' => $receiver,
            'body' => $request->input('body')
        ]);

        event(new MessageSent($message, $request->input('chatId')));

        return response()->json(['status' => 'ok', 'message' => $message], 200);
    }
}
