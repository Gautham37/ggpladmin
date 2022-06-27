@extends('layouts.app')

@section('content')
<!-- Content Header (Page header) -->
<div class="content-header">
  <div class="container-fluid">
    <div class="row mb-2">
      <div class="col-sm-6">
        <h1 class="m-0 text-dark">{{trans('lang.product_plural')}}<small class="ml-3 mr-3">|</small><small>{{trans('lang.product_desc')}}</small></h1>
      </div><!-- /.col -->
      <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
          <li class="breadcrumb-item"><a href="{{url('/dashboard')}}"><i class="fa fa-dashboard"></i> {{trans('lang.dashboard')}}</a></li>
          <li class="breadcrumb-item"><a href="{!! route('products.index') !!}">{{trans('lang.product_plural')}}</a>
          </li>
          <li class="breadcrumb-item active">{{trans('lang.product_table')}}</li>
        </ol>
      </div><!-- /.col -->
    </div><!-- /.row -->
  </div><!-- /.container-fluid -->
</div>
<!-- /.content-header -->

<div class="content">
  <div class="clearfix"></div>
  @include('flash::message')
  <div class="card">
    <div class="card-header">
      <ul class="nav nav-tabs align-items-end card-header-tabs w-100">
        <li class="nav-item">
          <a class="nav-link active" href="{!! url()->current() !!}"><i class="fa fa-list mr-2"></i>{{trans('lang.product_table')}}</a>
        </li>
        @can('products.create')
        <li class="nav-item">
          <a class="nav-link" href="{!! route('products.create') !!}"><i class="fa fa-plus mr-2"></i>{{trans('lang.product_create')}}</a>
        </li>
        @endcan
        <!-- @can('products.importPriceVariations')
          <li class="nav-item">
            <a class="btn btn-primary" id="import_price_variations" href="{{ route('products.importPriceVariations') }}">{{trans('lang.product_import_price_variations')}} </a>
          </li>
        @endcan -->
        @can('products.import')
          <li class="nav-item">
            <a class="btn btn-primary" id="import_products" href="{{ route('products.import') }}"> Import Products </a>
          </li>
        @endcan
        @include('layouts.right_toolbar', compact('dataTable'))
      </ul>
    </div>
    <div class="card-body">
      @include('products.table')
      <div class="clearfix"></div>
    </div>
  </div>
</div>

<!-- Modal -->
<div id="productStockModal" class="modal fade" role="dialog">
  <div class="modal-dialog modal-md">
    {!! Form::open([ 'url' => 'products/updateInventory', 'id' => 'productStockForm']) !!}  
    <!-- Modal content-->
    <div class="modal-content product-stock-modal">

      <div class="modal-header align-items-stretch">
          <h5 class="modal-title flex-grow-1">{!! trans('lang.product_adjust_stock_quantity') !!}</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
          </button>
      </div>
      <div class="modal-body">
        <input type="hidden" name="product_id" id="product_id" >
        <input type="hidden" name="uom" id="uom" >
        <div class="form-group">
            {!! Form::label('product_name', trans("lang.stock_product_name"), ['class' => 'control-label text-right']) !!}
            {!! Form::text('product_name', null,  ['class' => 'form-control', 'readonly' => 'readonly', 'id' => 'product_name', 'placeholder'=>  trans("lang.stock_product_name_placeholder")]) !!}
        </div>

        <div class="form-group">
            {!! Form::label('stock_level', trans("lang.stock_level"), ['class' => 'control-label text-right']) !!}
            {!! Form::text('stock_level', null,  ['class' => 'form-control', 'readonly' => 'readonly', 'id' => 'stock_level', 'placeholder'=>  trans("lang.stock_level")]) !!}
        </div>

        <div class="form-group">
            {!! Form::label('stock_type', trans("lang.add_or_adjust_stock"), ['class' => 'control-label text-right']) !!}
            {!! Form::select('stock_type', [ 'add' => 'Add (+)','reduce' => 'Reduce (-)'], null, ['class' => 'form-control','id'=>'stock_type','onchange' => 'stockUsage(this);']) !!}
        </div>
        
        <div class="form-group" id="usage-type" style="display:none;">
            {!! Form::label('inventory_track_usage', 'Stock Type', ['class' => 'control-label text-right']) !!}
            {!! Form::select('inventory_track_usage', [ '1' => 'Normal','2' => 'Wastage'], null, ['class' => 'form-control','id'=>'stock_type']) !!}
        </div>    

        <div class="form-group">
            {!! Form::label('quantity', trans("lang.adjust_quantity"), ['class' => 'control-label text-right']) !!}
            {!! Form::number('quantity', null,  ['class' => 'form-control', 'id' => 'quantity', 'placeholder'=>  trans("lang.adjust_quantity_placeholder")]) !!}
        </div>        

        <!-- Description Field -->
        <div class="form-group">
          {!! Form::label('remarks', trans("lang.stock_remarks"), ['class' => 'control-label text-right']) !!}
            {!! Form::textarea('remarks', null, ['class' => 'form-control', 'rows' => '3' ,'placeholder'=>
             trans("lang.stock_remarks_placeholder")  ]) !!}
            <div class="form-text text-muted">{{ trans("lang.category_description_help") }}</div>
        </div>
        
          
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default btn-sm" data-dismiss="modal">Cancel</button>
        <button type="submit" class="btn btn-primary btn-sm">Save</button>
      </div>
    </div>
    {!! Form::close() !!}
  </div>
</div>

@endsection

