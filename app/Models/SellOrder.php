<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;

/**
 * App\Models\SellOrder
 *
 * @mixin \Eloquent
 * @property int $id
 * @property int $donate_id
 * @property int $amount
 * @property string $name
 * @property string $email
 * @property string $address
 * @property string $city
 * @property string $state
 * @property int $del_flg
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property string|null $country
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\SellOrder whereAddress($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\SellOrder whereAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\SellOrder whereCity($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\SellOrder whereCountry($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\SellOrder whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\SellOrder whereDelFlg($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\SellOrder whereDonateId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\SellOrder whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\SellOrder whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\SellOrder whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\SellOrder whereState($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\SellOrder whereUpdatedAt($value)
 */
class SellOrder extends Authenticatable
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */

    protected $table = 'cbox_orders';

}
