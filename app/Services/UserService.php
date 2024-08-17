<?php

namespace App\Services;
use App\Models\UserModel;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class UserService
{
    public function register(array $data): ?UserModel
    {
        Log::info('Register method called', $data);

        $user = UserModel::create([
            "user_id" => $data["user_id"],
            "user_name" => $data["user_name"],
            "user_password" => Hash::make($data["user_password"]),
            "user_email" => $data["user_email"],
        ]);
        Log::info('User created', $user->toArray());
        return $user;
    }

    public function login(array $data): ?UserModel
    {
        Log::info('Login method called', $data);
        $data = [
            "user_id" => $data["user_id"],
            "password" => $data["user_password"]
        ];

        if (Auth::attempt($data)) {
            session()->regenerate();
            Log::info("User Logged in Successfully.", ["user_id" => $data["user_id"]]);
            return Auth::user();

        }
        return null;
    }

    public function update(array $data): ?UserModel
    {
        Log::info('Update method called');

        if(Auth::user()->user_name != $data["user_name"] && (!Hash::check($data["user_password"], Auth::user()->user_password))){
            Auth::user()->user_name = $data['user_name'];
            Auth::user()->user_password = Hash::make($data['user_password']);
            Log::info("User updated successfully.", ['user_name' => Auth::user()->user_name]);
            Auth::user()->save();
            return Auth::user();
        }

        return null;
    }

    public function logout(): void
    {
        Log::info("logout Method Called");
        Auth::logout();
        Log::info("logout Successfully");
    }

    public function delete(): void
    {
        Log::info("Delete method called");
        if(Auth::user()){
            Auth::user()->delete();
            Auth::logout();
        }

    }
}
