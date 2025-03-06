<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Country extends Model
{
    use HasFactory;

    protected $hidden = ['name_en', 'name_ar', 'name_fr'];
    protected $appends = ['name'];

    public function getNameAttribute()
    {
        $locale = app()->getLocale();
        $attribute = "name_{$locale}";
        return $this->{$attribute};
    }
    
      public function cities()
    {
        return $this->hasMany(City::class);
    }


}
