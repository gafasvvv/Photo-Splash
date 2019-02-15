<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;

class Photo extends Model
{
    protected $fillable = ['user_id', 'filename'];

    /** JSONに含めるアクセサ */
    protected $appends = [
        'url', 'likes_count', 'liked_by_user'
    ];

    /**JSONに含める属性 */
    protected $visiable = [
        'id', 'user', 'url', 'comments',
        'likes_count', 'liked_by_user'
    ];
    
    /**
     * リレーションシップ - usersテーブル
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id', 'users');
    }


    /**
     * リレーションシップ - usersテーブル
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function likes()
    {
        return $this->belongsToMany('App\User', 'likes')->withTimestamps();
    }

    /**
     * アクセサ - url
     * @return string
     */
    public function getUrlAttribute()
    {
        return Storage::cloud()->url($this->attributes['filename']);
    }

    /**
     * アクセサ- likes_count
     * @return int
     */
    public function getLikesCountAttribute()
    {
        return $this->likes->count();
    }

    /**
     * アクセサ- liked_by_user
     * @return boolean
     */
    public function getLikedByUserAttribute()
    {
        if(Auth::guest()){
            return false;
        }

        return $this->likes->contains(function($user) {
            return $user->id === Auth::user()->id;
        });
    }
    

    /**
     * リレーションシップ - commentsテーブル
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function comments()
    {
        return $this->hasMany('App\Comment')->orderBy('id', 'desc');
    }

}
