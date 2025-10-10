<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PointProductPurchase extends Model
{
    use HasFactory;
    protected $guarded = ['id'];
    protected $casts = [
    'purchased_at'=>'date'    
    ];
    
    
     public function user()
    {
        return $this->belongsTo(User::class);
    }
    
     public function pointProduct()
    {
        return $this->belongsTo(PointProduct::class);
    }


}
