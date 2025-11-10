<?php
// app/Models/InspectionCheck.php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\ReportCheckItem;

class InspectionCheck extends Model
{
    protected $fillable = ['inspection_category_id', 'label', 'code', 'required', 'severity', 'sort'];

    public function category()
    {
        return $this->belongsTo(InspectionCategory::class);
    }

    public function reportItems()
    {
        return $this->hasMany(ReportCheckItem::class);
    }
}
