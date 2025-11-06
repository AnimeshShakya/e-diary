<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daily Diary Reminder</title>
    <style>
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }
        .container {
            max-width: 600px;
            margin: 40px auto;
            background: #ffffff;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        .header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: #ffffff;
            padding: 30px;
            text-align: center;
        }
        .header h1 {
            margin: 0;
            font-size: 24px;
            font-weight: 600;
        }
        .content {
            padding: 40px 30px;
        }
        .greeting {
            font-size: 18px;
            margin-bottom: 20px;
            color: #333;
        }
        .message {
            font-size: 16px;
            color: #555;
            margin-bottom: 30px;
            line-height: 1.8;
        }
        .button {
            display: inline-block;
            padding: 14px 32px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: #ffffff;
            text-decoration: none;
            border-radius: 6px;
            font-weight: 600;
            font-size: 16px;
            text-align: center;
            transition: transform 0.2s;
        }
        .button:hover {
            transform: translateY(-2px);
        }
        .button-container {
            text-align: center;
            margin: 30px 0;
        }
        .tips {
            background: #f8f9fa;
            border-left: 4px solid #667eea;
            padding: 15px 20px;
            margin: 30px 0;
            border-radius: 4px;
        }
        .tips h3 {
            margin: 0 0 10px 0;
            font-size: 16px;
            color: #667eea;
        }
        .tips ul {
            margin: 10px 0;
            padding-left: 20px;
        }
        .tips li {
            margin: 5px 0;
            color: #555;
        }
        .footer {
            background: #f8f9fa;
            padding: 20px 30px;
            text-align: center;
            font-size: 14px;
            color: #777;
            border-top: 1px solid #e9ecef;
        }
        .footer a {
            color: #667eea;
            text-decoration: none;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>📝 Daily Diary Reminder</h1>
        </div>
        
        <div class="content">
            <div class="greeting">
                Hello {{ $user->name }}! 👋
            </div>
            
            <div class="message">
                It's time to reflect on your day and capture your thoughts, experiences, and memories. 
                Taking a few minutes each day to write in your diary can help you:
                <ul>
                    <li>Track your personal growth</li>
                    <li>Process your emotions</li>
                    <li>Remember important moments</li>
                    <li>Maintain mindfulness and gratitude</li>
                </ul>
            </div>

            <div class="button-container">
                <a href="{{ route('diaries.create') }}" class="button">
                    ✍️ Write Today's Entry
                </a>
            </div>

            <div class="tips">
                <h3>💡 Writing Tips</h3>
                <ul>
                    <li>Start with how you're feeling right now</li>
                    <li>Note one thing you're grateful for today</li>
                    <li>Reflect on a challenge or achievement</li>
                    <li>Set an intention for tomorrow</li>
                </ul>
            </div>

            <div class="message" style="margin-top: 30px; font-size: 14px; color: #777;">
                <p>This is an automated reminder to help you maintain your daily diary habit. 
                If you've already written today's entry, great job! Keep up the excellent work! 🌟</p>
            </div>
        </div>
        
        <div class="footer">
            <p>You're receiving this email because you have an active account with Daily Diary.</p>
            <p>Need help? <a href="{{ config('app.url') }}">Visit our website</a></p>
        </div>
    </div>
</body>
</html>
