<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserTransaction extends Model
{
    protected $fillable = [
        'source',
        'email',
        'status',
        'balance',
        'currency',
        'transaction_id',
        'transaction_date',
        'created_at',
        'updated_at',
    ];
}