<?php

namespace App\Http\Resolvers;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class RegisterResolver
{

    public function __invoke($root, array $args)
    {
        $validator = Validator::make($args, [
            'name' => 'required|string',
            'email' => 'required|email|max:255|unique:users,email',
            'password' => 'required|string|min:6',
            'avatar' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);


        $avatarPath = null;

        if (isset($args['avatar'])) {
            $avatarPath = $args['avatar']->store('avatars', 'public');
        }

        if ($validator->fails()) {
            return [
                '__typename' => 'Error',
                'message' => $validator->errors()->first(),
            ];
        }

        $user = User::create([
            'name' => $args['name'],
            'email' => $args['email'],
            'password' => Hash::make($args['password']),
            'avatar' => $avatarPath,
        ]);

        $token = $user->createToken("graphQL")->plainTextToken;

        return [
            '__typename' => 'AuthResult',
            'token' => $token,
            'user' => $user,
        ];
    }
}
