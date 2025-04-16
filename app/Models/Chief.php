<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Chief extends Authenticatable
{
    use Notifiable;

    protected $table = 'chiefs';
    protected $fillable = ['username', 'password', 'theme'];
    protected $hidden = ['password'];
}
