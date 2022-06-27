        @include('emails.header')
        
          <div id="child_content1">
            <h2>Purchase Return Details</h2>
            <hr>
            <p class="child_msg">Hi, {{ $details['customer_name'] }}, Your Purchase Return has been created.</p>
             <table cellpadding="6" class="child_msg1">
              <tr>
                <td style="width:40%"><b>Purchase Return Date: </b></td>
                <td style="width:60%"><b>{{ $details['purchase_date'] }}</b></td>
              </tr> 
              <tr>
                <td style="width:40%"><b>Purchase Return No: </b></td>
                <td style="width:60%"><b>{{ $details['purchase_code'] }}</b></td>
              </tr> 
              <tr>
                <td style="width:40%"><b>Purchase Return Amount: </b></td>
                <td style="width:60%"><b>{{ $details['total_purchase_amount'] }}</b></td>
              </tr> 
            </table>
          </div>
        
        @include('emails.footer')  
