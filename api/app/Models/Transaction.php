<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Transaction extends Model
{
    protected $fillable = [
        'from',
        'to',
        'details',
        'amount'
    ];

    /**
     * Returns the sender account of the transaction
     *
     * @return BelongsTo
     */
    public function sender()
    {
        return $this->belongsTo(Account::class, 'from');
    }

    /**
     * Returns the receiver account of the transaction
     *
     * @return BelongsTo
     */
    public function receiver()
    {
        return $this->belongsTo(Account::class, 'to');
    }
}
