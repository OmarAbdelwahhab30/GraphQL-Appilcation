<?php

namespace App\Http\Resolvers;

use App\Models\User;
use Illuminate\Support\Facades\Validator;

class LoginResolver
{

    public function __invoke($root, array $args)
    {
        $validator = Validator::make($args, [
            'email' => 'required|email|max:255,exists:users,email',
            'password' => 'required|string|min:6',
        ]);

        if ($validator->fails()) {
            return [
                '__typename' => 'Error',
                'message' => $validator->errors()->first(),
            ];
        }

        $user = User::where('email', $args['email'])->first();

        if (auth()->attempt(['email' => $args['email'], 'password' => $args['password']])) {
            $token = $user->createToken("graphQL")->plainTextToken;

            return [
                '__typename' => 'AuthResult',
                'token' => $token,
                'user' => $user,
            ];
        }

        return [
            '__typename' => 'Error',
            'message' => 'invalid_credentials',
        ];
    }
}
