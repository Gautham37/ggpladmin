@push('css_lib')
@include('layouts.datatables_css')
@endpush

<table class="table table-bordered dataTable no-footer dtr-inline purchase-history-table" width="100%">
  <thead>
    <tr>
      <th>DATE</th>
      <th>PURCHASE NO</th>
      <th>PURCHASE AMOUNT</th>
      <th>PURCHASE QUANTITY</th>
    </tr>
  </thead>
  <tbody>
  </tbody>
</table>

@push('scripts_lib')
@include('layouts.datatables_js')
@endpush

<!-- jQuery -->
<script src="{{asset('plugins/jquery/jquery.min.js')}}"></script>

@push('scripts')
<script type="text/javascript">
    
    var purchase_history_table = $('.purchase-history-table').DataTable({
       processing: true,
       serverSide: true,
       pageLength:20,
       ajax: "{{ route('reports.show') }}?report_type=product-purchase-history-report&product_id="+"{{$product->id}}&start_date=&end_date=",
       columns: [
           {data: 'date', name: 'date', orderable: false, searchable: true, class: "text-left"},
           {data: 'transaction_no', name: 'transaction_no', orderable: false, searchable: true, class: "text-center"},
           {data: 'amount', name: 'purchase_amount', orderable: false, searchable: true, class: "text-right"},
           {data: 'quantity', name: 'purchase_quantity', orderable: false, searchable: true, class: "text-right"},
       ]
    });

    function cb2(start, end) {
        $('#reportrange2 span').html(start.format('MMMM D, YYYY') + ' - ' + end.format('MMMM D, YYYY'));
        var start_date      = start.format('YYYY-MM-DD');
        var end_date        = end.format('YYYY-MM-DD');

        localStorage.setItem("start_date_party_purchase",start_date);
        localStorage.setItem("end_date_party_purchase",end_date);

        purchase_history_table.ajax.url("{{route('reports.show')}}?report_type=product-purchase-history-report&product_id="+"{{$product->id}}&start_date="+start_date+"&end_date="+end_date).load(function(result) {});
    }

    $(document).on("click","#report-ph",function() {

        var type        = this.value;
        var product_id  = "{{$product->id}}";
        
        var start_date  = localStorage.getItem("start_date_party_purchase");
        var end_date    = localStorage.getItem("end_date_party_purchase");

        window.open("{{url('')}}/reports/exportReport?type="+type+"&report_type=product-purchase-history-report&start_date="+start_date+"&end_date="+end_date+"&product_id="+product_id,'_blank');  
    }); 

</script>
@endpush



