<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;

/**
 * App\Models\Donate
 *
 * @mixin \Eloquent
 * @property int $id
 * @property int $org_id
 * @property string $name
 * @property string $picture
 * @property string $city
 * @property string $country
 * @property string $description
 * @property int $commitment
 * @property int $donate_count
 * @property int $exist_count
 * @property int $del_flg
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Donate whereCity($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Donate whereCommitment($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Donate whereCountry($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Donate whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Donate whereDelFlg($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Donate whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Donate whereDonateCount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Donate whereExistCount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Donate whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Donate whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Donate whereOrgId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Donate wherePicture($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Donate whereUpdatedAt($value)
 * @property string $image_origin
 * @property string|null $address
 * @property string|null $state
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Donate whereAddress($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Donate whereImageOrigin($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Donate whereState($value)
 */
class Donate extends Authenticatable
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */

    protected $table = 'cbox_donates';

}
