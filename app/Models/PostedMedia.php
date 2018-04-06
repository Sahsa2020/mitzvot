<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/** @noinspection PhpUnnecessaryFullyQualifiedNameInspection */

/**
 * App\Models\PostedMedia
 *
 * @property-read \App\Models\Post $inPost
 * @mixin \Eloquent
 * @property int $id
 * @property string $media_content_path
 * @property int $media_type 0 => Image, 1 => Video, 2 => Audio
 * @property int $in_post
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\PostedMedia whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\PostedMedia whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\PostedMedia whereInPost($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\PostedMedia whereMediaContentPath($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\PostedMedia whereMediaType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\PostedMedia whereUpdatedAt($value)
 */
class PostedMedia extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'posted_medias';

    /**
     * A media should be in a post
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function inPost()
    {
        return $this->belongsTo(Post::class, 'in_post', 'id');
    }
}
