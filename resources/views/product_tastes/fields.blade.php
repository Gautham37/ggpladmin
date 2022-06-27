<div class="col-md-12 column custom-from-css">
    <div class="row">

      <div class="col-md-5">

        <!-- Name Field -->
        <div class="form-group ">
            {!! Form::label('name', 'Name', ['class' => ' control-label text-left']) !!}
            {!! Form::text('name', null,  ['class' => 'form-control','placeholder'=> ""]) !!}
        </div>

        <!-- Description Field -->
        <div class="form-group ">
            {!! Form::label('description', "Description", ['class' => ' control-label text-left']) !!}
            {!! Form::textarea('description', null, ['class' => 'form-control','placeholder'=> "" ]) !!}
        </div>


      </div>

    </div>  
</div>

<!-- Submit Field -->
<div class="form-group col-12 text-right">
  <button type="submit" class="btn btn-{{setting('theme_color')}}" ><i class="fa fa-save"></i> Save Product Taste</button>
  <a href="{!! route('productTastes.index') !!}" class="btn btn-default"><i class="fa fa-undo"></i> Cancel</a>
</div>
