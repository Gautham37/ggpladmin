@push('css_lib')
@include('layouts.datatables_css')
@endpush
    <p>
		<span>
			<b>Total Number of Transaction : </b><span class="total-l-transaction"></span>
		</span>
		<span>
			<b>Account Balance : </b><span class="total-l-balance">@if(getBalanceStatus($market, 'balance')!='') {!! getBalanceStatus($market,'balance')!!} @else {!! '0 | Up to date' !!} @endif</span>
		</span>
	</p>
    
	<table class="table table-bordered dataTable no-footer dtr-inline ledger-table" width="100%">
		<thead>
			<tr>
				<th>DATE</th>
				<th>TRANSACTION TYPE</th>
				<th>TRANSACTION NUMBER</th>
				<th>DISCOUNT</th>
                <th>CREDIT</th>
                <th>DEBIT</th>
                <th>STATUS</th>
				<th>BALANCE</th>
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
    
    var ledger_table = $('.ledger-table').DataTable({
       processing: true,
       serverSide: true,
       pageLength:20,
       ajax: "{{ route('reports.show') }}?report_type=party-ledger-report&market_id="+"{{$market->id}}&start_date=&end_date=",
       columns: [
           {data: 'date', name: 'date', orderable: false, searchable: true, class: "text-left"},
           {data: 'category', name: 'category', orderable: false, searchable: true, class: "text-center"},
           {data: 'transaction_no', name: 'transaction_no', orderable: false, searchable: true, class: "text-center"},
           {data: 'discount', name: 'discount', orderable: false, searchable: true, class: "text-right"},
           {data: 'credit', name: 'credit', orderable: false, searchable: true, class: "text-right"},
           {data: 'debit', name: 'debit', orderable: false, searchable: true, class: "text-right"},
           {data: 'status', name: 'status', orderable: false, searchable: true, class: "text-center"},
           {data: 'closing_balance', name: 'balance', orderable: false, searchable: true, class: "text-right"},
       ]
    });

    function cb2(start, end) {
        $('#reportrange2 span').html(start.format('MMMM D, YYYY') + ' - ' + end.format('MMMM D, YYYY'));
        var start_date      = start.format('YYYY-MM-DD');
        var end_date        = end.format('YYYY-MM-DD');

        localStorage.setItem("start_date_l",start_date);
        localStorage.setItem("end_date_l",end_date);

        ledger_table.ajax.url("{{ route('reports.show') }}?report_type=party-ledger-report&market_id="+"{{$market->id}}&start_date="+start_date+"&end_date="+end_date).load(function(result) {
            var total = 0;
            $.each(result.data, function (key,value) {
                total += parseFloat(value.total);
            });

            $('.total-l-transaction').html(result.data.length);
            //$('.total-amount').html("{{setting('default_currency')}}"+(total).toFixed(2));
        });
    }

    $(document).on("click","#report-l",function() {

        var type        = this.value;
        var start_date  = localStorage.getItem("start_date_l");
        var end_date    = localStorage.getItem("end_date_l");

        window.open("{{ route('reports.show') }}?type="+type+"&report_type=party-ledger-report&market_id="+"{{$market->id}}&start_date="+start_date+"&end_date="+end_date,'_blank');  

    });

</script>
@endpush
