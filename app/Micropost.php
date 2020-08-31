<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Micropost extends Model
{
    protected $fillable = ['content'];

/**
     * この投稿を所有するユーザ。（ Userモデルとの関係を定義）
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    } 
    
    /**
     *20200901L15課題２お気に入り機能の多対多
     */
    public function favorite_users()
    {
        return $this->belongsToMany(User::class, 'favorites', 'micropost_id','user_id')->withTimestamps();
    
    }  
}
