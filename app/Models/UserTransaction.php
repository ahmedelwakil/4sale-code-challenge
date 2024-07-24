<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserTransaction extends Model
{
    use HasFactory;

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

    /**
     * @return array
     */
    public function clean()
    {
        return [
            'provider' => $this->source,
            'email' => $this->email,
            'status' => $this->status,
            'balance' => $this->balance,
            'currency' => $this->currency,
            'transaction_date' => $this->transaction_date,
            'transaction_id' => $this->transaction_id,
        ];
    }
}