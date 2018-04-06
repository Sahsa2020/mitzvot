<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;

/**
 * App\Models\Option
 *
 * @mixin \Eloquent
 * @property int $id
 * @property string $key
 * @property string $value
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Option whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Option whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Option whereKey($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Option whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Option whereValue($value)
 */
class Option extends Authenticatable
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */

    protected $table = 'cbox_options';


}
