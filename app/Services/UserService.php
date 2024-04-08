<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Hash;

class UserService extends Service
{
    public function __construct()
    {
        $this->model = new User;
    }

    public function findByEmail(string $email): Model
    {
        return User::where('email', $email)->firstOrFail();
    }

    public function checkLogin(array $data): array
    {
        $user = $this->findByEmail($data['email']);

        if (Hash::check($data['password'], $user->password)) {
            return [
                'success' => true,
                'message' => 'success',
                'user' => $user,
            ];
        }

        return [
            'success' => false,
            'message' => 'false password',
        ];
    }
}
