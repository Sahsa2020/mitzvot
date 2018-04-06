<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;

/**
 * App\Models\Message
 *
 * @mixin \Eloquent
 * @property int $id
 * @property string $message
 * @property int $is_seen
 * @property int $deleted_from_sender
 * @property int $deleted_from_receiver
 * @property int $user_id
 * @property int $conversation_id
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Message whereConversationId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Message whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Message whereDeletedFromReceiver($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Message whereDeletedFromSender($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Message whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Message whereIsSeen($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Message whereMessage($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Message whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Message whereUserId($value)
 */
class Message extends Authenticatable
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */

    protected $table = 'messages';


}
