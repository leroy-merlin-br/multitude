@extends('app.template')

@section('content')
    <div class="container">
        <div class="col-xs-12">
            <h1>
                Customer:
                <a href="{{ route("front.customer.show", ['id' => $customer->_id]) }}">
                    {{ $customer->email ?: $customer->docNumber }}
                </a>
            </h1>
        </div>
        <hr>
        <div class="col-xs-12">
            <h3 class="link">{{ $customer->email ?: $customer->docNumber }}</h3>
            <img data-module="Gravatar" data-email="{{ $customer->email }}" class="circular">

            <table class="table table-zebra">
                <thead>
                    <tr>
                        <th>When</th>
                        <th></th>
                        <th>Interaction</th>
                        <th>Details</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($customer->interactions() as $interaction)
                    <tr>
                        <td>{{ $interaction->created_at->toDateTime()->format('Y-m-d H:i') }}</td>
                        <th><div class="glyph glyph-magnifier"></div></th>
                        <td>{{ $interaction->interaction }}</td>
                        <td><code>{{ json_encode($interaction->params) }}</code></td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@stop
