<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        /* Base Reset */
        body {
            font-family: system-ui, -apple-system, sans-serif;
            background-color: #f8fafc;
            margin: 0;
            padding: 40px 20px;
            color: #0f172a;
        }

        /* 1. Widened Desktop Container */
        .email-wrapper {
            max-width: 750px;
            margin: 0 auto;
            background-color: #ffffff;
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1);
        }

        /* 2. Non-Rounded, Sharp Header */
        .ticket-header {
            background-color: #0f172a; /* Dark Navy/Black */
            color: #ffffff;
            text-align: center;
            padding: 40px 20px;
        }

        .ticket-header h1 {
            margin: 0 0 8px 0;
            font-size: 32px;
            font-weight: 900;
            text-transform: uppercase;
            letter-spacing: 2px;
        }

        .ticket-header p {
            margin: 0;
            color: #94a3b8;
            font-size: 16px;
        }

        /* Ticket Body */
        .ticket-body {
            padding: 50px 30px;
            text-align: center;
        }

        .attendee-name {
            font-size: 24px;
            font-weight: 900;
            margin: 0 0 5px 0;
        }

        .event-details {
            font-size: 14px;
            color: #64748b;
            margin-bottom: 30px;
        }

        /* 3. Gradient QR Code Container Match */
        .qr-container {
            background-color: #ffffff;
            border: 1px solid #f1f5f9;
            border-radius: 20px;
            padding: 30px;
            display: inline-block;
            margin-bottom: 20px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.05);
        }

        .qr-container img {
            width: 200px;
            height: 200px;
            display: block;
            margin: 0 auto;
        }

        .ticket-code {
            font-family: monospace;
            font-size: 14px;
            font-weight: 900;
            color: #64748b;
            letter-spacing: 4px;
            margin-top: 20px;
            text-transform: uppercase;
        }

        /* 4. Footer Matching Website UI */
        .footer {
            background-color: #0f172a;
            color: #cbd5e1;
            padding: 50px 40px 30px 40px;
            font-size: 13px;
        }

        .footer-logo {
            color: #ffffff;
            font-size: 20px;
            font-weight: 900;
            margin-bottom: 15px;
            display: flex;
            align-items: center;
        }

        .footer-description {
            line-height: 1.6;
            margin-bottom: 25px;
            color: #94a3b8;
        }

        /* Quick Links */
        .footer-links {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .footer-links li {
            margin-bottom: 12px;
        }

        .footer-links a {
            color: #cbd5e1;
            text-decoration: none;
        }

        .footer-bottom {
            border-top: 1px solid #1e293b;
            padding-top: 25px;
            margin-top: 40px;
            text-align: center;
            font-size: 11px;
            letter-spacing: 1px;
            color: #64748b;
        }
    </style>
</head>
<body>
    <div class="email-wrapper">

        <!-- Header -->
        <div class="ticket-header">
            <h1>You're In!</h1>
            <p>Present this ticket at the entrance.</p>
        </div>

        <!-- Body -->
        <div class="ticket-body">
            <h2 class="attendee-name">{{ $registrationRecord->name }}</h2>
            <p class="event-details">{{ $event->title }}<br>{{ $event->start_date->format('F d, Y') }} &bull; {{ $event->location }}</p>

            <!-- QR Container -->
            <div class="qr-container">
                <img src="{{ $qrCodeUrl ?? 'https://api.qrserver.com/v1/create-qr-code/?size=200x200&data=' . urlencode($registrationRecord->ticket_code) }}" alt="QR Code Ticket">
                <div class="ticket-code">BU-MADYA-{{ strtoupper($registrationRecord->ticket_code) }}</div>
            </div>

            <p style="font-size: 12px; color: #94a3b8; max-width: 400px; margin: 0 auto; line-height: 1.5;">
                To expedite check-in, please have this QR code ready on your screen or printed before approaching the facilitators.
            </p>
        </div>

        <!-- Footer -->
        <div class="footer">
            <table width="100%" cellpadding="0" cellspacing="0" border="0">
                <tr>
                    <!-- Left Column: Branding -->
                    <td width="60%" valign="top" style="padding-right: 20px;">

                        <!-- Safe Email Logo Structure -->
                        <table cellpadding="0" cellspacing="0" border="0" style="margin-bottom: 15px;">
                            <tr>
                                <td valign="middle" style="padding-right: 15px;">
                                    <img src="{{ $message->embed(public_path('images/MADYA Web Logo1.png')) }}" alt="BU MADYA" style="width: 50px; height: auto; display: block; border-radius: 50px; border: 3px solid #ffffff;">
                                </td>
                                <td valign="middle" style="color: #ffffff; font-size: 20px; font-weight: 900; font-family: system-ui, -apple-system, sans-serif;">
                                    BU MADYA
                                </td>
                            </tr>
                        </table>

                        <p class="footer-description">
                            The Bicol University - Movement for the Advancement of Youth-led Advocacy is a duly-accredited University Based Organization in Bicol University committed to service and reaching communities through advocacy.
                        </p>

                        <!-- Social Links with Correct SVGs -->
                        <table cellpadding="0" cellspacing="0" border="0">
                            <tr>
                                <!-- Facebook -->
                                <td style="padding-right: 10px;">
                                    <a href="https://www.facebook.com/BUMadya" style="display: inline-block; width: 36px; height: 36px; background-color: #1e293b; border-radius: 50%; text-align: center; line-height: 36px;">
                                        <img src="https://img.icons8.com/ios-filled/50/ffffff/facebook-new.png" alt="Facebook" style="width: 18px; height: 18px; vertical-align: middle;">
                                    </a>
                                </td>
                                <!-- Instagram -->
                                <td style="padding-right: 10px;">
                                    <a href="https://www.instagram.com/bu_madya" style="display: inline-block; width: 36px; height: 36px; background-color: #1e293b; border-radius: 50%; text-align: center; line-height: 36px;">
                                        <img src="https://img.icons8.com/ios-filled/50/ffffff/instagram-new.png" alt="Instagram" style="width: 18px; height: 18px; vertical-align: middle;">
                                    </a>
                                </td>
                                <!-- X / Twitter -->
                                <td>
                                    <a href="https://www.x.com/bu_madya" style="display: inline-block; width: 36px; height: 36px; background-color: #1e293b; border-radius: 50%; text-align: center; line-height: 36px;">
                                        <img src="https://img.icons8.com/ios-filled/50/ffffff/twitterx--v1.png" alt="X" style="width: 16px; height: 16px; vertical-align: middle;">
                                    </a>
                                </td>
                            </tr>
                        </table>
                    </td>

                    <!-- Right Column: Links -->
                    <td width="40%" valign="top" style="padding-left: 20px;">
                        <ul class="footer-links">
                            <li><a href="{{ url('/about') }}">About BU MADYA</a></li>
                            <li><a href="{{ url('/officers') }}">Our Officers</a></li>
                            <li><a href="{{ url('/transparency') }}">Transparency Board</a></li>
                            <li><a href="{{ url('/privacy-policy') }}" style="color: #ffffff; font-weight: bold;">Privacy Policy</a></li>
                        </ul>
                    </td>
                </tr>
            </table>

            <!-- Copyright -->
            <div class="footer-bottom">
                &copy; {{ date('Y') }} BU MADYA. ALL RIGHTS RESERVED.
            </div>
        </div>

    </div>
</body>
</html>
