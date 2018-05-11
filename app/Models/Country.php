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
class Country extends Authenticatable
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */

     protected $table = 'countries';
     // protected $fillable = [
     //     'user_id', 'follow_user_id'
     // ];
 
     /**
      * The attributes that should be hidden for arrays.
      *
      * @var array
      */
     protected $hidden = [
         'created_at',        
         'del_flg',
         'updated_at',
         'deleted_at'
     ];

}
