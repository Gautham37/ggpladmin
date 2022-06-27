        @include('emails.header')
        
          <div id="child_content1">
            <h2>{{ $details['party_type'] }} Details</h2>
            <hr>
            <p class="child_msg">Hi, {{ $details['customer_name'] }}, Your {{ $details['party_type'] }} Creation  has completed successfully.</p>
            
            <table cellpadding="6" class="child_msg1">
              <tr>
                <td style="width:40%"><b>Name: </b></td>
                <td style="width:60%"><b>{{ $details['customer_name'] }}</b></td>
              <tr> 
              <tr>
                <td style="width:40%"><b>Email Address: </b></td>
                <td style="width:60%"><b>{{ $details['customer_mail'] }}</b></td>
              <tr> 
            </table>
            
          </div>
          
        @include('emails.footer')  
