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
        'electrolysis_minutes',
        'electrolysis_recorded_at',
        'final_completed_at',
    ];

    protected $casts = [
        'work_date' => 'date',
        'daily_slot_no' => 'integer',
        'electrolysis_minutes' => 'integer',
        'racking_completed_at' => 'datetime',
        'electrolysis_recorded_at' => 'datetime',
        'final_completed_at' => 'datetime',
    ];

    /**
     * 指示書に含まれる行
     */
    public function lines(): HasMany
    {
        return $this->hasMany(RackSheetLine::class);
    }

    /**
     * 指示書作成者
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by_user_id');
    }
}