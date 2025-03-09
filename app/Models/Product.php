<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class Product extends Model
{
    use HasFactory;
    protected $guarded=[];

    protected $hidden = ['name_en', 'name_ar', 'name_fr'];
    protected $appends = ['name','description'];

       // Global scope to include only products with status = 1
    // protected static function booted()
    // {
    //     static::addGlobalScope('active', function (Builder $builder) {
    //         $builder->where('status', 1);
    //     });
    // }

    public function getNameAttribute()
    {
        $locale = app()->getLocale();
        $attribute = "name_{$locale}";
        return $this->{$attribute};
    }

    public function getDescriptionAttribute()
    {
        $locale = app()->getLocale();
        $attribute = "description_{$locale}";
        return $this->{$attribute};
    }

    public function productImages()
    {
        return $this->hasMany(ProductPhoto::class);
    }

    public function productReviews()
    {
        return $this->hasMany(ProductReview::class);
    }


    public function category()
    {
        return $this->belongsTo(Category::class,);
    }

    public function unit()
    {
        return $this->belongsTo(Unit::class,);
    }

    public function shop()
    {
        return $this->belongsTo(Shop::class,);
    }
    public function brand()
    {
        return $this->belongsTo(Brand::class,);
    }
    public function units()
    {
        return $this->belongsToMany(Unit::class, 'product_units', 'product_id', 'unit_id')->withPivot('barcode', 'releation', 'selling_price');
    }

    public function variations()
    {
        return $this->hasMany(Variation::class);
    }

    public function offers()
    {
        return $this->hasMany(Offer::class)->whereDate('expired_at', '>', now());
    }

    public function orders()
    {
        return $this->belongsToMany(Order::class, 'order_products')->withPivot('variation_id','quantity','unit_price','total_price_after_tax','tax_percentage','tax_value','total_price_before_tax','discount_percentage','discount_value');
    }

    public function noteVouchers()
    {
        return $this->belongsToMany(NoteVoucher::class, 'voucher_products', 'product_id', 'note_voucher_id')
            ->withPivot('quantity', 'unit_id', 'purchasing_price')
            ->withTimestamps();
    }


}
