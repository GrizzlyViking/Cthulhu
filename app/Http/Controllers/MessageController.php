<?php

namespace App\Http\Controllers;

use App\Jobs\SendMessage;
use App\Models\Message;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;

class MessageController extends Controller
{
    public function send(Request $request)
    {
        $request->validate([
            'recipients' => 'required|array',
            'recipients.*' => 'integer|exists:users,id',
            'content' => 'required|string'
        ]);

        collect($request->recipients)->each(function (int $recipient) use ($request) {
            $message = new Message();
            $message->receiver_id = $recipient;
            $message->sender_id = auth()->user()->id;
            $message->content = $request->get('content');
            $message->save();

            SendMessage::dispatch($message);
        });

        return Redirect::back()->with(['success' => 'Message sent!']);
    }
}
