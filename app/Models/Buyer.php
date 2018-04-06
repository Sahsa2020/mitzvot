<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;

/**
 * App\Models\Buyer
 *
 * @mixin \Eloquent
 * @property int $id
 * @property string $donateIds
 * @property string $donateQuantities
 * @property int $user_id
 * @property string $name
 * @property string $email
 * @property string $address
 * @property string $comment
 * @property int $del_flg
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Buyer whereAddress($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Buyer whereComment($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Buyer whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Buyer whereDelFlg($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Buyer whereDonateIds($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Buyer whereDonateQuantities($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Buyer whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Buyer whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Buyer whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Buyer whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Buyer whereUserId($value)
 * @property string|null $city
 * @property string|null $state
 * @property string|null $country
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Buyer whereCity($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Buyer whereCountry($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Buyer whereState($value)
 */
class Buyer extends Authenticatable
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */

    protected $table = 'cbox_buyers';

}
