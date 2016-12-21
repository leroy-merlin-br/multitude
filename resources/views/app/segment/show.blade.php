@extends('app.template')

@section('content')
    <div class="container">
        <div class="col-xs-12">
            <h1>
                Segment:
                <a href="{{ route("front.segment.show", ['id' => $segment->_id]) }}">
                    {{ $segment->name }}
                </a>
            </h1>
        </div>
        <hr>
    </div>
@stop
