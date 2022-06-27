@if($customFields)
<h5 class="col-12 pb-4">{!! trans('lang.main_fields') !!}</h5>
@endif
<div class="col-md-12 column custom-from-css">
    
    <div class="row">
        
            <div class="col-md-6">
                
            <!--selected staff Field -->
            <div class="form-group row ">
            <div class="col-md-12">
            <p><b>{{trans("lang.complaint_members")}}</b> &nbsp;&nbsp;&nbsp;{{$complaints->selected_staff_members}}</p>
            </div>
             </div>
          
            <div class="form-group row ">
                <div class="col-md-12">
               {!! Form::label('comments', trans("lang.complaint_comments"), ['class' => 'col-3 control-label text-left required']) !!}
               {!! Form::textarea('comments', null,  ['class' => 'form-control', 'placeholder'=>  trans("lang.complaint_comments_placeholder")]) !!} 
                </div>
            </div>

           </div>
           
            <div class="col-md-6">
            
               <div class="comments">
                           <style>
                        .comments {
                          margin: 50px auto 50px auto;
                          width: 100%;
                          padding: 0 10px 0 20px;
                          font-family: Arial, Tahoma;
                          border-left: 1px solid #ccc
                        }
                        
                        .comments p {
                          line-height: 1.5;
                          background-color: #fff;
                          border: 3px solid #00b0ff;
                          border-radius: 0 20px;
                          padding: 10px;
                          position: relative;
                          margin-bottom: 5px;
                        }
                        
                        .comments p:before {
                          content: "";
                          display: block;
                          width: 12px;
                          height: 12px;
                          border-radius: 50%;
                          background-color: #607d8b;
                          border: 3px solid #e0f7fa;
                          position: absolute;
                          top: 14px;
                          left: -30px
                        }
                        
                        .comments p:after {
                          content: "";
                          display: block;
                          width: 0;
                          height: 0;
                          border-style: solid;
                          border-width: 8px;
                          border-color: transparent #00b0ff transparent transparent;
                          position: absolute;
                          top: 8px;
                          left: -19px;
                        }
                    </style>
                @if(count($complaintsComments) >  0)
                    @foreach($complaintsComments as $val)
                        <span>{{$val->name }}</span>
                        <p>{{strip_tags($val->comments)}}</p>
                        <span style="float:right;">{{date("d M, Y", strtotime($val->created_at)) }}</span><br><br>
                    @endforeach
                @endif
            </div>
            
            </div>
         

</div>  

</div>



@if($customFields)
<div class="clearfix"></div>
<div class="col-12 custom-field-container">
  <h5 class="col-12 pb-4">{!! trans('lang.custom_field_plural') !!}</h5>
  {!! $customFields !!}
</div>
@endif
<!-- Submit Field -->
<div class="form-group col-12 text-right">
     @if($complaints->status=='1')
  <button type="submit" class="btn btn-{{setting('theme_color')}}" disabled><i class="fa fa-save"></i> {{trans('lang.save')}} {{trans('lang.complaint_comments')}}</button>
   @else
   <button type="submit" class="btn btn-{{setting('theme_color')}}" ><i class="fa fa-save"></i> {{trans('lang.save')}} {{trans('lang.complaint_comments')}}</button>
   @endif
  <a href="{!! route('complaints.index') !!}" class="btn btn-default"><i class="fa fa-undo"></i> {{trans('lang.cancel')}}</a>
</div>
