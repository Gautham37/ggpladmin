@push('css_lib')
@include('layouts.datatables_css')
@endpush

	<table class="table table-bordered dataTable no-footer dtr-inline stock-table" width="100%">
		<thead>
			<tr>
				<th>ID</th>
				<th>DATE</th>
				<th>TRANSACTION TYPE</th>
				<th>QUANTITY</th>
				<th>CLOSING STOCK</th>
				<th>NOTES</th>
			</tr>
		</thead>
		<tbody>
		</tbody>
	</table>

@push('scripts_lib')
@include('layouts.datatables_js')
@endpush

@push('scripts')
<script>
	
	var stock_table = $('.stock-table').DataTable({
       processing: true,
       serverSide: true,
       pageLength:20,
       ajax: "{{ route('reports.show') }}?report_type=product-stock-report&product_id="+"{{$product->id}}&start_date=&end_date=",
       columns: [
           {data: 'id', name: 'id', orderable: false, searchable: true, class: "text-left"},
           {data: 'date', name: 'date', orderable: false, searchable: true, class: "text-left"},
           {data: 'category', name: 'category', orderable: false, searchable: true, class: "text-center"},
           {data: 'quantity', name: 'quantity', orderable: false, searchable: true, class: "text-right"},
           {data: 'closing_stock', name: 'closing_stock', orderable: false, searchable: true, class: "text-center"},
           {data: 'notes', name: 'notes', orderable: false, searchable: true, class: "text-center"},
       ]
    });

    function cb(start, end) {
        $('#reportrange span').html(start.format('MMMM D, YYYY') + ' - ' + end.format('MMMM D, YYYY'));
        var start_date      = start.format('YYYY-MM-DD');
        var end_date        = end.format('YYYY-MM-DD');

        localStorage.setItem("start_date_p_stock",start_date);
        localStorage.setItem("end_date_p_stock",end_date);

        stock_table.ajax.url("{{route('reports.show')}}?report_type=product-stock-report&product_id="+"{{$product->id}}&start_date="+start_date+"&end_date="+end_date).load(function(result) {});
    }

    $(document).on("click","#report-st",function() {

        var type        = this.value;
        var product_id  = "{{$product->id}}";
        
        var start_date  = localStorage.getItem("start_date_p_stock");
        var end_date    = localStorage.getItem("end_date_p_stock");

        window.open("{{url('')}}/reports/exportReport?type="+type+"&report_type=product-stock-report&start_date="+start_date+"&end_date="+end_date+"&product_id="+product_id,'_blank');  
    });

</script>
@endpush
