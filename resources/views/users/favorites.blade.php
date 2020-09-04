@extends('layouts.app')

@section('content')
    <div class="row">
        <aside class="col-sm-4">
            
            @include('users.card')
        </aside>
            @include('users.navtabs')
            @include('users.favorite')
    </div>
@endsection