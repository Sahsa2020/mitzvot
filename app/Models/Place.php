<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;

/**
 * App\Models\Place
 *
 * @mixin \Eloquent
 * @property int $id
 * @property int $country_id
 * @property int $state_id
 * @property string $city
 * @property string $district
 * @property int $population
 * @property int $units
 * @property int $cost_assumption
 * @property int $profit_assumption
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 */
class Place extends Authenticatable
{
    protected $table = 'places';
    protected $fillable = [
        'country_id',
        'state_id',
        'city',
        'district',
        'population',
        'units',
        'cost_assumption',
        'profit_assumption'
    ];
    protected $hidden = [
        'created_at',
        'updated_at'
    ];

}
