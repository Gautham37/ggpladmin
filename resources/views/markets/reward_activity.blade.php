@push('css_lib')
@include('layouts.datatables_css')
@endpush

    <p>
		<span>
			<b>Party Reward Level : </b>
			@if($market->customer_level) 
				<button class="btn btn-sm ">{!! $market->customer_level->name !!} </button>
			@endif
		</span>
		<span>
			<b>Balance Points Available : </b>
			<button class="btn btn-sm ">{!! $market->user->points !!}</button>
		</span>
	</p>
    
	<table class="table table-bordered dataTable no-footer dtr-inline reward-table" width="100%">
		<thead>
			<tr>
				<th>DATE</th>
				<th>REWARD TYPE</th>
				<th>TRANSACTION NO</th>
				<th>POINT WORTH</th>
				<th>EARN / REDEEM</th>
			</tr>
		</thead>
		<tbody>
		</tbody>
	</table>

@push('scripts_lib')
@include('layouts.datatables_js')
@endpush

<script src="{{asset('plugins/jquery/jquery.min.js')}}"></script>

@push('scripts')

<script>
	
	var reward_table = $('.reward-table').DataTable({
       processing: true,
       serverSide: true,
       pageLength:20,
       ajax: "{{ route('reports.show') }}?report_type=party-reward-report&market_id="+"{{$market->id}}&start_date=&end_date=",
       columns: [
           {data: 'date', name: 'date', orderable: false, searchable: true, class: "text-left"},
           {data: 'category', name: 'category', orderable: false, searchable: true, class: "text-center"},
           {data: 'transaction_no', name: 'transaction_no', orderable: false, searchable: true, class: "text-center"},
           {data: 'amount', name: 'amount', orderable: false, searchable: true, class: "text-center"},
           {data: 'status', name: 'status', orderable: false, searchable: true, class: "text-center"},
       ]
    });

    function cb1(start, end) {
        $('#reportrange1 span').html(start.format('MMMM D, YYYY') + ' - ' + end.format('MMMM D, YYYY'));
        var start_date      = start.format('YYYY-MM-DD');
        var end_date        = end.format('YYYY-MM-DD');

        localStorage.setItem("start_date_r", start_date);
        localStorage.setItem("end_date_r", end_date);

        reward_table.ajax.url("{{ route('reports.show') }}?report_type=party-reward-report&market_id="+"{{$market->id}}&start_date="+start_date+"&end_date="+end_date).load(function(result) {

        });
    }
    
    $(document).on("click","#report-r",function() {

        var type        = this.value;
        var start_date  = localStorage.getItem("start_date_r");
        var end_date    = localStorage.getItem("end_date_r");

        window.open("{{ route('reports.show') }}?type="+type+"&report_type=party-reward-report&market_id="+"{{$market->id}}&start_date="+start_date+"&end_date="+end_date,'_blank');  

    });

</script>

@endpush
