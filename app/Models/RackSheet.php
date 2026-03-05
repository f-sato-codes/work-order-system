<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RackSheet extends Model
{
    protected $fillable = [
        'work_date',
        'daily_slot_no',
        'rack_no',
        'spec_code',
        'created_by_user_id',
        'racking_completed_at',
        'electrolysis_completed_at',
        'finishing_arrived_at',
        'completed_by_user_id',
        'note',
    ];

    public function lines(): HasMany
    {
        return $this->hasMany(RackSheetLine::class);
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by_user_id');
    }

    public function completer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'completed_by_user_id');
    }
}