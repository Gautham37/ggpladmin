  <!--Department-->
  <div class="modal fade" id="StockstatusModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        
        <div class="modal-header">
          <h4 class="modal-title" id="myModalLabel">Create Stock Status</h4>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        </div>
        
        {!! Form::open(['route' => 'stockStatus.store', 'class' => 'stock-status-form']) !!}

        <div class="modal-body">
       
          <div class="row">
              <div class="col-md-12">
                <div class="form-group">
                  {!! Form::label('status', trans("lang.department_name"), ['class' => 'col-3 control-label text-left required']) !!}
                  {!! Form::text('status', null,  ['class' => 'form-control','placeholder'=>  trans("lang.department_name_placeholder")]) !!}
                  <!-- <div class="form-text text-muted">
                    {{ trans("lang.category_name_help") }}
                  </div> -->
                </div>
              </div>            

            <div class="clearfix"></div>
          </div>
        </div>

        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
          <button type="submit" class="btn btn-primary stock-status-submit">Save changes</button>
        </div>

        {!! Form::close() !!}

      </div>
    </div>
  </div>