<?php

namespace App\Console\Commands;

use App\Mail\DailyDiaryReminder;
use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class SendDailyDiaryReminders extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'diary:send-reminders';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send daily diary reminder emails to all active users';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $this->info('Starting to send daily diary reminders...');

        // Get all active users (is_active = true, verified email, and not admin)
        $users = User::active()
            ->verified()
            ->nonAdmin()
            ->get();

        if ($users->isEmpty()) {
            $this->warn('No active users found to send reminders to.');

            return self::SUCCESS;
        }

        $count = 0;
        $errors = 0;

        $this->withProgressBar($users, function ($user) use (&$count, &$errors) {
            try {
                Mail::to($user->email)->send(new DailyDiaryReminder($user));
                $count++;
            } catch (\Exception $e) {
                $errors++;
                $this->newLine();
                $this->error("Failed to send email to {$user->email}: {$e->getMessage()}");
            }
        });

        $this->newLine(2);
        $this->info("Successfully sent {$count} reminder emails.");

        if ($errors > 0) {
            $this->warn("Failed to send {$errors} emails. Check the logs for details.");
        }

        return self::SUCCESS;
    }
}
