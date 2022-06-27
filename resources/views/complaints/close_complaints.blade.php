@extends('layouts.app')
@push('css_lib')
<!-- iCheck -->
<link rel="stylesheet" href="{{asset('plugins/iCheck/flat/blue.css')}}">
<!-- select2 -->
<link rel="stylesheet" href="{{asset('plugins/select2/select2.min.css')}}">
<!-- bootstrap wysihtml5 - text editor -->
<link rel="stylesheet" href="{{asset('plugins/summernote/summernote-bs4.css')}}">
{{--dropzone--}}
<link rel="stylesheet" href="{{asset('plugins/dropzone/bootstrap.min.css')}}">
@endpush
@section('content')
<!-- Content Header (Page header) -->
<div class="content-header">
  <div class="container-fluid">
    <div class="row mb-2">
      <div class="col-sm-6">
        <h1 class="m-0 text-dark">{{trans('lang.complaint_plural')}}<small class="ml-3 mr-3">|</small><small>{{trans('lang.complaint_desc')}}</small></h1>
      </div><!-- /.col -->
      <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
          <li class="breadcrumb-item"><a href="{{url('/dashboard')}}"><i class="fa fa-dashboard"></i> {{trans('lang.dashboard')}}</a></li>
          <li class="breadcrumb-item"><a href="{!! route('complaints.index') !!}">{{trans('lang.complaint_plural')}}</a>
          </li>
          <li class="breadcrumb-item active">{{trans('lang.complaint_comments')}}</li>
        </ol>
      </div><!-- /.col -->
    </div><!-- /.row -->
  </div><!-- /.container-fluid -->
</div>
<!-- /.content-header -->
<div class="content">
  <div class="clearfix"></div>
  @include('flash::message')
  @include('adminlte-templates::common.errors')
  <div class="clearfix"></div>
  <div class="card">
    <div class="card-header">
      <ul class="nav nav-tabs align-items-end card-header-tabs w-100">
        @can('complaints.index')
        <li class="nav-item">
          <a class="nav-link" href="{!! route('complaints.index') !!}"><i class="fa fa-list mr-2"></i>{{trans('lang.complaint_table')}}</a>
        </li>
        @endcan
        <li class="nav-item">
          <a class="nav-link active" href="{!! url()->current() !!}"><i class="fa fa-pencil mr-2"></i>{{trans('lang.complaint_close_complaints')}}</a>
        </li>
      </ul>
    </div>
    <div class="card-body">
      {!! Form::model($complaints, ['route' => ['complaints.saveCloseComplaints', $complaints->id], 'method' => 'post']) !!}
      <div class="row">
                @if($customFields)
                <h5 class="col-12 pb-4">{!! trans('lang.main_fields') !!}</h5>
                @endif
                <div class="col-md-12 column custom-from-css">

                    <div class="row">
                            <div class="col-md-6">
                                
                            <!--selected staff Field -->
                            <div class="form-group row ">
                                <div class="col-md-12">
                                {!! Form::label('staff_members', trans("lang.complaint_members"), ['class' => 'col-3 control-label text-left required']) !!}
                                 {!! Form::select('staff_members',$staff_members, $complaints->deduction_staff_id, ['class' => 'select2 form-control']) !!}
                                </div>
                            </div>
                            
                            <!--option type Field -->
                            <div class="form-group row ">
                                <div class="col-md-12">
                                {!! Form::label('option_type', trans("lang.complaint_option_type"), ['class' => 'col-3 control-label text-left required']) !!}
                                 {!! Form::select('option_type',['0'=>"Please Select",'1'=>"Deduction",'2'=>"Free products to customers"], null, ['class' => 'select2 form-control','id'=>'complaint_option_type']) !!}
                                </div>
                            </div>
                            
                             <!-- Deduction amount Field -->
                             @php if($complaints->option_type==1){ $show_deduction = ""; }else{ $show_deduction = 'style="display:none;"'; } @endphp
                             <div class="form-group row deduction_type" <?= $show_deduction ?>>
                                 <div class="col-md-12">
                                      {!! Form::label('deduction_amount', trans("lang.complaint_deduction_amount"), ['class' => ' control-label text-left']) !!}
                                       {!! Form::number('deduction_amount', $complaints->deduction_amount,  ['class' => 'form-control','step'=>"any",'placeholder'=>  trans("lang.complaint_deduction_amount_placeholder")]) !!}
                         
                               </div>
                            </div>

                           </div>
                           
                           <!-- Free products Field -->
                           @php if($complaints->option_type==2){ $show_free_products = ""; }else{ $show_free_products = 'style="display:none;"'; } @endphp
                            <div class="col-md-12 free_products_type" <?= $show_free_products ?>>
                                  <table id="salesItems" class="table table-bordered tbl-product-details">
                                    <thead>
                                      <tr>
                                        <th>NO</th>
                                        <th>ITEMS</th>
                                        <th>HSN</th>
                                        <th>MRP</th>
                                        <th>QTY</th>
                                        <!--<th>TOTAL</th>-->
                                       
                                      </tr>
                                    </thead>
                                    <tfoot class="text-center">
                                     @if($complaints->status!='1')
                                      <tr>
                                        <th colspan="7">
                                          <button onclick="addItem();" data-toggle="tooltip" title="{{ trans('lang.shift_m') }}" type="button" data-toggle="tooltip" class="btn btn-primary text-center add-item-btn-custom">
                                            <i class="fa fa-plus"></i> 
                                            &nbsp; Add Item
                                          </button>
                                        </th>
                                      </tr>
                                      @endif
                                      @php $i=1; 
                                       foreach($productOrder as $val){
                                       @endphp
                                       <tr>
                                           <td>{{$i}}</td>
                                           <td style="padding: 10px 15px !important;">{{$val->product->name}}</td>
                                           <td style="padding: 10px 15px !important;">{{$val->product->hsn_code}}</td>
                                           <td style="padding: 10px 15px !important;">{{number_format($val->price,2)}}</td>
                                           <td style="padding: 10px 15px !important;">{{$val->quantity}} {{$val->product->unit}}</td>
                                       </tr>
                                       @php $i++; } @endphp
                                    </tfoot>
                                  </table>
                                 </div>

                           
                      </div>  
                
                  </div>
                
                @if($customFields)
                <div class="clearfix"></div>
                <div class="col-12 custom-field-container">
                  <h5 class="col-12 pb-4">{!! trans('lang.custom_field_plural') !!}</h5>
                  {!! $customFields !!}
                </div>
                @endif
                <!-- Submit Field -->
                <div class="form-group col-12 text-right">
                  @if($complaints->status=='1')
                  <button type="submit" class="btn btn-{{setting('theme_color')}}" disabled><i class="fa fa-close"></i> {{trans('lang.complaint_close_complaints')}}</button>
                  @else
                  <button type="submit" class="btn btn-{{setting('theme_color')}}" ><i class="fa fa-close"></i> {{trans('lang.complaint_close_complaints')}}</button>
                  @endif
                  <a href="{!! route('complaints.index') !!}" class="btn btn-default"><i class="fa fa-undo"></i> {{trans('lang.cancel')}}</a>
                </div>
            </div>
      {!! Form::close() !!}
      <div class="clearfix"></div>
    </div>
  </div>
</div>

<!-- Modal -->
<div id="allproductModal" class="modal fade" tabindex="-1" role="dialog">
  <div class="modal-dialog modal-lg">
    {!! Form::open([ 'id' => 'productsForm', 'class' => 'po-modal-form']) !!}  
    <!-- Modal content-->
    <div class="modal-content all_products-modal">

    </div>
    {!! Form::close() !!}
  </div>
</div>
@include('layouts.media_modal')
@endsection
@push('scripts_lib')

<!-- iCheck -->
<script src="{{asset('plugins/iCheck/icheck.min.js')}}"></script>
<!-- select2 -->
<script src="{{asset('plugins/select2/select2.min.js')}}"></script>
<!-- AdminLTE dashboard demo (This is only for demo purposes) -->
<script src="{{asset('plugins/summernote/summernote-bs4.min.js')}}"></script>
{{--dropzone--}}
<script src="{{asset('plugins/dropzone/dropzone.js')}}"></script>
<script type="text/javascript">
    Dropzone.autoDiscover = false;
    var dropzoneFields = [];
    
     function addItem() {

      $('#allproductModal').modal('show');
      $.ajax({
          url: "{{ url('/complaints/loadComplaintsProducts') }}",
          type: 'post',
          data: {
            //'party'  : party,
            '_token' : "{{ csrf_token() }}"
          }
      })
      .done(function(response) {
          $('.all_products-modal').html(response);
      })
      .fail(function(response) {
          console.log(response);
      });
    
  }
  
    $("document").ready(function(){
      $("#productsForm").submit(function(e){
          $('.add-items').attr("disabled", true);
          $('.add-items').html('Adding...');
          e.preventDefault();
          $.ajax({
              url: "{{ url('/complaints/loadComplaintsItems') }}",
              type: 'post',
              dataType: 'JSON',
              data: $('#productsForm').serialize()
          })
          .done(function(results) {

              $.each(results, function (key,val) { 

                  var total_amount = parseFloat(val.quantity) * parseFloat(val.purchase_price);
                   $('#salesItems tr:last').after('<tr> <input type="hidden" name="product_id[]" value="'+val.id+'"><input type="hidden" name="product_unit[]" value="'+val.unit+'"><td>'+val.s_no+'</td><td><input type="text" name="product_name[]" class="form-control" value="'+val.name+'"></td><td><input type="text" name="product_hsn[]" class="form-control" value="'+val.hsn_code+'"></td><td><input type="text" name="product_mrp[]" class="form-control" value="'+(val.purchase_price).toFixed(2)+'"></td><td class="inline-flex"><input type="text" name="product_quantity[]" class="form-control product_quantity" value="'+val.quantity+'"> <span class="item-unit">'+val.unit+'</span></td><td><a onclick="removesaleseRow(this);" title="Remove"><i class="fa fa-trash"></i></a></td></tr>');
                   //$('#salesItems tr:last').after('<tr> <input type="hidden" name="product_id[]" value="'+val.id+'"><input type="hidden" name="product_unit[]" value="'+val.unit+'"><td>'+val.s_no+'</td><td><input type="text" name="product_name[]" class="form-control" value="'+val.name+'"></td><td><input type="text" name="product_hsn[]" class="form-control" value="'+val.hsn_code+'"></td><td><input type="text" name="product_mrp[]" class="form-control" value="'+(val.purchase_price).toFixed(2)+'"></td><td class="inline-flex"><input type="text" name="product_quantity[]" class="form-control product_quantity" value="'+val.quantity+'"> <span class="item-unit">'+val.unit+'</span></td><!--<td><input type="text" name="amount[]" class="form-control product_subtotal" value="'+total_amount+'"></td>--><td><a onclick="removesaleseRow(this);" title="Remove"><i class="fa fa-trash"></i></a></td></tr>');
                  $('.product_tax').trigger('change');
              });
              $('#allproductModal').modal('hide');
              $('.all_products-modal').html('');
              totalProducts();

          })
          .fail(function(results) {
              console.log(results);
          });
      });
  }); 
  
  
</script>
@endpush