<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Page extends Model
{
    use HasFactory;
    
     protected $guarded=[];

    // Constants for page types
    const TYPE_ABOUT_US = 1;
    const TYPE_TERMS_CONDITIONS = 2;
    const TYPE_PRIVACY_POLICY = 3;

    // Scope to get privacy policy
    public function scopePrivacyPolicy($query)
    {
        return $query->where('type', self::TYPE_PRIVACY_POLICY);
    }

    // Scope to get terms and conditions
    public function scopeTermsConditions($query)
    {
        return $query->where('type', self::TYPE_TERMS_CONDITIONS);
    }

    // Scope to get about us
    public function scopeAboutUs($query)
    {
        return $query->where('type', self::TYPE_ABOUT_US);
    }

    // Get page type name
    public function getTypeNameAttribute()
    {
        $types = [
            self::TYPE_ABOUT_US => 'About Us',
            self::TYPE_TERMS_CONDITIONS => 'Terms and Conditions',
            self::TYPE_PRIVACY_POLICY => 'Privacy Policy',
        ];

        return $types[$this->type] ?? 'Unknown';
    }
}
