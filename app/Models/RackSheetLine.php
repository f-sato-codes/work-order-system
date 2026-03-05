<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RackSheetLine extends Model
{
    protected $fillable = [
        'rack_sheet_id',
        'line_no',
        'control_code',
        'customer_name',
        'job_code',
        'planned_qty',
        'racked_qty',
        'note',
    ];

    public function rackSheet(): BelongsTo
    {
        return $this->belongsTo(RackSheet::class);
    }
}