<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;

/**
 * App\Models\Box
 *
 * @mixin \Eloquent
 * @property int $id
 * @property int $device_id
 * @property int $user_id
 * @property int $d_count
 * @property string $country_code
 * @property int $del_flg
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Box whereCountryCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Box whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Box whereDCount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Box whereDelFlg($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Box whereDeviceId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Box whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Box whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Box whereUserId($value)
 * @property int|null $update_flg
 * @property int|null $major_version
 * @property int|null $minor_version
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Box whereMajorVersion($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Box whereMinorVersion($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Box whereUpdateFlg($value)
 */
class Box extends Authenticatable
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */

    protected $table = 'cbox_boxes';
    protected $fillable = [
        'device_id', 'user_id', 'location', 'del_flg'
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
