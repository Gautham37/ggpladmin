  <!--Department-->
  <div class="modal fade" id="CategoryModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        
        <div class="modal-header">
          <h4 class="modal-title" id="myModalLabel">Create Category</h4>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        </div>
        
        {!! Form::open(['route' => 'categories.store', 'class' => 'category-form']) !!}

        <div class="modal-body">
       
          <div class="row">

              <div class="col-md-12">
                <div class="form-group">
                    {!! Form::label('department_id', trans("lang.department"), ['class' => 'col-3 control-label text-left required']) !!}
                    {!! Form::select('department_id', $departments, null, ['class' => 'select2 form-control department_id']) !!}
                </div>
              </div>

              <div class="col-md-12">
                <div class="form-group">
                    {!! Form::label('name', trans("lang.category_name"), ['class' => 'col-3 control-label text-left required']) !!}
                    {!! Form::text('name', null,  ['class' => 'form-control','placeholder'=>  trans("lang.category_name_placeholder")]) !!}
                </div>
              </div>            
              
              <?php /* ?>
              <div class="col-md-12 column custom-from-css">

                <div class="row">
                  <!-- Description Field -->
                  <div class="col-md-12">
                    <div class="form-group">
                      {!! Form::label('description', trans("lang.category_description"), ['class' => 'col-3 control-label text-left required ']) !!}
                      {!! Form::textarea('description', null, ['class' => 'form-control','placeholder'=>
                 trans("lang.category_description_placeholder")  ]) !!}
                    </div>
                  </div>
                </div>
          
                <div class="col-md-12">
                  <div class="form-group row">
                    {!! Form::label('active', trans("lang.category_active"),['class' => 'col-md-2 control-label text-left']) !!}
                    <div class="col-md-4 checkbox icheck">
                      <label class="form-check-inline">
                        {!! Form::hidden('active', 0) !!}
                        {!! Form::checkbox('active', 1, 1) !!}
                      </label>
                    </div>
                  </div>
                </div>

              </div>
              <?php /*/ ?>

            <div class="clearfix"></div>
          </div>
        </div>

        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
          <button type="submit" class="btn btn-primary category-submit">Save changes</button>
        </div>

        {!! Form::close() !!}

      </div>
    </div>
  </div>