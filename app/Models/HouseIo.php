<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;

/**
 * App\Models\HouseIo
 *
 * @mixin \Eloquent
 * @property int $id
 * @property int $house_id
 * @property int $order_id
 * @property int $amount
 * @property string $comment
 * @property int $del_flg
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\HouseIo whereAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\HouseIo whereComment($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\HouseIo whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\HouseIo whereDelFlg($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\HouseIo whereHouseId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\HouseIo whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\HouseIo whereOrderId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\HouseIo whereUpdatedAt($value)
 */
class HouseIo extends Authenticatable
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */

    protected $table = 'cbox_houseios';
    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        // 'password', 'remember_token',
    ];

}
