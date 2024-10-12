<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use HistoricalRecords\Concerns\HasHistory;

class User extends \Illuminate\Foundation\Auth\User
{
    use HasFactory, HasHistory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];
}
