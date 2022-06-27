        @include('emails.header')
          
          <div id="child_content1">
            
            <h2>{{ $details['subject'] }}</h2>
                <hr>
            <p class="child_msg">Hi {{ $details['customer_name'] }},</p>
                {!! $details['body_content'] !!}
            <br>
            
          </div>
          
        @include('emails.footer')  

