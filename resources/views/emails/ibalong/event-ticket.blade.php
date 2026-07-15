<!DOCTYPE html>
<html>
<head>
    <style>
        body {
            font-family: 'Courier New', Courier, monospace;
            background-color: #f9fafb;
            padding: 20px;
            color: #131011;
            margin: 0;
        }
        .wrapper {
            max-width: 600px;
            margin: 0 auto;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
        }
        .header img {
            max-width: 100%;
            width: 250px;
            height: auto;
        }
        .ticket-container {
            background-color: #ffffff;
            border: 4px solid #131011;
            box-shadow: 8px 8px 0px 0px #FF8623;
        }
        .ticket-header {
            background-color: #0095AC;
            color: #ffffff;
            padding: 15px;
            text-align: center;
            border-bottom: 4px solid #131011;
            font-weight: 900;
            text-transform: uppercase;
            font-size: 18px;
            letter-spacing: 2px;
        }
        .ticket-body {
            padding: 30px;
            text-align: center;
        }
        h1 {
            text-transform: uppercase;
            font-weight: 900;
            margin-top: 0;
            margin-bottom: 5px;
            font-family: Arial, sans-serif;
        }
        .event-time {
            color: #6b7280;
            font-weight: bold;
            text-transform: uppercase;
            margin-bottom: 20px;
            font-family: Arial, sans-serif;
        }
        .details-grid {
            border-top: 2px dashed #131011;
            border-bottom: 2px dashed #131011;
            padding: 20px 0;
            margin: 20px 0;
            text-align: left;
            font-family: Arial, sans-serif;
        }
        .qr-box {
            border: 4px solid #131011;
            padding: 15px;
            display: inline-block;
            margin: 20px 0;
        }
        .ticket-code {
            font-weight: bold;
            font-size: 14px;
            margin-top: 10px;
            letter-spacing: 3px;
        }
        .footer {
            margin-top: 30px;
            font-size: 11px;
            font-weight: bold;
            text-transform: uppercase;
            color: #6b7280;
            text-align: center;
            font-family: Arial, sans-serif;
        }
    </style>
</head>
<body>
    <div class="wrapper">
        <div class="header">
            <img src="{{ url('images/HOI Logo Blue.png') }}" alt="Heroes of Innovation 2026">
        </div>

        <div class="ticket-container">
            <div class="ticket-header">
                Official Boarding Pass
            </div>

            <div class="ticket-body">
                <h1>{{ $event->title }}</h1>
                <div class="event-time">
                    {{ $event->start_datetime->format('M d, Y') }} @ {{ $event->start_datetime->format('h:i A') }}
                </div>

                <div class="details-grid">
                    <p style="margin: 0 0 10px 0;"><strong>ATTENDEE:</strong> {{ $registration->name }}</p>
                    <p style="margin: 0 0 10px 0;"><strong>ROLE:</strong> {{ $registration->role }}</p>
                    <p style="margin: 0;"><strong>VENUE:</strong> {{ $event->venue_or_link ?: 'TBA' }}</p>
                </div>

                <!-- Web-Safe QR Code embedded via API -->
                <div class="qr-box">
                    <img src="https://api.qrserver.com/v1/create-qr-code/?size=200x200&data={{ $registration->ticket_code }}&color=131011&bgcolor=ffffff" alt="Ticket QR Code" width="200" height="200">
                    <div class="ticket-code">{{ $registration->ticket_code }}</div>
                </div>

                <p style="font-family: Arial, sans-serif; font-size: 12px; font-weight: bold;">Please present this QR code at the terminal gate for scanning.</p>
            </div>
        </div>

        <div class="footer">
            Powered by BU MADYA Web | Heroes of Innovation 2026
        </div>
    </div>
</body>
</html>
