        
        @include('emails.header')
        
          <div id="child_content1">
            <h2>Remainder for purchase</h2>
            <hr>
            <p class="child_msg">Hi, {{ $details['customer_name'] }}, New and Fresh Products are available.</p>
          </div>
          
        @include('emails.footer')  