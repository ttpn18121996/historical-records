<?php

namespace Workbench\App\Models;

use HistoricalRecords\Concerns\HasHistory;
use HistoricalRecords\Contracts\Historyable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Workbench\Database\Factories\UserFactory;

class User extends \Illuminate\Foundation\Auth\User implements Historyable
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

    protected static function newFactory()
    {
        return UserFactory::new();
    }
}
