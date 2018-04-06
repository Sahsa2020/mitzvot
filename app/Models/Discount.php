<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;

/**
 * App\Models\Discount
 *
 * @mixin \Eloquent
 * @property int $id
 * @property string $code
 * @property int $percent
 * @property int $del_flg
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Discount whereCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Discount whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Discount whereDelFlg($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Discount whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Discount wherePercent($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Discount whereUpdatedAt($value)
 */
class Discount extends Authenticatable
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */

    protected $table = 'cbox_discounts';
    protected $fillable = [
        'code', 'percent', 'del_flg'
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
