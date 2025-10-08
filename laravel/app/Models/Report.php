<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Report extends Model
{
    protected $fillable = ['schip_naam', 'schip_nummer', 'schip_bouwjaar', 'monteur', 'description', 'status'];
}
