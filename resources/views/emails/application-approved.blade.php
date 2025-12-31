@component('mail::message')
{{-- HEADER IMAGE / LOGO (Optional: Add your logo URL here) --}}
<div style="text-align: center; margin-bottom: 20px;">
    <img src="https://ui-avatars.com/api/?name=BU+MADYA&background=fee2e2&color=dc2626&size=100&font-size=0.33" alt="BU MADYA" style="border-radius: 50%; width: 80px; height: 80px; border: 4px solid #fff; box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);">
</div>

<h1 style="text-align: center; font-size: 24px; color: #1f2937; margin-bottom: 5px;">
    Greetings in the Spirit of Advocacy!
</h1>
<p style="text-align: center; color: #dc2626; font-weight: bold; font-size: 18px; margin-top: 0;">
    Hello, {{ $application->first_name }}
</p>

It is with delight and enthusiasm that we welcome you aboard at the **Bicol University - Movement for the Advancement of Youth-Led Advocacy (BU MADYA)** for Academic Year 2025-2026.

We believe that your first step with our organization serves as a beacon of hope and inspiration for young people. Together, we can create a meaningful impact on our community.

{{-- FEATURED PANEL: COMMITTEE ASSIGNMENT --}}
@component('mail::panel')
<div style="text-align: center;">
    <span style="font-size: 12px; text-transform: uppercase; letter-spacing: 1px; color: #6b7280;">Official Designation</span>
    <br>
    @if($application->assignedCommittee)
        <span style="font-size: 20px; font-weight: 800; color: #dc2626; line-height: 1.4;">
            {{ $application->assignedCommittee->name }}
        </span>
    @else
        <span style="font-size: 20px; font-weight: 800; color: #dc2626;">
            General Member
        </span>
    @endif
</div>
@endcomponent

Together, let us seize the opportunities this year offers—to learn, explore, thrive, and enjoy meaningful experiences. Through workshops and collaborative projects, may our passion inspire safer and better spaces for everyone.

---

### 🚀 Get Started

**1. Join the Community**
Stay connected via our official group chat:
<br>
<a href="https://m.me/j/AbZ_8AzvUOwJqFCx/" style="color: #dc2626; text-decoration: none; font-weight: bold;">
    👉 Click here to join the Group Chat
</a>

**2. Next Steps**
* Prepare the membership fee of **₱100.00**.
* Wait for the announcement regarding the General Assembly.

<br>

@component('mail::button', ['url' => 'https://facebook.com/BUMadya', 'color' => 'red'])
Visit Our Official Page
@endcomponent

Thank you for joining us on this journey. Welcome to the family!

<br>

<div style="border-top: 1px solid #e5e7eb; padding-top: 20px; margin-top: 20px;">
    <p style="margin-bottom: 5px;"><i>In Synergy and Hope,</i></p>
    <strong>Board of Directors</strong><br>
    <span style="color: #dc2626;">BU MADYA</span>
</div>

<div style="margin-top: 20px; font-size: 12px; color: #6b7280;">
    <strong>FOLLOW US:</strong>&nbsp;&nbsp;
    <a href="https://www.facebook.com/BUMadya" style="color: #6b7280; text-decoration: none;">Facebook</a> &bull;
    <a href="https://www.instagram.com/bu_madya" style="color: #6b7280; text-decoration: none;">Instagram</a> &bull;
    <a href="https://www.x.com/BUMadya" style="color: #6b7280; text-decoration: none;">X (Twitter)</a>
</div>
@endcomponent