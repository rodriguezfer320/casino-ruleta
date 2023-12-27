<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class User extends Model
{
    use HasFactory;
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'username', 'email', 'name', 'lastname', 'age', 'genero', 'cash'
    ];

    /**
     * Definimos la relación uno a muchos con el modelo Bet
     *
     * @return HasMany
     */
    public function bets(): HasMany
    {
        return $this->hasMany(BET::class, 'id_user');
    }

}