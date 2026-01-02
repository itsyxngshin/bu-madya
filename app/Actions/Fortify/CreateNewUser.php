<?php

namespace App\Actions\Fortify;

use App\Models\User;
use App\Models\Role;
use App\Models\Profile;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Laravel\Fortify\Contracts\CreatesNewUsers;
use Laravel\Jetstream\Jetstream;

class CreateNewUser implements CreatesNewUsers
{
    use PasswordValidationRules;

    public function create(array $input): User
    {
        $memberRole = Role::where('role_name', 'regular')->first();

        Validator::make($input, [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => $this->passwordRules(),
            // New Validations
            'college_id' => ['required', 'integer', 'exists:colleges,id'], // Assumes you have a colleges table
            'course'     => ['required', 'string', 'max:255'],
            'year_level' => ['required', 'string', 'max:50'],
            'terms' => Jetstream::hasTermsAndPrivacyPolicyFeature() ? ['accepted', 'required'] : '',
        ])->validate();

        $user = User::create([
            'name' => $input['name'],
            'email' => $input['email'],
            'role_id' => $memberRole ? $memberRole->id : null,
            'password' => Hash::make($input['password']),
        ]);

        // Simple name splitter (Consider adding separate inputs for precision later)
        $parts = explode(' ', $input['name']);
        $lastName = count($parts) > 1 ? array_pop($parts) : '';
        $firstName = implode(' ', $parts);

        Profile::create([
            'user_id'    => $user->id,
            'first_name' => $firstName ?: $input['name'],
            'last_name'  => $lastName,
            'college_id' => $input['college_id'],
            'course'     => $input['course'],
            'year_level' => $input['year_level'],
        ]);

        return $user;
    }
}