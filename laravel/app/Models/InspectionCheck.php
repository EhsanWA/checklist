<?php
// app/Models/InspectionCheck.php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InspectionCheck extends Model
{
    protected $fillable = ['inspection_category_id', 'label', 'code', 'required', 'severity', 'sort'];
    public function category()
    {
        return $this->belongsTo(InspectionCategory::class);
    }
}
