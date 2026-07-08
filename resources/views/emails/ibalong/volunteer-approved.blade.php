<!DOCTYPE html>
<html>
<head>
    <style>
        body { 
            font-family: Arial, sans-serif; 
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
            width: 250px; /* Keeps the logo at a reasonable size */
            height: auto;
        }
        .container { 
            background-color: #ffffff; 
            border: 4px solid #131011; 
            box-shadow: 6px 6px 0px 0px #0095AC; 
            padding: 30px; 
        }
        h1 { 
            text-transform: uppercase; 
            border-bottom: 4px solid #FF8623; 
            padding-bottom: 10px; 
            font-weight: 900; 
            margin-top: 0;
        }
        p {
            line-height: 1.6;
        }
        .highlight { 
            background-color: #0095AC; 
            color: #ffffff; 
            padding: 5px 10px; 
            font-weight: bold; 
            text-transform: uppercase; 
        }
        .footer { 
            margin-top: 30px; 
            padding-top: 20px; 
            border-top: 2px dashed #131011; 
            font-size: 12px; 
            font-weight: bold; 
            text-transform: uppercase; 
            color: #6b7280; 
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="wrapper">
        <!-- Header with the Event Logo -->
        <div class="header">
            <img src="{{ url('images/HOI Logo Blue.png') }}" alt="Heroes of Innovation Challenge 2026">
        </div>
        
        <div class="container">
            <h1>Welcome to the Roster!</h1>
            
            <p>Hello <strong>{{ $member->name }}</strong>,</p>
            
            <p>Thank you for answering the call! We are thrilled to inform you that your volunteer application has been reviewed and you have been officially assigned to the <span class="highlight">{{ $member->committee->name }}</span>.</p>
            
            <p>As a <strong>{{ $member->role }}</strong>, you will play a crucial role in bringing the Heroes of Innovation 2026 to life.</p>
            
            <p><strong>What happens next?</strong><br>
            Your Committee Head or a member of the Organizing Team will be reaching out to you shortly via this email or your registered mobile number ({{ $member->mobile_number }}) for your onboarding schedule and initial tasks.</p>

            <p>In the meantime, your profile is now officially live on the public roster.</p>

            <p>Let's build something amazing,<br>
            <strong>Heroes of Innovation 2026 Volunteer Committee</strong></p>

            <div class="footer">
                Powered by BU MADYA Web | Data Privacy Act Compliant
            </div>
        </div>
    </div>
</body>
</html>