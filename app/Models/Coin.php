<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;

/**
 * App\Models\Coin
 *
 * @mixin \Eloquent
 * @property int $id
 * @property int $size
 * @property string $country_code
 * @property float $amount
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Coin whereAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Coin whereCountryCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Coin whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Coin whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Coin whereSize($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Coin whereUpdatedAt($value)
 */
class Coin extends Authenticatable
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */

    protected $table = 'cbox_coins';


}
