<!DOCTYPE html>
<html>
<head>
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; max-width: 600px; margin: 0 auto; padding: 20px; }
        .header { background-color: #0095AC; color: white; padding: 20px; text-align: center; border-radius: 5px 5px 0 0; }
        .content { background-color: #f9f9f9; padding: 20px; border-left: 1px solid #ddd; border-right: 1px solid #ddd; border-bottom: 1px solid #ddd; border-radius: 0 0 5px 5px; }
        .team-name { font-weight: bold; color: #CF452C; }
        .footer { margin-top: 20px; font-size: 12px; color: #777; text-align: center; }
    </style>
</head>
<body>
    <div class="header">
        <h2>Application Received</h2>
    </div>
    <div class="content">
        <p>Hi <strong>{{ $teamLeader['full_name'] }}</strong>,</p>
        <p>Thank you for registering <span class="team-name">{{ $registration->team_name }}</span> for the Heroes of Innovation Challenge 2026!</p>
        <p>We have successfully received your cohort's application. The BU MADYA Secretariat is currently evaluating your submission.</p>
        <p><strong>What's Next?</strong><br>
        Once your team is verified and approved, we will send another email containing your official Community Center login credentials. Please ensure your team members check their inboxes (and spam folders) regularly.</p>
        <p>Stay tuned for further updates!</p>
        <br>
        <p>Best regards,<br><strong>Heroes of Innovation 2026 Secretariat</strong></p>
    </div>
    <div class="footer">
        This is an automated message. Please do not reply directly to this email.
    </div>
</body>
</html>