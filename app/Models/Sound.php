<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;

/**
 * App\Models\Sound
 *
 * @mixin \Eloquent
 * @property int $id
 * @property int $user_id
 * @property string $name
 * @property string $file_url
 * @property int $del_flg
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Sound whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Sound whereDelFlg($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Sound whereFileUrl($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Sound whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Sound whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Sound whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Sound whereUserId($value)
 */
class Sound extends Authenticatable
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */

    protected $table = 'cbox_sounds';
    protected $fillable = [
        'user_id', 'name', 'file_url', 'del_flg'
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
