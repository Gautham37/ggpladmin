@push('css_lib')
@include('layouts.datatables_css')
@endpush

{!! $dataTable->table(['class' => 'table-bordered','width' => '100%']) !!}

@push('scripts_lib')
@include('layouts.datatables_js')
{!! $dataTable->scripts() !!}
@endpush
