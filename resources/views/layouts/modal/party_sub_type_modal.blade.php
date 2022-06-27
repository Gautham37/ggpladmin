  <!--Department-->
  <div class="modal fade" id="partySubtypeModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        
        <div class="modal-header">
          <h4 class="modal-title" id="myModalLabel">Create Party Sub Type</h4>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        </div>
        
        {!! Form::open(['route' => 'partySubTypes.store', 'class' => 'party-sub-type-form']) !!}

        <div class="modal-body">
       
          <div class="row">

              <div class="col-md-12">
                <div class="form-group">
                 {!! Form::label('party_type_id', trans("lang.party_type"),['class' => ' control-label text-left required']) !!}
                  {!! Form::select('party_type_id', $party_types, null, ['class' => 'select2 form-control type party_type_id']) !!}
                  <!-- <div class="form-text text-muted">
                    {{ trans("lang.category_name_help") }}
                  </div> -->
                </div>
              </div>

              <div class="col-md-12">
                <div class="form-group">
                  {!! Form::label('name', trans("lang.party_type_name"), ['class' => ' control-label text-left required']) !!}
                  {!! Form::text('name', null,  ['class' => 'form-control','placeholder'=>  trans("lang.party_type_name_placeholder")]) !!}
                  <!-- <div class="form-text text-muted">
                    {{ trans("lang.category_name_help") }}
                  </div> -->
                </div>
              </div>  

              <div class="col-md-12">
                <div class="form-group">
                {!! Form::label('prefix_value', trans("lang.party_sub_type_prefix"), ['class' => ' control-label text-left required']) !!}
                  {!! Form::text('prefix_value', null,  ['class' => 'form-control','placeholder'=>  trans("lang.party_sub_type_prefix_placeholder")]) !!}
                  <!-- <div class="form-text text-muted">
                    {{ trans("lang.category_name_help") }}
                  </div> -->
                </div>
              </div>          
              
              <?php /* ?>
              <div class="col-md-12 column custom-from-css">
                <div class="row">
                  <!-- Description Field -->
                  <div class="col-md-12">
                    <div class="form-group">
                      {!! Form::label('description', trans("lang.department_description"), ['class' => 'col-3 control-label text-left required ']) !!}
                      {!! Form::textarea('description', null, ['class' => 'form-control','placeholder'=>
                 trans("lang.department_description_placeholder")  ]) !!}
                      <!-- <div class="form-text text-muted">{{ trans("lang.category_description_help") }}</div> -->
                    </div>
                  </div>
                </div>
          
                <div class="col-md-12">
                  <div class="form-group row">
                    {!! Form::label('active', trans("lang.department_active"),['class' => 'col-md-2 control-label text-left']) !!}
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
          <button type="submit" class="btn btn-primary party-sub-type-submit">Save changes</button>
        </div>

        {!! Form::close() !!}

      </div>
    </div>
  </div>