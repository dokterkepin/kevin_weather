<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class UserAccount extends Authenticatable
{
    use HasFactory;
    protected $table = "user_account";
    protected $primaryKey = "user_id";
    public $incrementing = true;
    protected $fillable = ["user_id", "user_name", "user_password", "user_email"];
    protected $hidden = ["user_password", "remember_token"];


}
