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
        .qr-box { background-color: #ffffff; border: 2px dashed #e5e7eb; border-radius: 24px; padding: 20px; display: inline-block; margin: 20px 0; }
        .details { text-align: left; background-color: #f9fafb; border-radius: 16px; padding: 24px; margin-top: 20px; }
        .detail-row { margin-bottom: 16px; }
        .detail-label { font-size: 12px; font-weight: bold; color: #6b7280; text-transform: uppercase; letter-spacing: 1px; margin-bottom: 4px; }
        .detail-value { font-size: 16px; font-weight: bold; color: #111827; margin: 0; }
        .footer { text-align: center; padding: 20px; font-size: 12px; color: #9ca3af; }
    </style>
</head>
<body>
    <div class="wrapper">
        <div class="main">
            {{-- Header --}}
            <div class="header">
                <div style="font-size: 12px; font-weight: bold; color: #ea580c; text-transform: uppercase; letter-spacing: 2px; margin-bottom: 8px;">BU MADYA Event Ticket</div>
                <h1>{{ $event->title }}</h1>
            </div>

            {{-- Content --}}
            <div class="content">
                <h2 style="font-size: 20px; color: #111827; margin-top: 0;">Hi {{ $registration->name }}, you're in!</h2>
                <p style="color: #4b5563; font-size: 14px; line-height: 1.6;">Present the QR code below at the entrance for quick check-in.</p>

                {{-- The QR Code (Embedded directly via Base64 SVG so it doesn't get blocked as an external image) --}}
                <div class="qr-box">
                    <img src="data:image/svg+xml;base64,{{ base64_encode(SimpleSoftwareIO\QrCode\Facades\QrCode::size(200)->style('round')->color(17, 24, 39)->generate($registration->ticket_code)) }}" alt="Ticket QR Code" width="200" height="200">
                </div>

                <div style="font-size: 14px; font-weight: bold; color: #9ca3af; letter-spacing: 2px;">{{ $registration->ticket_code }}</div>

                {{-- Event Details --}}
                <div class="details">
                    <div class="detail-row">
                        <div class="detail-label">Date & Time</div>
                        <div class="detail-value">{{ $event->start_time->format('l, F j, Y \a\t g:i A') }}</div>
                    </div>
                    <div style="margin-bottom: 0;">
                        <div class="detail-label">Location</div>
                        <div class="detail-value">{{ $event->location }}</div>
                    </div>
                </div>
            </div>
        </div>

        <div class="footer">
            This is an automated message from the BU MADYA System.<br>
            Please do not reply to this email.
        </div>
    </div>
</body>
</html>
