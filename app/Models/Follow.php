<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;

/**
 * App\Models\Follow
 *
 * @mixin \Eloquent
 * @property int $id
 * @property int $user_id
 * @property int $follow_user_id
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Follow whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Follow whereFollowUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Follow whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Follow whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Follow whereUserId($value)
 */
class Follow extends Authenticatable
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */

    protected $table = 'cbox_following';
    protected $fillable = [
        'user_id', 'follow_user_id'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
    ];

}
