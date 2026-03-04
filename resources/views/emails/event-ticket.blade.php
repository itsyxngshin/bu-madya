<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Your Event Ticket</title>
</head>
<body style="margin: 0; padding: 0; background-color: #f8fafc; font-family: Arial, sans-serif; -webkit-font-smoothing: antialiased;">

    <table width="100%" border="0" cellpadding="0" cellspacing="0" style="background-color: #f8fafc; padding: 40px 15px;">
        <tr>
            <td align="center">
                
                <table width="100%" max-width="600" border="0" cellpadding="0" cellspacing="0" style="background-color: #ffffff; max-width: 500px; border-radius: 20px; overflow: hidden; box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);">
                    
                    <tr>
                        <td style="background-color: #111827; padding: 30px; text-align: center;">
                            <h2 style="margin: 0; color: #ffffff; font-size: 24px; font-weight: bold; letter-spacing: 1px;">YOU'RE IN!</h2>
                            <p style="margin: 5px 0 0 0; color: #9ca3af; font-size: 14px;">Present this ticket at the entrance.</p>
                        </td>
                    </tr>

                    <tr>
                        <td style="padding: 30px 30px 15px 30px; text-align: center;">
                            <h3 style="margin: 0 0 15px 0; font-size: 20px; color: #111827; line-height: 1.4;">{{ $event->title }}</h3>
                            <p style="margin: 0 0 5px 0; color: #4b5563; font-size: 14px;">
                                <strong>Date:</strong> {{ $event->start_date ? $event->start_date->format('l, F j, Y \a\t g:i A') : 'TBA' }}
                            </p>
                            <p style="margin: 0; color: #4b5563; font-size: 14px;">
                                <strong>Location:</strong> {{ $event->location }}
                            </p>
                        </td>
                    </tr>

                    <tr>
                        <td style="padding: 20px 30px 40px 30px; text-align: center;">
                            
                            @php
                                // We use QuickChart.io to generate a static QR code image for emails.
                                // The gradient goes from Red (ef4444) to Green (22c55e).
                                $qrUrl = "https://quickchart.io/qr?text=" . urlencode($registration->ticket_code) . "&size=250&dark=ef4444&light=ffffff";
                            @endphp
                            
                            <div style="background-color: #ffffff; border: 2px solid #e5e7eb; border-radius: 16px; padding: 15px; display: inline-block; margin-bottom: 15px;">
                                <img src="{{ $qrUrl }}" alt="Ticket QR Code" style="display: block; width: 200px; height: 200px;">
                            </div>
                            
                            <p style="margin: 0; font-family: monospace; font-size: 18px; font-weight: bold; letter-spacing: 4px; color: #6b7280;">
                                {{ $registration->ticket_code }}
                            </p>
                        </td>
                    </tr>

                    <tr>
                        <td style="padding: 0 30px;">
                            <div style="border-top: 2px dashed #e5e7eb; margin: 0; width: 100%;"></div>
                        </td>
                    </tr>

                    <tr>
                        <td style="padding: 30px; background-color: #f9fafb;">
                            <table width="100%" border="0" cellpadding="0" cellspacing="0">
                                <tr>
                                    <td width="50%" style="padding-bottom: 15px;">
                                        <p style="margin: 0 0 2px 0; font-size: 10px; color: #6b7280; text-transform: uppercase; font-weight: bold; letter-spacing: 1px;">Name</p>
                                        <p style="margin: 0; font-size: 16px; color: #111827; font-weight: bold;">{{ $registration->name }}</p>
                                    </td>
                                    <td width="50%" style="padding-bottom: 15px;">
                                        <p style="margin: 0 0 2px 0; font-size: 10px; color: #6b7280; text-transform: uppercase; font-weight: bold; letter-spacing: 1px;">Classification</p>
                                        <p style="margin: 0; font-size: 14px; color: #111827;">{{ $registration->classification }}</p>
                                    </td>
                                </tr>
                                @if($registration->organization_name)
                                <tr>
                                    <td colspan="2">
                                        <p style="margin: 0 0 2px 0; font-size: 10px; color: #6b7280; text-transform: uppercase; font-weight: bold; letter-spacing: 1px;">Affiliation</p>
                                        <p style="margin: 0; font-size: 14px; color: #111827;">{{ $registration->organization_name }}</p>
                                    </td>
                                </tr>
                                @endif
                            </table>
                        </td>
                    </tr>
                    
                </table>

                <p style="text-align: center; color: #9ca3af; font-size: 12px; margin-top: 20px;">
                    This is an automated message from BU MADYA.<br>
                    Please do not reply to this email.
                </p>

            </td>
        </tr>
    </table>
</body>
</html>