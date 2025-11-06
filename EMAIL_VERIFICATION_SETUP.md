# Email Verification Setup

This document describes the email verification system implemented for new user registrations.

## Overview

Email verification ensures that new users provide a valid email address during registration. Users must verify their email before accessing protected routes like the dashboard, diaries, and affirmations.

## Implementation Details

### 1. User Model Configuration

The `User` model implements the `MustVerifyEmail` interface:

```php
use Illuminate\Contracts\Auth\MustVerifyEmail;

class User extends Authenticatable implements MustVerifyEmail
{
    // ... model code
}
```

### 2. Protected Routes

The following routes require email verification (via `'verified'` middleware):

- **Dashboard**: `/dashboard`
- **Diaries**: `/diaries`, `/diaries/create`, `/diaries/{diary}/edit`
- **Affirmations**: `/affirmations`, `/affirmations/create`, `/affirmations/{affirmation}/edit`
- **Profile**: `/profile` (edit, update, delete)
- **Password Settings**: `/account/password`
- **Admin Routes**: All `/admin/*` routes

### 3. Email Verification Flow

1. **User Registration**:
   - User fills out registration form on the welcome page
   - Upon submission, a `Registered` event is fired
   - Laravel automatically sends a verification email to the user
   - User is logged in immediately but redirected to verification notice page

2. **Email Verification**:
   - User receives an email with a verification link
   - Clicking the link verifies their email address
   - User is redirected to the dashboard after successful verification

3. **Access Protection**:
   - Unverified users are redirected to `/verify-email` when accessing protected routes
   - They can request a new verification link if needed

### 4. Verification Routes

The following routes are available (defined in `routes/auth.php`):

- `GET /verify-email` - Shows verification notice page
- `GET /verify-email/{id}/{hash}` - Handles email verification
- `POST /email/verification-notification` - Resends verification email

## User Experience

### New User Flow

1. Visit the welcome page and click "Register"
2. Fill out registration form with name, email, and password
3. Submit the form
4. Receive verification email
5. Click the verification link in the email
6. Access the dashboard and start using the application

### Unverified User Flow

1. If an unverified user tries to access protected routes (e.g., `/diaries`)
2. They are redirected to `/verify-email` page
3. They can resend the verification email if needed
4. After verifying, they can access all protected routes

## Email Configuration

### Current Setup (Gmail SMTP)

The application is configured to send emails via Gmail SMTP. The configuration is in `.env`:

```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=your-email@gmail.com
MAIL_PASSWORD=your-app-password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS="your-email@gmail.com"
MAIL_FROM_NAME="${APP_NAME}"
```

### Setting Up Gmail App Password

1. Go to your Google Account settings: https://myaccount.google.com/
2. Navigate to Security → 2-Step Verification
3. Scroll down to "App passwords"
4. Generate a new app password for "Mail"
5. Copy the 16-character password
6. Update `.env` with your Gmail address and app password
7. Run `php artisan config:clear` to clear the config cache

### Gmail Sending Limits

- **Individual Gmail**: Up to 500 emails per day
- **Google Workspace**: Up to 2,000 emails per day

For production applications with many users, consider using:
- AWS SES (Simple Email Service)
- SendGrid
- Mailgun
- Postmark

## Testing Email Verification

### Manual Testing

1. Clear your application cache:
   ```bash
   php artisan config:clear
   php artisan cache:clear
   ```

2. Register a new user account with a real email address

3. Check your email inbox for the verification email

4. Click the verification link

5. Verify you can access the dashboard

### Using Mailtrap for Testing

For development/testing, you can use Mailtrap instead of Gmail:

1. Sign up at https://mailtrap.io
2. Get your SMTP credentials from the Inbox settings
3. Update `.env`:
   ```env
   MAIL_MAILER=smtp
   MAIL_HOST=sandbox.smtp.mailtrap.io
   MAIL_PORT=2525
   MAIL_USERNAME=your-mailtrap-username
   MAIL_PASSWORD=your-mailtrap-password
   MAIL_ENCRYPTION=tls
   ```
4. Run `php artisan config:clear`

## Admin User Verification Command

Admins can manually verify a user's email using the Artisan command:

```bash
php artisan user:verify user@example.com
```

This is useful for:
- Helping users who didn't receive the verification email
- Testing the system
- Bulk verification if needed

## Troubleshooting

### User Not Receiving Verification Email

1. Check `.env` email configuration
2. Verify Gmail App Password is correct
3. Check `storage/logs/laravel.log` for email errors
4. Test email sending with `php artisan tinker`:
   ```php
   Mail::raw('Test email', function($message) {
       $message->to('test@example.com')->subject('Test');
   });
   ```

### Verification Link Not Working

1. Ensure `APP_URL` in `.env` matches your application URL
2. Check that the verification route exists in `routes/auth.php`
3. Clear config cache: `php artisan config:clear`

### User Stuck on Verification Page

1. Check if email is already verified in database:
   ```bash
   php artisan tinker
   User::where('email', 'user@example.com')->first()->email_verified_at
   ```
2. Manually verify using: `php artisan user:verify user@example.com`

## Security Considerations

1. **Verification Links**: Expire after a certain time (configurable in `config/auth.php`)
2. **Rate Limiting**: Resend verification email is rate-limited to prevent abuse
3. **Signed Routes**: Verification links use signed URLs for security
4. **Gmail Security**: Use App Passwords, never your actual Gmail password

## Daily Reminder Integration

The daily diary reminder system respects email verification:

```php
User::active()->verified()->nonAdmin()->get();
```

Only **verified** users receive daily reminder emails at 8:00 PM (Asia/Kathmandu timezone).

## Related Files

- `app/Models/User.php` - User model with MustVerifyEmail interface
- `routes/auth.php` - Email verification routes
- `routes/web.php` - Protected routes with 'verified' middleware
- `app/Http/Controllers/Auth/RegisteredUserController.php` - Registration controller
- `app/Console/Commands/VerifyUser.php` - Manual verification command
- `.env` - Email configuration

## Summary

Email verification is now fully functional. New users must verify their email address before accessing diaries, affirmations, and other protected features. The system integrates seamlessly with Laravel Breeze authentication and the daily reminder email system.
