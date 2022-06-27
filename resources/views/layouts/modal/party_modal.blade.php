  <!--Party Form-->
  <div class="modal fade" id="partyModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        
        <div class="modal-header">
          <h4 class="modal-title" id="myModalLabel">Create Party</h4>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        </div>
        
        {!! Form::open(['route' => 'markets.store', 'class' => 'party-modal-form']) !!}

        <div class="modal-body">
          <div class="row">

            <!-- Name Field -->
            <div class="col-md-4">
               <div class="form-group">
                  {!! Form::label('name', trans("lang.market_name"), ['class' => ' control-label text-right required']) !!}
                  {!! Form::text('name', null,  ['class' => 'form-control','placeholder'=>  trans("lang.market_name_placeholder")]) !!}
                  <div class="form-text text-muted">
                     <!-- {{ trans("lang.market_name_help") }} -->
                  </div>
               </div>
            </div>
            <!-- Email Field -->
            <div class="col-md-4">
               <div class="form-group">
                  {!! Form::label('email', trans("lang.market_email"), ['class' => 'control-label text-right required']) !!}
                  {!! Form::email('email', null,  ['class' => 'form-control','placeholder'=>  trans("lang.market_email_placeholder")]) !!}
                  <div class="form-text text-muted">
                     <!-- {{ trans("lang.market_email_help") }} -->
                  </div>
               </div>
            </div>
            <!-- Mobile Field -->
            <div class="col-md-4">
               <div class="form-group">
                  {!! Form::label('mobile', trans("lang.market_mobile"), ['class' => 'control-label text-right']) !!}
                  {!! Form::text('mobile', null,  ['class' => 'form-control','placeholder'=>  trans("lang.market_mobile_placeholder")]) !!}
                  <div class="form-text text-muted">
                     <!-- {{ trans("lang.market_mobile_help") }} -->
                  </div>
               </div>
            </div>

            <!-- Party Stream Field -->
            <div class="col-md-4">
               <div class="form-group">
                  {!! Form::label('stream', "Party Stream",['class' => 'control-label text-right required']) !!}
                  <div class="input-group-append">
                     {!! Form::select('stream', $party_streams, null, ['class' => 'select2 form-control stream']) !!}
                  </div>
               </div>
            </div>

            <!-- Party Type Field -->
            <div class="col-md-4">
               <div class="form-group">
                  {!! Form::label('type', "Party Type", ['class' => 'control-label text-right required']) !!}
                  <div class="input-group-append">
                     {!! Form::select('type', $party_types, null, ['class' => 'select2 form-control type']) !!}
                  </div>
               </div>
            </div>

            <!-- Party Sub Type Field -->
            <div class="col-md-4">
               <div class="form-group">
                  {!! Form::label('sub_type', "Party Sub Type",['class' => 'control-label text-right required']) !!}
                  <div class="input-group-append">
                     {!! Form::select('sub_type', $party_sub_types, null, ['class' => 'select2 form-control sub_type','id'=>'sub_type']) !!}
                  </div>
               </div>
            </div>

            <div class="col-md-12">
               <hr>
            </div>

            <!-- Street No Field -->
            <div class="col-md-4">
               <div class="form-group">
                  {!! Form::label('street_no', 'Street No', ['class' => 'control-label text-right']) !!}
                  {!! Form::text('street_no', null,  ['class' => 'form-control','placeholder'=>  "Insert Street No"]) !!}
                  <div class="form-text text-muted">
                  </div>
               </div>
            </div>

            <!-- Street Name Field -->
            <div class="col-md-4">
               <div class="form-group">
                  {!! Form::label('street_name', 'Street Name', ['class' => 'control-label text-right']) !!}
                  {!! Form::text('street_name', null,  ['class' => 'form-control','placeholder'=>  "Insert Street Name"]) !!}
                  <div class="form-text text-muted">
                  </div>
               </div>
            </div>

            <!-- Street Type Field -->
            <div class="col-md-4">
               <div class="form-group">
                  {!! Form::label('street_type', 'Street Type', ['class' => 'control-label text-right']) !!}
                  {!! Form::text('street_type', null,  ['class' => 'form-control','placeholder'=>  "Insert Street Type"]) !!}
                  <div class="form-text text-muted">
                  </div>
               </div>
            </div>

            <!-- Landmark 1 Field -->
            <div class="col-md-4">
               <div class="form-group">
                  {!! Form::label('landmark_1', 'Landmark 1', ['class' => 'control-label text-right']) !!}
                  {!! Form::text('landmark_1', null,  ['class' => 'form-control','placeholder'=>  "Insert Landmark 1"]) !!}
                  <div class="form-text text-muted">
                  </div>
               </div>
            </div>

            <!-- Landmark 2 Field -->
            <div class="col-md-4">
               <div class="form-group">
                  {!! Form::label('landmark_2', 'Landmark 2', ['class' => 'control-label text-right']) !!}
                  {!! Form::text('landmark_2', null,  ['class' => 'form-control','placeholder'=>  "Insert Landmark 2"]) !!}
                  <div class="form-text text-muted">
                  </div>
               </div>
            </div>

            <!-- Address Field -->
            <div class="col-md-4">
               <div class="form-group">
                  {!! Form::label('address_line_1', 'Address Line 1', ['class' => 'control-label text-right']) !!}
                  {!! Form::text('address_line_1', null,  ['class' => 'form-control','placeholder'=>  'Address Line 1']) !!}
                  <div class="form-text text-muted">
                     <!-- {{ trans("lang.market_address_line_1_help") }} -->
                  </div>
               </div>
            </div>

            <!-- Address Field -->
            <div class="col-md-4">
               <div class="form-group">
                  {!! Form::label('address_line_2', 'Address Line 2', ['class' => 'control-label text-right']) !!}
                  {!! Form::text('address_line_2', null,  ['class' => 'form-control','placeholder'=>  'Address Line 2']) !!}
                  <div class="form-text text-muted">
                     <!-- {{ trans("lang.market_address_line_2_help") }} -->
                  </div>
               </div>
            </div>

            <!-- Town Field -->
            <div class="col-md-4">
               <div class="form-group">
                  {!! Form::label('town', 'Town', ['class' => 'control-label text-right']) !!}
                  {!! Form::text('town', null,  ['class' => 'form-control','placeholder'=> "Insert Town"]) !!}
                  <div class="form-text text-muted">
                  </div>
               </div>
            </div>

            <!-- City Field -->
            <div class="col-md-4">
               <div class="form-group">
                  {!! Form::label('city', 'City', ['class' => 'control-label text-right']) !!}
                  {!! Form::text('city', null,  ['class' => 'form-control','placeholder'=>  'City']) !!}
                  <div class="form-text text-muted">
                     <!-- {{ trans("lang.market_city_help") }} -->
                  </div>
               </div>
            </div>
            <!-- State Field -->
            <div class="col-md-4">
               <div class="form-group">
                  {!! Form::label('state', 'State', ['class' => 'control-label text-right']) !!}
                  {!! Form::text('state', null,  ['class' => 'form-control','placeholder'=>  'State']) !!}
                  <div class="form-text text-muted">
                     <!-- {{ trans("lang.market_state_help") }} -->
                  </div>
               </div>
            </div>
            <!-- Pincode Field -->
            <div class="col-md-4">
               <div class="form-group">
                  {!! Form::label('pincode', 'Pincode', ['class' => 'control-label text-right']) !!}
                  {!! Form::text('pincode', null,  ['class' => 'form-control','placeholder'=>  'Pincode']) !!}
                  <div class="form-text text-muted">
                     <!-- {{ trans("lang.market_pincode_help") }} -->
                  </div>
               </div>
            </div>
            <!-- GSTIN Field -->
            <div class="col-md-4">
               <div class="form-group">
                  {!! Form::label('gstin', 'GSTIN', ['class' => 'control-label text-right']) !!}
                  {!! Form::text('gstin', null,  ['class' => 'form-control','placeholder'=>  'GSTIN']) !!}
                  <div class="form-text text-muted">
                     <!-- {{ trans("lang.market_gstin_help") }} -->
                  </div>
               </div>
            </div>

            <!-- Latitude Field -->
            <div class="col-md-4">
               <div class="form-group">
                  {!! Form::label('latitude', 'Latitude', ['class' => 'control-label text-right']) !!}
                  <div class="input-group-append">
                     {!! Form::text('latitude', null,  ['class' => 'form-control','placeholder'=>  'Latitude']) !!}
                     <span class="input-group-text" style="margin-left: -5px;border: none;background: #e3e9ec;border-left: 2px solid #d6d5d5;backface-visibility: visible;z-index: 1;border-radius: 0px 8px 8px 0px;"> <a data-toggle="modal" href="#myModal"><i class="fa fa-map-marker mr-1"></i></a></i></span>
                  </div>
               </div>
            </div>

            <!-- Longitude Field -->
            <div class="col-md-4">
               <div class="form-group">
                  {!! Form::label('longitude', 'Longitude', ['class' => 'control-label text-right']) !!}
                  <div class="input-group-append">
                     {!! Form::text('longitude', null,  ['class' => 'form-control','placeholder'=>  'Longitude']) !!}
                     <span class="input-group-text" style="margin-left: -5px;border: none;background: #e3e9ec;border-left: 2px solid #d6d5d5;backface-visibility: visible;z-index: 1;border-radius: 0px 8px 8px 0px;"> <a data-toggle="modal" href="#myModal"><i class="fa fa-map-marker mr-1"></i></a></i></span>
                  </div>
               </div>
            </div>

          </div>
        </div>

        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
          <button type="submit" class="btn btn-primary party-modal-form-submit"><i class="fa fa-save"></i> Save Party</button>
        </div>

        {!! Form::close() !!}

      </div>
    </div>
  </div>
@push('scripts')
<script>
   $(document).ready(function() {
        $('.type').on('change', function(e) {
            var type = e.target.value;
            $.ajax({
                url: "{{ route('markets.showPartySubTypes') }}",
                type: "POST",
                data: {
                    type: type,
                    "_token": "{{ csrf_token() }}",
                },
                success: function(data) {
                    $('.sub_type').empty();
                    $('.sub_type').append('<option value="">Please Select</option>');
                    $.each(data.party_sub_types[0].party_sub_types, function(index, party_sub_type) {
                        $('.sub_type').append('<option value="' + party_sub_type.id + '">' + party_sub_type.name + '</option>');
                    })
                }
            })
        });
   });
</script>
@endpush  