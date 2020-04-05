<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Account extends Model
{
    protected $fillable = [
        'name',
        'balance',
        'user_id'
    ];

    /**
     * Returns the transactions that were made from the account
     *
     * @return HasMany
     */
    public function transactionsMade()
    {
        return $this->hasMany(Transaction::class, 'from');
    }

    /**
     * Returns the transactions that were received by the account
     *
     * @return HasMany
     */
    public function transactionsReceived()
    {
        return $this->hasMany(Transaction::class, 'to');
    }

    /**
     * Returns the currency of the account
     *
     * @return BelongsTo
     */
    public function currency()
    {
        return $this->belongsTo(Currency::class);
    }

    /**
     * Returns the owner of the account
     *
     * @return BelongsTo
     */
    public function owner()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
