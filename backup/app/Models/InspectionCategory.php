<?php
// app/Models/InspectionCategory.php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
    
class InspectionCategory extends Model
{
    protected $fillable = ['inspection_list_id', 'name', 'sort'];
    public function list()
    {
        return $this->belongsTo(InspectionList::class, 'inspection_list_id');
    }
    public function checks()
    {
        return $this->hasMany(InspectionCheck::class)->orderBy('sort');
    }
}
