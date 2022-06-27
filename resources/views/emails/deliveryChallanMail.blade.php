            
        @include('emails.header')        
                    
          <div id="child_content1">
             
             <h2>Delivery Challan Details</h2>
             <hr>
             <p class="child_msg">Hi, {{ $details['customer_name'] }}, Your Delivery Challan has been created.</p>
             
             <table cellpadding="6" class="child_msg1">
              <tr>
              <td style="width:40%"><b>Delivery Date: </b></td>
              <td style="width:60%"><b>{{ $details['delivery_date'] }}</b></td>
              </tr> 
               <tr>
              <td style="width:40%"><b>Delivery No: </b></td>
              <td style="width:60%"><b>{{ $details['delivery_code'] }}</b></td>
              </tr>
              <tr>
              <td style="width:40%"><b>Delivery Amount: </b></td>
              <td style="width:60%"><b>{{ $details['total_delivery_amount'] }}</b></td>
              </tr>
            </table>
            
          </div>
        
        @include('emails.footer')   
        