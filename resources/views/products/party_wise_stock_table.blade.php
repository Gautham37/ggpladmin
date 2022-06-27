@push('css_lib')
@include('layouts.datatables_css')
@endpush

	<table class="table table-bordered dataTable no-footer dtr-inline party-stock-table" width="100%">
		<thead>
			<tr>
				<th>PARTY NAME</th>
				<th>SALES QUANTITY</th>
				<th>SALES AMOUNT</th>
				<th>PURCHASE QUANTITY</th>
				<th>PURCHASE AMOUNT</th>
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
    var party_stock_table = $('.party-stock-table').DataTable({
       processing: true,
       serverSide: true,
       pageLength:20,
       ajax: "{{ route('reports.show') }}?report_type=product-party-stock-report&product_id="+"{{$product->id}}&start_date=&end_date=",
       columns: [
           {data: 'name', name: 'name', orderable: false, searchable: true, class: "text-left"},
           {data: 'sales_quantity', name: 'sales_quantity', orderable: false, searchable: true, class: "text-center"},
           {data: 'sales_amount', name: 'sales_amount', orderable: false, searchable: true, class: "text-right"},
           {data: 'purchase_quantity', name: 'purchase_quantity', orderable: false, searchable: true, class: "text-center"},
           {data: 'purchase_amount', name: 'purchase_amount', orderable: false, searchable: true, class: "text-right"},
       ]
    });

    function cb1(start, end) {
        $('#reportrange1 span').html(start.format('MMMM D, YYYY') + ' - ' + end.format('MMMM D, YYYY'));
        var start_date      = start.format('YYYY-MM-DD');
        var end_date        = end.format('YYYY-MM-DD');

        localStorage.setItem("start_date_party_stock",start_date);
        localStorage.setItem("end_date_party_stock",end_date);

        party_stock_table.ajax.url("{{route('reports.show')}}?report_type=product-party-stock-report&product_id="+"{{$product->id}}&start_date="+start_date+"&end_date="+end_date).load(function(result) {});
    }

    $(document).on("click","#report-pr",function() {

        var type        = this.value;
        var product_id  = "{{$product->id}}";
        
        var start_date  = localStorage.getItem("start_date_party_stock");
        var end_date    = localStorage.getItem("end_date_party_stock");

        window.open("{{url('')}}/reports/exportReport?type="+type+"&report_type=product-party-stock-report&start_date="+start_date+"&end_date="+end_date+"&product_id="+product_id,'_blank');  
    });

</script>
@endpush

