        @include('emails.header')
        
          <div id="child_content1">
            <h2>Delivery Status Details</h2>
            <hr>
            <p class="child_msg">Hi, {{ $details['customer_name'] }}, Delivery is {{ $details['delivery_status'] }}.</p>
          </div>
        
        @include('emails.footer')     

