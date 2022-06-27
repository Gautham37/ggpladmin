<style>
   
#invoice-POS {
    box-shadow: 0 0 1in -0.25in rgba(0, 0, 0, 0.5);
    padding: 2mm;
    margin: 0 auto;
    width: 50.8mm;
    background: #fff;
}
 #invoice-POS ::selection {
    background: #f31544;
    color: #fff;
}
 #invoice-POS ::moz-selection {
    background: #f31544;
    color: #fff;
}
 #invoice-POS h1 {
    font-size: 1.5em;
    color: #222;
}
 #invoice-POS h2 {
    font-size: 0.9em;
}
 #invoice-POS h3 {
    font-size: 1.2em;
    font-weight: 300;
    line-height: 2em;
}
 #invoice-POS p {
    font-size: 0.7em;
    color: #666;
    line-height: 1.2em;
}
 #invoice-POS #top, #invoice-POS #mid, #invoice-POS #bot {
   /* Targets all id with 'col-' */
    border-bottom: 1px solid #eee;
}

#invoice-POS #mid {
   margin-bottom: 10px;
}

#invoice-POS #bot {
    min-height: 50px;
}
 #invoice-POS #top .logo {
    height: 60px;
    width: 60px;
    background: url(http://michaeltruong.ca/images/logo1.png) no-repeat;
    background-size: 60px 60px;
}
 #invoice-POS .clientlogo {
    float: left;
    height: 60px;
    width: 60px;
    background: url(http://michaeltruong.ca/images/client.jpg) no-repeat;
    background-size: 60px 60px;
    border-radius: 50px;
}
 #invoice-POS .info {
    display: block;
    margin-left: 0;
    font-size: 12px;
}
 #invoice-POS .title {
    float: right;
}
 #invoice-POS .title p {
    text-align: right;
}
 #invoice-POS table {
    width: 100%;
    border-collapse: collapse;
}
 #invoice-POS .tabletitle {
    font-size: 0.5em;
    /*background: #eee;*/
    border: 1px solid #eee;
}
 #invoice-POS .service {
    border-bottom: 1px solid #eee;
}
 #invoice-POS .item {
    width: 24mm;
}
 #invoice-POS .itemtext {
    font-size: 0.5em;
}
 #invoice-POS #legalcopy {
    margin-top: 5mm;
}
.text-right {
   text-align: right;
}
#invoice-POS .tabletitle td {
   padding: 3px 2px;
}
.tableitem  {
   padding: 3px 2px;
}
</style>
  <div id="invoice-POS">
    
    <center id="top">
      <div class="info"> 
        <h2>{{setting('app_name')}}</h2>
        <p> 
           {{setting('app_store_address_line_1')}}
           {{setting('app_store_address_line_2')}} 
           {{setting('app_store_city')}}, 
           {{setting('app_store_pincode')}}.
           {{setting('app_store_state')}}, {{setting('app_store_country')}},
           <br>
           <b>Mobile :</b> {{setting('app_store_phone_no')}} 
           <br>
           <b>GSTIN : </b> {{setting('app_store_gstin')}}
        </p>
      </div><!--End Info-->
    </center><!--End InvoiceTop-->
    
    <div id="mid">
      <div id="table" style="display: flex;">

         <table style="width:50%;">
            <tr class="service">
               <td colspan="2" class="tableitem"><p class="itemtext">{{$purchase_invoice->paymentmethod->name}} Payment</p></td>
            </tr>
            <tr class="service">
               <td class="tableitem"><p class="itemtext">Bill To</p></td>
               <td class="tableitem"><p class="itemtext">{{$purchase_invoice->market->name}}</p></td>
            </tr>
            <tr class="service">
               <td class="tableitem"><p class="itemtext">Bill No</p></td>
               <td class="tableitem"><p class="itemtext">{{$purchase_invoice->code}}</p></td>
            </tr>
         </table>

         <table style="width:50%;">
            <tr class="service">
               <td class="tableitem"><p class="itemtext">User</p></td>
               <td class="tableitem"><p class="itemtext">{{$purchase_invoice->createdby->name}}</p></td>
            </tr>
            <tr class="service">
               <td class="tableitem"><p class="itemtext">Date</p></td>
               <td class="tableitem"><p class="itemtext">{{$purchase_invoice->date->format('d/m/Y')}}</p></td>
            </tr>
            <tr class="service">
               <td class="tableitem"><p class="itemtext">Time</p></td>
               <td class="tableitem"><p class="itemtext">{{$purchase_invoice->created_at->format('H:i:s')}}</p></td>
            </tr>
         </table>

      </div><!--End Table-->
    </div><!--End Invoice Mid-->
    
    <div id="bot">

               <div id="table">
                  <table>
                     <tr class="tabletitle">
                        <td class="item">Product</td>
                        <td class="Hours">Rate</td>
                        <td class="Hours">Qty</td>
                        <td class="Rate">Amount</td>
                     </tr>
                     @if(count($purchase_invoice->items) > 0)
                        @foreach($purchase_invoice->items as $item)
                           <tr class="service">
                              <td class="tableitem"><p class="itemtext">{{$item->product_name}}</p></td>
                              <td class="tableitem"><p class="itemtext">{{number_format($item->unit_price,2,'.','')}}</p></td>
                              <td class="tableitem"><p class="itemtext">{{number_format($item->quantity,3,'.','')}} {{$item->uom->name}}</p></td>
                              <td class="tableitem text-right"><p class="itemtext">{{number_format($item->amount,2,'.','')}}</p></td>
                           </tr>
                        @endforeach
                     @endif
                     
                     <tr class="tabletitle">
                        <td colspan="3" class="Rate text-right">Sub Total</td>
                        <td class="payment text-right"><b>{{number_format($purchase_invoice->sub_total,2,'.','')}}</b></td>
                     </tr>

                     <tr class="tabletitle">
                        <td colspan="3" class="Rate text-right">Discount</td>
                        <td class="payment text-right"><b>{{number_format($purchase_invoice->discount_total,2,'.','')}}</b></td>
                     </tr>

                     <tr class="tabletitle">
                        <td colspan="3" class="Rate text-right">GST</td>
                        <td class="payment text-right"><b>{{number_format($purchase_invoice->tax_total,2,'.','')}}</b></td>
                     </tr>

                     <tr class="tabletitle">
                        <td colspan="3" class="Rate text-right">Delivery Charge</td>
                        <td class="payment text-right"><b>{{number_format($purchase_invoice->delivery_charge,2,'.','')}}</b></td>
                     </tr>

                     <tr class="tabletitle">
                        <td colspan="3" class="Rate text-right"><b>Net Amount</b></td>
                        <td class="payment text-right"><b>{{number_format($purchase_invoice->total,2,'.','')}}</b></td>
                     </tr>

                     <tr class="tabletitle">
                        <td colspan="4" class="Rate text-right">{{$words}}</td>
                     </tr>

                  </table>
               </div><!--End Table-->

               <div id="legalcopy">
                  <p class="legal"><strong>Thank you for your purchase!</strong></p>
               </div>

            </div><!--End InvoiceBot-->
  </div><!--End Invoice-->
