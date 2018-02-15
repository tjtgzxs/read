@extends('layouts.app')
@section('content')
    <div class="container">
        <h1 class="h1">{{$info['header']}}</h1>
        <p class="phpdebugbar-fa-paragraph">{{$info['detail']}}</p>
    </div>
    <nav aria-label="...">
        <ul class="pager">
            @if(!empty($info['lastPage']))
                <li class="previous"><a href="{{route('getContent',[$info['c1'],$info['c2'],$info['lastPage']])}}"><span aria-hidden="true">&larr;</span> 上一页</a></li>
            @endif
                <li class="center-block"><a href="{{route('getCatalogue',[$info['c1'],$info['c2']])}}"><button type="button" class="btn btn-primary ">返回目录</button></a></li>
            @if(!empty($info['nextPage']))
                <li class="next"><a href="{{route('getContent',[$info['c1'],$info['c2'],$info['nextPage']])}}">下一页 <span aria-hidden="true">&rarr;</span></a></li>
            @endif
        </ul>
    </nav>

@endsection