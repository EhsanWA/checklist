<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\InspectionList;

class Report extends Model
{
    protected $fillable = [
        'schip_naam',
        'schip_nummer',
        'schip_bouwjaar',
        'monteur',
        'description',
        'status',
        'inspection_list_id'
    ];

    public function inspectionList()
    {
        return $this->belongsTo(InspectionList::class, 'inspection_list_id');
    }
}
