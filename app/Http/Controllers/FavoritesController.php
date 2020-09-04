<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class FavoritesController extends Controller
{
    
    /**
     * 投稿を登録するアクション。
     */
    public function store($micropost_id)
    {
        // 認証済みユーザ（閲覧者）が、 micropost_idの投稿をお気に入りにする
        \Auth::user()->favorite($micropost_id);
        // 前のURLへリダイレクトさせる
        return back();
    } //
    
    
    /**
     * 投稿を削除するアクション。
     */
    public function destroy($micropost_id)
    {
        // 認証済みユーザ（閲覧者）が、 idのユーザをアンフォローする
        \Auth::user()->unfavorite($micropost_id);
        // 前のURLへリダイレクトさせる
        return back();
    }
}
