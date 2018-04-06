<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;

/**
 * App\Models\SellBox
 *
 * @mixin \Eloquent
 * @property int $id
 * @property string $title
 * @property string $detail
 * @property string $type
 * @property float $price
 * @property int $sold_count
 * @property int $amount
 * @property string $main_image_url
 * @property int $del_flg
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\SellBox whereAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\SellBox whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\SellBox whereDelFlg($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\SellBox whereDetail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\SellBox whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\SellBox whereMainImageUrl($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\SellBox wherePrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\SellBox whereSoldCount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\SellBox whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\SellBox whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\SellBox whereUpdatedAt($value)
 */
class SellBox extends Authenticatable
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */

    protected $table = 'cbox_sell_boxes';

}
