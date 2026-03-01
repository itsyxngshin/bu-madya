<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        body { font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif; background-color: #f9fafb; margin: 0; padding: 0; }
        .wrapper { width: 100%; table-layout: fixed; background-color: #f9fafb; padding: 40px 0; }
        .main { max-width: 600px; margin: 0 auto; background-color: #ffffff; border-radius: 24px; border: 1px solid #f3f4f6; overflow: hidden; box-shadow: 0 4px 6px rgba(0,0,0,0.05); }
        .header { background-color: #111827; color: #ffffff; padding: 40px; text-align: center; }
        .header h1 { margin: 0; font-size: 24px; font-weight: 900; }
        .content { padding: 40px; text-align: center; }
        .circle-check { width: 64px; height: 64px; background-color: #ecfdf5; border-radius: 50%; display: inline-flex; align-items: center; justify-content: center; margin-bottom: 20px; color: #10b981; }
        .footer { text-align: center; padding: 20px; font-size: 12px; color: #9ca3af; }
        .btn { display: inline-block; background-color: #ea580c; color: #ffffff; text-decoration: none; padding: 12px 24px; border-radius: 12px; font-weight: bold; margin-top: 24px; text-transform: uppercase; font-size: 12px; letter-spacing: 1px; }
    </style>
</head>
<body>
    <div class="wrapper">
        <div class="main">
            {{-- Header --}}
            <div class="header">
                <div style="font-size: 12px; font-weight: bold; color: #ea580c; text-transform: uppercase; letter-spacing: 2px; margin-bottom: 8px;">Check-in Confirmed</div>
                <h1>{{ $event->title }}</h1>
            </div>

            {{-- Content --}}
            <div class="content">
                <div class="circle-check">
                    <svg style="width: 32px; height: 32px;" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg>
                </div>
                
                <h2 style="font-size: 20px; color: #111827; margin-top: 0;">Thanks for coming, {{ $registration->name }}!</h2>
                <p style="color: #4b5563; font-size: 14px; line-height: 1.6;">
                    Your attendance has been officially verified by the BU MADYA team. We hope you had a great time and learned a lot!
                </p>

                {{-- Optional: Add a button to your evaluations page if you want! --}}
                <a href="{{ url('/projects') }}" class="btn">View Our Campaigns</a>
            </div>
        </div>
        
        <div class="footer">
            This is an automated message from the BU MADYA System.<br>
            Please do not reply to this email.
        </div>
    </div>
</body>
</html>