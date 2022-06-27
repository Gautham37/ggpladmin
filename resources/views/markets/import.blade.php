@extends('layouts.app')
@section('content')
<!-- Content Header (Page header) -->
<div class="content-header">
   <div class="container-fluid">
      <div class="row mb-2">
         <div class="col-sm-6">
            <h1 class="m-0 text-dark">Import Parties<small class="ml-3 mr-3">|</small><small>Parties Management</small></h1>
         </div>
         <!-- /.col -->
         <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
               <li class="breadcrumb-item"><a href="{{url('/dashboard')}}"><i class="fa fa-dashboard"></i> Dashboard </a></li>
               <li class="breadcrumb-item"><a href="{!! route('markets.index') !!}"> Parties </a></li>
               <li class="breadcrumb-item active"> Import Parties </li>
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

         {!! Form::open(['route'=>'markets.importmarkets', 'id'=>'market-import-form ','enctype'=>'multipart/form-data']) !!}
            
            <div class="row">
               <div style="flex: 50%;max-width: 50%;padding: 0 4px;" class="column">
                  <div class="form-group row">
                     <label for="unit" class="col-3 control-label text-right">Import Data</label>
                     <div class="col-9">
                        <input type="file" name="file" class="form-control">
                     </div>
                  </div>
               </div>
            </div>
            
            <div class="row">
               <div style="flex: 50%;max-width: 50%;padding: 0 4px;" class="column">
                  <div class="form-group row">
                     <label for="unit" class="col-3 control-label text-right"></label>
                     <div class="col-9">
                        <a href="{{url('storage/parties/parties_import.xlsx')}}" class="btn btn-md btn-success" > 
                           <i class="fa fa-download" aria-hidden="true" id="demo_file"></i>
                           &nbsp;Download Sample
                        </a>
                        <br>
                        <span style="line-height:40px;">
                           Note: Import file must be like above mentioned Demo File.
                           <br>
                           <b>Instrucations :</b>
                           <p>
                              <span><b>Email :</b> give unique email for every parties</span>
                              <br>
                              <span><b>Party Types :</b> Available {{$party_types}}</span>
                              <br>
                              <span><b>Party Sub Roles :</b> Available [customer, driver, manager, supervisor, vendor, worker]</span>
                           </p>
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
                           Import Parties
                        </button>
                        &nbsp;&nbsp;
                        <a href="{!! route('markets.index') !!}" class="btn btn-default"><i class="fa fa-undo"></i> 
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