<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;

/**
 * App\Models\BoxImage
 *
 * @mixin \Eloquent
 * @property int $id
 * @property int $box_id
 * @property string $image_url
 * @property int $order
 * @property int $del_flg
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\BoxImage whereBoxId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\BoxImage whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\BoxImage whereDelFlg($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\BoxImage whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\BoxImage whereImageUrl($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\BoxImage whereOrder($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\BoxImage whereUpdatedAt($value)
 */
class BoxImage extends Authenticatable
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */

    protected $table = 'cbox_images';

}
