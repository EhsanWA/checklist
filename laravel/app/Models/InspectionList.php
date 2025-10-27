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
    public function reports()
    {
        return $this->hasMany(Report::class);
    }
}
