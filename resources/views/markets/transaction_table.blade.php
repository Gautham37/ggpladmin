@push('css_lib')
@include('layouts.datatables_css')
@endpush
    <p>
		<span>
			<b>Total Number of Transaction : </b> <span class="total-transaction"></span>
		</span>
		<span>
			<b>Total Amount : </b> <span class="total-amount"></span>
		</span>
	</p>
    
	<table class="table table-bordered dataTable no-footer dtr-inline transaction-table" width="100%">
		<thead>
			<tr>
				<th>DATE</th>
				<th>TRANSACTION TYPE</th>
                <th>TRANSACTION NUMBER</th>
                <th>TYPE</th>
				<th>AMOUNT</th>
				<th>&nbsp;</th>
			</tr>
		</thead>
		<tbody>
		</tbody>
	</table>

@push('scripts_lib')
@include('layouts.datatables_js')
@endpush

@push('scripts')
<script type="text/javascript">
    
    var table = $('.transaction-table').DataTable({
       processing: true,
       serverSide: true,
       pageLength:20,
       ajax: "{{ route('reports.show') }}?report_type=party-transaction-report&market_id="+"{{$market->id}}&transaction="+$('#transaction').val()+"&start_date=&end_date=",
       columns: [
           {data: 'date', name: 'date', orderable: false, searchable: true, class: "text-left"},
           {data: 'category', name: 'category', orderable: false, searchable: true, class: "text-center"},
           {data: 'transaction_no', name: 'transaction_no', orderable: false, searchable: true, class: "text-center"},
           {data: 'type', name: 'type', orderable: false, searchable: true, class: "text-center"},
           {data: 'amount', name: 'amount', orderable: false, searchable: true, class: "text-center"},
           {data: 'status', name: 'status', orderable: false, searchable: true, class: "text-center"},
       ]
    });

    function cb(start, end) {
        $('#reportrange span').html(start.format('MMMM D, YYYY') + ' - ' + end.format('MMMM D, YYYY'));
        var start_date      = start.format('YYYY-MM-DD');
        var end_date        = end.format('YYYY-MM-DD');

        localStorage.setItem("start_date_t",start_date);
        localStorage.setItem("end_date_t",end_date);

        table.ajax.url("{{ route('reports.show') }}?report_type=party-transaction-report&market_id="+"{{$market->id}}&transaction="+$('#transaction').val()+"&start_date="+start_date+"&end_date="+end_date).load(function(result) {

            var total = 0;
            $.each(result.data, function (key,value) {
                total += parseFloat(value.total);
            });

            $('.total-transaction').html(result.data.length);
            $('.total-amount').html("{{setting('default_currency')}}"+(total).toFixed(2));
        });
    }

    $(document).on("click","#report-t",function() {

        var type        = this.value;
        var transaction = $('#transaction').val();
        var market_id   = "{{$market->id}}";
        var start_date  = localStorage.getItem("start_date_t");
        var end_date    = localStorage.getItem("end_date_t");
        window.open("{{ route('reports.show') }}?type="+type+"&report_type=party-transaction-report&market_id="+"{{$market->id}}&transaction="+$('#transaction').val()+"&start_date="+start_date+"&end_date="+end_date,'_blank');  

    });

</script>
@endpush