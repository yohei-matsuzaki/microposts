<!--@if (count($microposts) > 0)
    <ul class="list-unstyled">
        @foreach ($microposts as $user)
            <li class="media">
                {{-- ユーザのメールアドレスをもとにGravatarを取得して表示 --}}
                <img class="mr-2 rounded" src="{{ Gravatar::get($user->email, ['size' => 50]) }}" alt="">
                <div class="media-body">
                    <div>
                        {{ $user->content }}
                    </div>
                    <div>
                        {{-- ユーザ詳細ページへのリンク --}}
                        <p>{!! link_to_route('users.show', 'View profile', ['user' => $user->id]) !!}</p>
                    </div>
                </div>
            </li>
        @endforeach
    </ul>
    {{-- ページネーションのリンク --}}
    {{ $microposts->links() }}
@endif-->

@if (count($microposts) > 0)
    <ul class="list-unstyled">
        @foreach ($microposts as $micropost)
            <li class="media mb-3">
             {{-- ユーザのメールアドレスをもとにGravatarを取得して表示 --}}
                <img class="mr-2 rounded" src="{{ Gravatar::get($user->email, ['size' => 50]) }}" alt="">
                <div class="media-body">
                    <div>
                        {{-- 投稿の所有者のユーザ詳細ページへのリンク --}}
                        {!! link_to_route('users.show', $micropost->name, ['user' => $micropost]) !!}
                        <span class="text-muted">posted at {{ $micropost->created_at }}</span>
                    </div>  
                    <div>
                        {{-- 投稿内容 --}}
                        {!! nl2br(e($micropost->content)) !!}
                    </div>
                    <div>
                    {{-- お気に入り登録／アンお気に入りボタン --}}
                    @if (Auth::id() != $user->id)
                        @if (Auth::user()->is_favorite($micropost->id))
                            {{-- お気に入り解除ボタンのフォーム --}}
                            {!! Form::open(['route' => ['favorites.unfavorite', $micropost->id], 'method' => 'delete']) !!}
                                {!! Form::submit('Unfavorite', ['class' => "btn btn-danger btn-block"]) !!}
                            {!! Form::close() !!}
                        @else
                            {{-- お気に入り登録ボタンのフォーム --}}
                            {!! Form::open(['route' => ['favorites.favorite', $micropost->id]]) !!}
                                {!! Form::submit('Favorite', ['class' => "btn btn-primary btn-block"]) !!}
                            {!! Form::close() !!}
                        @endif
                    @endif
                    </div>
               </div>
            </li>
        @endforeach
    </ul>
    {{-- ページネーションのリンク --}}
    {{ $microposts->links() }}
@endif

