<?php

namespace App\Domain\Models;

use Illuminate\Database\Eloquent\Model;

class TransactionsNotification extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'transactions_notification';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'message',
        'users_id',
        'transactions_id',
        'status',
    ];
}