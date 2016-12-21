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
                                <a href="{{ route("front.segment.show", ['id' => $segment->_id]) }}">
                                    View
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@stop
