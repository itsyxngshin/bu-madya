<div class="min-h-screen font-sans text-gray-900 pb-20">
    
    {{-- HEADER & TOOLBAR (Same as before) --}}
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-8">
        <div>
            <h1 class="font-heading font-black text-2xl text-gray-800 tracking-tight">
                User <span class="text-red-600">Management</span>
            </h1>
            <p class="text-xs text-gray-500 font-bold uppercase tracking-widest mt-1">
                Roles, Assignments & Profiles
            </p>
        </div>
        <button wire:click="$set('isCreateModalOpen', true)" 
           class="px-5 py-2.5 bg-gray-900 text-white text-xs font-bold uppercase rounded-xl shadow-lg hover:bg-red-600 transition flex items-center gap-2">
            <span>+ Add User</span>
        </button>
    </div>

    <div class="bg-white p-4 rounded-2xl shadow-sm border border-gray-100 mb-6 flex flex-col md:flex-row gap-4 items-center justify-between">
        <div class="relative w-full md:w-96">
            <input wire:model.live.debounce.300ms="search" type="text" placeholder="Search users..." 
                   class="w-full pl-4 pr-10 py-2 border-gray-200 rounded-xl text-sm focus:ring-red-500">
        </div>
        <div class="flex items-center gap-2 w-full md:w-auto">
            <select wire:model.live="roleFilter" class="block w-full pl-3 pr-8 py-2 text-xs font-bold border-gray-200 rounded-xl bg-gray-50">
                <option value="">All Roles</option>
                @foreach($roles as $role) <option value="{{ $role->id }}">{{ $role->role_name }}</option> @endforeach
            </select>
            <select wire:model.live="statusFilter" class="block w-full pl-3 pr-8 py-2 text-xs font-bold border-gray-200 rounded-xl bg-gray-50">
                <option value="">All Status</option>
                <option value="active">Active</option>
                <option value="suspended">Suspended</option>
            </select>
        </div>
    </div>

    {{-- USERS TABLE --}}
    <div class="bg-white rounded-[2rem] shadow-sm border border-gray-100 overflow-hidden">
        <table class="min-w-full divide-y divide-gray-100">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-4 text-left text-[10px] font-bold text-gray-400 uppercase tracking-widest">User Details</th>
                    <th class="px-6 py-4 text-left text-[10px] font-bold text-gray-400 uppercase tracking-widest">Role / College</th>
                    <th class="px-6 py-4 text-center text-[10px] font-bold text-gray-400 uppercase tracking-widest">Status</th>
                    <th class="px-6 py-4 text-right text-[10px] font-bold text-gray-400 uppercase tracking-widest">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($users as $user)
                <tr class="group hover:bg-stone-50 transition">
                    {{-- User --}}
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="flex items-center gap-3">
                            <img class="h-10 w-10 rounded-full object-cover border border-gray-200" src="{{ asset($user->profile_photo_path) }}" alt="{{ $user->name }}" />
                            <div>
                                <div class="text-sm font-bold text-gray-900">{{ $user->name }}</div>
                                <div class="text-[10px] text-gray-500">{{ $user->email }}</div>
                            </div>
                        </div>
                    </td>

                    {{-- College / Assignment --}}
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="flex flex-col">
                            {{-- Active Assignment Badge --}}
                            @php
                                $currentAssign = $user->directorAssignment()->whereHas('academicYear', fn($q) => $q->where('is_active', true))->first();
                                $currentComm = $user->committeeMember()->whereHas('academicYear', fn($q) => $q->where('is_active', true))->first();
                            @endphp

                            @if($currentAssign)
                                <span class="self-start inline-flex items-center gap-1 px-2 py-0.5 mb-1 rounded bg-blue-50 text-blue-700 text-[9px] font-bold uppercase border border-blue-100">
                                    {{ $currentAssign->director->name }}
                                </span>
                            @elseif($currentComm)
                                <span class="self-start inline-flex items-center gap-1 px-2 py-0.5 mb-1 rounded bg-gray-100 text-gray-600 text-[9px] font-bold uppercase border border-gray-200">
                                    {{ $currentComm->committee->name ?? 'Committee' }}
                                </span>
                            @endif

                            {{-- College Info --}}
                            @if($user->profile && $user->profile->college)
                                <span class="text-[10px] font-bold text-gray-500">{{ $user->profile->college->name }} • {{ $user->profile->year_level ?? 'N/A' }}</span>
                            @else
                                <span class="text-[10px] text-gray-300 italic">No Profile Data</span>
                            @endif
                        </div>
                    </td>

                    {{-- Status --}}
                    <td class="px-6 py-4 text-center">
                        <span class="px-2 py-1 text-[9px] font-bold uppercase rounded-full {{ $user->status === 'active' ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                            {{ $user->status }}
                        </span>
                    </td>

                    {{-- Actions --}}
                    <td class="px-6 py-4 text-right flex items-center justify-end gap-2">
                        <button wire:click="openAssignmentModal({{ $user->id }})" class="p-2 text-blue-400 hover:text-blue-600 hover:bg-blue-50 rounded-lg transition" title="Assign Position">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                        </button>
                        <button wire:click="viewProfile({{ $user->id }})" class="p-2 text-gray-400 hover:text-gray-900 hover:bg-gray-100 rounded-lg transition" title="Edit Profile">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                        </button>
                        @if($user->status === 'active')
                            <button wire:click="toggleStatus({{ $user->id }}, 'suspended')" class="p-2 text-gray-400 hover:text-red-600 hover:bg-red-50 rounded-lg transition"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"></path></svg></button>
                        @else
                            <button wire:click="toggleStatus({{ $user->id }}, 'active')" class="p-2 text-gray-400 hover:text-green-600 hover:bg-green-50 rounded-lg transition"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg></button>
                        @endif
                    </td>
                </tr>
                @empty
                <tr><td colspan="4" class="px-6 py-8 text-center text-xs text-gray-400">No users found.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4 px-2">
        {{ $users->links() }} 
    </div>

    {{-- INCLUDE CREATE/ASSIGN MODALS HERE (Same as previous response) --}}
    @if($isCreateModalOpen)
    <div class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 backdrop-blur-sm p-4">
        <div class="bg-white rounded-2xl shadow-xl w-full max-w-lg overflow-hidden">
            <div class="bg-gray-50 px-6 py-4 border-b border-gray-100 flex justify-between items-center">
                <h3 class="font-bold text-gray-900">Add New User</h3>
                <div class="flex gap-2 bg-gray-200 p-1 rounded-lg">
                    <button wire:click="$set('createMode', 'single')" class="px-3 py-1 text-[10px] font-bold uppercase rounded-md transition {{ $createMode === 'single' ? 'bg-white shadow text-gray-900' : 'text-gray-500' }}">Manual</button>
                    <button wire:click="$set('createMode', 'bulk')" class="px-3 py-1 text-[10px] font-bold uppercase rounded-md transition {{ $createMode === 'bulk' ? 'bg-white shadow text-gray-900' : 'text-gray-500' }}">Bulk</button>
                </div>
            </div>
            
            <div class="p-6 space-y-4">
                @if($createMode === 'single')
                    <input wire:model="newUser.name" type="text" placeholder="Full Name" class="w-full border-gray-200 rounded-lg text-sm">
                    <input wire:model="newUser.email" type="email" placeholder="Email Address" class="w-full border-gray-200 rounded-lg text-sm">
                    <input wire:model="newUser.password" type="password" placeholder="Password" class="w-full border-gray-200 rounded-lg text-sm">
                    <select wire:model="newUser.role_id" class="w-full border-gray-200 rounded-lg text-sm text-gray-600">
                        <option value="">Select Role</option>
                        @foreach($roles as $role) <option value="{{ $role->id }}">{{ $role->role_name }}</option> @endforeach
                    </select>
                @else
                    <div class="bg-blue-50 p-3 rounded-lg text-xs text-blue-700 mb-2">
                        Enter one email per line. Accounts will be created with default password: <strong>MadyaMember2025!</strong>
                    </div>
                    <textarea wire:model="bulkEmails" rows="8" class="w-full border-gray-200 rounded-lg text-sm" placeholder="john@example.com&#10;jane@example.com"></textarea>
                @endif
                
                @error('newUser.*') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
            </div>

            <div class="px-6 py-4 bg-gray-50 flex justify-end gap-3">
                <button wire:click="$set('isCreateModalOpen', false)" class="px-4 py-2 text-xs font-bold text-gray-500 hover:bg-gray-200 rounded-lg">Cancel</button>
                <button wire:click="saveUser" class="px-4 py-2 bg-gray-900 text-white text-xs font-bold rounded-lg hover:bg-red-600 transition">Create User(s)</button>
            </div>
        </div>
    </div>
    @endif

    @if($isAssignmentModalOpen)
    <div class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 backdrop-blur-sm p-4">
        <div class="bg-white rounded-2xl shadow-xl w-full max-w-md">
            <div class="px-6 py-4 border-b border-gray-100"><h3 class="font-bold text-gray-900">Assign Position</h3></div>
            <div class="p-6 space-y-4">
                
                {{-- Year Selector --}}
                <div>
                    <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Academic Year</label>
                    <select wire:model="assignYearId" class="w-full border-gray-200 rounded-lg text-sm">
                        @foreach($years as $year) <option value="{{ $year->id }}">{{ $year->name }}</option> @endforeach
                    </select>
                </div>

                {{-- Type Switch --}}
                <div class="flex gap-4 border-b border-gray-100 pb-4">
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="radio" wire:model.live="assignType" value="committee" class="text-red-600 focus:ring-red-500">
                        <span class="text-sm font-bold text-gray-700">Committee Member</span>
                    </label>
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="radio" wire:model.live="assignType" value="director" class="text-red-600 focus:ring-red-500">
                        <span class="text-sm font-bold text-gray-700">Director</span>
                    </label>
                </div>

                @if($assignType === 'director')
                    <div>
                        <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Director Position</label>
                        <select wire:model="assignDirectorId" class="w-full border-gray-200 rounded-lg text-sm">
                            <option value="">Select Position</option>
                            @foreach($directors as $d) <option value="{{ $d->id }}">{{ $d->name }}</option> @endforeach
                        </select>
                    </div>
                @endif

                <div>
                    <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Committee (Optional for Directors)</label>
                    <select wire:model="assignCommitteeId" class="w-full border-gray-200 rounded-lg text-sm">
                        <option value="">Select Committee</option>
                        @foreach($committees as $c) <option value="{{ $c->id }}">{{ $c->name }}</option> @endforeach
                    </select>
                </div>

                @if($assignType === 'committee')
                    <div>
                        <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Title</label>
                        <input wire:model="assignTitle" type="text" placeholder="e.g. Member, Co-Head" class="w-full border-gray-200 rounded-lg text-sm">
                    </div>
                @endif
                
                @error('assignDirectorId') <span class="text-red-500 text-xs block">{{ $message }}</span> @enderror
            </div>
            <div class="px-6 py-4 bg-gray-50 flex justify-end gap-3">
                <button wire:click="$set('isAssignmentModalOpen', false)" class="px-4 py-2 text-xs font-bold text-gray-500">Cancel</button>
                <button wire:click="saveAssignment" class="px-4 py-2 bg-blue-600 text-white text-xs font-bold rounded-lg hover:bg-blue-700">Assign</button>
            </div>
        </div>
    </div>
    @endif

    {{-- MODAL 4: PROFILE MANAGER (UPDATED) --}}
    @if($isProfileModalOpen && $viewingUser)
    <div class="fixed inset-0 z-50 flex items-center justify-end bg-black/50 backdrop-blur-sm" x-data="{ tab: 'history' }">
        <div class="bg-white w-full max-w-2xl h-full overflow-y-auto shadow-2xl animate-slide-in-right p-8">
            
            {{-- Header --}}
            <div class="flex justify-between items-start mb-6">
                <div class="flex items-center gap-4">
                    <img class="h-16 w-16 rounded-full border-2 border-red-100" src="{{ $viewingUser->profile_photo_url }}">
                    <div>
                        <h2 class="font-heading font-black text-2xl text-gray-900">{{ $viewingUser->name }}</h2>
                        <span class="px-2 py-0.5 bg-gray-100 text-gray-600 text-[10px] font-bold uppercase rounded">{{ $viewingUser->email }}</span>
                    </div>
                </div>
                <button wire:click="$set('isProfileModalOpen', false)" class="text-gray-400 hover:text-red-600">Close X</button>
            </div>

            {{-- Tabs --}}
            <div class="flex gap-4 border-b border-gray-200 mb-6">
                <button @click="tab = 'history'" :class="tab === 'history' ? 'border-red-600 text-red-600' : 'border-transparent text-gray-400'" class="pb-2 text-xs font-bold uppercase border-b-2 transition">Role History</button>
                <button @click="tab = 'details'" :class="tab === 'details' ? 'border-red-600 text-red-600' : 'border-transparent text-gray-400'" class="pb-2 text-xs font-bold uppercase border-b-2 transition">Academic Profile</button>
                <button @click="tab = 'portfolio'" :class="tab === 'portfolio' ? 'border-red-600 text-red-600' : 'border-transparent text-gray-400'" class="pb-2 text-xs font-bold uppercase border-b-2 transition">Portfolio</button>
            </div>

            {{-- TAB: ROLE HISTORY (Editable) --}}
            <div x-show="tab === 'history'" class="space-y-6">
                
                {{-- Add New Button --}}
                <button wire:click="openAssignmentModal({{ $viewingUser->id }})" class="w-full py-2 border-2 border-dashed border-gray-300 rounded-lg text-gray-400 text-xs font-bold hover:border-red-400 hover:text-red-500 transition mb-4">
                    + Assign New Position
                </button>

                {{-- Director Roles --}}
                <div>
                    <h4 class="text-xs font-bold text-blue-600 uppercase mb-2">Directorships</h4>
                    <div class="space-y-2">
                        @forelse($viewingUser->directorAssignments as $assign)
                        <div class="bg-blue-50 p-3 rounded-lg flex justify-between items-center group">
                            <div>
                                <p class="text-sm font-bold text-gray-900">{{ $assign->director->name }}</p>
                                <p class="text-[10px] text-gray-500">{{ $assign->committee->name ?? 'No Committee' }} • {{ $assign->academicYear->name }}</p>
                            </div>
                            <div class="flex gap-2 opacity-0 group-hover:opacity-100 transition">
                                {{-- Edit Button --}}
                                <button wire:click="editAssignment('director', {{ $assign->id }})" class="text-blue-400 hover:text-blue-600">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
                                </button>
                                {{-- Delete Button --}}
                                <button wire:click="deleteAssignment('director', {{ $assign->id }})" wire:confirm="Remove this role?" class="text-gray-400 hover:text-red-600">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                </button>
                            </div>
                        </div>
                        @empty
                        <p class="text-xs text-gray-400 italic">No directorships.</p>
                        @endforelse
                    </div>
                </div>

                {{-- Committee Roles --}}
                <div>
                    <h4 class="text-xs font-bold text-gray-600 uppercase mb-2">Committees</h4>
                    <div class="space-y-2">
                        @forelse($viewingUser->committeeMembers as $member)
                        <div class="bg-gray-50 p-3 rounded-lg flex justify-between items-center group">
                            <div>
                                <p class="text-sm font-bold text-gray-900">{{ $member->committee->name }}</p>
                                <p class="text-[10px] text-gray-500">{{ $member->title }} • {{ $member->academicYear->name }}</p>
                            </div>
                            <div class="flex gap-2 opacity-0 group-hover:opacity-100 transition">
                                <button wire:click="editAssignment('committee', {{ $member->id }})" class="text-blue-400 hover:text-blue-600">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
                                </button>
                                <button wire:click="deleteAssignment('committee', {{ $member->id }})" wire:confirm="Remove this role?" class="text-gray-400 hover:text-red-600">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                </button>
                            </div>
                        </div>
                        @empty
                        <p class="text-xs text-gray-400 italic">No memberships.</p>
                        @endforelse
                    </div>
                </div>
            </div>

            {{-- TAB: ACADEMIC PROFILE (Same as before) --}}
            <div x-show="tab === 'details'" class="space-y-4">

                {{-- NEW: SYSTEM ROLE DROPDOWN --}}
                <div class="bg-yellow-50 p-3 rounded-lg border border-yellow-200 mb-4">
                    <label class="block text-xs font-bold text-yellow-800 uppercase mb-1">System Permission Role</label>
                    <select wire:model="editingRoleId" class="w-full border-yellow-300 rounded-lg text-sm bg-white focus:ring-yellow-500 text-gray-700">
                        @foreach($roles as $role)
                            <option value="{{ $role->id }}">{{ $role->role_name }}</option>
                        @endforeach
                    </select>
                    <p class="text-[10px] text-yellow-700 mt-1">Changing this affects what the user can access in the admin panel.</p>
                </div>
                
                 {{-- ... Paste Academic Profile Inputs Here ... --}}
                 <div class="grid grid-cols-3 gap-3">
                    <input wire:model="editingProfile.first_name" type="text" placeholder="First Name" class="col-span-1 border-gray-200 rounded-lg text-sm">
                    <input wire:model="editingProfile.middle_name" type="text" placeholder="Middle" class="col-span-1 border-gray-200 rounded-lg text-sm">
                    <input wire:model="editingProfile.last_name" type="text" placeholder="Last Name" class="col-span-1 border-gray-200 rounded-lg text-sm">
                </div>
                <div class="grid grid-cols-2 gap-3">
                    <select wire:model="editingProfile.college_id" class="w-full border-gray-200 rounded-lg text-sm">
                        <option value="">Select College</option>
                        @foreach($colleges as $c) <option value="{{ $c->id }}">{{ $c->name }}</option> @endforeach
                    </select>
                    <input wire:model="editingProfile.year_level" type="text" placeholder="Year Level" class="w-full border-gray-200 rounded-lg text-sm">
                </div>
                <input wire:model="editingProfile.course" type="text" placeholder="Course" class="w-full border-gray-200 rounded-lg text-sm">
                <textarea wire:model="editingProfile.bio" rows="3" placeholder="Short Bio..." class="w-full border-gray-200 rounded-lg text-sm"></textarea>
                <div class="flex justify-end pt-4">
                    <button wire:click="updateProfile" class="px-4 py-2 bg-red-600 text-white text-xs font-bold rounded-lg hover:bg-red-700">Save Changes</button>
                </div>
            </div>

            {{-- TAB: PORTFOLIO & ENGAGEMENT (Add/Edit) --}}
            <div x-show="tab === 'portfolio'" class="space-y-6">
                
                {{-- Add Engagement Section --}}
                <div class="bg-gray-50 p-4 rounded-xl border border-gray-200">
                    <h5 class="text-xs font-bold text-gray-700 uppercase mb-2">Add New Engagement</h5>
                    <div class="space-y-2">
                        <input wire:model="newEngagement.title" type="text" placeholder="Title (e.g. Attended Seminar)" class="w-full border-gray-200 rounded text-xs">
                        <textarea wire:model="newEngagement.description" rows="2" placeholder="Description" class="w-full border-gray-200 rounded text-xs"></textarea>
                        <button wire:click="saveEngagement" class="w-full py-1.5 bg-gray-900 text-white text-xs font-bold rounded">Add Engagement</button>
                    </div>
                </div>

                {{-- List Engagements --}}
                <div class="space-y-2">
                    @foreach($viewingUser->engagements as $eng)
                    <div class="flex justify-between items-start border-l-2 border-red-500 pl-3">
                        <div>
                            <p class="text-sm font-bold">{{ $eng->title }}</p>
                            <p class="text-[10px] text-gray-500">{{ $eng->description }}</p>
                        </div>
                        <button wire:click="deleteEngagement({{ $eng->id }})" class="text-gray-300 hover:text-red-600">x</button>
                    </div>
                    @endforeach
                </div>

                <hr class="border-gray-100">

                {{-- Add Portfolio Section --}}
                <div class="bg-gray-50 p-4 rounded-xl border border-gray-200">
                    <h5 class="text-xs font-bold text-gray-700 uppercase mb-2">Add New Portfolio</h5>
                    <div class="grid grid-cols-2 gap-2 mb-2">
                        <input wire:model="newPortfolio.designation" type="text" placeholder="Designation" class="border-gray-200 rounded text-xs">
                        <input wire:model="newPortfolio.place" type="text" placeholder="Place/Org" class="border-gray-200 rounded text-xs">
                    </div>
                    <div class="grid grid-cols-2 gap-2 mb-2">
                        <input wire:model="newPortfolio.duration" type="text" placeholder="Duration (e.g. 2024-2025)" class="border-gray-200 rounded text-xs">
                        <select wire:model="newPortfolio.status" class="border-gray-200 rounded text-xs">
                            <option value="Active">Active</option>
                            <option value="Former">Former</option>
                        </select>
                    </div>
                    <button wire:click="savePortfolio" class="w-full py-1.5 bg-gray-900 text-white text-xs font-bold rounded">Add Portfolio</button>
                </div>

                {{-- List Portfolios --}}
                <div class="space-y-2">
                     @if($viewingUser->profile)
                        @foreach($viewingUser->profile->portfolios as $pf)
                        <div class="bg-white border border-gray-200 p-2 rounded flex justify-between items-center">
                            <div>
                                <p class="text-xs font-bold">{{ $pf->designation }} <span class="text-gray-400 font-normal">at {{ $pf->place }}</span></p>
                                <p class="text-[10px] text-gray-500">{{ $pf->duration }} • {{ $pf->status }}</p>
                            </div>
                            <button wire:click="deletePortfolio({{ $pf->id }})" class="text-gray-300 hover:text-red-600 text-xs font-bold">Remove</button>
                        </div>
                        @endforeach
                     @endif
                </div>

            </div>

        </div>
    </div>
    @endif

    {{-- ASSIGNMENT MODAL (UPDATED FOR EDITING) --}}
    @if($isAssignmentModalOpen)
    <div class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 backdrop-blur-sm p-4">
        <div class="bg-white rounded-2xl shadow-xl w-full max-w-md">
            <div class="px-6 py-4 border-b border-gray-100 flex justify-between items-center">
                <h3 class="font-bold text-gray-900">{{ $editingAssignmentId ? 'Edit' : 'Assign' }} Position</h3>
                <button wire:click="$set('isAssignmentModalOpen', false)" class="text-gray-400">X</button>
            </div>
            <div class="p-6 space-y-4">
                
                {{-- Year Selector (Now Editable) --}}
                <div>
                    <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Academic Year</label>
                    <select wire:model="assignYearId" class="w-full border-gray-200 rounded-lg text-sm">
                        @foreach($years as $year) <option value="{{ $year->id }}">{{ $year->name }}</option> @endforeach
                    </select>
                </div>

                {{-- Radio buttons disabled if editing to prevent type mismatch, or allow them if you handle logic --}}
                @if(!$editingAssignmentId)
                <div class="flex gap-4 border-b border-gray-100 pb-4">
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="radio" wire:model.live="assignType" value="committee" class="text-red-600 focus:ring-red-500">
                        <span class="text-sm font-bold text-gray-700">Committee Member</span>
                    </label>
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="radio" wire:model.live="assignType" value="director" class="text-red-600 focus:ring-red-500">
                        <span class="text-sm font-bold text-gray-700">Director</span>
                    </label>
                </div>
                @endif

                @if($assignType === 'director')
                    <div>
                        <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Director Position</label>
                        <select wire:model="assignDirectorId" class="w-full border-gray-200 rounded-lg text-sm">
                            <option value="">Select Position</option>
                            @foreach($directors as $d) <option value="{{ $d->id }}">{{ $d->name }}</option> @endforeach
                        </select>
                    </div>
                @endif

                <div>
                    <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Committee</label>
                    <select wire:model="assignCommitteeId" class="w-full border-gray-200 rounded-lg text-sm">
                        <option value="">Select Committee</option>
                        @foreach($committees as $c) <option value="{{ $c->id }}">{{ $c->name }}</option> @endforeach
                    </select>
                </div>

                @if($assignType === 'committee')
                    <div>
                        <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Title</label>
                        <input wire:model="assignTitle" type="text" placeholder="e.g. Member, Co-Head" class="w-full border-gray-200 rounded-lg text-sm">
                    </div>
                @endif
                
                @error('assignDirectorId') <span class="text-red-500 text-xs block">{{ $message }}</span> @enderror
            </div>
            <div class="px-6 py-4 bg-gray-50 flex justify-end gap-3">
                <button wire:click="$set('isAssignmentModalOpen', false)" class="px-4 py-2 text-xs font-bold text-gray-500">Cancel</button>
                <button wire:click="saveAssignment" class="px-4 py-2 bg-blue-600 text-white text-xs font-bold rounded-lg hover:bg-blue-700">
                    {{ $editingAssignmentId ? 'Update' : 'Assign' }}
                </button>
            </div>
        </div>
    </div>
    @endif
</div>