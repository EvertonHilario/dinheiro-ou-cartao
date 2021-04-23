<?php

namespace App\Domain\Models;

use Illuminate\Database\Eloquent\Model;

class Transactions extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'transactions';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'hash',
        'value',
        'payer_id',
        'payee_id',
        'transactions_type_id',
        'transactions_status_id',
    ];
}