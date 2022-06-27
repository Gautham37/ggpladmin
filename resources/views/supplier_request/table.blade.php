@push('css_lib')
@include('layouts.datatables_css')
@endpush

{!! $dataTable->table(['class' => 'table-bordered','width' => '100%']) !!}

@push('scripts_lib')
@include('layouts.datatables_js')
{!! $dataTable->scripts() !!}
@endpush

<script src="{{asset('plugins/jquery/jquery.min.js')}}"></script>
<script type="text/javascript">
	function reloadTable() {
		var table = $('#dataTableBuilder').DataTable({});
	    //table.ajax.url("{{ route('reports.expenseCategory') }}?start_date="+start_date+"&end_date="+end_date);
	    table.ajax.reload(); 
	}
</script>
