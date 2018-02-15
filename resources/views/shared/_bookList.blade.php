@extends('layouts.app')
@section('content')

    <div class="container">
        <div class="row">
    @foreach($info['books'] as $book)

                <div class="col-sm-6 col-md-4">
                    <div class="thumbnail">
                        <img src="{{$book['img']}}" alt="...">
                        <div class="caption">
                            <h3>{{$book['name']}}</h3>
                            <p>{{$book['summary']}}</p>
                            <p><a href="{{route('getCatalogue',[$book['link'][0],$book['link'][1]])}}" class="btn btn-primary" role="button">Button</a> </p>
                        </div>
                    </div>
                </div>

    @endforeach
        </div>
    </div>
    <nav aria-label="...">
        <ul class="pager">
            @if(!empty($info['last']))
                <li class="previous"><a href="{{route('article',[$info['last']])}}"><span aria-hidden="true">&larr;</span> 上一页</a></li>
            @endif
            @if(!empty($info['next']))
                <li class="next"><a href="{{route('article',[$info['next']])}}">下一页 <span aria-hidden="true">&rarr;</span></a></li>
            @endif
        </ul>
    </nav>
@endsection