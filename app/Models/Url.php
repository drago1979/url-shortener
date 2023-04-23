<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;


class Url extends Model
{
    use HasFactory;

    protected $fillable = [
        'original_url',
        'random_value',
        'total_visits'
    ];

    protected $appends = ['short_url'];

    protected static function boot()
    {
        parent::boot();

        static::creating(function (Url $url) {
            static::assignRandomValueToUrl($url);
        });
    }

    ### Attribute modifiers
    protected function shortUrl(): Attribute
    {
        return Attribute::make(
            get: fn() => env('APP_URL') . '/' . $this->random_value,
        );
    }

    ### For "internal" use
    protected static function assignRandomValueToUrl(Url $url)
    {
        $randomValue = Str::random(8);

        if (Url::where('random_value', $randomValue)->exists()) {
           return static::assignRandomValueToUrl($url);
        }

        $url->random_value = $randomValue;
    }
}

