@extends('layouts.app')
@section('content')
    <div class="container">
        <div class="list-group">
            @foreach($lists as $list )
            <a href="{{route('getContent',[$list['link'][0],$list['link'][1],$list['link'][2]])}}"><button type="button" class="list-group-item">{{$list['name']}}</button></a>
            @endforeach
        </div>
    </div>
@endsection