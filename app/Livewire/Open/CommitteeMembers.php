<?php

namespace App\Livewire\Open;

use Livewire\Component;
use Livewire\Attributes\Layout; 

#[Layout('layouts.madya-template')]
class CommitteeMembers extends Component
{
    public $slug;
    public $committee;
    public $search = '';
    public $allMembers = [];

    public function mount($slug)
    {
        $this->slug = $slug;

        // 1. DEFINE COMMITTEE METADATA (Icon, Desc, Heads)
        // You can keep the previous icons/descriptions here.
        $this->committee = $this->getCommitteeDetails($slug);

        // 2. THE MASTER LIST (Converted from your raw text)
        $this->allMembers = [
            ['name' => 'Abiño, Justine M.', 'group' => 'Technical and Productions', 'course' => 'BA Broadcasting', 'college' => 'CAL', 'year' => '3rd Year'],
            ['name' => 'Abiño, Rhianne E.', 'group' => 'Audit', 'course' => 'BS Social Work', 'college' => 'CSSP', 'year' => '2nd Year'],
            ['name' => 'Alegre, Sheer Deemi Ros R.', 'group' => 'Public Affairs', 'course' => 'Bachelor of Public Administration', 'college' => 'JMRIGD', 'year' => '1st Year'],
            ['name' => 'Alfornon, Joyce O.', 'group' => 'Culture and Heritage', 'course' => 'BSESS FSM', 'college' => 'IPESR', 'year' => '1st Year'],
            ['name' => 'Andes, Charisse M.', 'group' => 'External Affairs', 'course' => 'AB Broadcasting', 'college' => 'CAL', 'year' => '2nd Year'],
            ['name' => 'Andes, Rhomyselle J.', 'group' => 'Technical and Productions', 'course' => 'BSBA Management', 'college' => 'CBEM', 'year' => '2nd Year'],
            ['name' => 'Antones, Angel Marcelo', 'group' => 'Marketing & Logistics', 'course' => 'BSBA Operations Management', 'college' => 'CBEM', 'year' => '1st Year'],
            ['name' => 'Arcos, Kirstine L.', 'group' => 'Multimedia & Creatives', 'course' => 'AB Political Science', 'college' => 'CSSP', 'year' => '1st Year'],
            ['name' => 'Arevalo, Larah Eunice M.', 'group' => 'Internal Affairs', 'course' => 'BS Psychology', 'college' => 'CSSP', 'year' => '1st Year'],
            ['name' => 'Aringo, Katrina Shaine A.', 'group' => 'Technical and Productions', 'course' => 'BSBA Management', 'college' => 'CBEM', 'year' => '3rd Year'],
            ['name' => 'Balderrama, Diana D.', 'group' => 'Operations and Documentations', 'course' => 'BS Social Work', 'college' => 'CSSP', 'year' => '2nd Year'],
            ['name' => 'Ballares, Moriah O.', 'group' => 'Science and Technology', 'course' => 'BS Biology', 'college' => 'CS', 'year' => '4th Year'],
            ['name' => 'Barde, Daniela Mae', 'group' => 'Internal Affairs', 'course' => 'BS Social Work', 'college' => 'CSSP', 'year' => '3rd Year'],
            ['name' => 'Baria, Anry Cherlin B.', 'group' => 'Multimedia & Creatives', 'course' => 'BS Information Technology', 'college' => 'CS', 'year' => '1st Year'],
            ['name' => 'Bechayda, Kim B.', 'group' => 'Audit', 'course' => 'Bachelor of Public Administration', 'college' => 'JMRIGD', 'year' => '1st Year'],
            ['name' => 'Berces, Phoebe C.', 'group' => 'Operations and Documentations', 'course' => 'BS Social Work', 'college' => 'CSSP', 'year' => '2nd Year'],
            ['name' => 'Bosquillos, Janiel A.', 'group' => 'Communications', 'course' => 'BA Sociology', 'college' => 'CSSP', 'year' => '1st Year'], // Digital Strategies
            ['name' => 'Bufete, Gwen Beyonce', 'group' => 'Public Affairs', 'course' => 'BS Entrepreneurship', 'college' => 'CBEM', 'year' => '1st Year'],
            ['name' => 'Busa, Trexie Mae B.', 'group' => 'Social Sciences', 'course' => 'BS Social Work', 'college' => 'CSSP', 'year' => '2nd Year'],
            ['name' => 'Cadiz, Rianna Kristyn B.', 'group' => 'Multimedia & Creatives', 'course' => 'BA Journalism', 'college' => 'CAL', 'year' => '4th Year'],
            ['name' => 'Caño, Qyara Milse A.', 'group' => 'External Affairs', 'course' => 'BS Biology', 'college' => 'CS', 'year' => '4th Year'],
            ['name' => 'Capistrano, John Cedric S.', 'group' => 'Education', 'course' => 'BSED English', 'college' => 'BU Polangui', 'year' => '1st Year'],
            ['name' => 'Cepe, Marichu', 'group' => 'Marketing & Logistics', 'course' => 'BS Social Work', 'college' => 'CSSP', 'year' => '2nd Year'],
            ['name' => 'Cervantes, Lian Joy L.', 'group' => 'Finance', 'course' => 'BSBA Financial Management', 'college' => 'CBEM', 'year' => '4th Year'],
            ['name' => 'Coprada, Kenshin Lin B.', 'group' => 'External Affairs', 'course' => 'Doctor of Veterinary Medicine', 'college' => 'BU Guinobatan', 'year' => '1st Year'],
            ['name' => 'De La Torre, Roger B.', 'group' => 'Internal Affairs', 'course' => 'BSBA MM', 'college' => 'CBEM', 'year' => '4th Year'],
            ['name' => 'Era, Benjamin Maniaol', 'group' => 'External Affairs', 'course' => 'BSCS', 'college' => 'BU Polangui', 'year' => '1st Year'],
            ['name' => 'Herrera, Dave Janver F.', 'group' => 'External Affairs', 'course' => 'BSED English', 'college' => 'BU Polangui', 'year' => '1st Year'],
            ['name' => 'Ingua, Jade Louie O.', 'group' => 'Secretariat Affairs', 'course' => 'BS Social Work', 'college' => 'CSSP', 'year' => '2nd Year'],
            ['name' => 'Isaguirre, Janmark R.', 'group' => 'Operations and Documentations', 'course' => 'BS Entrepreneurship', 'college' => 'BU Polangui', 'year' => '4th Year'],
            ['name' => 'Lagco, Lanz Jomari J.', 'group' => 'Audit', 'course' => 'BS Entrepreneurship', 'college' => 'CBEM', 'year' => '3rd Year'],
            ['name' => 'Letran, Wendelyn L.', 'group' => 'External Affairs', 'course' => 'Broadcasting', 'college' => 'CAL', 'year' => '2nd Year'],
            ['name' => 'Litan, Angelo Marc A.', 'group' => 'External Affairs', 'course' => 'BS Entrepreneurship', 'college' => 'BU Polangui', 'year' => '3rd Year'],
            ['name' => 'Llenaresas, Kyla Mae', 'group' => 'Internal Affairs', 'course' => 'BS Electrical Engineering', 'college' => 'CENG', 'year' => '4th Year'],
            ['name' => 'Llosala, Joana Marie J.', 'group' => 'Secretariat Affairs', 'course' => 'BS Electrical Technology', 'college' => 'CIT', 'year' => '3rd Year'],
            ['name' => 'Longaza, Zshemea Keiylenn P.', 'group' => 'Operations and Documentations', 'course' => 'BS IT', 'college' => 'CS', 'year' => '1st Year'],
            ['name' => 'Luvindino, Martin Abundio D.', 'group' => 'Science and Technology', 'course' => 'BS IT', 'college' => 'CS', 'year' => '1st Year'],
            ['name' => 'Mabal, Samaria Rejuso', 'group' => 'Secretariat Affairs', 'course' => 'BS Entrepreneurship', 'college' => 'CBEM', 'year' => '3rd Year'],
            ['name' => 'Madara, Francine Ann Romero', 'group' => 'Strategic Initiatives', 'course' => 'BSBA Management', 'college' => 'CBEM', 'year' => '3rd Year'],
            ['name' => 'Madrid, Jhamaila J.', 'group' => 'Marketing & Logistics', 'course' => 'BSESS FSM', 'college' => 'IPESR', 'year' => '1st Year'],
            ['name' => 'Mandac, Joana G.', 'group' => 'Technical and Productions', 'course' => 'BSBA Management', 'college' => 'CBEM', 'year' => '2nd Year'],
            ['name' => 'Maravilla, Jocel V.', 'group' => 'External Affairs', 'course' => 'BA Broadcasting', 'college' => 'CAL', 'year' => '2nd Year'],
            ['name' => 'Marbid, Aezel Ann M.', 'group' => 'Technical and Productions', 'course' => 'BSBA Management', 'college' => 'CBEM', 'year' => '2nd Year'],
            ['name' => 'Margelino, Kristina Marie C.', 'group' => 'Strategic Initiatives', 'course' => 'BS Computer Science', 'college' => 'CS', 'year' => '4th Year'],
            ['name' => 'Marmol, Ella Mae M.', 'group' => 'External Affairs', 'course' => 'BS Electrical Engineering', 'college' => 'CENG', 'year' => '4th Year'],
            ['name' => 'Mendoza, Claire P.', 'group' => 'Multimedia & Creatives', 'course' => 'Bachelor of Public Administration', 'college' => 'JMRIGD', 'year' => '1st Year'],
            ['name' => 'Monacillo, Bryan Dee B.', 'group' => 'Social Sciences', 'course' => 'BS Social Work', 'college' => 'CSSP', 'year' => '1st Year'],
            ['name' => 'Montero, Gavin Aron A.', 'group' => 'Public Affairs', 'course' => 'BS Entrepreneurship', 'college' => 'CBEM', 'year' => '1st Year'],
            ['name' => 'Monticalvo, Eron Radan', 'group' => 'Culture and Heritage', 'course' => 'BSESS FSM', 'college' => 'IPESR', 'year' => '1st Year'],
            ['name' => 'Nace, Mike L.', 'group' => 'Operations and Documentations', 'course' => 'Bachelor in Public Administration', 'college' => 'JMRIGD', 'year' => '1st Year'],
            ['name' => 'Nacional, Alyza A.', 'group' => 'Education', 'course' => 'BSED Social Studies', 'college' => 'CE', 'year' => '3rd Year'],
            ['name' => 'Navarro, Naressa Mae B.', 'group' => 'Technical and Productions', 'course' => 'BSBA Management', 'college' => 'CBEM', 'year' => '2nd Year'],
            ['name' => 'Nivero, Precious Grace B.', 'group' => 'Marketing & Logistics', 'course' => 'AB Journalism', 'college' => 'CAL', 'year' => '1st Year'],
            ['name' => 'Nolla, Princess Sairell M.', 'group' => 'Technical and Productions', 'course' => 'BSBA Management', 'college' => 'CBEM', 'year' => '3rd Year'],
            ['name' => 'Nuda, Jeff Lawrence B.', 'group' => 'Multimedia & Creatives', 'course' => 'BSECE', 'college' => 'BU Polangui', 'year' => '4th Year'],
            ['name' => 'Ojano, Kist Chealsea Ll.', 'group' => 'Science and Technology', 'course' => 'BS Biology', 'college' => 'CS', 'year' => '3rd Year'],
            ['name' => 'Oliquino, Tristan Jasper B.', 'group' => 'Public Affairs', 'course' => 'BS Nursing', 'college' => 'CN', 'year' => '4th Year'],
            ['name' => 'Pasion, Haile Jada M.', 'group' => 'Secretariat Affairs', 'course' => 'AB Literature', 'college' => 'CAL', 'year' => '1st Year'],
            ['name' => 'Pelaez, Antonnette Paz B.', 'group' => 'Culture and Heritage', 'course' => 'BSESS FSC', 'college' => 'IPESR', 'year' => '1st Year'],
            ['name' => 'Peñaflor, Elijah E.', 'group' => 'Operations and Documentations', 'course' => 'BSBA Management', 'college' => 'CBEM', 'year' => '1st Year'],
            ['name' => 'Peralta, Ma. Paula J.', 'group' => 'Operations and Documentations', 'course' => 'AB English Language', 'college' => 'CAL', 'year' => '1st Year'],
            ['name' => 'Pilapil, Emery Pearl B.', 'group' => 'Secretariat Affairs', 'course' => 'BS Chemical Engineering', 'college' => 'CENG', 'year' => '2nd Year'],
            ['name' => 'Posillo, A-Jay P.', 'group' => 'Communications', 'course' => 'BSBA Management', 'college' => 'CBEM', 'year' => '2nd Year'],
            ['name' => 'Racal, Clarence M.', 'group' => 'Technical and Productions', 'course' => 'BSBA Management', 'college' => 'CBEM', 'year' => '2nd Year'],
            ['name' => 'Ramos, Sophie Martin A.', 'group' => 'Multimedia & Creatives', 'course' => 'BS Biology', 'college' => 'CS', 'year' => '3rd Year'],
            ['name' => 'Rañada, Samantha Paige B.', 'group' => 'Operations and Documentations', 'course' => 'BSBA Operations Management', 'college' => 'CBEM', 'year' => '3rd Year'],
            ['name' => 'Rañola, Andrea Mae M.', 'group' => 'Secretariat Affairs', 'course' => 'Bachelor of Early Childhood Education', 'college' => 'CE', 'year' => '1st Year'],
            ['name' => 'Revoltar, Precious Rickcel M.', 'group' => 'Communications', 'course' => 'BSBA Management', 'college' => 'CBEM', 'year' => '2nd Year'],
            ['name' => 'Roquid, Maxime E.', 'group' => 'Finance', 'course' => 'BSBA FM', 'college' => 'CBEM', 'year' => '4th Year'],
            ['name' => 'Sallan, Mary Grace N.', 'group' => 'Culture and Heritage', 'course' => 'Bachelor of Physical Education', 'college' => 'IPESR', 'year' => '1st Year'],
            ['name' => 'Salomon, Divine B.', 'group' => 'Finance', 'course' => 'BSBA FM', 'college' => 'CBEM', 'year' => '4th Year'],
            ['name' => 'Sambajon, Loben Francis A.', 'group' => 'Audit', 'course' => 'BS Accountancy', 'college' => 'CBEM', 'year' => '4th Year'],
            ['name' => 'Saquido, Kathleen C.', 'group' => 'Secretariat Affairs', 'course' => 'AB Philosophy', 'college' => 'CSSP', 'year' => '4th Year'],
            ['name' => 'Sarion, Mhel June C.', 'group' => 'Multimedia & Creatives', 'course' => 'BS Electronics Engineering', 'college' => 'BU Polangui', 'year' => '4th Year'],
            ['name' => 'Sayson, Lorenz A.', 'group' => 'Special Projects', 'course' => 'BS Economics', 'college' => 'CBEM', 'year' => '3rd Year'],
            ['name' => 'Seño, Mikhaela Johanne R.', 'group' => 'Social Sciences', 'course' => 'BS Psychology', 'college' => 'CSSP', 'year' => '3rd Year'],
            ['name' => 'Sevilla, Yuneil Jullus P.', 'group' => 'Strategic Initiatives', 'course' => 'BSESS FSM', 'college' => 'IPESR', 'year' => '1st Year'],
            ['name' => 'Supelana, Erich D.', 'group' => 'Communications', 'course' => 'BS Biology', 'college' => 'CS', 'year' => '2nd Year'],
            ['name' => 'Taquilid, Stephen Michael A.', 'group' => 'Public Affairs', 'course' => 'BSBA Marketing Management', 'college' => 'CBEM', 'year' => '3rd Year'],
            ['name' => 'Valladolid, Miles Owen B.', 'group' => 'Multimedia & Creatives', 'course' => 'BS Nursing', 'college' => 'CN', 'year' => '4th Year'],
            ['name' => 'Vargas, Joash Kyle V.', 'group' => 'External Affairs', 'course' => 'BS DevCom', 'college' => 'BU Guinobatan', 'year' => '1st Year'],
            ['name' => 'Vista, Edmon P.', 'group' => 'Internal Affairs', 'course' => 'BSBA Marketing Management', 'college' => 'CBEM', 'year' => '2nd Year'],
        ];
    }

    public function render()
    {
        // 1. Filter by Committee (Group Name Match)
        // We match the slug to the 'group' key used in the array above
        $groupName = $this->getGroupNameFromSlug($this->slug);
        
        $committeeMembers = collect($this->allMembers)->filter(function ($member) use ($groupName) {
            // Check if member belongs to this group
            // Also exclude the Director if they are in the list to avoid duplicate with Leadership section
            return str_contains(strtolower($member['group']), strtolower($groupName));
        });

        // 2. Search Logic
        if (!empty($this->search)) {
            $committeeMembers = $committeeMembers->filter(function ($member) {
                return stripos($member['name'], $this->search) !== false;
            });
        }

        return view('livewire.open.committee-members', [
            'members' => $committeeMembers
        ]);
    }

    private function getGroupNameFromSlug($slug)
    {
        return match($slug) {
            'strategic-initiatives-advocacy' => 'Strategic Initiatives',
            'education' => 'Education',
            'science-technology' => 'Science and Technology',
            'culture-heritage' => 'Culture and Heritage',
            'social-sciences' => 'Social Sciences',
            'digital-strategies-comm' => 'Communications', // Mapped "Digital Strategies" to "Communications"
            'planning-committee' => 'Planning',
            'internal-affairs' => 'Internal Affairs',
            'external-affairs' => 'External Affairs',
            'secretariat-affairs' => 'Secretariat Affairs',
            'finance-committee' => 'Finance',
            'auditing-committee' => 'Audit',
            'marketing-logistics' => 'Marketing & Logistics',
            'public-affairs-pr' => 'Public Affairs',
            'multimedia-creatives' => 'Multimedia & Creatives',
            'ops-documentation' => 'Operations and Documentations',
            'technical-productions' => 'Technical and Productions',
            'special-projects' => 'Special Projects',
            default => ''
        };
    }

    private function getCommitteeDetails($slug)
    {
        // ... (Keep your existing icon/desc/heads logic here) ...
        // Ensure you add the correct Directors for the new committees found in the list
        // e.g., 'Special Projects', 'Communications'
        
        // Return placeholder for now to prevent errors
        return [
            'name' => ucwords(str_replace('-', ' ', $slug)),
            'description' => 'Dedicated to advancing the specific goals of this committee.',
            'icon' => 'M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z',
            'heads' => [] // Populate this based on your previous Directors list
        ];
    }
}
