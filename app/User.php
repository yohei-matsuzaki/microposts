<?php

namespace App;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;

 // 中略
 /**
     * このユーザに関係するモデルの件数をロードする。
     */
    public function loadRelationshipCounts()
    {
        $this->loadCount('microposts','followings', 'followers','favorites');
    }
    /**
     * このユーザが所有する投稿。（ Micropostモデルとの関係を定義）
     */
    public function microposts()
    {
        return $this->hasMany(Micropost::class);
    }
    /**
     * The attributes that are mass assignable.
     * このユーザがフォロー中のユーザ。（ Userモデルとの関係を定義）
     */
    public function followings()
    {
        return $this->belongsToMany(User::class, 'user_follow', 'user_id', 'follow_id')->withTimestamps();
    }

    /**
     * このユーザをフォロー中のユーザ。（ Userモデルとの関係を定義）
     */
    public function followers()
    {
        return $this->belongsToMany(User::class, 'user_follow', 'follow_id', 'user_id')->withTimestamps();
    }
    
    
     /**
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
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
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];
    
    
     /**
     * $userIdで指定されたユーザをフォローする。
     *
     * @param  int  $userId
     * @return bool
     */
    public function follow($userId)
    {
        // すでにフォローしているかの確認
        $exist = $this->is_following($userId);
        // 相手が自分自身かどうかの確認
        $its_me = $this->id == $userId;

        if ($exist || $its_me) {
            // すでにフォローしていれば何もしない
            return false;
        } else {
            // 未フォローであればフォローする
            $this->followings()->attach($userId);
            return true;
        }
    }

    /**
     * $userIdで指定されたユーザをアンフォローする。
     *
     * @param  int  $userId
     * @return bool
     */
    public function unfollow($userId)
    {
        // すでにフォローしているかの確認
        $exist = $this->is_following($userId);
        // 相手が自分自身かどうかの確認
        $its_me = $this->id == $userId;

        if ($exist && !$its_me) {
            // すでにフォローしていればフォローを外す
            $this->followings()->detach($userId);
            return true;
        } else {
            // 未フォローであれば何もしない
            return false;
        }
    }

    /**
     * 指定された $userIdのユーザをこのユーザがフォロー中であるか調べる。フォロー中ならtrueを返す。
     *
     * @param  int  $userId
     * @return bool
     */
    public function is_following($userId)
    {
        // フォロー中ユーザの中に $userIdのものが存在するか
        return $this->followings()->where('follow_id', $userId)->exists();
    }
    /**
     * このユーザとフォロー中ユーザの投稿に絞り込む。
     */
    public function feed_microposts()
    {
        // このユーザがフォロー中のユーザのidを取得して配列にする
        $userIds = $this->followings()->pluck('users.id')->toArray();
        // このユーザのidもその配列に追加
        $userIds[] = $this->id;
        // それらのユーザが所有する投稿に絞り込む
        return Micropost::whereIn('user_id', $userIds);
    }
    
    /**
     *　　20200901追加L15課題２お気に入り一覧
     */
    public function favorites()
    {
        return $this->belongsToMany(Micropost::class, 'favorites', 'user_id', 'micropost_id')->withTimestamps();
    }
    
     /**
     *　　20200901追加L15課題２中間テーブルへのデータ登録
     */
    /**
     * $micropostIdで指定された投稿をお気に入り登録する。
     * 
     * @param  int  $micropostId
     * @return bool
     */
    public function favorite($micropostId)
    {
       
        // すでにお気に入り登録しているかの確認
        $exist = $this->is_favorite($micropostId);
        // 相手が自分自身かどうかの確認
        // MEMO: $this->idはログインユーザーのIDであって、micropotsテーブルのidではないので、
        // $micropostIdを元に、micropostsから該当するレコードを取得し、そのレコードのuser_idが$this->idと一致するかどうかを判別する必要があると思います
        
        $micropost = Micropost::find($micropostId);
        $its_me = $this->id == $micropost->user_id;

        if ($exist || $its_me) {
            // すでにお気に入り登録していれば何もしない
            return false;
        } else {
            // 未お気に入りであればお気に入りする
            $this->favorites()->attach($micropostId);
            return true;
        }
    }
    
     /**
     *　　20200901追加L15課題２中間テーブルへのデータ削除
     */
    /**
     * $micropost_idで指定されたお気に入り投稿をアンふぁぼする。
     */
    public function unfavorite($micropostId)
    {
       
        // すでにフォローしているかの確認
        $exist = $this->is_favorite($micropostId);
        // 相手が自分自身かどうかの確認
        // MEMO: $this->idはログインユーザーのIDであって、micropotsテーブルのidではないので、
        // $micropostIdを元に、micropostsから該当するレコードを取得し、そのレコードのuser_idが$this->idと一致するかどうかを判別する必要があると思います
        $micropost = Micropost::find($micropostId);
        $its_me = $this->id == $micropost->user_id;
        //$its_me = $this->id == $micropostId;

        if ($exist && !$its_me) {
            // すでにお気に入り登録していればお気に入り登録を外す
            $this->favorites()->detach($micropostId);
            return true;
        } else {
            // 未お気に入りであれば何もしない
            return false;
        }
    }
     /**
     * 指定された  /**
     * 指定された $micropostIdの投稿をこのユーザがお気に入り登録中であるか調べる。フォロー中ならtrueを返す。
     *
     * @param  int  $userId
     * @return bool
     */
    
    public function is_favorite($micropostId)
    {
        
        //return $this->favorite()->where('micropost_id',$userId)->exists();
        return $this->favorites()->where('micropost_id', $micropostId)->exists();
        //return $this->favorites()->where('micropost_id', $userId)->exists();
    }
}
