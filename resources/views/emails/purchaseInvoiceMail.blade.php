        @include('emails.header')
        
          <div id="child_content1">
            <h2>Purchase Invoice Details</h2>
            <hr>
            <p class="child_msg">Hi, {{ $details['customer_name'] }}, Your Purchase Invoice has been created.</p>
            <table cellpadding="6" class="child_msg1">
              <tr>
                <td style="width:40%"><b>Purchase Invoice Date: </b></td>
                <td style="width:60%"><b>{{ $details['purchase_date'] }}</b></td>
              </tr> 
              <tr>
                <td style="width:40%"><b>Purchase Invoice No: </b></td>
                <td style="width:60%"><b>{{ $details['purchase_code'] }}</b></td>
              </tr>
              <tr>
                <td style="width:40%"><b>Purchase Invoice Amount: </b></td>
                <td style="width:60%"><b>{{ $details['total_purchase_amount'] }}</b></td>
              </tr>
            </table>
          </div>
        
        @include('emails.footer')
         

