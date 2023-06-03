<?php

namespace App\Jobs;

use App\Mail\ForgotPasswordMail;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class ProcessForgotPassword implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    protected string $token;
    protected string $email;
    protected string $name;

    public function __construct(string $token, string $email, string $name)
    {
        $this->token = $token;
        $this->email = $email;
        $this->name = $name;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $token = $this->token;
        $email = $this->email;
        $userName = $this->name;

        Mail::to($email)->send(new ForgotPasswordMail($token, $email, $userName));
    }
}
