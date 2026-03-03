<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome to BU MADYA</title>
</head>
<body style="margin: 0; padding: 0; background-color: #f8fafc; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif; -webkit-font-smoothing: antialiased; line-height: 1.6;">

    <table width="100%" border="0" cellpadding="0" cellspacing="0" style="background-color: #f8fafc; padding: 40px 15px;">
        <tr>
            <td align="center">
                <table width="100%" max-width="600" border="0" cellpadding="0" cellspacing="0" style="background-color: #ffffff; width: 100%; max-width: 600px; border-radius: 20px; overflow: hidden; box-shadow: 0 10px 25px rgba(0, 0, 0, 0.05);">
                    
                    <tr>
                        <td style="background-color: #fef2f2; background: linear-gradient(135deg, rgba(236,72,153,0.1) 0%, rgba(249,115,22,0.15) 35%, rgba(234,179,8,0.15) 65%, rgba(16,185,129,0.1) 100%); padding: 45px 30px 30px 30px; text-align: center; border-bottom: 3px solid #ef4444;">
                            <img src="{{ $message->embed(public_path('images/MADYA Web Logo1.png')) }}" alt="BU MADYA" style="border-radius: 50%; width: 90px; height: 90px; border: 4px solid #ffffff; box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1); margin-bottom: 20px;">
                            <h1 style="margin: 0; font-size: 26px; font-weight: 900; color: #111827; letter-spacing: -0.5px;">Welcome to the Movement!</h1>
                            <p style="margin: 8px 0 0 0; color: #dc2626; font-size: 13px; font-weight: 800; text-transform: uppercase; letter-spacing: 1.5px;">
                                Greetings in the Spirit of Advocacy
                            </p>
                        </td>
                    </tr>

                    <tr>
                        <td style="padding: 35px 35px 20px 35px; color: #4b5563; font-size: 16px;">
                            <p style="margin-top: 0; margin-bottom: 20px; font-size: 18px; color: #1f2937;">
                                Hello, <strong>{{ $application->first_name }}</strong>! 👋
                            </p>
                            
                            <p style="margin-bottom: 20px; line-height: 1.7;">
                                It is with profound enthusiasm that we officially welcome you to the <strong>Bicol University - Movement for the Advancement of Youth-Led Advocacy (BU MADYA)</strong> for Academic Year 2025-2026.
                            </p>
                            
                            <p style="margin-bottom: 30px; line-height: 1.7;">
                                We believe that your first step with our organization serves as a beacon of hope and inspiration for young people. Together, we can create a meaningful impact on our community.
                            </p>

                            <div style="background-color: #dc2626; background: linear-gradient(135deg, #ef4444 0%, #b91c1c 100%); border-radius: 16px; padding: 25px 20px; text-align: center; margin-bottom: 30px; box-shadow: 0 10px 15px -3px rgba(220, 38, 38, 0.25);">
                                <span style="font-size: 11px; text-transform: uppercase; letter-spacing: 2px; color: #fca5a5; font-weight: 800;">Your Role in the Movement</span>
                                <br>
                                <span style="font-size: 24px; font-weight: 900; color: #ffffff; line-height: 1.4; display: block; margin-top: 8px; text-shadow: 0 2px 4px rgba(0,0,0,0.2);">
                                    @if($application->assignedCommittee)
                                        {{ $application->assignedCommittee->name }}
                                    @else
                                        Member-Advocate
                                    @endif
                                </span>
                            </div>

                            <p style="margin-bottom: 35px; line-height: 1.7;">
                                As a new member - advocate of BU MADYA, you are now part of a collective driven by passion, purpose, and synergy. Get ready to learn, lead, and leave a lasting mark this year.
                            </p>

                            <div style="background-color: #f9fafb; border: 1px solid #f3f4f6; border-radius: 16px; padding: 25px 25px 5px 25px; margin-bottom: 35px;">
                                <h3 style="color: #1f2937; margin-top: 0; margin-bottom: 20px; font-size: 18px; font-weight: 800; display: flex; align-items: center;">
                                    <span style="margin-right: 8px;">🚀</span> Ignite Your Advocacy
                                </h3>
                                
                                <table width="100%" border="0" cellpadding="0" cellspacing="0" style="margin-bottom: 15px;">
                                    <tr>
                                        <td width="30" valign="top" style="padding-top: 2px;"><span style="font-size: 18px;">💬</span></td>
                                        <td style="padding-bottom: 20px; color: #4b5563; font-size: 15px; line-height: 1.5;">
                                            <strong style="color: #111827;">Join the Community</strong><br>
                                            Stay connected and get real-time updates.<br>
                                            <a href="https://m.me/j/AbZ_8AzvUOwJqFCx/" style="color: #dc2626; text-decoration: underline; font-weight: bold;">Click here to join the Official Group Chat &rarr;</a>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td width="30" valign="top" style="padding-top: 2px;"><span style="font-size: 18px;">💳</span></td>
                                        <td style="padding-bottom: 20px; color: #4b5563; font-size: 15px; line-height: 1.5;">
                                            <strong style="color: #111827;">Membership Fee</strong><br>
                                            Please prepare the membership fee of <strong>₱100.00</strong>. Collection instructions will be posted in the GC.
                                        </td>
                                    </tr>
                                    <tr>
                                        <td width="30" valign="top" style="padding-top: 2px;"><span style="font-size: 18px;">📢</span></td>
                                        <td style="padding-bottom: 20px; color: #4b5563; font-size: 15px; line-height: 1.5;">
                                            <strong style="color: #111827;">General Assembly</strong><br>
                                            Keep an eye out for the upcoming announcement regarding our first major assembly!
                                        </td>
                                    </tr>
                                </table>
                            </div>

                            <div style="text-align: center; margin-bottom: 20px;">
                                <a href="https://facebook.com/BUMadya" style="background-color: #ef4444; background: linear-gradient(90deg, #ec4899, #ef4444, #eab308); color: #ffffff; text-decoration: none; padding: 16px 32px; border-radius: 50px; font-weight: 800; font-size: 15px; display: inline-block; text-transform: uppercase; letter-spacing: 1px; box-shadow: 0 4px 6px rgba(239, 68, 68, 0.25);">
                                    Visit Our Official Page
                                </a>
                            </div>

                        </td>
                    </tr>

                    <tr>
                        <td style="padding: 0 35px 35px 35px;">
                            <div style="border-top: 2px dashed #f3f4f6; padding-top: 30px; text-align: center;">
                                <p style="margin: 0 0 5px 0; color: #6b7280; font-style: italic; font-size: 15px;">In Synergy and Hope,</p>
                                <p style="margin: 0; color: #111827; font-weight: 900; font-size: 18px;">The Board of Directors</p>
                                <p style="margin: 2px 0 0 0; color: #dc2626; font-weight: 800; font-size: 12px; text-transform: uppercase; letter-spacing: 2px;">BU MADYA</p>
                            </div>

                            <div style="margin-top: 35px; text-align: center;">
                                <p style="font-size: 11px; font-weight: bold; color: #9ca3af; letter-spacing: 1px; text-transform: uppercase; margin-bottom: 12px;">Follow our journey</p>
                                <a href="https://www.facebook.com/BUMadya" style="display: inline-block; color: #6b7280; text-decoration: none; font-size: 13px; font-weight: bold; margin: 0 8px;">Facebook</a> &bull;
                                <a href="https://www.instagram.com/bu_madya" style="display: inline-block; color: #6b7280; text-decoration: none; font-size: 13px; font-weight: bold; margin: 0 8px;">Instagram</a> &bull;
                                <a href="https://www.x.com/BUMadya" style="display: inline-block; color: #6b7280; text-decoration: none; font-size: 13px; font-weight: bold; margin: 0 8px;">X (Twitter)</a>
                            </div>
                        </td>
                    </tr>

                </table>
                
                <p style="text-align: center; color: #9ca3af; font-size: 12px; margin-top: 20px;">
                    © {{ date('Y') }} BU MADYA. All rights reserved.<br>
                    Bicol University, Legazpi City, Philippines.
                </p>
            </td>
        </tr>
    </table>
</body>
</html>