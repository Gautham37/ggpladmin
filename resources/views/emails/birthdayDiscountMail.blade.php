        @include('emails.header')
        
          <div id="child_content1">
          <h2>Birthday Special Discount</h2>
          
            <hr>
            <p class="child_msg">Hi, {{ $details['customer_name'] }}, You have birthday special 10% discount.</p>
            
          </div>
          
        @include('emails.footer')   