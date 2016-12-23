@extends('app.template')

@section('content')
    <div class="container">
        <div class="col-xs-12">
            <h1>Segments</h1>
        </div>
        <hr>
        <div class="col-xs-12">
            <table class="table table-zebra">
                <thead>
                    <tr>
                        <th>Id</th>
                        <th>Name</th>
                        <th>Customers</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($segments as $segment)
                        <tr>
                            <th>{{ $segment->slug }}</th>
                            <td>{{ $segment->name }}</td>
                            <td>0</td>
                            <td>
                                <a class="button button-neutral" href="{{ route("front.segment.show", ['id' => $segment->_id]) }}">
                                    <span class="glyph glyph-eye"></span>
                                    View
                                </a>
                                <a class="button button-neutral" href="{{ route("front.segment.edit", ['id' => $segment->_id]) }}">
                                    <span class="glyph glyph-pencil"></span>
                                    Edit
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <hr>
        <div class="col-xs-12">
            <a href="{{ route("front.segment.create") }}" class="button button-primary">Create new</a>
        </div>
    </div>
@stop
