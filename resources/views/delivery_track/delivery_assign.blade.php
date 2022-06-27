<div class="modal-header">
  <h5 class="modal-title" id="myModalLabel">Assign Delivery Person</h5>
  <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
</div>

{!! Form::open(['route' => 'deliveryTracker.store','class' => 'delivery-tracker-form']) !!}

  <div class="modal-body">

    <div class="row">
      <div class="col-md-12">  
        
        <div class="form-group">
            <table class="table table-bordered bg-white">
                <thead>
                    <th width="25%">Order No</th>
                    <th width="25%">Order Date</th>
                    <th width="25%">Collectable Amount</th>
                    <th width="25%">Status</th>
                </thead>
                <tbody>
                    <tr>
                        <td>{{$order->order_code}}</td>
                        <td>{{$order->created_at->format('M d, Y')}}</td>
                        <td>
                            @if(isset($order->payment) && $order->payment->status == 'paid')
                                <b>{{setting('default_currency').'0.00'}}</b>
                            @else
                                <b>{{setting('default_currency').number_format($order->order_amount,2,'.','')}}</b>
                            @endif
                        </td>
                        <td>
                            {{str_replace('_',' ',strtoupper($order->status))}}
                        </td>
                    <tr>
                </tbody>
            </table>
        </div>        

        {!! Form::hidden('order_id', $order->id) !!}
        {!! Form::hidden('type', $type) !!}
        {!! Form::hidden('category', $category) !!}

        <div class="form-group">
            {!! Form::label('user_id', 'Driver', ['class' => ' control-label text-left']) !!}
            {!! Form::select('user_id', $users, null, ['class' => 'select2 form-control user_id']) !!}
        </div>

        <div class="form-group">
            {!! Form::label('notes', 'Notes', ['class' => ' control-label text-left']) !!}
            {!! Form::textarea('notes', null, ['class' => 'form-control', 'rows' => '4' ]) !!}
        </div>

      </div>  
      <div class="clearfix"></div>
    </div>
  </div>

  <div class="modal-footer">
    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
    <button type="submit" class="btn btn-primary delivery-tracker-form-submit">Save changes</button>
  </div>

{!! Form::close() !!}

<script>

  $('.delivery-tracker-form').validate({ // initialize the plugin
      rules: {
          user_id: {
              required: true
          }
      },
      highlight: function (element, errorClass, validClass) {   
          var elem = $(element);
          if(elem.hasClass('select2')) {
              elem.siblings('.select2-container').addClass(errorClass);
          } else {
              elem.addClass(errorClass);
          }
      },
      unhighlight: function (element, errorClass, validClass) {
          var elem = $(element); 
          if(elem.hasClass('select2')) {
              elem.siblings('.select2-container').removeClass(errorClass);
          } else {
              elem.removeClass(errorClass);
          }
      },
      errorElement: "div",
      errorPlacement: function(error, element) { },
      success: function (error) {
          error.remove();
      },
      submitHandler: function(form) {
          
          $('.delivery-tracker-form-submit').html('<i class="fa fa-spinner fa-spin"></i>');
          $('.delivery-tracker-form-submit').attr("disabled", true);
          
          $.ajax({
              url: form.action,
              type: form.method,
              data: $(form).serialize()         
          }).done(function(results) {            
              
              $('.delivery-tracker-form-submit').html('<i class="fa fa-save"></i> Save Changes');
              $('.delivery-tracker-form-submit').attr("disabled", false);
              $('#deliveryModal').modal('hide');

              iziToast.success({
                  backgroundColor: 'linear-gradient(to right, #44a08d, #093637)',
                  messageColor: '#fff',
                  timeout: 3000, 
                  icon: 'fa fa-check', 
                  position: "bottomRight", 
                  iconColor:'#fff',
                  message: 'Delivery Person Assigned Sucessfully'
              });
              setTimeout(function () { location.reload(); }, 1000);

          })
          .fail(function(results) {            
              
              $('.delivery-tracker-form-submit').html('<i class="fa fa-save"></i> Save Changes');
              $('.delivery-tracker-form-submit').attr("disabled", false);

              iziToast.success({
                  backgroundColor: 'linear-gradient(to right, #ff4b1f, #ff9068)', 
                  messageColor: '#fff', 
                  timeout: 3000, 
                  icon: 'fa fa-remove', 
                  position: "bottomRight", 
                  iconColor:'#fff', 
                  message: results.statusText
              });

          });
      }
  });


</script>