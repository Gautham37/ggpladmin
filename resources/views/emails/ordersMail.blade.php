        @include('emails.header')
       
          <div id="child_content1">
            
            <p class="child_msg">Hi {{ $details['customer_name'] }}, Order has been placed Successfully.</p>
            
            <p style="text-align:left;"><b>Order Number:</b> {{$details['order']->id}}</p>
            <p style="text-align:left;"><b>Order Date:</b> {{date('d-m-Y',strtotime($details['order']->created_at))}}</p>
             @if($details['order_delivery_address']!='')
             <p style="text-align:left;"><b>Delivery Address:</b> {{$details['order_delivery_address']->address_line_1}} {{$details['order_delivery_address']->address_line_2}} {{$details['order_delivery_address']->city}}, {{$details['order_delivery_address']->state}} - {{$details['order_delivery_address']->pincode}}</p>
             @endif
             <hr>
            <h2>Order Details</h2>
            <table cellpadding="6" class="orderstable" style="width:100%;border: 1px solid grey;border-collapse: collapse;">
              <tr>
                <td style="width:20%;border: 1px solid grey;"><b>product</b></td>
                <td style="width:20%;border: 1px solid grey;"><b>Price</b></td>
                <td style="width:20%;border: 1px solid grey;"><b>Quantity</b></td>
                <td style="width:20%;border: 1px solid grey;"><b>Tax</b></td>
                <td style="width:20%;border: 1px solid grey;"><b>Total</b></td>
              <tr> 
            @foreach($details['product_orders'] as $val)

            @php
            $subtotal = $val->price*$val->quantity;
            @endphp
              <tr>
                <td style="width:20%;border: 1px solid grey;">{{$val->name}}</td>
                <td style="width:20%;border: 1px solid grey;">{{$val->price}}</td>
                <td style="width:20%;border: 1px solid grey;">{{$val->quantity}} {{$val->unit}}</td>
                <td style="width:20%;border: 1px solid grey;">{{$details['order']->tax}} ({{$val->tax}}%)</td>
                <td style="width:20%;border: 1px solid grey;">₹ {{$subtotal}}</td>
              </tr> 
             @endforeach
             @if($details['order']->delivery_fee>0)
              <tr>
                <td colspan="4" style="border: 1px solid grey;"><b>Delivery Charge</b></td>               
                <td style="width:20%;border: 1px solid grey;">₹ {{$details['order']->delivery_fee}}</td>
              </tr>
             @endif
              @if($details['order']->redeem_amount>0)
              <tr>
                <td colspan="4" style="border: 1px solid grey;"><b>Redeem Amount</b></td>               
                <td style="width:20%;border: 1px solid grey;">₹ {{$details['order']->redeem_amount}}</td>
              </tr>
             @endif
              @if($details['order']->coupon_amount>0)
              <tr>
                <td colspan="4" style="border: 1px solid grey;"><b>Coupon Discount</b></td>               
                <td style="width:20%;border: 1px solid grey;">₹ {{$details['order']->coupon_amount}}</td>
              </tr>
             @endif
             <tr>
                <td colspan="4" style="border: 1px solid grey;"><b>Total</b></td>               
                <td style="width:20%;border: 1px solid grey;">₹ {{$details['order']->order_amount}}</td>
              </tr>
            </table>
            
          </div>
          
        @include('emails.footer')  
