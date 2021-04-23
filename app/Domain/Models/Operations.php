<?php

namespace App\Domain\Models;

use Illuminate\Database\Eloquent\Model;

class Operations extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'operations';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'title',
        'value',
        'wallets_id',
        'transactions_id',
        'transactions_id',
        'operations_type_id',
    ];
}