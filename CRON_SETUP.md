# Daily Diary Reminder Cron Job Setup

This guide explains how to set up the automated daily diary reminder email system.

## Overview

The system sends automated reminder emails to all active (verified) non-admin users every day to encourage them to write their daily diary entries.

## Components

### 1. Command: `diary:send-reminders`
**Location:** `app/Console/Commands/SendDailyDiaryReminders.php`

This Artisan command:
- Fetches all active users (those with verified emails, excluding admins)
- Sends a reminder email to each user
- Displays progress and statistics
- Uses queued emails for better performance

**Manual execution:**
```bash
php artisan diary:send-reminders
```

### 2. Mailable: `DailyDiaryReminder`
**Location:** `app/Mail/DailyDiaryReminder.php`

A mailable that:
- Takes a User model as parameter
- Sends a personalized reminder email synchronously (not queued)
- Includes a direct link to create a new diary entry

### 3. Email Template
**Location:** `resources/views/emails/daily-diary-reminder.blade.php`

A beautiful HTML email template featuring:
- Personalized greeting
- Benefits of daily journaling
- Direct "Write Today's Entry" button
- Writing tips
- Professional styling with gradients

### 4. Schedule Configuration
**Location:** `routes/console.php`

The command is scheduled to run:
- **Time:** Daily at 8:00 PM
- **Timezone:** Asia/Kathmandu (configurable)
- **Options:** 
  - `withoutOverlapping()` - Prevents multiple instances running simultaneously
  - `runInBackground()` - Runs asynchronously

## Setting Up the Cron Job

### Step 1: Configure Laravel Scheduler

Add this single cron entry to your server's crontab:

```bash
* * * * * cd /var/www/html/daily_diary && php artisan schedule:run >> /dev/null 2>&1
```

**To edit crontab:**
```bash
crontab -e
```

This cron job runs every minute and checks if any scheduled tasks need to be executed.

### Step 2: Configure Mail Settings

Update your `.env` file with SMTP settings:

```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.mailtrap.io  # or your SMTP server
MAIL_PORT=2525
MAIL_USERNAME=your_username
MAIL_PASSWORD=your_password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=noreply@yourdomain.com
MAIL_FROM_NAME="${APP_NAME}"
```

**For production, use a reliable service:**
- AWS SES
- SendGrid
- Mailgun
- Postmark

### Step 4: Test the Setup

**Test the schedule:**
```bash
php artisan schedule:list
```

## Customization

### Change Send Time

Edit `routes/console.php`:
```php
Schedule::command('diary:send-reminders')
    ->dailyAt('09:00')  // Change to 9:00 AM
    ->timezone('America/New_York');  // Change timezone
```

### Change Email Content

Edit the template at:
`resources/views/emails/daily-diary-reminder.blade.php`

### Change Who Receives Reminders

Edit the query in `app/Console/Commands/SendDailyDiaryReminders.php`:

```php
// Example: Only users who haven't written today
$users = User::whereNotNull('email_verified_at')
    ->whereNotIn('role', ['admin', 'super_admin'])
    ->whereDoesntHave('diaries', function ($query) {
        $query->whereDate('date', today());
    })
    ->get();
```

## Monitoring

### View Logs
```bash
tail -f storage/logs/laravel.log
```

## Troubleshooting

### Emails Not Being Sent

1. **Check mail configuration:**
   ```bash
   php artisan config:clear
   php artisan tinker
   >>> use App\Models\User;
   >>> use App\Mail\DailyDiaryReminder;
   >>> use Illuminate\Support\Facades\Mail;
   >>> $user = User::first();
   >>> Mail::to('test@example.com')->send(new DailyDiaryReminder($user));
   ```

2. **Check Laravel logs:**
   ```bash
   tail -f storage/logs/laravel.log
   ```

### Cron Not Running

1. **Check crontab is set:**
   ```bash
   crontab -l
   ```

2. **Check Laravel scheduler:**
   ```bash
   php artisan schedule:list
   ```

3. **Test schedule manually:**
   ```bash
   php artisan schedule:run
   ```

## Production Checklist

- [ ] Cron job added to crontab
- [ ] SMTP credentials configured
- [ ] Test email sent successfully
- [ ] Timezone configured correctly
- [ ] Logs monitoring set up
- [ ] Error notifications configured
- [ ] Email rate limits checked with provider

## Additional Features (Optional)

### Send Only to Users Without Today's Entry

Modify the command to check if user has already written today:

```php
$users = User::whereNotNull('email_verified_at')
    ->whereNotIn('role', ['admin', 'super_admin'])
    ->whereDoesntHave('diaries', function ($query) {
        $query->whereDate('date', today());
    })
    ->get();
```

### Add User Preferences

Create a migration to add email preferences:
```bash
php artisan make:migration add_email_preferences_to_users_table
```

Then filter based on user preferences in the command.

### Track Email Engagement

Consider using services like Mailgun or SendGrid that provide:
- Open tracking
- Click tracking
- Bounce handling
- Delivery analytics
