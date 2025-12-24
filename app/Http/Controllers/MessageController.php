<?php

namespace App\Http\Controllers;

use App\Jobs\SendMessage;
use App\Models\Message;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;

class MessageController extends Controller
{
    public function send(Request $request)
    {
        $request->validate([
            'recipients'   => 'required|array',
            'recipients.*' => 'integer|exists:users,id',
            'content'      => 'required|string',
        ]);

        collect($request->recipients)->each(function (int $recipient) use ($request) {
            $message              = new Message();
            $message->receiver_id = $recipient;
            $message->sender_id   = auth()->user()->id;
            $message->content     = $request->get('content');
            $message->save();

            SendMessage::dispatch($message);
        });

        return Redirect::back()->with(['success' => 'Message sent!']);
    }

    public function read(Request $request)
    {
        $request->validate([
            'message_id' => 'required|integer|exists:messages,id',
        ]);

        $message  = Message::find($request->get('message_id'));
        $response = $message->update(['read' => true]);

        return response()->json(['success' => 'Message read!'.$response]);
    }

    public function index()
    {
        $messages = Auth::user()->messages()->get();

        return Inertia::render('Messages', compact('messages'));
    }
}
