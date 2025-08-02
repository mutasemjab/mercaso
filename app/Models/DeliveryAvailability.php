<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DeliveryAvailability extends Model
{
    use HasFactory;

    protected $guarded=[];
     protected $casts = [
        'time_from' => 'datetime:H:i',
        'time_to' => 'datetime:H:i',
        'is_active' => 'boolean'
    ];

    /**
     * Get the delivery that owns the availability
     */
    public function delivery()
    {
        return $this->belongsTo(Delivery::class);
    }

    /**
     * Scope to get active availabilities
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope to get availabilities for a specific day
     */
    public function scopeForDay($query, $day)
    {
        return $query->where('day_of_week', $day);
    }

    /**
     * Get formatted time range
     */
    public function getTimeRangeAttribute()
    {
        return $this->time_from->format('H:i') . ' - ' . $this->time_to->format('H:i');
    }

    /**
     * Get formatted day name
     */
    public function getDayNameAttribute()
    {
        return ucfirst($this->day_of_week);
    }
}
