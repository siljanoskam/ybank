<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Currency extends Model
{
    public $timestamps = false;

    /**
     * Returns the accounts that have the currency
     *
     * @return HasMany
     */
    public function accounts()
    {
        return $this->hasMany(Account::class);
    }
}
