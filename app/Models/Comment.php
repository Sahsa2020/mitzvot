<?php

namespace App\Models;

use App\User;
use Illuminate\Database\Eloquent\Model;

/** @noinspection PhpUnnecessaryFullyQualifiedNameInspection */

/**
 * App\Models\Comment
 *
 * @property int $id
 * @property string $comment
 * @property int $is_edited 0 => No, 1 =>Yes
 * @property int $on_post
 * @property int $commented_by
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property-read \App\User $commentedBy
 * @property-read \App\Models\Post $onPost
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Comment whereComment($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Comment whereCommentedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Comment whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Comment whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Comment whereIsEdited($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Comment whereOnPost($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Comment whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Comment extends Model
{
    /**
     * A post should always belongs to a post
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function onPost()
    {
        return $this->belongsTo(Post::class, 'on_post', 'id');
    }

    /**
     * A post should always belongs to an user
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function commentedBy()
    {
        return $this->belongsTo(User::class, 'commented_by', 'id');
    }
}
