        @include('emails.header')
        
          <div id="child_content1">
            <h2>User Registration Details</h2>
            <hr>
            <p class="child_msg">Hi, {{ $details['customer_name'] }}, Your {{setting('app_name')}} Registration has completed successfully.</p>
            <table cellpadding="6" class="child_msg1">
              <tr>
                <td style="width:40%"><b>Username: </b></td>
                <td style="width:60%"><b>{{ $details['customer_name'] }}</b></td>
              </tr> 
              <tr>
                <td style="width:40%"><b>Email Address: </b></td>
                <td style="width:60%"><b>{{ $details['customer_mail'] }}</b></td>
              </tr> 
            </table>
          </div>
        
        @include('emails.footer')

