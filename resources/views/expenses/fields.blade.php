
<div class="col-md-12 column custom-from-css">
  <div class="row">

    <div class="col-md-3">
      <!-- Expenses Category Field -->
      <div class="form-group">
        {!! Form::label('expense_category_id', trans("lang.expenses_categories"),['class' => ' control-label text-left']) !!}
        <div class="input-group-append">
          {!! Form::select('expense_category_id', $expenses_category, null, ['class' => 'select2 form-control']) !!}
          @if(!isset($expenses))
            <span class="input-group-text" style="margin-left: -5px;border: none;background: #e3e9ec;border-left: 2px solid #d6d5d5;backface-visibility: visible;z-index: 1;border-radius: 0px 8px 8px 0px;"> 
              <a  href="" data-toggle="modal" data-target="#expenseCategoryModal">
                <i class="fa fa-plus mr-1"></i>
              </a>
            </span>
          @endif
        </div>
      </div>  
    </div>

    <div class="col-md-3">
      <!-- Expenses Category Field -->
      <div class="form-group">
          {!! Form::label('payment_mode', 'Payment Mode',['class' => ' control-label text-left']) !!}
          {!! Form::select('payment_mode', $payment_mode, null, ['class' => 'select2 form-control']) !!}
      </div>
    </div>

    <div class="col-md-3">
      <!-- Expenses Created By Field -->
      <div class="form-group">
          {!! Form::label('created_by', 'Created By',['class' => ' control-label text-left']) !!}
          {!! Form::select('created_by', $users, null, ['class' => 'select2 form-control']) !!}
      </div>
    </div>

    <div class="col-md-3">
      <!-- Date Field -->
      <div class="form-group">
        {!! Form::label('date', 'Date', ['class' => ' control-label text-left']) !!}
        {!! Form::text('date', isset($expense->date) ? $expense->date->format('d-m-Y') : date('d-m-Y') ,  ['class' => 'form-control datepicker','autocomplete'=>'off']) !!}
      </div>
    </div>
  
    

  </div>
</div>

<div class="col-md-12 column custom-from-css">
  <div class="row">
  
  <div class="col-md-12">
      <div style="flex: 50%;max-width: 100%;padding: 0 4px;" class="row">
        <div class="col-md-12 text-center">
          <table id="expenseTable" class="table table-bordred">
            <thead>
              <tr>
                <th width="5%">S.NO</th>
                <th width="30%">ITEM NAME</th>
                <th width="20%">QUANTITY</th>
                <th width="20%">RATE</th>
                <th width="20%">TOTAL AMOUNT</th>
                <th width="5%"></th>
              </tr>
            </thead>
            <tbody class="expense-table-items"></tbody>
            <tbody>
              <tr>
                <td colspan="4" class="text-right"><b>TOTAL</b></td>
                <td>
                  <div class="form-group ">
                    {!! Form::text('total_amount', null, ['class' => 'form-control total']) !!}
                  </div>
                </td>
              </tr>
            </tbody>
            <tfoot>
              <tr>
                <td colspan="6">
                  <a href="#" class="btn btn-primary text-center add-expense-item mt-2"><i class="fa fa-plus"></i> &nbsp; Add Expense Item</a>
                </td>
              </tr>
            </tfoot>
          </table>
        </div>
      </div>
  </div>

  </div>
</div>

<div class="col-md-12">
    <div class="col-md-6">
     <!-- Description Field -->
      <div class="form-group ">
        {!! Form::label('notes', 'Notes', ['class' => ' control-label text-left']) !!}
        {!! Form::textarea('notes', null, ['class' => 'form-control'  ]) !!}
      </div>
    </div>
</div>

<!-- Submit Field -->
<div class="form-group col-12 text-right">
  <button type="submit" class="btn btn-{{setting('theme_color')}} expenses-form-submit" ><i class="fa fa-save"></i> Save Expense</button>
  <a href="{!! route('expenses.index') !!}" class="btn btn-default"><i class="fa fa-undo"></i> Cancel </a>
</div>

@push('scripts_lib')

<script>
  
  var expense_items = [];

  function arraytoTable() {
    var html = '';
    var i    = 0;
    $(".expense-table-items").html('');
    $.each(expense_items, function(key,value) { 

      if(parseFloat(value.quantity) > 0 && parseFloat(value.rate) > 0) {
        value.amount = (parseFloat(value.quantity) * parseFloat(value.rate)).toFixed(2);
      }

      i++;
      html += '<tr>';
      html += '<td>'+i+'</td>';
      html += '<td>';
      if(value.expense_item_id != undefined && value.expense_item_id != ''){
          html += '<input type="hidden" name="expense_item_id[]" value="'+value.expense_item_id+'" />';   
      }
      html += '<input type="text" name="name[]" id="name_' + key + '" index="' + key + '" field="name" class="form-control change_info" value="' + value.name +'" />';
      html += '</td>';
      html += '<td>';
      html += '<input type="number" min="1" step="any" name="quantity[]" id="quantity_' + key + '" index="' + key + '" field="quantity" class="form-control change_info" value="' + value.quantity +'" />';
      html += '</td>';
      html += '<td>';
      html += '<input type="number" min="1" step="any" name="rate[]" id="rate_' + key + '" index="' + key + '" field="rate" class="form-control change_info" value="' + value.rate +'" />';
      html += '</td>';
      html += '<td>';
      html += '<input type="number" min="1" step="any" name="amount[]" id="amount_' + key + '" index="' + key + '" field="amount" class="form-control change_info amount" value="' + value.amount +'" />';
      html += '</td>';
      html += '<td>';
      html += '<a title="Delete" class="delete btn btn-sm btn-danger text-white" index="'+key+'"><i class="fa fa-remove"></i></a>';
      html += '</td>';

    });
    $(".expense-table-items").html(html);
    calculateTotal();
  }

  $('.add-expense-item').click(function() {
    var expense_item_obj = {};
    expense_item_obj.name     = '';
    expense_item_obj.quantity = '';
    expense_item_obj.rate     = '';
    expense_item_obj.amount   = '';
    expense_items.push(expense_item_obj);
    arraytoTable();
  });


  $(document).on('click', '.delete', function(e){
      
      var index = $(this).attr('index');
      if(expense_items[index].expense_item_id == undefined || expense_items[index].expense_item_id == ''){
         
         expense_items.splice(index,1);
         arraytoTable();

      } else {

         iziToast.show({
             theme: 'dark',
             icon: 'fa fa-trash',
             overlay: true,
             title: 'Delete',
             message: 'Are you sure?',
             position: 'center', // bottomRight, bottomLeft, topRight, topLeft, topCenter, bottomCenter
             progressBarColor: 'yellow',
             backgroundColor: 'linear-gradient(to right, #ff4b1f, #ff9068)', 
             messageColor: '#fff', 
             buttons: [
                 ['<button>Yes</button>', function (instance, toast) {
                    
                     $('.delete_items').html('<input type="hidden" name="delete_item_id[]" value="'+expense_items[index].expense_item_id+'" />');
                     instance.hide({transitionOut: 'fadeOutUp'}, toast, 'buttonName');
                     expense_items.splice(index,1);
                     arraytoTable();

                 }, true], // true to focus
                 ['<button>No</button>', function (instance, toast) {
                     instance.hide({
                         transitionOut: 'fadeOutUp',
                         onClosing: function(instance, toast, closedBy){
                             console.info('closedBy: ' + closedBy); // The return will be: 'closedBy: buttonName'
                         }
                     }, toast, 'buttonName');
                 }]
             ]
         });

      }

  });

  $(document).on('keyup','.change_info',function (e) { 
    
    var field = $(this).attr('field');
    var index = $(this).attr('index');
    var val = $(this).val();
    
    if(field == 'name'){
        if(val != undefined && val!=''){
            expense_items[index].name = val;
            $("#name_" + index).val(val);
        } else {
            expense_items[index].name = '';
            $("#name_" + index).val('');
        }
    } else if(field == 'quantity'){
        if(val != undefined && val!=''){
            expense_items[index].quantity = val;
            $("#quantity_" + index).val(val);
        } else {
            expense_items[index].quantity = '';
            $("#quantity_" + index).val('');
        }
    } else if(field == 'rate'){
        if(val != undefined && val!=''){
            expense_items[index].rate = val;
            $("#rate_" + index).val(val);
        } else {
            expense_items[index].rate = '';
            $("#rate_" + index).val('');
        }
    } 

    if(field != 'name'){
      setTimeout(function() { arraytoTable(); }, 300);
    }

    e.preventDefault();
  });

  function calculateTotal() {
    var amount = 0;
    $(".amount").each(function() {
        var val = this.value;
        amount += val == "" || isNaN(val) ? 0 : parseFloat(val);
    });
    $('.total').val((parseFloat(amount)).toFixed(2));
  }

  @if(isset($expense))
  
    $(document).ready(function() {

      var url   = '{!!route('expenses.show',$expense->id)!!}';
      var token = "{{ csrf_token() }}";
      $.ajax({
          type: 'GET',
          data: {
              '_token': token
          },
          url: url,
          success: function (response) {      
               console.log(response);
               if(response.data.items.length > 0){
                  $.each(response.data.items, function(key,value){
                     
                    var expense_item_obj = {};
                    expense_item_obj.expense_item_id  = value.id;
                    expense_item_obj.name             = value.name;
                    expense_item_obj.quantity         = value.quantity;
                    expense_item_obj.rate             = value.rate;
                    expense_item_obj.amount           = value.amount;
                    expense_items.push(expense_item_obj);

                  });
                  arraytoTable();
               }

          }
      });

    });  

  @endif

</script>

@endpush

