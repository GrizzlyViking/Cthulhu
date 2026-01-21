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
        $validated = $request->validate([
            'recipients'   => ['required', 'array'],
            'recipients.*' => ['integer', 'exists:users,id'],
            'content'      => ['required', 'string'],
        ]);

        foreach ($validated['recipients'] as $recipientId) {
            $message = Message::create([
                'receiver_id' => $recipientId,
                'sender_id'   => $request->user()->id,
                'content'     => $validated['content'],
            ]);

            SendMessage::dispatch($message);
        }

        return Redirect::back()->with(['success' => 'Message sent!']);
    }

    public function read(Request $request)
    {
        $validated = $request->validate([
            'message_id' => ['required', 'integer', 'exists:messages,id'],
        ]);

        $message = Message::findOrFail($validated['message_id']);
        $message->update(['read' => true]);

        return response()->json(['success' => 'Message read!']);
    }

    public function index()
    {
        $messages = Auth::user()->messages()->get();

        return Inertia::render('Messages', compact('messages'));
    }
}
