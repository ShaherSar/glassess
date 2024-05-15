<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Lens extends Model
{
    use HasFactory;

    protected $guarded = [];

    private static $prescription_type = [
        0 => 'fashion',
        1 => 'single_vision',
        2 => 'varifocal'
    ];

    private static $lens_type = [
        0 => 'classic',
        1 => 'blue_light',
        2 => 'transition'
    ];

    public function currencies()
    {
        return $this->belongsToMany(Currency::class, 'currency_lens')->withPivot(['price']);
    }

    public static function getLensesTypes()
    {
        return self::$lens_type;
    }

    public static function getPrescriptionTypes()
    {
        return self::$prescription_type;
    }
}
