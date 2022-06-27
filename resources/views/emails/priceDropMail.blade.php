        @include('emails.header')
        
          <div id="child_content1">
            <h2>Product Price Drop Details</h2>
            <hr>
            <p class="child_msg">Hi, {{ $details['customer_name'] }}, Product Price has been dropped.</p>
            <table cellpadding="6" class="child_msg1">
              <tr>
                <td style="width:40%"><b>Product Name: </b></td>
                <td style="width:60%"><b>{{ $details['product_name'] }}</b></td>
              </tr> 
              <tr>
                <td style="width:40%"><b>Product Price: </b></td>
                <td style="width:60%"><b>₹ {{ $details['product_price'] }}</b></td>
              </tr> 
            </table>
          </div>
        
        @include('emails.footer')   

