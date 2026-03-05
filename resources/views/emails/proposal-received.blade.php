<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Proposal Received</title>
</head>
<body style="margin: 0; padding: 0; background-color: #f8fafc; font-family: Arial, sans-serif; -webkit-font-smoothing: antialiased;">

    <table width="100%" border="0" cellpadding="0" cellspacing="0" style="background-color: #f8fafc; padding: 40px 15px;">
        <tr>
            <td align="center">
                
                <table width="100%" max-width="600" border="0" cellpadding="0" cellspacing="0" style="background-color: #ffffff; max-width: 600px; border-radius: 16px; overflow: hidden; box-shadow: 0 10px 25px rgba(0, 0, 0, 0.05);">
                    
                    <tr>
                        <td style="background-color: #dc2626; padding: 30px; text-align: center;">
                            <h2 style="margin: 0; color: #ffffff; font-size: 24px; font-weight: bold; letter-spacing: 1px;">Proposal Received</h2>
                        </td>
                    </tr>

                    <tr>
                        <td style="padding: 40px 30px; color: #374151; font-size: 16px; line-height: 1.6;">
                            <p style="margin-top: 0;">Hi <strong>{{ $proposal->contact_person }}</strong>,</p>
                            
                            <p>Thank you for reaching out to BU MADYA! This email is to confirm that we have successfully received the partnership proposal from <strong>{{ $proposal->organization_name }}</strong>.</p>
                            
                            <div style="background-color: #f9fafb; border-left: 4px solid #f59e0b; padding: 15px; margin: 25px 0;">
                                <p style="margin: 0 0 5px 0; font-size: 12px; color: #6b7280; text-transform: uppercase; font-weight: bold;">Proposal Details</p>
                                <p style="margin: 0 0 5px 0;"><strong>Title:</strong> {{ $proposal->title }}</p>
                                <p style="margin: 0;"><strong>Type:</strong> {{ $proposal->partnership_type }}</p>
                            </div>

                            <p>Our Office of External Affairs will review your submission and any attached documents. You can expect to hear back from us within <strong>3 to 5 business days</strong> regarding the next steps.</p>

                            <p style="margin-bottom: 0;">We appreciate your interest in collaborating with us to create a sustainable impact for the youth!</p>
                        </td>
                    </tr>

                    <tr>
                        <td style="background-color: #111827; padding: 20px; text-align: center; color: #9ca3af; font-size: 12px;">
                            <p style="margin: 0;"><strong>BU MADYA</strong> • Bicol University</p>
                            <p style="margin: 5px 0 0 0;">This is an automated confirmation. Please do not reply to this email directly.</p>
                        </td>
                    </tr>
                    
                </table>

            </td>
        </tr>
    </table>
</body>
</html>