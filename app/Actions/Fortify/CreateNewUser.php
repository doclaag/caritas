<?php

namespace App\Actions\Fortify;

use App\Models\User;
use App\Models\UsuarioRol;
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
        Validator::make($input, [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => $this->passwordRules(),
            'gender' => ['required', 'string'],
            'roles' => ['required', 'array'],
            'roles.*' => ['exists:roles,id'],
            'terms' => Jetstream::hasTermsAndPrivacyPolicyFeature() ? ['accepted', 'required'] : '',
        ])->validate();

        $user = User::create([
            'name' => $input['name'],
            'email' => $input['email'],
            'password' => Hash::make($input['password']),
            'gender' => $input['gender'],
        ]);
        if (is_array($input['roles'])) {
            foreach ($input['roles'] as $roleId) {
                UsuarioRol::create([
                    'usuario_id' => $user->id,
                    'rol_id' => $roleId,
                ]);
            }
        }

        return $user;
    }
}
