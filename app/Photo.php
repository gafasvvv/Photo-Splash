<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Photo extends Model
{
    protected $fillable = ['user_id', 'photo_url'];
    
    /**
     * リレーションシップ - usersテーブル
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id', 'users');
    }

    /**
     * アクセサ - url
     * @return string
     */
    public function getUrlAttribute()
    {
        return Storage::cloud()->url();
    }

    /**JSONに含める属性 */
    protected $visiable = [
        'id', 'user', 'url'
    ];

}
