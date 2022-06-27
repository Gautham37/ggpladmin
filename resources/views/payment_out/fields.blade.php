<div class="col-md-12 column custom-from-css">
  <div class="row">

 
    <div class="col-md-3">
      <div class="form-group">
        {!! Form::label('market_id', 'Party',['class' => 'control-label text-left']) !!}
        {!! Form::select('market_id', $users, null, ['class' => 'select2 form-control market_id']) !!}
      </div>
    </div>

    <div class="col-md-3">
      <div class="form-group">
        {!! Form::label('date', 'Date',['class' => ' control-label text-left']) !!}
        {!! Form::text('date', date('d-m-Y'),  ['class' => 'datepicker form-control', 'placeholder'=> "Please select the date"]) !!}
      </div>
    </div>

    <div class="col-md-3">
      <div class="form-group">
        {!! Form::label('payment_method', 'Payment Method',['class' => ' control-label text-left']) !!}
        {!! Form::select('payment_method', $payment_methods, null, ['class' => 'select2 form-control']) !!}
      </div>
    </div>

    <div class="col-md-3">
      <div class="form-group">
        {!! Form::label('code', 'Payment Out Code',['class' => ' control-label text-left']) !!}
        {!! Form::text('code', isset($payment_out_no) ? $payment_out_no : null ,  ['class' => 'form-control']) !!}
      </div>
    </div>

    <div class="col-md-3">
      <div class="form-group">
        {!! Form::label('total', 'Payable Amount '.setting('default_currency'), ['class' => ' control-label text-left']) !!}
        {!! Form::number('total', null,  ['class' => 'form-control total','min'=>"1",'placeholder'=>""]) !!}
      </div>
    </div>

    <div class="col-md-3" style="vertical-align:middle;">
      <h5 class="text-success text-center mt-4"><b>Balance : <span class="balance_amount">0</span></b></h5> 
      <input type="hidden" name="balance_amount" id="balance_amount" />
    </div>

    <div class="col-md-6">
      <div class="form-group">
        {!! Form::label('notes', 'Notes',['class' => ' control-label text-left']) !!}
        {!! Form::text('notes', null ,  ['class' => 'form-control']) !!}
      </div>
    </div>

    <div class="col-md-12 mt-3">
      <h6><b>Settle invoice with payment</b></h6>
      <table id="payoutInvoices" class="table table-bordered mt-3">
        <thead >
          <tr class="text-center">
            <th>SETTLE INVOICE</th>
            <th>INVOICE NUMBER</th>
            <th>DATE</th>
            <th>AMOUNT DUE</th>
            <th>AMOUNT PAY</th>
          </tr>
        </thead>
        <tbody class="pending-invoices">
          <tr>
            <td class="text-center" colspan="5">No Invoices Found</td>
          </tr>
        </tbody>
     </table>
    </div>

  </div>

  <!-- <div class="row">

    <div class="col-md-4">
      <div class="form-group">
          {!! Form::label('notes', "Notes", ['class' => ' control-label text-left']) !!}
          {!! Form::textarea('notes', null, ['class' => 'form-control']) !!}
      </div>
    </div>

  </div> -->

</div>

<!-- Submit Field -->
<div class="form-group col-12 text-right">
  <button type="submit" class="btn btn-{{setting('theme_color')}} payment-out-form-submit" ><i class="fa fa-save"></i> Save Payment Out</button>
  <a href="{!! route('paymentOut.index') !!}" class="btn btn-default"><i class="fa fa-undo"></i> Cancel</a>
</div>

@push('scripts_lib')
<script>
    var invoices = [];

    function arraytoTable() {
      var html = '';
      var i    = 0;
      $(".pending-invoices").html('');

      var total = $('.total').val();

      $.each(invoices, function(key,value) { 

        
        if(parseFloat(total) >= 0) {

          if(parseFloat(value.amount_due) > 0 && parseFloat(total) <= parseFloat(value.amount_due)) {

            value.settle_amount = parseFloat(total);
            total = parseFloat(total) - parseFloat(value.settle_amount);

          } else if(parseFloat(total) > parseFloat(value.amount_due)) {

            value.settle_amount = parseFloat(value.amount_due);
            total = parseFloat(total) - parseFloat(value.settle_amount);
          }

        }

        (value.settle_amount > 0) ? value.status = 1 : value.status = 0 ;

        //value.settle_amount = 

        html += '<tr class="text-center">';
        html += '<td>';
        
        (value.status==1) ? status = 'checked="checked"'  : status = '' ;
        html += '<input type="checkbox" '+ status +' name="settle[]" class="change_info" id="settle_'+key+'" field="settle" index="'+key+'" value="' + value.id + '">';
        
        if(value.payment_out_settle_id != undefined && value.payment_out_settle_id != ''){
            html += '<input type="hidden" name="payment_out_settle_id[]" value="'+value.payment_out_settle_id+'" />';   
        }

        html += '<input type="hidden" name="column_name[]" value="' + value.column_name + '">';
        html += '<input type="hidden" name="settle_type[]" value="' + value.settle_type + '">';
        html += '<input type="hidden" name="invoice_id[]" value="' + value.id + '">';

        html += '</td>';
        html += '<td> ' + value.code + ' </td>';
        html += '<td> ' + value.date + ' </td>';
        html += '<td> ' + (value.amount_due).toFixed(2) + ' </td>';
        html += '<td>';
        html += '<input type="number" class="form-control change_info" name="amount[]" id="amount_'+key+'" field="amount" index="'+key+'" value="'+value.settle_amount+'" readonly="readonly"/> ';
        html += '</td>';
        html += '</tr>';

      });
      $(".pending-invoices").html(html);

    }

    $('.market_id').change(function() {

      invoices = [];
      $(".party_details").addClass("card-loader");
      var market_id = $(this).val();
      var url       = '{!!  route('markets.view', [':marketID']) !!}';
      url           = url.replace(':marketID', market_id);
      var token     = "{{ csrf_token() }}";

      $.ajax({
          type: 'GET',
          data: {
              '_token': token,
              'market_id': market_id,
              'type': 'load_pending_invoices'
          },
          url: url,
          success: function (response) {
            if(response.data != null) {
              
              $('.balance_amount').html((response.data.balance).toFixed(2));
              $('#balance_amount').val((response.data.balance).toFixed(2));
              
              if(response.data.pendingsalesreturns != null) {
                if(response.data.pendingsalesreturns.length > 0) {
                  $.each(response.data.pendingsalesreturns, function(key,value) {

                    var invoice_obj = {};
                    invoice_obj  = value;
                    invoice_obj.column_name   = 'sales_return_id';
                    invoice_obj.settle_type   = 'sales';
                    invoice_obj.settle_amount = ''; 
                    invoice_obj.status        = 0; 
                    invoices.push(invoice_obj);

                  });
                  arraytoTable();
                }
              }

              if(response.data.pendingpurchaseinvoices != null) {
                if(response.data.pendingpurchaseinvoices.length > 0) {
                  $.each(response.data.pendingpurchaseinvoices, function(key,value) {

                    var invoice_obj = {};
                    invoice_obj  = value;
                    invoice_obj.column_name   = 'purchase_invoice_id';
                    invoice_obj.settle_type   = 'purchase';
                    invoice_obj.settle_amount = ''; 
                    invoice_obj.status        = 0; 
                    invoices.push(invoice_obj);

                  });
                  arraytoTable();
                }
              }

            } 
          }
      });

  });

  $('.total').on('keyup', function() {
    arraytoTable();
  });  

  $(document).on('change','.change_info',function (e) { 
    var field = $(this).attr('field');
    var index = $(this).attr('index');
    var val = $(this).val();

    if(field == 'amount') {
        if(val != undefined && val!=''){
            invoices[index].settle_amount = val;
            $("#amount_" + index).val(val);
        } else {
            invoices[index].settle_amount = '';
            $("#amount_" + index).val('');
        }
    } else if(field == 'settle') { 
        if($(this).is(':checked')==true) {
          
          invoices[index].status = 1;
          invoices[index].settle_amount = invoices[index].amount_due;
          $("#amount_" + index).val(invoices[index].amount_due);

          var total = ($('.total').val() > 0) ? $('.total').val() : 0 ;
          $('.total').val(parseFloat(total) + parseFloat(invoices[index].settle_amount));

        } else {
          
          var total = ($('.total').val() > 0) ? $('.total').val() : 0 ;
          $('.total').val(parseFloat(total) - parseFloat(invoices[index].settle_amount));

          invoices[index].status = 0;
          invoices[index].settle_amount = '';
          $("#amount_" + index).val('');

        }
    }

    setTimeout(function() {
        //arraytoTable();
    },300);
    e.preventDefault();

  });

  @if(isset($payment_out))

    //$('.market_id').attr('disabled',true);

    $(document).ready(function() {

      var url   = '{!!route('paymentOut.show',$payment_out->id)!!}';
      var token = "{{ csrf_token() }}";
      $.ajax({
          type: 'GET',
          data: {
              '_token': token
          },
          url: url,
          success: function (response) {      
               
               if(response.data.paymentoutsettle.length > 0){
                  $.each(response.data.paymentoutsettle, function(key,value){
                     
                    var invoice_obj = {};
                    if(value.settle_type=='sales') {
                    
                    invoice_obj                       = value.salesreturn;
                    invoice_obj.amount_due            = parseFloat(value.salesreturn.total) - (parseFloat(value.salesreturn.total) - parseFloat(value.amount));
                      
                    } else if(value.settle_type=='purchase') {

                    invoice_obj                       = value.purchaseinvoice;
                    invoice_obj.amount_due            = parseFloat(value.purchaseinvoice.total) - (parseFloat(value.purchaseinvoice.total) - parseFloat(value.amount));  

                    }
                    
                    invoice_obj.payment_out_settle_id = value.id;
                    invoice_obj.column_name           = value.settle_type + '_invoice_id';
                    invoice_obj.settle_type           = value.settle_type;
                    invoice_obj.settle_amount         = value.amount; 
                    invoice_obj.status                = 1; 
                    invoices.push(invoice_obj);

                  });
                  arraytoTable();
               }

          }
      });

    });

  @endif

</script>
@endpush