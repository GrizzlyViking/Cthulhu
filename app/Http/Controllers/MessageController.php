<?php

namespace App\Http\Controllers;

use App\Jobs\SendMessage;
use App\Models\Message;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;
use Inertia\Response;

class MessageController extends Controller
{
    public function send(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'recipients'   => ['required', 'array'],
            'recipients.*' => ['integer', 'exists:users,id'],
            'content'      => ['required', 'string'],
        ]);

        // Messages never cross group boundaries: recipients outside the
        // sender's group are dropped silently.
        $recipients = User::query()
            ->inGroupOf($request->user())
            ->whereIn('id', $validated['recipients'])
            ->pluck('id');

        foreach ($recipients as $recipientId) {
            $message = Message::create([
                'receiver_id' => $recipientId,
                'sender_id'   => $request->user()->id,
                'content'     => $validated['content'],
            ]);

            SendMessage::dispatch($message);
        }

        return Redirect::back()->with(['success' => 'Message sent!']);
    }

    public function read(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'message_id' => ['required', 'integer', 'exists:messages,id'],
        ]);

        $message = Message::findOrFail($validated['message_id']);

        abort_unless($message->receiver_id === $request->user()->id, 403);

        $message->update(['read' => true]);

        return response()->json(['success' => 'Message read!']);
    }

    public function index(): Response
    {
        $messages = Auth::user()->messages()->get();

        return Inertia::render('Messages', compact('messages'));
    }
}
