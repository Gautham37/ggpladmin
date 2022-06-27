  <!--Season-->
  <div class="modal fade" id="productSeasonModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        
        <div class="modal-header">
          <h4 class="modal-title" id="myModalLabel">Create Product Season</h4>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        </div>
        
        {!! Form::open(['route' => 'productSeasons.store', 'class' => 'product-season-form']) !!}

        <div class="modal-body">
       
          <div class="row">
              <div class="col-md-12">
                <div class="form-group">
                  {!! Form::label('name', 'Name', ['class' => 'col-3 control-label text-left required']) !!}
                  {!! Form::text('name', null,  ['class' => 'form-control','placeholder'=>  trans("lang.department_name_placeholder")]) !!}
                </div>
              </div>  
              
            <div class="clearfix"></div>
          </div>
        </div>

        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
          <button type="submit" class="btn btn-primary product-season-form-submit">Save changes</button>
        </div>

        {!! Form::close() !!}

      </div>
    </div>
  </div>