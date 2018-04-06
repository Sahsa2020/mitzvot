<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;

/**
 * App\Models\MessageHistory
 *
 * @mixin \Eloquent
 * @property int $id
 * @property string $email
 * @property string $name
 * @property string $subject
 * @property string $message
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property int $replied
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\MessageHistory whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\MessageHistory whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\MessageHistory whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\MessageHistory whereMessage($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\MessageHistory whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\MessageHistory whereReplied($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\MessageHistory whereSubject($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\MessageHistory whereUpdatedAt($value)
 */
class MessageHistory extends Authenticatable
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */

    protected $table = 'cbox_messages_history';
    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        // 'password', 'remember_token',
    ];

}
