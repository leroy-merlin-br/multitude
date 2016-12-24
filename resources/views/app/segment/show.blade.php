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
                <a class="button button-danger" href="{{ route("front.segment.exportCsv", ['id' => $segment->_id]) }}" target="_blank">
                    <span class="glyph glyph-double-arrow-down"></span>
                    Export CSV
                </a>
            </h1>
        </div>
        <hr>
        <div class="col-xs-12">
            <legend class="legend">{{ $customers->count() }} Customers are part of this segment.</legend>
                <table class="table table-zebra">
                    <thead>
                        <tr>
                            <th>Customer</th>
                            <th>Segments</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $count = 1?>
                        @foreach ($customers as $customer)
                            <tr>
                                <th>{{ $customer->email ?: $customer->docNumber }}</th>
                                <td>
                                    @foreach($customer->segments as $segmentSlug)
                                        <span class="badge badge-primary">{{ $segmentSlug }}</span>
                                    @endforeach
                                </td>
                                <td>
                                    <a class="button button-neutral" href="{{ route("front.customer.show", ['id' => $customer->_id]) }}">
                                        <span class="glyph glyph-eye"></span>
                                        View
                                    </a>
                                </td>
                            </tr>
                            <?php $count++ ?>
                            @if ($count > 15)
                            <tr>
                                <td colspan="3">
                                     <p class="align-center">{{ $customers->count() - $count }} more...</p>
                                </td>
                            </tr>
                            <?php break; ?>
                            @endif
                        @endforeach
                    </tbody>
                </table>
        </div>
        @include('app.segment._link-index')
    </div>
@stop
