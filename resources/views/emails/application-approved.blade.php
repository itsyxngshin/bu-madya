@component('mail::message')
# Greetings in the Spirit of Advocacy, {{ $application->first_name }}!

It is with delight and enthusiasm that we welcome you aboard at the **Bicol University - Movement for the Advancement of Youth-Led Advocacy (BU MADYA)** for the Academic Year 2025-2026. We believe that your first step with our organization serves as a beacon of hope and inspiration for young people. Together, we can create a meaningful impact on our community.

Your involvement as a member-advocate in BU MADYA will enable us to build sustainable and inclusive platforms for civic engagement and cultural exchange in the fields of education, science, culture, and youth empowerment. Guided by the SDGs and other significant frameworks, we will continue to strengthen our advocacy efforts within and beyond the Bicol University community by encouraging active participation in socio-civic development.

Together, let us seize the opportunities this Academic Year offers—to learn, explore, thrive, and enjoy meaningful experiences with fellow advocates and friends from diverse backgrounds. Through engagements ranging from workshops to collaborative projects, may our passion and collective hope inspire us to create safer and better spaces for everyone.

As we gear up for what is in store for this year, stay connected and updated with us via our official member-advocates group chat, which you can access through this link:

🔗 **[Click here to join the Group Chat](https://m.me/j/AbZ_8AzvUOwJqFCx/)**

---

{{-- DYNAMIC ASSIGNMENT DISPLAY --}}
@if($application->assignedCommittee)
You have been officially assigned to the:
# {{ $application->assignedCommittee->name }}
@else
You have been accepted as a **General Member**.
@endif

### Next Steps:
1. Please prepare the membership fee of **₱100.00**.
2. Wait for the announcement regarding the General Assembly.

@component('mail::button', ['url' => 'https://facebook.com/BUMadya'])
Visit Our Page
@endcomponent

Thank you for joining us on this journey. May the spirit of hope and compassion continue to guide and inspire us. See you soon! Welcome to the family!

<i>In Synergy and Hope,</i><br>
**Board of Directors**<br>
BU MADYA

<br>

**FOLLOW US ON OUR SOCIAL MEDIA ACCOUNTS:**
☘️ [Facebook](https://www.facebook.com/BUMadya)
🌼 [Instagram](https://www.instagram.com/bu_madya)
⚡ [X (Twitter)](https://www.x.com/BUMadya)

@endcomponent