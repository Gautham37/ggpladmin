        @include('emails.header')
        
          <div id="child_content1">
            <h2>Sales Invoice Details</h2>
            <hr>
            <p class="child_msg">Hi, {{ $details['customer_name'] }}, Your Sales Invoice has been created.</p>
            <table cellpadding="6" class="child_msg1">
              <tr>
                <td style="width:40%"><b>Sales Invoice Date: </b></td>
                <td style="width:60%"><b>{{ $details['sales_date'] }}</b></td>
              </tr> 
              <tr>
                <td style="width:40%"><b>Sales Invoice No: </b></td>
                <td style="width:60%"><b>{{ $details['sales_code'] }}</b></td>
              </tr> 
              <tr>
                <td style="width:40%"><b>Sales Invoice Amount: </b></td>
                <td style="width:60%"><b>{{ $details['total_sales_amount'] }}</b></td>
              </tr> 
            </table>
          </div>
        
        @include('emails.footer')
