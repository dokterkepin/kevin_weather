<?php

namespace App\Services;
use App\Models\UserModel;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use App\Exceptions\ValidationException;
use Illuminate\Support\Facades\Log;

class UserService
{
    public function register(array $data){
        Log::info('Register method called', $data);
        if(UserModel::where("user_id", $data["user_id"])->exists()){
            throw new ValidationException("User Id Already Exist");
        }

        $user = UserModel::create([
            "user_id" => $data["user_id"],
            "user_name" => $data["user_name"],
            "user_password" => Hash::make($data["user_password"]),
            "user_email" => $data["user_email"],
        ]);
        Log::info('User created', $user->toArray());
        return $user;
    }

}
