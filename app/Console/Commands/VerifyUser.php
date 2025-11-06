<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;

class VerifyUser extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'user:verify {email}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Verify a user email address';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $email = $this->argument('email');

        $user = User::where('email', $email)->first();

        if (! $user) {
            $this->error("User with email '{$email}' not found.");

            return self::FAILURE;
        }

        if ($user->email_verified_at) {
            $this->info("User '{$user->name}' is already verified.");

            return self::SUCCESS;
        }

        $user->email_verified_at = now();
        $user->save();

        $this->info("User '{$user->name}' ({$email}) has been verified successfully!");

        return self::SUCCESS;
    }
}
