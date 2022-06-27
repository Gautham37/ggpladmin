<div class="col-md-12 column custom-from-css">
    <div class="row">

      <div class="col-md-6 offset-md-3">

        <!-- Name Field -->
        <div class="form-group row">
            {!! Form::label('name', 'Name', ['class' => ' col-4 control-label text-left required']) !!}
            {!! Form::text('name', null,  ['class' => 'form-control col-8','placeholder'=> ""]) !!}
        </div>

        <!-- Email Field -->
        <div class="form-group row">
            {!! Form::label('email', 'Email', ['class' => ' col-4 control-label text-left required']) !!}
            {!! Form::email('email', null,  ['class' => 'form-control col-8','placeholder'=> ""]) !!}
        </div>

        <!-- Mobile Field -->
        <div class="form-group row">
            {!! Form::label('mobile', 'Mobile', ['class' => ' col-4 control-label text-left required']) !!}
            {!! Form::text('mobile', null,  ['class' => 'form-control col-8','placeholder'=> ""]) !!}
        </div>

        <!-- Address Line 1 Field -->
        <div class="form-group row">
            {!! Form::label('address_line_1', 'Address Line 1', ['class' => ' col-4 control-label text-left required']) !!}
            {!! Form::text('address_line_1', null,  ['class' => 'form-control col-8','placeholder'=> ""]) !!}
        </div>

        <!-- Address Line 2 Field -->
        <div class="form-group row">
            {!! Form::label('address_line_2', 'Address Line 2', ['class' => ' col-4 control-label text-left ']) !!}
            {!! Form::text('address_line_2', null,  ['class' => 'form-control col-8','placeholder'=> ""]) !!}
        </div>

        <!-- Town Field -->
        <div class="form-group row">
            {!! Form::label('town', 'Town', ['class' => ' col-4 control-label text-left required']) !!}
            {!! Form::text('town', null,  ['class' => 'form-control col-8','placeholder'=> ""]) !!}
        </div>

        <!-- City Field -->
        <div class="form-group row">
            {!! Form::label('city', 'City', ['class' => ' col-4 control-label text-left required']) !!}
            {!! Form::text('city', null,  ['class' => 'form-control col-8','placeholder'=> ""]) !!}
        </div>

        <!-- State Field -->
        <div class="form-group row">
            {!! Form::label('state', 'State', ['class' => ' col-4 control-label text-left required']) !!}
            {!! Form::text('state', null,  ['class' => 'form-control col-8','placeholder'=> ""]) !!}
        </div>

        <!-- Pincode Field -->
        <div class="form-group row">
            {!! Form::label('pincode', 'Pincode', ['class' => ' col-4 control-label text-left required']) !!}
            {!! Form::text('pincode', null,  ['class' => 'form-control col-8','placeholder'=> ""]) !!}
        </div>



      </div>

    </div>  
</div>

<!-- Submit Field -->
<div class="form-group col-12 text-right">
  <button type="submit" class="btn btn-{{setting('theme_color')}}" ><i class="fa fa-save"></i> Save Charity</button>
  <a href="{!! route('charity.index') !!}" class="btn btn-default"><i class="fa fa-undo"></i> Cancel</a>
</div>
