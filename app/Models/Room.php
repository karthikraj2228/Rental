<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Room extends Model
{
    protected $guarded = [];

    public function house(): BelongsTo
    {
        return $this->belongsTo(House::class);
    }

    public function tenant(): HasOne
    {
        // Active tenant
        return $this->hasOne(Tenant::class)->where('status', '!=', 'left');
    }

    public function rents(): HasMany
    {
        return $this->hasMany(Rent::class);
    }
}
