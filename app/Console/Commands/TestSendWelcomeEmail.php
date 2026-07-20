<?php

namespace App\Console\Commands;

use App\Mail\WelcomeEmail;
use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

#[Signature('email:test-welcome {email} {name}')]
#[Description('Kirim email welcome secara manual untuk pengujian')]
class TestSendWelcomeEmail extends Command
{
    protected $description = 'Men-dispatch job Welcome Email secara manual untuk pengujian';

    public function handle(): int
    {
        $email = $this->argument('email');
        $name = $this->argument('name');

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $this->error('Format email tidak valid.');
            return self::INVALID;
        }

        Mail::to($email)->send(new WelcomeEmail($name));

        $this->info("Berhasil! Email welcome telah dikirim ke [{$email}].");

        return self::SUCCESS;
    }
}
