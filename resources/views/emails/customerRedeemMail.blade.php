        
        @include('emails.header')
        
          <div id="child_content1">
            <h2>Remainder For 20 Rupees worth of points to redeem</h2>
            <hr>
            <p class="child_msg">Hi, {{ $details['customer_name'] }}, You have 20 Rupees worth of points to redeem. New and Fresh Products are available.</p>
          </div>
        
        @include('emails.footer') 