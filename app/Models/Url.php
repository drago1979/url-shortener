<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;


class Url extends Model
{
    use HasFactory;

    protected $fillable = [
        'original_url',
        'random_value',
        'total_visits',
        'unique_visits'
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
        $urlLength = self::determineUrlLength();

        $randomValue = Str::random($urlLength);

        if (Url::where('random_value', $randomValue)->exists()) {
            return static::assignRandomValueToUrl($url);
        }

        $url->random_value = $randomValue;
    }

    protected static function determineUrlLength()
    {
        $charactersPoolUsedByStrRandomFunction = 62;
        $urlPoolPercentageThatCanBeUsed = 40;

        $urlPoolUsedTillNow = Url::count();

        $urlLengthObject = DB::table('url_length')->first();
        $urlLength       = $urlLengthObject->length;

        $urlPoolTotal = $charactersPoolUsedByStrRandomFunction ** $urlLength;

        // If we did not spend the defined %, we return the current value for "url_lengt".
        // Otherwise, we will increment the "url_length" by 1.
        if ($urlPoolUsedTillNow <= $urlPoolTotal * ($urlPoolPercentageThatCanBeUsed / 100)) {
            return $urlLength;
        } else {
            DB::table('url_length')->where('id', '=', $urlLengthObject->id)->update(['length' => ++$urlLength]);

            return $urlLength;
        }
    }
}

