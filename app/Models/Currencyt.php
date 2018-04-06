<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;

/**
 * App\Models\Currencyt
 *
 * @mixin \Eloquent
 * @property int $id
 * @property string $currencyt
 * @property float $rate
 * @property string $country_name
 * @property int $del_flg
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Currencyt whereCountryName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Currencyt whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Currencyt whereCurrencyt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Currencyt whereDelFlg($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Currencyt whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Currencyt whereRate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Currencyt whereUpdatedAt($value)
 */
class Currencyt extends Authenticatable
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */

    protected $table = 'cbox_currencyts';
    protected $fillable = [
        'currencyt', 'rate',  'del_flg'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        // 'password', 'remember_token',
    ];

}
