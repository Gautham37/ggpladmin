<div class="col-md-8 offset-md-2 column custom-from-css">
   <div class="row">

      <div class="col-md-12 mb-3 mt-3">
         <h5>Party Activity</h5>
      </div>

      {!! Form::hidden('market_id', $market->id) !!}

      <!-- Action Field -->
      <div class="col-md-12">
         <div class="form-group">
            {!! Form::label('action', 'Action', ['class' => ' control-label text-right required']) !!}
            {!! Form::text('action', null,  ['class' => 'form-control','placeholder'=> "Please enter the activity action"]) !!}
         </div>
      </div>

      <!-- Notes Field -->
      <div class="col-md-12">
         <div class="form-group">
            {!! Form::label('notes', 'Action Detail', ['class' => ' control-label text-right required']) !!}
            {!! Form::text('notes', null,  ['class' => 'form-control','placeholder'=> "Please enter the activity action detail"]) !!}
         </div>
      </div>

      <!-- Assign To Field -->
      <div class="col-md-12">
         <div class="form-group">
            {!! Form::label('assign_to', 'Assign To', ['class' => ' control-label text-right']) !!}
            {!! Form::select('assign_to', $staffs, null, ['class' => 'select2 form-control']) !!}
         </div>
      </div>

      <!-- Assign To Field -->
      <div class="col-md-12">
         <style>
             .colorinput-colors {
               padding: 4px 8px 2px 25px;
               width: 100%;
             }
             .colorinput-colors:before {
               left: 5px;
             }
           </style>
      
           <div class="form-group row">
              
              {!! Form::label('priority', 'Priority', ['class' => 'control-label text-left col-12']) !!}

              <div class="row gutters-xs col-12">
                 <div class="col-auto">
                    <label class="colorinput">
                     {{ Form::radio('priority', 'high' , false,['class' => 'colorinput-input']) }}
                     <span class="colorinput-color colorinput-colors bg-red">High</span>
                    </label>
                 </div>

                 <div class="col-auto">
                     <label class="colorinput">
                        {{ Form::radio('priority', 'medium' , true,['class' => 'colorinput-input']) }}
                        <span class="colorinput-color colorinput-colors bg-green">Medium</span>
                     </label>
                 </div>

                 <div class="col-auto">
                     <label class="colorinput">
                        {{ Form::radio('priority', 'low' , false,['class' => 'colorinput-input']) }}
                        <span class="colorinput-color colorinput-colors bg-yellow">Low</span>
                     </label>
                 </div>
              </div>

           </div>

      </div>

   </div>
</div>

