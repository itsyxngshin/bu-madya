<!DOCTYPE html>
<html>
<head>
    <style>
        body {
            font-family: Arial, sans-serif;
            color: #333333;
            line-height: 1.6;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }
        .footer {
            margin-top: 30px;
            font-size: 12px;
            color: #777777;
            border-top: 1px solid #eeeeee;
            padding-top: 10px;
        }
    </style>
</head>
<body>
    <div class="container">
        {{-- Renders the custom body text and respects line breaks --}}
        <div>
            {!! nl2br(e($customBody)) !!}
        </div>

        <div class="footer">
            <p>This is an automated message from the BU MADYA Evaluation System. Please do not reply directly to this email.</p>
        </div>
    </div>
</body>
</html>