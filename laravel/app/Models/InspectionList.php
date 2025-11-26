<?php

// app/Models/InspectionList.php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InspectionList extends Model
{
    protected $fillable = ['title', 'description'];

    public function categories()
    {
        return $this->hasMany(InspectionCategory::class)->orderBy('sort');
    }

    public function checks()
    {
        return $this->hasManyThrough(
            InspectionCheck::class,
            InspectionCategory::class,
            'inspection_list_id',
            'inspection_category_id'
        );
    }

    public function reports()
    {
        return $this->hasMany(Report::class);
    }
}
