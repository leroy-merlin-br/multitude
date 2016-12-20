@extends('app.template')

@section('content')
    <div class="container">
        <div class="col-xs-12">
            <h1>Segments</h1>
        </div>
        <hr>
        <div class="col-xs-12">
            <div data-module="QueryBuilder"></div>
        </div>
        <div class="col-xs-12">
            <hr>
            <a class="button"
                data-module="PreviewQuery"
                data-querybuilder="[data-module=QueryBuilder]"
                data-previewbox="#queryPreview">
                Preview
            </a>
            <a class="button button-primary"
                data-module="PreviewQuery"
                data-querybuilder="[data-module=QueryBuilder]"
                data-previewbox="#queryPreview">
                Create new segment
            </a>
        </div>
    </div>
@stop
