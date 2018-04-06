<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;

/**
 * App\Models\Deposit
 *
 * @mixin \Eloquent
 * @property int $id
 * @property int $device_id
 * @property int $user_id
 * @property float $amount
 * @property string $currencyt
 * @property int $coin_size
 * @property int $del_flg
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Deposit whereAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Deposit whereCoinSize($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Deposit whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Deposit whereCurrencyt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Deposit whereDelFlg($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Deposit whereDeviceId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Deposit whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Deposit whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Deposit whereUserId($value)
 */
class Deposit extends Authenticatable
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */

    protected $table = 'cbox_deposits';
    protected $fillable = [
        'device_id', 'amount', 'currencyt', 'del_flg'
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
