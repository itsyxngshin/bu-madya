@component('mail::message')

{{-- BANNER HEADER --}}
<div style="background-color: #fef2f2; padding: 35px 20px; text-align: center; border-radius: 12px 12px 0 0; border-bottom: 4px solid #dc2626;">
    <img src="{{ asset('images/MADYA Web Logo1.png') }}" alt="BU MADYA Logo" style="width: 80px; height: 80px; margin-bottom: 15px; border-radius: 50%; border: 3px solid #ffffff; box-shadow: 0 4px 6px rgba(0,0,0,0.1);">
    <h1 style="margin: 0; color: #1f2937; font-size: 26px; font-weight: 900; letter-spacing: -0.5px;">Welcome to the Movement!</h1>
    <p style="margin: 8px 0 0 0; color: #dc2626; font-size: 14px; font-weight: 700; text-transform: uppercase; letter-spacing: 1.5px;">Greetings in the Spirit of Advocacy</p>
</div>

{{-- BODY CONTENT --}}
<div style="padding: 25px 5px 10px 5px;">
    <p style="font-size: 18px; color: #374151; margin-bottom: 20px;">
        Hello, <strong>{{ $application->first_name }}</strong>! 👋
    </p>
    <p style="color: #4b5563; line-height: 1.6; font-size: 16px;">
        It is with great delight and enthusiasm that we officially welcome you aboard the <strong>Bicol University - Movement for the Advancement of Youth-Led Advocacy (BU MADYA)</strong> for the Academic Year 2025-2026.
    </p>
    <p style="color: #4b5563; line-height: 1.6; font-size: 16px;">
        We believe that your first step with our organization serves as a beacon of hope and inspiration for young people. Together, we can create a meaningful impact on our community.
    </p>
</div>

{{-- OFFICIAL DESIGNATION BADGE --}}
<div style="background-color: #dc2626; background: linear-gradient(135deg, #dc2626, #991b1b); border-radius: 16px; padding: 30px 20px; text-align: center; margin: 25px 0; color: #ffffff; box-shadow: 0 10px 15px -3px rgba(220, 38, 38, 0.3);">
    <p style="margin: 0 0 8px 0; font-size: 11px; text-transform: uppercase; letter-spacing: 2.5px; color: #fca5a5; font-weight: 800;">Your Official Designation</p>
    <h2 style="margin: 0; font-size: 28px; font-weight: 900; text-shadow: 0 2px 4px rgba(0,0,0,0.2);">
        @if($application->assignedCommittee)
            {{ $application->assignedCommittee->name }}
        @else
            Member-Advocate
        @endif
    </h2>
</div>

<p style="color: #4b5563; line-height: 1.6; font-size: 16px; margin-bottom: 30px;">
    Together, let us seize the opportunities this year offers—to learn, explore, thrive, and enjoy meaningful experiences. Through workshops and collaborative projects, may our passion inspire safer and better spaces for everyone.
</p>

{{-- NEXT STEPS CHECKLIST --}}
<div style="background-color: #f9fafb; border-radius: 16px; padding: 25px; margin-bottom: 30px; border-left: 5px solid #dc2626;">
    <h3 style="margin-top: 0; color: #1f2937; font-size: 18px; margin-bottom: 20px; font-weight: 800;">🚀 Your Next Steps</h3>

    <table style="width: 100%; border-collapse: collapse;">
        <tr>
            <td style="padding-bottom: 18px; vertical-align: top; width: 35px;"><span style="font-size: 22px;">💬</span></td>
            <td style="padding-bottom: 18px; color: #4b5563; line-height: 1.5; font-size: 15px;">
                <strong style="color: #1f2937;">Join the Community</strong><br>
                Stay connected and get real-time updates via our official group chat.<br>
                <a href="https://m.me/j/AbZ_8AzvUOwJqFCx/" style="color: #dc2626; text-decoration: none; font-weight: bold;">👉 Click here to join the GC</a>
            </td>
        </tr>
        <tr>
            <td style="padding-bottom: 18px; vertical-align: top;"><span style="font-size: 22px;">💳</span></td>
            <td style="padding-bottom: 18px; color: #4b5563; line-height: 1.5; font-size: 15px;">
                <strong style="color: #1f2937;">Membership Fee</strong><br>
                Please prepare the membership fee of <strong>₱100.00</strong>. Collection details will be announced soon.
            </td>
        </tr>
        <tr>
            <td style="vertical-align: top;"><span style="font-size: 22px;">📢</span></td>
            <td style="color: #4b5563; line-height: 1.5; font-size: 15px;">
                <strong style="color: #1f2937;">General Assembly</strong><br>
                Keep an eye out for the upcoming announcement regarding our first General Assembly!
            </td>
        </tr>
    </table>
</div>

@component('mail::button', ['url' => 'https://facebook.com/BUMadya', 'color' => 'red'])
Visit Our Official Page
@endcomponent

<br>

{{-- FOOTER / SIGN-OFF --}}
<div style="text-align: center; padding-top: 30px; margin-top: 20px; border-top: 1px solid #e5e7eb;">
    <p style="margin: 0; color: #6b7280; font-size: 15px; font-style: italic;">In Synergy and Hope,</p>
    <p style="margin: 5px 0 0 0; color: #1f2937; font-size: 18px; font-weight: 900;">The Board of Directors</p>
    <p style="margin: 0; color: #dc2626; font-size: 13px; font-weight: 800; text-transform: uppercase; letter-spacing: 2px;">BU MADYA</p>

    <div style="margin-top: 30px;">
        <span style="font-size: 11px; font-weight: bold; color: #9ca3af; letter-spacing: 1px; text-transform: uppercase; display: block; margin-bottom: 10px;">Follow our journey</span>
        <a href="https://www.facebook.com/BUMadya" style="display: inline-block; background-color: #f3f4f6; color: #4b5563; text-decoration: none; padding: 8px 16px; border-radius: 20px; font-size: 12px; font-weight: bold; margin: 0 4px;">Facebook</a>
        <a href="https://www.instagram.com/bu_madya" style="display: inline-block; background-color: #f3f4f6; color: #4b5563; text-decoration: none; padding: 8px 16px; border-radius: 20px; font-size: 12px; font-weight: bold; margin: 0 4px;">Instagram</a>
        <a href="https://www.x.com/BUMadya" style="display: inline-block; background-color: #f3f4f6; color: #4b5563; text-decoration: none; padding: 8px 16px; border-radius: 20px; font-size: 12px; font-weight: bold; margin: 0 4px;">X (Twitter)</a>
    </div>
</div>

@endcomponent
