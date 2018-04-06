<?php

namespace App;

use App\Models\Comment;
use App\Models\Like;
use App\Models\Post;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use SammyK\LaravelFacebookSdk\SyncableGraphNodeTrait;

/** @noinspection PhpUnnecessaryFullyQualifiedNameInspection */
/** @noinspection PhpUndefinedClassInspection */

/**
 * App\User
 *
 * @property int $id
 * @property string $name
 * @property string $email
 * @property string $password
 * @property string|null $remember_token
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property int $age
 * @property string $school
 * @property string $company
 * @property string $city
 * @property string $country
 * @property string $address
 * @property string $phone
 * @property string $birthday
 * @property int $status
 * @property string $activation_token
 * @property string $password_token
 * @property string $image_url
 * @property string $image_origin
 * @property int $del_flg
 * @property int $goal_daily
 * @property int $goal_weekly
 * @property int $goal_monthly
 * @property int $parent_id
 * @property string $ip_address
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereActivationToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereAddress($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereAge($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereBirthday($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereCity($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereCompany($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereCountry($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereDelFlg($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereGoalDaily($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereGoalMonthly($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereGoalWeekly($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereImageOrigin($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereImageUrl($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereIpAddress($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereParentId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User wherePassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User wherePasswordToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User wherePhone($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereRememberToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereSchool($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereUpdatedAt($value)
 * @mixin \Eloquent
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection|\Illuminate\Notifications\DatabaseNotification[] $notifications
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Role[] $roles
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Post[] $posts
 * @property string|null $weekly_mail_video
 * @property int|null $weekly_mail_ignore
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereWeeklyMailIgnore($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereWeeklyMailVideo($value)
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Comment[] $comments
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Like[] $likes
 * @property int $facebook_user_id
 * @property string|null $access_token
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereAccessToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereFacebookUserId($value)
 * @property string $oauth_token
 * @property string $oauth_token_secret
 * @property string $oauth_callback_confirmed
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereOauthCallbackConfirmed($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereOauthToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereOauthTokenSecret($value)
 * @property string|null $user_id
 * @property string|null $screen_name
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereScreenName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereUserId($value)
 */
class User extends Authenticatable
{
    use Notifiable, /** @noinspection PhpUndefinedClassInspection */
        HasRoles;
        use SyncableGraphNodeTrait;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that are mass syncable with Facebook Graph node data
     * 
     * @var array
     */
    protected static $graph_node_field_aliases = [
        'id' => 'facebook_user_id',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token', 'access_token'
    ];

    /**
     * An users may has many posts
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function posts()
    {
        return $this->hasMany(Post::class, 'posted_by', 'id');
    }

    /**
     * A user may has many comments
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function comments()
    {
        return $this->hasMany(Comment::class, 'commented_by', 'id');
    }

    /**
     * A user may has many likes
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function likes()
    {
        return $this->hasMany(Like::class, 'liked_by', 'id');
    }
}
