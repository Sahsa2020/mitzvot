<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;

/**
 * App\Models\House
 *
 * @mixin \Eloquent
 * @property int $id
 * @property string $name
 * @property string $manager
 * @property string $address
 * @property string $city
 * @property string $state
 * @property int $del_flg
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property string $manager_email
 * @property string|null $country
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\House whereAddress($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\House whereCity($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\House whereCountry($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\House whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\House whereDelFlg($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\House whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\House whereManager($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\House whereManagerEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\House whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\House whereState($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\House whereUpdatedAt($value)
 */
class House extends Authenticatable
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['name', 'manager', 'manager_email', 'address', 'city', 'state', 'del_flg'];
    protected $table = 'cbox_houses';
    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        // 'password', 'remember_token',
    ];

}
