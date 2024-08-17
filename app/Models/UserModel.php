<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class UserModel extends Authenticatable
{
    use HasFactory;
    protected $table = "user_account";
    protected $primaryKey = "user_id";
    public $incrementing = false;
    protected $keyType = "string";
    protected $fillable = ["user_id", "user_name", "user_password", "user_email"];
    protected $hidden = ["user_password", "remember_token"];

    public function getAuthPassword()
    {
        return $this->user_password;
    }
}
