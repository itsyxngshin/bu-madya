<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <style>
        /* Base Reset */
        body {
            font-family: 'Courier New', Courier, monospace;
            font-family: system-ui, -apple-system, sans-serif;
            background-color: #f3f4f6;
            color: #131011;
            margin: 0;
            padding: 40px 20px;
        }

        /* Brutalist Container */
        .email-wrapper {
            max-width: 600px;
            margin: 0 auto;
            background-color: #ffffff;
            border: 4px solid #131011;
            box-shadow: 10px 10px 0px 0px #0095AC;
            padding: 0;
        }

        /* Dark Header with Logo */
        .header {
            background-color: #131011;
            padding: 30px 20px;
            text-align: center;
            border-bottom: 4px solid #131011;
        }

        .header img {
            max-width: 250px;
            height: auto;
            display: block;
            margin: 0 auto;
        }

        /* Body Content */
        .content {
            padding: 40px 30px;
            font-size: 16px;
            line-height: 1.6;
            background-color: #FFFBF7;
        }

        h2 {
            color: #0095AC;
            text-transform: uppercase;
            margin-top: 0;
            border-bottom: 4px solid #0095AC;
            display: inline-block;
            padding-bottom: 5px;
            letter-spacing: 1px;
            font-size: 24px;
        }

        /* Stylized Team Tag */
        .team-name {
            background-color: #FF8623;
            color: #131011;
            font-weight: bold;
            padding: 2px 8px;
            border: 2px solid #131011;
            text-transform: uppercase;
        }

        /* Brutalist Alert Box */
        .status-box {
            background-color: #ffffff;
            border: 4px solid #131011;
            padding: 20px;
            margin: 30px 0;
            box-shadow: 6px 6px 0px 0px #FF8623;
        }

        .status-title {
            font-weight: bold;
            text-transform: uppercase;
            color: #FF8623;
            margin-bottom: 10px;
            font-size: 14px;
            letter-spacing: 1px;
        }

        .message-block {
            background-color: #f3f4f6;
            border: 2px dashed #131011;
            padding: 15px;
            margin-top: 15px;
            /* Note: white-space: pre-wrap is explicitly removed for HTML compatibility */
        }

        /* Force Email Clients to respect Quill paragraph spacing */
        .message-block p {
            margin: 0 0 4px 0 !important;
            padding: 0 !important;
            display: block !important;
            line-height: 1.4 !important;
        }

        .message-block p:first-of-type { margin-top: 0 !important; }
        .message-block p:last-of-type { margin-bottom: 0 !important; }

        /* Hide the annoying empty `<p><br></p>` tags */
        .message-block p:empty { display: none !important; }

        /* Rich Text Formatting Rules */
        .message-block strong { font-weight: bold; color: #131011; }
        .message-block em { font-style: italic; }
        .message-block u { text-decoration: underline; }
        .message-block ul { margin: 10px 0 10px 20px !important; padding: 0; list-style-type: disc; }
        .message-block ol { margin: 10px 0 10px 20px !important; padding: 0; list-style-type: decimal; }
        .message-block li { margin-bottom: 5px; }
        .message-block a { color: #0095AC; text-decoration: underline; font-weight: bold; }

        /* Footer */
        .footer {
            background-color: #e5e7eb;
            border-top: 4px solid #131011;
            padding: 20px;
            font-size: 12px;
            text-align: center;
            color: #4b5563;
        }

        .important-text {
            font-weight: bold;
            color: #CF452C;
            text-transform: uppercase;
        }
    </style>
</head>
<body>
    <div class="email-wrapper">

        <!-- Header with Embedded Logo -->
        <div class="header">
            <img src="{{ $message->embed(public_path('images/HOI Logo Blue.png')) }}" alt="Heroes of Innovation Challenge 2026">
        </div>

        <div class="content">
            <h2>Priority Dispatch</h2>

            <p>Attention <span class="team-name">{{ $teamName }}</span>,</p>

            <p>An official transmission has been routed to your cohort from the Command Center.</p>

            <!-- Brutalist Status Box for the Message Payload -->
            <div class="status-box">
                <div class="status-title">▶ SUBJECT: {{ $subject }}</div>

                <!-- Raw HTML output bound tightly to the tags to prevent stray indents -->
                <div class="message-block">{!! $messageBody !!}</div>
            </div>

            <p><span class="important-text">Directive Action Required:</span><br>
            Please review the transmission above and coordinate with your cohort accordingly.</p>

            <br>
            <p>Best regards,<br><strong>Heroes of Innovation 2026 Organizing Committee</strong></p>
        </div>

        <!-- Footer -->
        <div class="footer">
            <p style="margin: 0 0 10px 0;"><strong>SYSTEM ALERT:</strong> This is an automated broadcast generated by the Command Center mainframe. Please do not reply directly to this email.</p>
            <p style="margin: 0; font-size: 10px; font-weight: bold; text-transform: uppercase; letter-spacing: 1px; color: #9ca3af;">Powered by BU MADYA Web | Heroes of Innovation 2026</p>
        </div>
    </div>
</body>
</html>
