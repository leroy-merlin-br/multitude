email,documentNumber,segment
@foreach ($customers as $customer)
"{{ $customer->email }}","{{ $customer->docNumber }}","{{ $segment->slug }}"
@endforeach
