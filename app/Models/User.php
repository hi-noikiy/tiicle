<?php

namespace App\Models;

use Auth;
use App\Models\Traits\ActiveUserHelper;
use Illuminate\Notifications\Notifiable;
use Venturecraft\Revisionable\RevisionableTrait;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable {
    use Notifiable {
        notify as protected laravelNotify;
    }
    use ActiveUserHelper;
    use RevisionableTrait;
    use Traits\UserSocialiteHelper;

    public static function boot() {
        parent::boot();
    }


    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email','gender', 'real_name', 'password', 'github_id', 'github_name', 'github_url', 'city', 'company', 'website', 'introduction', 'avatar', 'register_source',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * 一个用户可以发布多个话题
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function topics() {
        return $this->hasMany(Topic::class);
    }

    /**
     * 定义用户话题投票关联
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function votedTopics() {
        return $this->belongsToMany(Topic::class, 'user_topic')->withTimestamps();
    }

    /**
     * 用户关注问题
     * @param Topic $topic
     * @return array
     */
    public function VotedTopicThis($topic) {
        return $this->votedTopics()->toggle($topic);
    }

    /**
     * 判断用户是否投票了某个话题
     * @param Topic $topic
     * @return bool
     */
    public function votedTopicd($topic) {
        return ! !$this->votedTopics()->where('topic_id', $topic)->count();
    }

    /**
     * 用户授权
     * @param $model
     * @return bool
     */
    public function isAuthorOf($model) {
        return $this->id == $model->user_id;
    }

    /**
     * 一个用户有多个评论
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function replies() {
        return $this->hasMany(Reply::class);
    }

    /**
     * 一个用户可以关注多个用户.一个用户也可以被多个用户关注
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function followers()
    {
        return $this->belongsToMany(self::class,'user_followers','follower_id','followed_id')->withTimestamps();
    }

    /**
     * 关注用户
     * @param $user
     * @return array
     */
    public function toggleFollow($user)
    {
        return $this->followers()->toggle($user);
    }

    /**
     * 判断是否关注了某个用户
     * @param int $user_id
     * @return bool
     */
    public function isFollowing(int $user_id)
    {
        return !!$this->followers()->where('followed_id',$user_id)->count();
    }

    /**
     * 一个用户可以发送多个私信
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function messages()
    {
        return $this->hasMany(Message::class,'to_user_id');
    }

    public function notify($instance)
    {
        // 如果要通知的人是当前用户，就不必通知了！
        /**
         * 当前评论用户的id是否和当前登录用户的id一样
         */
        if ($this->id == Auth::id())
        {
            return;
        }

        $this->increment('notification_count');
        $this->laravelNotify($instance);
    }

    /**
     * 将通知总数清空
     */
    public function markAsRead()
    {
        $this->notification_count = 0;
        $this->save();
        $this->unreadNotifications->markAsRead();
    }
}
