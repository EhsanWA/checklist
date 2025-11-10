<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ReportCheckItem extends Model
{
    protected $fillable = [
        'report_id',
        'inspection_check_id',
        'status',
        'notes',
        'photos',
    ];

    protected $casts = [
        'photos' => 'array',
    ];

    public function report()
    {
        return $this->belongsTo(Report::class);
    }

    public function inspectionCheck()
    {
        return $this->belongsTo(InspectionCheck::class);
    }
}
