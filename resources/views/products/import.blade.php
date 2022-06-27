@extends('layouts.app')
@section('content')
<!-- Content Header (Page header) -->
<div class="content-header">
   <div class="container-fluid">
      <div class="row mb-2">
         <div class="col-sm-6">
            <h1 class="m-0 text-dark">Import Products<small class="ml-3 mr-3">|</small><small>Products Management</small></h1>
         </div>
         <!-- /.col -->
         <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
               <li class="breadcrumb-item"><a href="{{url('/dashboard')}}"><i class="fa fa-dashboard"></i> Dashboard </a></li>
               <li class="breadcrumb-item"><a href="{!! route('products.index') !!}"> Products </a></li>
               <li class="breadcrumb-item active"> Import Products </li>
            </ol>
         </div>
         <!-- /.col -->
      </div>
      <!-- /.row -->
   </div>
   <!-- /.container-fluid -->
</div>
<!-- /.content-header -->
<div class="content">
   <div class="clearfix"></div>
   @include('flash::message')
   <div class="card">
      <div class="card-header">
         <br><br>

         {!! Form::open(['route'=>'products.importproducts', 'id'=>'product-import-form ','enctype'=>'multipart/form-data']) !!}
            
            <div class="row">
               <div style="flex: 50%;max-width: 50%;padding: 0 4px;" class="column">
                  <div class="form-group row">
                     <label for="unit" class="col-3 control-label text-right">Import Data</label>
                     <div class="col-9">
                        <input type="file" name="file" class="form-control">
                     </div>
                  </div>
                  <div class="form-group row">
                     <label for="import_type" class="col-3 control-label text-right">Import type</label>
                     <div class="col-9">
                        {!! Form::select('import_type', ['new' => 'Import New Products','update'=>'Update Existing Products'], null, ['class' => 'select2 form-control product_type','id'=>'product_type']) !!}
                     </div>
                  </div>
               </div>
            </div>
            
            <div class="row">
               <div style="flex: 50%;max-width: 50%;padding: 0 4px;" class="column">
                  <div class="form-group row">
                     <label for="unit" class="col-3 control-label text-right"></label>
                     <div class="col-9">
                        <a href="{{url('storage/product/product_import.xlsx')}}" class="btn btn-md btn-success" > 
                           <i class="fa fa-download" aria-hidden="true" id="demo_file"></i>
                           &nbsp;Download Sample
                        </a>
                        <br>
                        <span style="line-height:40px;">
                           Note: Import file must be like above mentioned Demo File.
                        </span>
                     </div>
                  </div>
               </div>
            </div>

            <br>
            
            <div class="row">
               <div style="flex: 50%;max-width: 50%;padding: 0 4px;" class="column">
                  <div class="form-group row">
                     <label for="unit" class="col-3 control-label text-right"></label>
                     <div class="col-9">
                        <button type="submit"  class="btn btn-{{setting('theme_color')}}"><i class="fa fa-save"></i> 
                           Import Products
                        </button>
                        &nbsp;&nbsp;
                        <a href="{!! route('products.index') !!}" class="btn btn-default"><i class="fa fa-undo"></i> 
                           Cancel
                        </a>
                     </div>
                  </div>
               </div>
            </div>

         {!! Form::close() !!}

      </div>
   </div>
</div>
</div>
</div>
@endsection

<style>
   #demo_file:hover { text-decoration: underline; }
</style>