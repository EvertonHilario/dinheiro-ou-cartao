<?php

namespace App\Domain\Models;

use Illuminate\Database\Eloquent\Model;

class Wallets extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'wallets';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'balance',
    ];
}