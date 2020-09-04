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
                    <div>
                        {{-- 投稿の所有者のユーザ詳細ページへのリンク --}}
                        {!! link_to_route('users.show', $micropost->name, ['user' => $micropost]) !!}
                        <span class="text-muted">posted at {{ $micropost->created_at }}</span>
                    </div>  
                    <div>
                        {{-- 投稿内容 --}}
                        <p class="text-muted">{{ $user->content}}</p>
                        <span class="text-muted"> {{ nl2br(e($micropost->content)) }}</span>
                        <p class="mb-0">{!! nl2br(e($micropost)) !!}</p>
                    </div>
                    <div>
                        {{-- 投稿削除ボタンのフォーム --}}
                        {!! Form::open(['route' => ['microposts.destroy', $microposts], 'method' => 'delete']) !!}
                        {!! Form::submit('Delete', ['class' => 'btn btn-danger btn-sm']) !!}
                        {!! Form::close() !!}
                    </div>
                    {{-- お気に入り登録／アンお気に入りボタン --}}
                    <!--@include('user_favorites.favorite_button')-->
                </div>
            </li>
        @endforeach
    </ul>
    {{-- ページネーションのリンク --}}
    {{ $microposts->links() }}
@endif

