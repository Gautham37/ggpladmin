<div style="width:70%; margin:0 auto;">
@include('emails.header')
          
  <div id="child_content1">
    
    <h2>{{ $emailnotifications->name }}</h2>
    <hr>
    <!--<p class="child_msg">Hi {{ setting('app_name') }},</p>-->
    {!! $emailnotifications->description !!}
    <br>
  </div>
  
@include('emails.footer')
</div>