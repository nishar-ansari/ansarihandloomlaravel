<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class BankAccount extends Model
{
    protected $fillable = ['bank_name', 'account_name', 'account_number', 'branch', 'balance', 'status'];

    public function transactions(): HasMany
    {
        return $this->hasMany(BankTransaction::class);
    }
}
