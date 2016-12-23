@extends('app.template')

@section('content')
    <div class="container">
        <div class="col-xs-12">
            <h1>
                Edit segment:
                <a href="{{ route("front.segment.show", ['id' => $segment->_id]) }}">
                    {{ $segment->name }}
                </a>
            </h1>
        </div>
        <hr>
        @include('app.segment._form')
    </div>
@stop
