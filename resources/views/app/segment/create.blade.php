@extends('app.template')

@section('content')
    <div class="container">
        <div class="col-xs-12">
            <h1>Creating new Segment</h1>
        </div>
        <hr>
        @include('app.segment._form')
    </div>
@stop
