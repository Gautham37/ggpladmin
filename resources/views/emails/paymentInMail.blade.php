        @include('emails.header')
        
          <div id="child_content1">
            <h2>Payment In Details</h2>
            <hr>
            <p class="child_msg">Hi, {{ $details['customer_name'] }}, Your Payment In has been created.</p>
            <table cellpadding="6" class="child_msg1">
              <tr>
                <td style="width:40%"><b>Payment Date: </b></td>
                <td style="width:60%"><b>{{ $details['payment_in_date'] }}</b></td>
              </tr> 
              <tr>
                <td style="width:40%"><b>Payment No: </b></td>
                <td style="width:60%"><b>{{ $details['payment_in_no'] }}</b></td>
              </tr> 
              <tr>
                <td style="width:40%"><b>Payment Amount: </b></td>
                <td style="width:60%"><b>{{ $details['payment_in_amount'] }}</b></td>
              </tr> 
            </table>
          </div>
          
        @include('emails.footer')  

