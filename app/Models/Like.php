<?php

namespace App\Models;

use App\User;
use Illuminate\Database\Eloquent\Model;

/** @noinspection PhpUnnecessaryFullyQualifiedNameInspection */

/**
 * App\Models\Like
 *
 * @property int $id
 * @property int $on_post
 * @property int $liked_by
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property-read \App\User $likedBy
 * @property-read \App\Models\Post $onPost
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Like whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Like whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Like whereLikedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Like whereOnPost($value)
 * @mixin \Eloquent
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Like whereUpdatedAt($value)
 */
class Like extends Model
{
    /**
     * A like should always belongs to a post
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function onPost()
    {
        return $this->belongsTo(Post::class, 'on_post', 'id');
    }

    /**
     * A like should always belongs to an user
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function likedBy()
    {
        return $this->belongsTo(User::class, 'liked_by', 'id');
    }
}
