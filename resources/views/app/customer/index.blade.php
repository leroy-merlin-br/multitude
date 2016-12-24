@extends('app.template')

@section('content')
    <div class="container">
        <div class="col-xs-12">
            <h1>Customers</h1>
        </div>
        <hr>
        <div class="col-xs-12">
            <div id="customerList">
                <legend class="legend">{{ $customerTotal }} Customers found.</legend>
                <table class="table table-zebra">
                    <thead>
                        <tr>
                            <th>Customer</th>
                            <th>Updated at</th>
                            <th>Interactions</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($customers as $customer)
                            <tr>
                                <th>{{ $customer->email ?: $customer->docNumber }}</th>
                                <td>{{ $customer->updated_at->toDateTime()->format('Y-m-d H:i') }}</td>
                                <td>{{ count($customer->interactions) }}</td>
                                <td>
                                    <a class="button button-neutral" href="{{ route("front.customer.show", ['id' => $customer->_id]) }}">
                                        <span class="glyph glyph-eye"></span>
                                        View
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@stop
