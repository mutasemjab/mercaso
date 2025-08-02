<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Delivery extends Model
{
    use HasFactory;
    protected $guarded=[];

    public function users()
    {
        return $this->hasMany(User::class);
    }
    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    protected $casts = [
        'price' => 'double'
    ];

    /**
     * Get the availabilities for the delivery
     */
    public function availabilities()
    {
        return $this->hasMany(DeliveryAvailability::class);
    }

    /**
     * Get active availabilities for the delivery
     */
    public function activeAvailabilities()
    {
        return $this->hasMany(DeliveryAvailability::class)->where('is_active', true);
    }

    /**
     * Get availabilities grouped by day
     */
    public function getAvailabilitiesByDay()
    {
        return $this->availabilities()
            ->orderBy('day_of_week')
            ->orderBy('time_from')
            ->get()
            ->groupBy('day_of_week');
    }

    /**
     * Check if delivery is available on a specific day and time
     */
    public function isAvailable($day, $time)
    {
        return $this->availabilities()
            ->where('day_of_week', $day)
            ->where('time_from', '<=', $time)
            ->where('time_to', '>=', $time)
            ->where('is_active', true)
            ->exists();
    }

}
