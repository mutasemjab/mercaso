<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Unit extends Model
{
    use HasFactory;

    protected $guarded=[];
    
       protected $hidden = ['name_en', 'name_ar', 'name_fr'];
    protected $appends = ['name'];

    public function getNameAttribute()
    {
        $locale = app()->getLocale();
        $attribute = "name_{$locale}";
        return $this->{$attribute};
    }

    public function products()
    {
        return $this->belongsToMany(Product::class, 'product_units', 'unit_id', 'product_id');
    }
}
