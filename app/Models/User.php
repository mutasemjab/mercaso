<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;



class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $guarded = [
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];



    public function addresses()
    {
       return $this->hasMany(UserAddress::class);
    }
    
    public function wholeSales()
    {
       return $this->hasMany(WholeSale::class);
    }
    
    public function orders()
    {
       return $this->hasMany(Order::class);
    }
    public function favourites()
    {
        return $this->belongsToMany(Product::class, 'favourites', 'user_id', 'product_id');
    }
    public function country()
    {
       return $this->belongsTo(Country::class);
    }
    public function representative()
    {
       return $this->belongsTo(Representative::class);
    }
    public function coupons()
    {
        return $this->belongsToMany(Coupon::class, 'coupon_users','user_id', 'coupon_id');
    }



}
