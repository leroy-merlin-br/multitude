@extends('app.template')

@section('content')
    <div class="container">
        <div class="col-xs-12">
            <h1>
                Segment:
                <a href="{{ route("front.segment.show", ['id' => $segment->_id]) }}">
                    {{ $segment->name }}
                </a>
                <a class="button button-neutral" href="{{ route("front.segment.edit", ['id' => $segment->_id]) }}">
                    <span class="glyph glyph-pencil"></span>
                    Edit
                </a>
            </h1>
        </div>
        <hr>
        @include('app.segment._link-index')
    </div>
@stop
