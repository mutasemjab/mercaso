<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PointProduct extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    /**
     * Get the purchases for this point product.
     */
    public function purchases()
    {
        return $this->hasMany(PointProductPurchase::class);
    }

    /**
     * Check if point product is available for purchase
     */
    public function isAvailable()
    {
        return $this->is_active && $this->stock > 0;
    }

    /**
     * Check if user can afford this point product
     */
    public function canUserAfford(User $user): bool
    {
        return $user->points >= $this->points_required;
    }

    /**
     * Scope to get point products user can afford
     */
    public function scopeAffordableFor($query, User $user)
    {
        return $query->where('points_required', '<=', $user->points)
                    ->where('is_active', true)
                    ->where('stock', '>', 0);
    }
}
