<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Account extends Model
{
    /**
     * Returns the transactions of the account
     *
     * @return HasMany
     */
    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }

    /**
     * Returns the owner of the account
     *
     * @return BelongsTo
     */
    public function owner()
    {
        return $this->belongsTo(User::class);
    }
}
