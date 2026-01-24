<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Tenant extends Model
{
    protected $guarded = [];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function room(): BelongsTo
    {
        return $this->belongsTo(Room::class);
    }

    public function advancePayments(): HasMany
    {
        return $this->hasMany(AdvancePayment::class);
    }

    public function rents(): HasMany
    {
        return $this->hasMany(Rent::class);
    }

    public function settlement(): HasOne
    {
        return $this->hasOne(TenantSettlement::class);
    }
}
