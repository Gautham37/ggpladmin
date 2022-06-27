  <!--Sub Category-->
  <div class="modal fade" id="SubCategoryModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        
        <div class="modal-header">
          <h4 class="modal-title" id="myModalLabel">Create Category</h4>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        </div>
        
        {!! Form::open(['route' => 'subcategory.store', 'class' => 'subcategory-form']) !!}

        <div class="modal-body">
       
          <div class="row">

              <div class="col-md-6">
                <div class="form-group ">
                  {!! Form::label('department_id', trans("lang.department"),['class' => ' control-label text-left required']) !!}
                  {!! Form::select('department_id', $departments, null, ['class' => 'select2 form-control department_id']) !!}
                </div>
              </div>
              
              <!-- Category Id Field -->
              <div class="col-md-6">
                <div class="form-group ">
                  {!! Form::label('category_id', trans("lang.product_category_id"),['class' => ' control-label text-left required']) !!}
                  {!! Form::select('category_id', $category, $categorySelected, ['class' => 'select2 form-control category_id','id'=>'categorymodal']) !!}
                </div>
              </div>    

              <!-- Name Field -->
              <div class="col-md-12">
                <div class="form-group">
                    {!! Form::label('name', trans("lang.subcategory_name"), ['class' => ' control-label text-left required']) !!}
                    {!! Form::text('name', null,  ['class' => 'form-control','placeholder'=>  trans("lang.subcategory_name_placeholder")]) !!}
                </div>
              </div> 

              <!-- Short Name Field -->
              <div class="col-md-12">
                <div class="form-group">
                    {!! Form::label('short_name', 'Short Name', ['class' => ' control-label text-left required']) !!}
                    {!! Form::text('short_name', null,  ['class' => 'form-control','placeholder'=> ""]) !!}
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
          <button type="submit" class="btn btn-primary subcategory-submit">Save changes</button>
        </div>

        {!! Form::close() !!}

      </div>
    </div>
  </div>