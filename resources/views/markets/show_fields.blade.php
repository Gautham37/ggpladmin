<style>
   .pro_name {
   font-size: 13px;
   }
   .table-scroll{
   /*width:100%; */
   display: block;
   empty-cells: show;
   /* Decoration */
   border-spacing: 0;
   height: 550px;       /* Just for the demo          */
   overflow-y: auto;    /* Trigger vertical scroll    */
   overflow-x: hidden;
   }
   .table-scroll thead{
   background-color: #f1f1f1;  
   position:absolute;
   display: block;
   width:94%;
   /*overflow-y: scroll;*/
   }
   .table-scroll tbody{
   /* Position */
   display: block; 
   /*position:relative;*/
   width:100%;
   /* Decoration */
   border-top: 1px solid rgba(0,0,0,0.2);
   margin-top: 40px;
   }
   .table-scroll tr{
   width: 100%;
   display:flex;
   }
   .table-scroll td,.table-scroll th{
   flex-basis:100%;
   flex-grow:2;
   display: block;
   padding: 1rem;
   }
   .active-tab {
   background: #f4f6f9;
   font-weight: bold !important;
   }
   /**
   * tab panel widget
   */
   .tabPanel-widget {
   position: relative;  /* containing block for headings (top:0) */
   background: #fff;
   }
   /**
   * because labels come first in source order - we use z-index to move them in front of the headings
   */
   .tabPanel-widget > label {
   position: absolute;
   z-index: 1;
   }
   /**
   * labels and headings must share same values so grouping declarations in this rule prevents async edits (risk of breakage)
   * line-height == height -> vertical centering
   * the width dictates the offset for all headings but the first one: left offset = width * number of previous heading(s)
   * note that width and offset of label/heading pair can be customized if necessary
   */
   .tabPanel-widget > label,
   .tabPanel-widget > h2 {
   font-size: 1.1em;
   width: 9em;
   height: 3em;
   line-height: 3em;
   font-weight: bold;
   }
   /**
   * position:relative is for the markers (the down arrow in tabs)
   */
   .tabPanel-widget > h2 {
   position: relative;
   margin: 0;
   text-align: center;
   background: #fff;
   color: #585858;
   }
   .tabPanel-widget > label {
   border: 1px solid #e6e6e6;
   }
   /**
   * all first level labels and headings after the very first ones 
   */
   .tabPanel-widget > label ~ label,
   .tabPanel-widget > h2 ~ h2 {
   position: absolute;
   top: 0;
   }
   /**
   * We target all the label/heading pairs
   * we increment the :nth-child() params by 4 as well as the left value (according to "tab" width)
   */
   .tabPanel-widget label:nth-child(1),
   .tabPanel-widget h2:nth-child(3) {
   left: 0em;
   }
   .tabPanel-widget label:nth-child(5),
   .tabPanel-widget h2:nth-child(7) {
   left: 9em;
   }
   .tabPanel-widget label:nth-child(9),
   .tabPanel-widget h2:nth-child(11) {
   left: 18em;
   }
   /**
   * we visually hide all the panels
   * https://developer.yahoo.com/blogs/ydn/clip-hidden-content-better-accessibility-53456.html
   */
   .tabPanel-widget input + h2 + div {
   position: absolute !important;
   clip: rect(1px, 1px, 1px, 1px);
   padding:0 !important;
   border:0 !important;
   height: 1px !important; 
   width: 1px !important; 
   overflow: hidden;
   }
   /**
   * we reveal a panel depending on which control is selected 
   */
   .tabPanel-widget input:checked + h2 + div {
   position: static !important;
   padding: 1em !important;
   height: auto !important; 
   width: auto !important; 
   }
   /**
   * shows a hand cursor only to pointing device users
   */
   .tabPanel-widget label:hover {
   cursor: pointer;
   }
   .tabPanel-widget > div {
   background: #fff;
   padding: 1em;
   }
   /**
   * we hide radio buttons and also remove them from the flow
   */
   .tabPanel-widget input[name="tabs"] {
   opacity: 0;
   position: absolute;
   }
   /** 
   * this is to style the tabs when they get focus (visual cue)
   */
   .tabPanel-widget input[name="tabs"]:focus + h2 {
   outline: 1px dotted #000;
   outline-offset: 10px;
   }
   /**
   * reset of the above within the tab panel (for pointing-device users)
   */
   .tabPanel-widget:hover h2 {
   outline: none !important;
   }
   /**
   * visual cue of the selection
   */
   .tabPanel-widget input[name="tabs"]:checked + h2 {
   background: #f1f1f1;
   color: #28a745;
   border-bottom: 2px solid;
   }
   /**
   * the marker for tabs (down arrow)
   /**
   * Make it plain/simple below 45em (stack everything)
   */
   @media screen and (max-width: 45em) {
   /* hide unecessary label/control pairs */
   .tabPanel-widget label,
   .tabPanel-widget input[name="tabs"] {
   display: none;
   }
   /* reveal all panels */
   .tabPanel-widget > input + h2 + div {
   display: block !important;
   position: static !important;
   padding: 1em !important;
   height: auto !important; 
   width: auto !important; 
   }
   /* "unstyle" the heading */
   .tabPanel-widget h2 {
   width: auto;
   position: static !important;
   background: #999 !important;
   }
   /* "kill" the marker */
   .tabPanel-widget h2:after {
   display: none !important;
   }
   }
   main {
   min-width: auto;
   max-width: auto;
   margin: 0 auto;
   background: #fff;
   }
   section {
   display: none;
   padding: 20px 0 0;
   border-top: 1px solid #ddd;
   }
   input {
   display: none;
   }
   .tab-label {
      display: inline-block;
      margin: 0 0 -1px;
      padding: 10px 25px;
      font-weight: 600;
      text-align: center;
      color: #000;
      border: 1px solid transparent;
      width: calc(100% / 7);
   }
   .tab-label:before {
   font-family: fontawesome;
   font-weight: normal;
   margin-right: 10px;
   }
   /*label[for*='1']:before {
   content: '\f1cb';
   }
   label[for*='2']:before {
   content: '\f17d';
   }
   label[for*='3']:before {
   content: '\f16b';
   }
   label[for*='4']:before {
   content: '\f1a9';
   }*/
   .tab-label:hover {
   color: #888;
   cursor: pointer;
   }
   input:checked + .tab-label {
   color: #555;
   border: 1px solid #ddd;
   border-top: 2px solid orange;
   border-bottom: 1px solid #fff;
   }
   #tab1:checked ~ #content1,
   #tab2:checked ~ #content2,
   #tab3:checked ~ #content3,
   #tab4:checked ~ #content4, 
   #tab5:checked ~ #content5,
   #tab6:checked ~ #content6 {
   display: block;
   }
   @media screen and (max-width: 650px) {
   label {
   font-size: 0;
   }
   label:before {
   margin: 0;
   font-size: 18px;
   }
   }
   @media screen and (max-width: 400px) {
   label {
   padding: 15px;
   }
   }
   .parties-view-tbl .form-group { margin-bottom:10px; }
   tr:nth-child(even) {
      background-color: #e8fcff80;
   }
</style>
<!--<div class="col-md-3">
   <input type="text" class="form-control" id="myInput" onkeyup="myFunction()" placeholder="{{trans('lang.search_party')}}" title="Type in a name">
   <table id="productTable" class="table table-bordered table-scroll small-first-col">
     <tbody style="margin-top:0px;">
       @foreach($markets as $selMarket)
         <tr @if($market->id==$selMarket->id) class="active-tab" @endif>
           <td>
             <a style="color:#000;" href="{{route('markets.view',$selMarket->id)}}">
               <p class="pro_name"><b>{{$selMarket->name}}</b></p>
               <p>
                 <span class="float-left">
                     @if($selMarket->type==1)
                       Customer
                     @elseif($selMarket->type==2)
                       Supplier
                     @elseif($selMarket->type==3)
                       Farmer
                     @endif
                 </span>
                 <span class="float-right"><b>{!!getBalance($selMarket,'balance')!!}</b></span>
               </p>
             </a>
           </td>
         </tr>
       @endforeach
     </tbody>
   </table>
   
   
   
   </div>-->
<div class="col-md-12">

   <div class="col-md-12 custom-from-css" style="padding: 0px;">
      <div class="form-group">
         {!! Form::label('party', 'Party', ['class' => 'control-label text-right']) !!}
         <select class="form-control select2 parties">
            <option value="">Select</option>
            @foreach($markets as $selMarket)
            <option value="{{$selMarket->id}}" @if($market->id==$selMarket->id) selected @endif >{{$selMarket->name}} - {{$selMarket->mobile}}</option>
            @endforeach
         </select>
      </div>
   </div>

   <div class="col-md-12 float-right" style="display: inline-flex; padding: 0px;padding-bottom:10px;">
      <a class="btn btn-info btn-sm text-white"><b>{!! $market->code !!}</b></a>
      &nbsp;&nbsp;&nbsp;
      @can('markets.edit')
      <a data-toggle="tooltip" data-placement="bottom" title="{{trans('lang.market_edit')}}" href="{{ route('markets.edit', $market->id) }}" class="btn btn-sm btn-warning">
      <i class="fa fa-edit" style="font-size:20px;vertical-align:middle;"></i>
      </a>
      @endcan
      &nbsp;&nbsp;&nbsp;
      @can('markets.destroy')
      {!! Form::open(['route' => ['markets.destroy', $market->id], 'method' => 'delete']) !!}
      {!! Form::button('<i class="fa fa-trash" style="font-size:20px;vertical-align:middle;"></i>', [
      'type' => 'submit',
      'class' => 'btn btn-sm btn-danger text-white',
      'title' => 'Delete Party',
      'onclick' => "return confirm('Are you sure?')"
      ]) !!}
      {!! Form::close() !!}
      @endcan
   </div>

   <main>
      <input id="tab1" type="radio" name="tabs" checked>
      <label class="tab-label" for="tab1">{{trans('lang.party_profile')}}</label>
      <input id="tab2" type="radio" name="tabs">
      <label class="tab-label" for="tab2">{{trans('lang.party_transactions')}}</label>
      <input id="tab3" type="radio" name="tabs">
      <label class="tab-label" for="tab3">{{trans('lang.party_ledger')}}</label>
      <input id="tab4" type="radio" name="tabs">
      <label class="tab-label" for="tab4">{{trans('lang.party_reward')}}</label>
      <input id="tab5" type="radio" name="tabs">
      <label class="tab-label" for="tab5">Notes</label>
      <input id="tab6" type="radio" name="tabs">
      <label class="tab-label" for="tab6">Activity</label>
      <section id="content1">

         <?php /* ?>
         <div class="row">
            <div class="col-md-6">
               <table class="table ">
                  <tbody>
                     <tr>
                        <th width="30%">Party Code</th>
                        <th width="30%" class="text-center">:</th>
                        <td width="40%">{{$market->code}}</td>
                     </tr>
                     <tr>
                        <th width="30%">Party Full Name</th>
                        <th width="30%" class="text-center">:</th>
                        <td width="40%">{{$market->name}}</td>
                     </tr>
                     <tr>
                        <th width="30%">Party Status</th>
                        <th width="30%" class="text-center">:</th>
                        <td width="40%">
                           {!! ($market->status==0) ? '<a class="btn btn-sm btn-success text-white">Active</a>' : '<a class="btn btn-sm btn-danger text-white">Inactive</a>' !!}
                        </td>
                     </tr>
                     <tr>
                        <th width="30%">Party Status Date</th>
                        <th width="30%" class="text-center">:</th>
                        <td width="40%">{{$market->updated_at->format('d M Y')}}</td>
                     </tr>
                     <tr>
                        <th width="30%">Party Type</th>
                        <th width="30%" class="text-center">:</th>
                        <td width="40%">{{($market->party_type) ? $market->party_type->name : '' }}</td>
                     </tr>
                     <tr>
                        <th width="30%">Party Subtype</th>
                        <th width="30%" class="text-center">:</th>
                        <td width="40%">{{($market->party_sub_type) ? $market->party_sub_type->name : '' }}</td>
                     </tr>

                     <!-- <tr>
                        <th width="30%">Title</th>
                        <th width="30%" class="text-center">:</th>
                        <td width="40%"></td>
                     </tr>

                     <tr>
                        <th width="30%">First Name</th>
                        <th width="30%" class="text-center">:</th>
                        <td width="40%"></td>
                     </tr>

                     <tr>
                        <th width="30%">Middle Name</th>
                        <th width="30%" class="text-center">:</th>
                        <td width="40%"></td>
                     </tr>

                     <tr>
                        <th width="30%">Last Name</th>
                        <th width="30%" class="text-center">:</th>
                        <td width="40%"></td>
                     </tr> -->

                     <tr>
                        <th width="30%">DOB</th>
                        <th width="30%" class="text-center">:</th>
                        <td width="40%">@if($market->date_of_birth!=null) {{$market->date_of_birth->format('d M Y')}} @endif</td>
                     </tr>

                     <tr>
                        <th width="30%">Gender</th>
                        <th width="30%" class="text-center">:</th>
                        <td width="40%">{{ucfirst($market->gender)}}</td>
                     </tr>

                     <tr>
                        <th width="30%">Mobile</th>
                        <th width="30%" class="text-center">:</th>
                        <td width="40%">{!!$market->mobile!!}</td>
                     </tr>

                     <tr>
                        <th width="30%">Phone</th>
                        <th width="30%" class="text-center">:</th>
                        <td width="40%">{!!$market->phone!!}</td>
                     </tr>

                     <tr>
                        <th width="30%">Email ID</th>
                        <th width="30%" class="text-center">:</th>
                        <td width="40%">{!!$market->user->email!!}</td>
                     </tr>

                     <tr>
                        <th width="30%">Prefered Language</th>
                        <th width="30%" class="text-center">:</th>
                        <td width="40%"> {!!ucfirst($market->preferred_language)!!} </td>
                     </tr>

                     <tr>
                        <th width="30%">Address Validation</th>
                        <th width="30%" class="text-center">:</th>
                        <td width="40%"></td>
                     </tr>

                     <tr>
                        <th width="30%">Street No</th>
                        <th width="30%" class="text-center">:</th>
                        <td width="40%">{!!$market->street_no!!}</td>
                     </tr>

                     <tr>
                        <th width="30%">Street Name</th>
                        <th width="30%" class="text-center">:</th>
                        <td width="40%">{!!$market->street_name!!}</td>
                     </tr>

                     <tr>
                        <th width="30%">Street Type</th>
                        <th width="30%" class="text-center">:</th>
                        <td width="40%">{!!$market->street_type!!}</td>
                     </tr>

                     <tr>
                        <th width="30%">Landmark 1</th>
                        <th width="30%" class="text-center">:</th>
                        <td width="40%">{!!$market->landmark_1!!}</td>
                     </tr>

                     <tr>
                        <th width="30%">Landmark 2</th>
                        <th width="30%" class="text-center">:</th>
                        <td width="40%">{!!$market->landmark_2!!}</td>
                     </tr>

                     <tr>
                        <th width="30%">Town</th>
                        <th width="30%" class="text-center">:</th>
                        <td width="40%">{!!$market->town!!}</td>
                     </tr>

                     <tr>
                        <th width="30%">City</th>
                        <th width="30%" class="text-center">:</th>
                        <td width="40%">{!!$market->city!!}</td>
                     </tr>

                     <tr>
                        <th width="30%">State</th>
                        <th width="30%" class="text-center">:</th>
                        <td width="40%">{!!$market->state!!}</td>
                     </tr>

                     <tr>
                        <th width="30%">Pincode</th>
                        <th width="30%" class="text-center">:</th>
                        <td width="40%">{!!$market->pincode!!}</td>
                     </tr>

                     <tr>
                        <th width="30%">Address Note</th>
                        <th width="30%" class="text-center">:</th>
                        <td width="40%"></td>
                     </tr>

                     <tr>
                        <th width="30%">Full Address</th>
                        <th width="30%" class="text-center">:</th>
                        <td width="40%"></td>
                     </tr>

                     <tr>
                        <th width="30%">How did you hear about us?</th>
                        <th width="30%" class="text-center">:</th>
                        <td width="40%">{!!$market->hear_about_us!!}</td>
                     </tr>

                     <tr>
                        <th width="30%">Reffered by</th>
                        <th width="30%" class="text-center">:</th>
                        <td width="40%">{!!$market->user->referred_by!!}</td>
                     </tr>

                     <tr>
                        <th width="30%">Customer Level</th>
                        <th width="30%" class="text-center">:</th>
                        <td width="40%">{!!($market->customer_level) ? $market->customer_level->name : '' !!}</td>
                     </tr>

                     <tr>
                        <th width="30%">Customer Stream</th>
                        <th width="30%" class="text-center">:</th>
                        <td width="40%">{!!($market->party_stream) ? $market->party_stream->name : '' !!}</td>
                     </tr>

                     <tr>
                        <th width="30%">Credit Period</th>
                        <th width="30%" class="text-center">:</th>
                        <td width="40%"></td>
                     </tr>

                     <tr>
                        <th width="30%">Orders / Deliveries Till date</th>
                        <th width="30%" class="text-center">:</th>
                        <td width="40%"> {{count($market->salesinvoice) + count($market->user->orders)}} </td>
                     </tr>

                     @if(count($market->saleunitlist) > 0) 
                        @php $i=0 ; @endphp
                        @foreach($market->saleunitlist as $unit)
                        @php $i++; @endphp
                           <tr>
                              <th width="30%">Quantities {{$i}} till Date ({{$unit->uom->name}})</th>
                              <th width="30%" class="text-center">:</th>
                              <td width="40%"> {{number_format($market->salesbyunit($market->id,$unit->uom->id),3,'.','')}} {{$unit->uom->name}}</td>
                           </tr>
                        @endforeach
                     @endif   

                     <tr>
                        <th width="30%">Customer Impact till date</th>
                        <th width="30%" class="text-center">:</th>
                        <td width="40%"></td>
                     </tr>

                  </tbody>
               </table>
            </div>
            <div class="col-md-6">
               <table class="table ">
                  <tbody>

                     <tr>
                        <th width="30%">Designation</th>
                        <th width="30%" class="text-center">:</th>
                        <td width="40%">
                           @if($market->designation) {{$market->designation->name}} @endif
                        </td>
                     </tr>

                     <tr>
                        <th width="30%">Date of Joining</th>
                        <th width="30%" class="text-center">:</th>
                        <td width="40%">
                           @if($market->date_of_joining) {{$market->date_of_joining->format('M d, Y')}} @endif
                        </td>
                     </tr>

                     <tr>
                        <th width="30%">Probation Ended On</th>
                        <th width="30%" class="text-center">:</th>
                        <td width="40%">
                           @if($market->probation_ended_on) {{$market->probation_ended_on->format('M d, Y')}} @endif
                        </td>
                     </tr>
                     
                     <tr>
                        <th width="30%">Probation Ended On</th>
                        <th width="30%" class="text-center">:</th>
                        <td width="40%">
                           @if($market->termination_date) {{$market->termination_date->format('M d, Y')}} @endif
                        </td>
                     </tr>

                     <tr>
                        <th width="30%">Salary Agreed</th>
                        <th width="30%" class="text-center">:</th>
                        <td width="40%">
                           @if($market->salary_agreed) {{ucfirst($market->salary_agreed)}} @endif
                        </td>
                     </tr>

                     <tr>
                        <th width="30%">Salary</th>
                        <th width="30%" class="text-center">:</th>
                        <td width="40%">
                           @if($market->salary) {{number_format($market->salary,2,'.','')}} @endif
                        </td>
                     </tr>

                     <tr>
                        <th width="30%">Party Alert</th>
                        <th width="30%" class="text-center">:</th>
                        <td width="40%">
                           {!! ($market->party_alert==1) ? '<a class="btn btn-sm btn-success text-white">Enable</a>' : '<a class="btn btn-sm btn-danger text-white">Disable</a>' !!}
                        </td>
                     </tr>

                     <tr>
                        <th width="30%">Party Alert Type</th>
                        <th width="30%" class="text-center">:</th>
                        <td width="40%">{{$market->party_alert_type}}</td>
                     </tr>

                     <tr>
                        <th width="30%">Party Alert End Date</th>
                        <th width="30%" class="text-center">:</th>
                        <td width="40%">@if($market->party_alert_end_date!=null) {{$market->party_alert_end_date->format('d M Y')}} @endif</td>
                     </tr>

                     <tr>
                        <th width="30%">Party Size</th>
                        <th width="30%" class="text-center">:</th>
                        <td width="40%">{{$market->party_size}}</td>
                     </tr>

                     <tr>
                        <th width="30%">Supply Point</th>
                        <th width="30%" class="text-center">:</th>
                        <td width="40%">{{($market->supplypoint) ? $market->supplypoint->name : '' }}</td>
                     </tr>

                     <tr>
                        <th width="30%">Membership Type</th>
                        <th width="30%" class="text-center">:</th>
                        <td width="40%">{{$market->membership_type}}</td>
                     </tr>

                     <tr>
                        <th width="30%">Email Subscription</th>
                        <th width="30%" class="text-center">:</th>
                        <td width="40%">
                           {!! ($market->email_subscription==1) ? '<a class="btn btn-sm btn-success text-white">Enable</a>' : '<a class="btn btn-sm btn-danger text-white">Disable</a>' !!}
                        </td>
                     </tr>

                     <tr>
                        <th width="30%">Promotional MSG Subscription</th>
                        <th width="30%" class="text-center">:</th>
                        <td width="40%">
                           {!! ($market->sms_subscription==1) ? '<a class="btn btn-sm btn-success text-white">Enable</a>' : '<a class="btn btn-sm btn-danger text-white">Disable</a>' !!}
                        </td>
                     </tr>

                     <tr>
                        <th width="30%">Read Policy T & C</th>
                        <th width="30%" class="text-center">:</th>
                        <td width="40%">
                           {!! ($market->policy_and_terms==1) ? '<a class="btn btn-sm btn-success text-white">Agreed</a>' : '<a class="btn btn-sm btn-danger text-white">Disagreed</a>' !!}
                        </td>
                     </tr>

                     <tr>
                        <th width="30%">Account Verified by</th>
                        <th width="30%" class="text-center">:</th>
                        <td width="40%"> @if($market->verifiedby) {{$market->verifiedby->name}} @endif </td>
                     </tr>

                     <tr>
                        <th width="30%">Longitude</th>
                        <th width="30%" class="text-center">:</th>
                        <td width="40%">{!!$market->longitude!!}</td>
                     </tr>

                     <tr>
                        <th width="30%">Lattitude</th>
                        <th width="30%" class="text-center">:</th>
                        <td width="40%">{!!$market->latitude!!}</td>
                     </tr>

                     <tr>
                        <th width="30%">Image | Avatar</th>
                        <th width="30%" class="text-center">:</th>
                        <td width="40%">
                           @if(isset($market) && $market->hasMedia('image'))
                              <img class="party-avatar" src="{!! url($market->getFirstMediaUrl('image','full')) !!}">
                           @endif
                        </td>
                     </tr>

                     <tr>
                        <th width="30%">Instruction | Description</th>
                        <th width="30%" class="text-center">:</th>
                        <td width="40%">{!! $market->description !!}</td>
                     </tr>

                     <tr>
                        <th width="30%">Earned Points</th>
                        <th width="30%" class="text-center">:</th>
                        <td width="40%"> <button class="btn btn-info btn-sm ">{!! $market->user->points !!}</button> </td>
                     </tr>

                     <tr>
                        <th width="30%">Referred by</th>
                        <th width="30%" class="text-center">:</th>
                        <td width="40%">{!! $market->referred_by !!}</td>
                     </tr>

                     <tr>
                        <th width="30%">Created By</th>
                        <th width="30%" class="text-center">:</th>
                        <td width="40%">{!! ($market->createdby) ? $market->createdby->name : '' !!}</td>
                     </tr>

                     <tr>
                        <th width="30%">Created Via</th>
                        <th width="30%" class="text-center">:</th>
                        <td width="40%">{!! ucwords(str_replace('_',' ',$market->created_via)) !!}</td>
                     </tr>

                  </tbody>
               </table>
            </div>

            <div class="col-md-12">
               <h6 class="mt-3 mb-4"><b>Additional Address</b></h6>
               <table class="table table-bordered">
                  <thead>
                     <th>Street No</th>
                     <th>Street Type</th>
                     <th>Landmark 1</th>
                     <th>Landmark 2</th>
                     <th>Address Line 1</th>
                     <th>Address Line 2</th>
                     <th>Town</th>
                     <th>City</th>
                     <th>State</th>
                     <th>Pincode</th>
                     <th>Lat.</th>
                     <th>Lon.</th>
                  </thead>
                  <tbody>
                     @if(count($market->user->deliveryaddress) > 0)
                        @foreach($market->user->deliveryaddress as $deliveryaddress)
                           <tr>
                              <td>{{$deliveryaddress->street_no}}</td>
                              <td>{{$deliveryaddress->street_type}}</td>
                              <td>{{$deliveryaddress->landmark_1}}</td>
                              <td>{{$deliveryaddress->landmark_2}}</td>
                              <td>{{$deliveryaddress->address_line_1}}</td>
                              <td>{{$deliveryaddress->address_line_2}}</td>
                              <td>{{$deliveryaddress->town}}</td>
                              <td>{{$deliveryaddress->city}}</td>
                              <td>{{$deliveryaddress->state}}</td>
                              <td>{{$deliveryaddress->pincode}}</td>
                              <td>{{$deliveryaddress->latitude}}</td>
                              <td>{{$deliveryaddress->longitude}}</td>
                           </tr>
                        @endforeach
                     @else 
                        <tr>
                           <td class="text-center" colspan="12"> No Additional Address available </td>
                        </tr>
                     @endif   
                  </tbody>
               </table>
            </div>
         </div>
         <?php */ ?>

         <div class="row">
            <div class="col-md-6">
               <table class="table ">
                  <tbody>
                     <tr>
                        <th width="30%" class="color-grp-1">Party Code</th>
                        <th width="30%" class="text-center">:</th>
                        <td width="40%" class="color-grp-1">{{$market->code}}</td>
                     </tr>
                     <tr>
                        <th width="30%" class="color-grp-1">Party Full Name</th>
                        <th width="30%" class="text-center">:</th>
                        <td width="40%" class="color-grp-1">{{$market->name}}</td>
                     </tr>
                     <tr>
                        <th width="30%" class="color-grp-1">Party Status</th>
                        <th width="30%" class="text-center">:</th>
                        <td width="40%" class="color-grp-1">
                           {!! ($market->status==0) ? '<a class="btn btn-sm btn-success text-white">Active</a>' : '<a class="btn btn-sm btn-danger text-white">Inactive</a>' !!}
                        </td>
                     </tr>
                     <tr>
                        <th width="30%" class="color-grp-1">Party Status Date</th>
                        <th width="30%" class="text-center">:</th>
                        <td width="40%" class="color-grp-1">{{$market->updated_at->format('d M Y')}}</td>
                     </tr>
                     <tr>
                        <th width="30%" class="color-grp-1">Party Type</th>
                        <th width="30%" class="text-center">:</th>
                        <td width="40%" class="color-grp-1">{{($market->party_type) ? $market->party_type->name : '' }}</td>
                     </tr>
                     <tr>
                        <th width="30%" class="color-grp-1">Party Subtype</th>
                        <th width="30%" class="text-center">:</th>
                        <td width="40%" class="color-grp-1">{{($market->party_sub_type) ? $market->party_sub_type->name : '' }}</td>
                     </tr>

                     <!-- <tr>
                        <th width="30%" class="color-grp-2">Title</th>
                        <th width="30%" class="text-center">:</th>
                        <td width="40%" class="color-grp-2"></td>
                     </tr>

                     <tr>
                        <th width="30%" class="color-grp-2">First Name</th>
                        <th width="30%" class="text-center">:</th>
                        <td width="40%" class="color-grp-2"></td>
                     </tr>

                     <tr>
                        <th width="30%" class="color-grp-2">Middle Name</th>
                        <th width="30%" class="text-center">:</th>
                        <td width="40%" class="color-grp-2"></td>
                     </tr>

                     <tr>
                        <th width="30%" class="color-grp-2">Last Name</th>
                        <th width="30%" class="text-center">:</th>
                        <td width="40%" class="color-grp-2"></td>
                     </tr> -->

                     <tr>
                        <th width="30%" class="color-grp-2">DOB</th>
                        <th width="30%" class="text-center">:</th>
                        <td width="40%" class="color-grp-2">@if($market->date_of_birth!=null) {{$market->date_of_birth->format('d M Y')}} @endif</td>
                     </tr>

                     <tr>
                        <th width="30%" class="color-grp-2">Gender</th>
                        <th width="30%" class="text-center">:</th>
                        <td width="40%" class="color-grp-2">{{ucfirst($market->gender)}}</td>
                     </tr>

                     <tr>
                        <th width="30%" class="color-grp-2">Mobile</th>
                        <th width="30%" class="text-center">:</th>
                        <td width="40%" class="color-grp-2">{!!$market->mobile!!}</td>
                     </tr>

                     <tr>
                        <th width="30%" class="color-grp-3">Phone</th>
                        <th width="30%" class="text-center">:</th>
                        <td width="40%" class="color-grp-3">{!!$market->phone!!}</td>
                     </tr>

                     <tr>
                        <th width="30%" class="color-grp-2">Email ID</th>
                        <th width="30%" class="text-center">:</th>
                        <td width="40%" class="color-grp-2">{!!$market->user->email!!}</td>
                     </tr>

                     <tr>
                        <th width="30%" class="color-grp-3">Prefered Language</th>
                        <th width="30%" class="text-center">:</th>
                        <td width="40%" class="color-grp-3"> {!!ucfirst($market->preferred_language)!!} </td>
                     </tr>

                     <tr>
                        <th width="30%" class="color-grp-3">Address Validation</th>
                        <th width="30%" class="text-center">:</th>
                        <td width="40%" class="color-grp-3"></td>
                     </tr>

                     <tr>
                        <th width="30%" class="color-grp-2">Street No</th>
                        <th width="30%" class="text-center">:</th>
                        <td width="40%" class="color-grp-2">{!!$market->street_no!!}</td>
                     </tr>

                     <tr>
                        <th width="30%" class="color-grp-2">Street Name</th>
                        <th width="30%" class="text-center">:</th>
                        <td width="40%" class="color-grp-2">{!!$market->street_name!!}</td>
                     </tr>

                     <tr>
                        <th width="30%" class="color-grp-2">Street Type</th>
                        <th width="30%" class="text-center">:</th>
                        <td width="40%" class="color-grp-2">{!!$market->street_type!!}</td>
                     </tr>

                     <tr>
                        <th width="30%" class="color-grp-3">Landmark 1</th>
                        <th width="30%" class="text-center">:</th>
                        <td width="40%" class="color-grp-3">{!!$market->landmark_1!!}</td>
                     </tr>

                     <tr>
                        <th width="30%" class="color-grp-3">Landmark 2</th>
                        <th width="30%" class="text-center">:</th>
                        <td width="40%" class="color-grp-3">{!!$market->landmark_2!!}</td>
                     </tr>

                     <tr>
                        <th width="30%" class="color-grp-2">Town</th>
                        <th width="30%" class="text-center">:</th>
                        <td width="40%" class="color-grp-2">{!!$market->town!!}</td>
                     </tr>

                     <tr>
                        <th width="30%" class="color-grp-2">City</th>
                        <th width="30%" class="text-center">:</th>
                        <td width="40%" class="color-grp-2">{!!$market->city!!}</td>
                     </tr>

                     <tr>
                        <th width="30%" class="color-grp-2">State</th>
                        <th width="30%" class="text-center">:</th>
                        <td width="40%" class="color-grp-2">{!!$market->state!!}</td>
                     </tr>

                     <tr>
                        <th width="30%" class="color-grp-3">Pincode</th>
                        <th width="30%" class="text-center">:</th>
                        <td width="40%" class="color-grp-3">{!!$market->pincode!!}</td>
                     </tr>

                     <tr>
                        <th width="30%" class="color-grp-3">Address Note</th>
                        <th width="30%" class="text-center">:</th>
                        <td width="40%" class="color-grp-3"></td>
                     </tr>

                     <tr>
                        <th width="30%" class="color-grp-5">Full Address</th>
                        <th width="30%" class="text-center">:</th>
                        <td width="40%" class="color-grp-5"></td>
                     </tr>

                     <tr>
                        <th width="30%" class="color-grp-3">How did you hear about us?</th>
                        <th width="30%" class="text-center">:</th>
                        <td width="40%" class="color-grp-3">{!!$market->hear_about_us!!}</td>
                     </tr>

                     <tr>
                        <th width="30%" class="color-grp-3">Reffered by</th>
                        <th width="30%" class="text-center">:</th>
                        <td width="40%" class="color-grp-3">{!!$market->user->referred_by!!}</td>
                     </tr>

                     <tr>
                        <th width="30%" class="color-grp-1">Customer Level</th>
                        <th width="30%" class="text-center">:</th>
                        <td width="40%" class="color-grp-1">{!!($market->customer_level) ? $market->customer_level->name : '' !!}</td>
                     </tr>

                     <tr>
                        <th width="30%" class="color-grp-1">Customer Stream</th>
                        <th width="30%" class="text-center">:</th>
                        <td width="40%" class="color-grp-1">{!!($market->party_stream) ? $market->party_stream->name : '' !!}</td>
                     </tr>

                     <tr>
                        <th width="30%" class="color-grp-1">Credit Period</th>
                        <th width="30%" class="text-center">:</th>
                        <td width="40%" class="color-grp-1"></td>
                     </tr>

                     <tr>
                        <th width="30%" class="color-grp-1">Orders / Deliveries Till date</th>
                        <th width="30%" class="text-center">:</th>
                        <td width="40%" class="color-grp-1"> {{count($market->salesinvoice) + count($market->user->orders)}} </td>
                     </tr>

                     @if(count($market->saleunitlist) > 0) 
                        @php $i=0 ; @endphp
                        @foreach($market->saleunitlist as $unit)
                        @php $i++; @endphp
                           <tr>
                              <th width="30%" class="color-grp-1">Quantities {{$i}} till Date ({{$unit->uom->name}})</th>
                              <th width="30%" class="text-center">:</th>
                              <td width="40%" class="color-grp-1"> {{number_format($market->salesbyunit($market->id,$unit->uom->id),3,'.','')}} {{$unit->uom->name}}</td>
                           </tr>
                        @endforeach
                     @endif   

                     <tr>
                        <th width="30%" class="color-grp-1">Customer Impact till date</th>
                        <th width="30%" class="text-center">:</th>
                        <td width="40%" class="color-grp-1"></td>
                     </tr>

                  </tbody>
               </table>
            </div>
            <div class="col-md-6">
               <table class="table ">
                  <tbody>

                     <tr>
                        <th width="30%" class="color-grp-6">Designation</th>
                        <th width="30%" class="text-center">:</th>
                        <td width="40%" class="color-grp-6">
                           @if($market->designation) {{$market->designation->name}} @endif
                        </td>
                     </tr>

                     <tr>
                        <th width="30%" class="color-grp-6">Date of Joining</th>
                        <th width="30%" class="text-center">:</th>
                        <td width="40%" class="color-grp-6">
                           @if($market->date_of_joining) {{$market->date_of_joining->format('M d, Y')}} @endif
                        </td>
                     </tr>

                     <tr>
                        <th width="30%" class="color-grp-6">Probation Ended On</th>
                        <th width="30%" class="text-center">:</th>
                        <td width="40%" class="color-grp-6">
                           @if($market->probation_ended_on) {{$market->probation_ended_on->format('M d, Y')}} @endif
                        </td>
                     </tr>
                     
                     <tr>
                        <th width="30%" class="color-grp-6">Termination Date</th>
                        <th width="30%" class="text-center">:</th>
                        <td width="40%" class="color-grp-6">
                           @if($market->termination_date) {{$market->termination_date->format('M d, Y')}} @endif
                        </td>
                     </tr>

                     <tr>
                        <th width="30%" class="color-grp-6">Salary Agreed</th>
                        <th width="30%" class="text-center">:</th>
                        <td width="40%" class="color-grp-6">
                           @if($market->salary_agreed) {{ucfirst($market->salary_agreed)}} @endif
                        </td>
                     </tr>

                     <tr>
                        <th width="30%" class="color-grp-6">Salary</th>
                        <th width="30%" class="text-center">:</th>
                        <td width="40%" class="color-grp-6">
                           @if($market->salary) {{number_format($market->salary,2,'.','')}} @endif
                        </td>
                     </tr>

                     <tr>
                        <th width="30%" class="color-grp-1">Party Alert</th>
                        <th width="30%" class="text-center">:</th>
                        <td width="40%" class="color-grp-1">
                           {!! ($market->party_alert==1) ? '<a class="btn btn-sm btn-success text-white">Enable</a>' : '<a class="btn btn-sm btn-danger text-white">Disable</a>' !!}
                        </td>
                     </tr>

                     <tr>
                        <th width="30%" class="color-grp-1">Party Alert Type</th>
                        <th width="30%" class="text-center">:</th>
                        <td width="40%" class="color-grp-1">{{$market->party_alert_type}}</td>
                     </tr>

                     <tr>
                        <th width="30%" class="color-grp-1">Party Alert End Date</th>
                        <th width="30%" class="text-center">:</th>
                        <td width="40%" class="color-grp-1">@if($market->party_alert_end_date!=null) {{$market->party_alert_end_date->format('d M Y')}} @endif</td>
                     </tr>

                     <tr>
                        <th width="30%" class="color-grp-3">Party Size</th>
                        <th width="30%" class="text-center">:</th>
                        <td width="40%" class="color-grp-3">{{$market->party_size}}</td>
                     </tr>

                     <tr>
                        <th width="30%" class="color-grp-3">Supply Point</th>
                        <th width="30%" class="text-center">:</th>
                        <td width="40%" class="color-grp-3">{{($market->supplypoint) ? $market->supplypoint->name : '' }}</td>
                     </tr>

                     <tr>
                        <th width="30%" class="color-grp-1">Membership Type</th>
                        <th width="30%" class="text-center">:</th>
                        <td width="40%" class="color-grp-1">{{$market->membership_type}}</td>
                     </tr>

                     <tr>
                        <th width="30%" class="color-grp-3">Email Subscription</th>
                        <th width="30%" class="text-center">:</th>
                        <td width="40%" class="color-grp-3">
                           {!! ($market->email_subscription==1) ? '<a class="btn btn-sm btn-success text-white">Enable</a>' : '<a class="btn btn-sm btn-danger text-white">Disable</a>' !!}
                        </td>
                     </tr>

                     <tr>
                        <th width="30%" class="color-grp-3">Promotional MSG Subscription</th>
                        <th width="30%" class="text-center">:</th>
                        <td width="40%" class="color-grp-3">
                           {!! ($market->sms_subscription==1) ? '<a class="btn btn-sm btn-success text-white">Enable</a>' : '<a class="btn btn-sm btn-danger text-white">Disable</a>' !!}
                        </td>
                     </tr>

                     <tr>
                        <th width="30%" class="color-grp-6">Read Policy T & C</th>
                        <th width="30%" class="text-center">:</th>
                        <td width="40%" class="color-grp-6">
                           {!! ($market->policy_and_terms==1) ? '<a class="btn btn-sm btn-success text-white">Agreed</a>' : '<a class="btn btn-sm btn-danger text-white">Disagreed</a>' !!}
                        </td>
                     </tr>

                     <tr>
                        <th width="30%" class="color-grp-5">Account Verified by</th>
                        <th width="30%" class="text-center">:</th>
                        <td width="40%" class="color-grp-5"> @if($market->verifiedby) {{$market->verifiedby->name}} @endif </td>
                     </tr>

                     <tr>
                        <th width="30%" class="color-grp-5">Longitude</th>
                        <th width="30%" class="text-center">:</th>
                        <td width="40%" class="color-grp-5">{!!$market->longitude!!}</td>
                     </tr>

                     <tr>
                        <th width="30%" class="color-grp-5">Lattitude</th>
                        <th width="30%" class="text-center">:</th>
                        <td width="40%" class="color-grp-5">{!!$market->latitude!!}</td>
                     </tr>

                     <tr>
                        <th width="30%" class="color-grp-3">Image | Avatar</th>
                        <th width="30%" class="text-center">:</th>
                        <td width="40%" class="color-grp-3">
                           @if(isset($market) && $market->hasMedia('image'))
                              <img class="party-avatar" src="{!! url($market->getFirstMediaUrl('image','full')) !!}">
                           @endif
                        </td>
                     </tr>

                     <tr>
                        <th width="30%" class="color-grp-3">Instruction | Description</th>
                        <th width="30%" class="text-center">:</th>
                        <td width="40%" class="color-grp-3">{!! $market->description !!}</td>
                     </tr>

                     <tr>
                        <th width="30%">Earned Points</th>
                        <th width="30%" class="text-center">:</th>
                        <td width="40%"> <button class="btn btn-info btn-sm ">{!! $market->user->points !!}</button> </td>
                     </tr>

                     <!-- <tr>
                        <th width="30%">Referred by</th>
                        <th width="30%" class="text-center">:</th>
                        <td width="40%">{!! $market->referred_by !!}</td>
                     </tr> -->

                     <tr>
                        <th width="30%">Created By</th>
                        <th width="30%" class="text-center">:</th>
                        <td width="40%">{!! ($market->createdby) ? $market->createdby->name : '' !!}</td>
                     </tr>

                     <tr>
                        <th width="30%">Created Via</th>
                        <th width="30%" class="text-center">:</th>
                        <td width="40%">{!! ucwords(str_replace('_',' ',$market->created_via)) !!}</td>
                     </tr>

                  </tbody>
               </table>
            </div>

            <div class="col-md-12">
               <h6 class="mt-3 mb-4"><b>Additional Address</b></h6>
               <table class="table table-bordered">
                  <thead>
                     <th>Street No</th>
                     <th>Street Type</th>
                     <th>Landmark 1</th>
                     <th>Landmark 2</th>
                     <th>Address Line 1</th>
                     <th>Address Line 2</th>
                     <th>Town</th>
                     <th>City</th>
                     <th>State</th>
                     <th>Pincode</th>
                     <th>Lat.</th>
                     <th>Lon.</th>
                  </thead>
                  <tbody>
                     @if(count($market->user->deliveryaddress) > 0)
                        @foreach($market->user->deliveryaddress as $deliveryaddress)
                           <tr>
                              <td>{{$deliveryaddress->street_no}}</td>
                              <td>{{$deliveryaddress->street_type}}</td>
                              <td>{{$deliveryaddress->landmark_1}}</td>
                              <td>{{$deliveryaddress->landmark_2}}</td>
                              <td>{{$deliveryaddress->address_line_1}}</td>
                              <td>{{$deliveryaddress->address_line_2}}</td>
                              <td>{{$deliveryaddress->town}}</td>
                              <td>{{$deliveryaddress->city}}</td>
                              <td>{{$deliveryaddress->state}}</td>
                              <td>{{$deliveryaddress->pincode}}</td>
                              <td>{{$deliveryaddress->latitude}}</td>
                              <td>{{$deliveryaddress->longitude}}</td>
                           </tr>
                        @endforeach
                     @else 
                        <tr>
                           <td class="text-center" colspan="12"> No Additional Address available </td>
                        </tr>
                     @endif   
                  </tbody>
               </table>
            </div>
         </div>

      </section>


      <section id="content2">
         <div class="row">
            <div class="col-md-3 form-group">
               <div id="reportrange"  class="pull-left" style="background: #fff; cursor: pointer; padding: 7px 10px; border: 1px solid #ccc;">
                  <i class="glyphicon glyphicon-calendar fa fa-calendar"></i>&nbsp;
                  <span></span> 
               </div>
            </div>
            <div class="col-md-3 form-group">
               {!! Form::select('transaction', $transactions, null, ['class' => 'select2 form-control', 'id' => 'transaction','onchange' => 'cb(start,end)']) !!}
            </div>
            <div class="col-md-5 text-right">

               <!--<button id="report-t" value="export" class="btn export-buttons btn-primary btn-sm">Excel Download <span><i class="fa fa-file-excel-o"></i></span> </button>
                  &nbsp;&nbsp;&nbsp;-->

               <button id="report-t" value="download" class="btn export-buttons btn-danger btn-sm" title="Download PDF"> 
                  <span><i class="fa fa-file-pdf-o" style="color:#fff;"></i></span>
               </button>

               &nbsp;&nbsp;&nbsp;
               
               <button id="report-t" value="print" class="btn export-buttons btn-primary btn-sm" title="Print"> 
                  <span><i class="fa fa-print" style="color:#fff;"></i></span>
               </button>

            </div>
         </div>
         @include('markets.transaction_table')
      </section>


      <section id="content3">
         <div class="row">

            <div class="col-md-3 form-group">
               <div id="reportrange2"  class="pull-left" style="background: #fff; cursor: pointer; padding: 7px 10px; border: 1px solid #ccc;">
                  <i class="glyphicon glyphicon-calendar fa fa-calendar"></i>&nbsp;
                  <span></span> 
               </div>
            </div>

            <div class="col-md-8 text-right">
               <!-- <button id="report-l" value="export" class="btn export-buttons btn-success btn-sm" title="Download Excel"> 
                  <span><i class="fa fa-file-excel-o"></i></span> 
               </button>
               &nbsp;&nbsp;&nbsp; --> 
               <button id="report-l" value="download" class="btn export-buttons btn-danger btn-sm" title="Download PDF">
                  <span><i class="fa fa-file-pdf-o"></i></span> 
               </button>
               &nbsp;&nbsp;&nbsp;
               <button id="report-l" value="print" class="btn export-buttons btn-primary btn-sm" title="Print">
                  <span><i class="fa fa-print"></i></span> 
               </button>
            </div>

         </div>
         @include('markets.ledger_statement')
      </section>


      <section id="content4">
         <div class="row">
            <div class="col-md-4 form-group">
               <div id="reportrange1"  class="pull-left" style="background: #fff; cursor: pointer; padding: 7px 10px; border: 1px solid #ccc;">
                  <i class="glyphicon glyphicon-calendar fa fa-calendar"></i>&nbsp;
                  <span></span> 
               </div>
            </div>
            <div class="col-md-8 text-right">
               <!-- <button id="report-r" value="export" class="btn export-buttons btn-success btn-sm" title="Download Excel">
                  <span><i class="fa fa-file-excel-o"></i></span>
               </button>
               &nbsp;&nbsp;&nbsp; -->
               <button id="report-r" value="download" class="btn export-buttons btn-danger btn-sm" title="Download PDF">
                  <span><i class="fa fa-file-pdf-o"></i></span>
               </button>
               &nbsp;&nbsp;&nbsp;
               <button id="report-r" value="print" class="btn export-buttons btn-primary btn-sm" title="Print">
                  <span><i class="fa fa-print"></i></span>
               </button>
            </div>
         </div>
         @include('markets.reward_activity') 
      </section>


      <section id="content5">
         <div class="row">
            <div class="col-md-6 market-notes" style="overflow-y: scroll; height:400px;"></div>
            <div class="col-md-6">
               {!! Form::open(['route' => 'marketNotes.store','class' => 'market-notes-form']) !!}
                  {!! Form::hidden('market_id', $market->id) !!}
                  <div class="form-group">
                     <label>Notes</label>
                     <textarea id="notes" name="notes" rows="10" cols="50" class="form-control"></textarea>
                  </div>
                  <div class="form-group">
                     <button type="submit" class="btn btn-sm btn-success market-notes-form-submit">Save Notes</button>
                  </div>
               {!! Form::close() !!}
            </div>
         </div>
      </section>

      <section id="content6">
         <div class="row">

            @can('marketActivity.index')
               
               <div class="col-md-3">
                  <div class="form-group">
                     {!! Form::label('activity_date', 'Activity Date', ['class' => ' control-label text-right']) !!}
                     {!! Form::text('activity_date', null,  ['class' => 'form-control act_filter datepicker', 'readonly' => 'readonly']) !!}
                  </div>
               </div>

               <div class="col-md-3">
                  <div class="form-group">
                     {!! Form::label('activity_status', 'Activity Status', ['class' => ' control-label text-right']) !!}
                     {!! Form::select('activity_status', [null => 'All','pending'=>'Pending','completed'=>'Completed','cancelled'=>'Cancelled'], null, ['class' => 'select2 form-control act_filter']) !!}
                  </div>
               </div>

               <div class="col-md-3">
                  <div class="form-group">
                     {!! Form::label('activity_priority', 'Activity Priority', ['class' => ' control-label text-right']) !!}
                     {!! Form::select('activity_priority', [null => 'All','low'=>'Low','medium'=>'Medium','high'=>'High'], null, ['class' => 'select2 form-control act_filter']) !!}
                  </div>
               </div>

               <div class="col-md-3">
                  @can('marketActivity.create')
                     <div class="col-md-12 mt-4">
                        <button data-toggle="modal" data-target="#marketActivityModal" class="btn btn-sm btn-primary float-right"> + Add New Activity </button>
                     </div>
                  @endcan
               </div>

            @endcan

            @can('marketActivity.index')

               <table class="table table-bordered party-activity-table" width="100%">
                  <thead>
                     <tr>
                        <th>Date  & Time</th>
                        <th>Assigned By</th>
                        <th>Assigned To</th>
                        <th>Action & Summary</th>
                        <th>Status</th>
                        <th>Priority</th>
                        <th>Updated At</th>
                        <th>Action</th>
                     </tr>
                  </thead>
               </table>   
               <!-- <div class="col-md-12 market-activity"></div> -->
            @endcan   
         </div>
      </section>


   </main>
   <div style="clear:both;height:30px;"></div>
</div>


<!-- Modal -->
<div id="marketActivityModal" class="modal fade" role="dialog">
  <div class="modal-dialog modal-lg">

    <!-- Modal content-->
    <div class="modal-content">
      {!! Form::open(['route'=>'marketActivity.store', 'class' => 'market-activity-form']) !!}
         <div class="modal-body">
            <div class="row">
               @include('market_activity.fields')    
            </div>
         </div>
         <div class="modal-footer">
            <button type="submit" class="btn btn-success market-activity-form-submit"> <i class="fa fa-save"></i> Save Activity </button>
            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
         </div>
      {!! Form::close() !!}
    </div>

  </div>
</div>


@push('scripts')

<script>
   function marketNotes() {
      //refresh counts
      var url = "{!!route('marketNotes.index')!!}";
      var token = "{{ csrf_token() }}";
      $.ajax({
          type: 'GET',
          data: {
              '_token': token,
              'market_id': "{{$market->id}}"
          },
          url: url,
          success: function (response) {
             $('.market-notes').html(response.data);
          }
      });
   }
   marketNotes();

   /*function marketActivity() {
      //refresh counts
      var url = "{!!route('marketActivity.index')!!}";
      var token = "{{ csrf_token() }}";
      $.ajax({
          type: 'GET',
          data: {
              '_token': token,
              'market_id': "{{$market->id}}",
              'type': 'grid'
          },
          url: url,
          success: function (response) {
             $('.market-activity').html(response.data);
          }
      });
   }
   marketActivity();*/

   var activity_table = $('.party-activity-table').DataTable({
       processing: true,
       serverSide: true,
       pageLength:20,
       ajax: "{{ route('marketActivity.index') }}?type=list&market_id={{$market->id}}&date="+$('#activity_date').val()+"&status="+$('#activity_status').val()+"&priority="+$('#activity_priority').val(),
       columns: [
           {data: 'date', name: 'date', orderable: false, searchable: true, class: "text-left"},
           {data: 'assigned_by', name: 'assigned_by', orderable: false, searchable: true, class: "text-left"},
           {data: 'assign_to', name: 'assign_to', orderable: false, searchable: true, class: "text-left"},
           {data: 'action_summary', name: 'action_summary', orderable: false, searchable: true, class: "text-left"},
           {data: 'status', name: 'status', orderable: false, searchable: true, class: "text-center"},
           {data: 'priority', name: 'priority', orderable: false, searchable: true, class: "text-center"},
           {data: 'updated_at', name: 'updated_at', orderable: false, searchable: true, class: "text-center"},
           {data: 'action', name: 'action', orderable: false, searchable: true, class: "text-center"},
       ]
   });

   $('.act_filter').change(function() {
         activity_table.ajax.url("{{ route('marketActivity.index') }}?type=list&market_id={{$market->id}}&date="+$('#activity_date').val()+"&status="+$('#activity_status').val()+"&priority="+$('#activity_priority').val()).load(function(result) {});
   });

   function activityStatusupdate(elem) {
      var id     = $(elem).data('id');
      var status = $(elem).data('status');
      var url     = '{!!  route('marketActivity.update', [':activityID']) !!}';
      url         = url.replace(':activityID', id);
      var token   = "{{csrf_token()}}";

      $.ajax({
         type: 'POST',
         data: {
             '_token': token,
             '_method': 'PATCH',
             'status': status,
             'type': 'status-update'
         },
         url: url,
         success: function (response) {
             if(response.status=='success') {
                 iziToast.success({
                     backgroundColor: 'linear-gradient(to right, #44a08d, #093637)',
                     messageColor: '#fff',
                     timeout: 3000, 
                     icon: 'fa fa-check', 
                     position: "bottomRight", 
                     iconColor:'#fff',
                     message: response.data
                 });
             } else {
                 iziToast.success({
                     backgroundColor: 'linear-gradient(to right, #ff4b1f, #ff9068)', 
                     messageColor: '#fff', 
                     timeout: 3000, 
                     icon: 'fa fa-remove', 
                     position: "bottomRight", 
                     iconColor:'#fff', 
                     message: response.data
                 });
             }
             activity_table.ajax.reload();
         }
     });
   }

</script>

@endpush