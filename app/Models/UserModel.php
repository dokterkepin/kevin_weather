<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;


class UserModel extends Model
{
    use HasFactory;
    protected $table = "user_account";
    protected $primaryKey = "user_id";
    public $incrementing = true;
    protected $fillable = ["user_id", "user_name", "user_password", "user_email"];
    protected $hidden = ["user_password", "remember_token"];


}
