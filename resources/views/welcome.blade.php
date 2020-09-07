@extends('layouts.app')

@section('content')
    @if (Auth::check())
        <div class="row">
            <aside class="col-sm-4">
                {{-- ユーザ情報 --}}
                @include('users.card')
                <ul class="nav nav-pills nav-fill">
                <li class="nav-item">
                <a href="{{ route('users.favorites', ['id' => $user->id]) }}" class="nav-link {{ Request::routeIs('users.favorites' ) ? 'active' : '' }}">
                Favorites
                <span class="badge badge-secondary">{{ $user->favorites_count }}</span>
                </a>
                </li>
                </ul>
            </aside>
            <div class="col-sm-8">
                {{-- 投稿フォーム --}}
                @include('microposts.form')
                {{-- 投稿一覧 --}}
                @include('microposts.microposts')
                 {{-- お気に入り一覧タブ --}}
            </div>
        </div>
    @else
        <div class="center jumbotron">
            <div class="text-center">
                <h1>Welcome to the Microposts</h1>
                {{-- ユーザ登録ページへのリンク --}}
                {!! link_to_route('signup.get', 'Sign up now!', [], ['class' => 'btn btn-lg btn-primary']) !!}
            </div>
        </div>
    @endif
@endsection