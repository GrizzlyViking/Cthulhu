<?php

namespace App\Jobs;

use App\Events\MessageSent;
use App\Models\Message;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SendMessage implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct(
        public Message $message
    ) { }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $this->message->sender->characters->each(function ($character) {
            if ($character->user->is_online) {
                // send message to online user
                $this->message->user->notify(new MessageSent($this->message));
            }
        });
    }
}
