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

    /**
     * Validate and create a newly registered user.
     *
     * @param  array<string, string>  $input
     */
    public function create(array $input): User
    {
        $memberRole = Role::where('role_name', 'member')->first();
        Validator::make($input, [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => $this->passwordRules(),
            'terms' => Jetstream::hasTermsAndPrivacyPolicyFeature() ? ['accepted', 'required'] : '',
        ])->validate();

        return User::create([
            'name' => $input['name'],
            'email' => $input['email'],
            'role_id' => $memberRole ? $memberRole->id : null,
            'password' => Hash::make($input['password']),
        ]);

        $parts = explode(' ', $input['name']);
        $lastName = array_pop($parts);
        $firstName = implode(' ', $parts);

        Profile::create([
            'user_id' => $user->id,
            'first_name' => $firstName ?: $input['name'],
            'last_name' => $lastName ?: '',
        ]);

        return $user;
    }
}
