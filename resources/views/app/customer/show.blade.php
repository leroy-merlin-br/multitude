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
            <p>
                <strong>Location</strong>
                <span class="badge">{{ $customer->location ?: "none" }}</span>
            </p>

            <table class="table table-zebra table-bordered">
                <thead>
                    <tr>
                        <th>When</th>
                        <th>Channel</th>
                        <th>Interaction</th>
                        <th>Details</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($customer->interactions()->sort(['_id' => -1]) as $interaction)
                    <tr>
                        <td>{{ $interaction->created_at->toDateTime()->format('Y-m-d H:i') }}</td>
                        <th>
                            @if ('mobile' == $interaction->channel)
                                <img title="mobile" width="32" src="http://image.flaticon.com/icons/svg/15/15874.svg">
                            @else
                                <img title="web" width="32" src="http://image.flaticon.com/icons/svg/34/34288.svg">
                            @endif
                        </th>
                        <td><span class="badge badge-primary">{{ $interaction->interaction }}</span></td>
                        <td><code>
                            @foreach($interaction->params as $key => $value)
                                <p>
                                    <strong>{{ $key }}:</strong>
                                    {!! is_array($value) ? implode(',<br>', $value) : $value !!}
                                </p>
                            @endforeach
                        </code></td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@stop
