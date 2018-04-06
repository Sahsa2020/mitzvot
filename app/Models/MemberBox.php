<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;

/**
 * App\Models\MemberBox
 *
 * @mixin \Eloquent
 * @property int $id
 * @property int $member_id
 * @property int $device_id
 * @property int $del_flg
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\MemberBox whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\MemberBox whereDelFlg($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\MemberBox whereDeviceId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\MemberBox whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\MemberBox whereMemberId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\MemberBox whereUpdatedAt($value)
 */
class MemberBox extends Authenticatable
{
    protected $table = 'cbox_member_boxes';

}
