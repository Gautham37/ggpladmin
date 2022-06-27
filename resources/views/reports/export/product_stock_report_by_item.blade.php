<style>
	body {
		font-size: 12px;
		font-family: Arial, Helvetica, sans-serif;
	}
	.text-right {
		text-align: right;
	}
	.text-left {
		text-align: left;
	}
	.detail-table {
		padding: 5px;
		border: 1px solid #000;
		width: 40%;
		float: right;
	}
	.report-table {
		width: 100%;
		margin-top:80px;
	}

	/* Center tables for demo */
	table {
	  margin: 0 auto;
	}

	/* Default Table Style */
	table {
	  color: #333;
	  background: white;
	  border-bottom: 1px solid grey;
	  border-collapse: collapse;
	}
	table thead th,
	table tfoot th {
	  color: #777;
	  background: rgba(0,0,0,.1);
	}
	table caption {
	  padding:.5em;
	}
	table th {
	  padding: 5px;
	  border-bottom: 1px solid lightgrey;
	  font-weight: bold;
	}
	table td {
	  padding: 5px;
	  border-bottom: 1px solid lightgrey;
	  text-align: center;
	}

    .pdf_head
    {
        font-size:36px;
        color:<?= (setting('app_invoice_color')!='') ? setting('app_invoice_color')  : 'green'  ; ?>;
        font-weight:700;
        line-height: 35px;
    }
</style>
<table class="table"  cellpadding="5" style="width: 100%;background-color:#f4f4f533;">
   <tbody>
      <tr>
         <td style="width: 4%;">
            <img src="{{$app_logo}}" alt="{{setting('app_name')}}" style="width:100px; height:100px;" >
         </td>
         <td style="width: 96%;">
            <span class="pdf_head" > {{ setting('app_name') }}</span><br>
            <span style="font-size:12px;line-height: 25px;">
              {{setting('app_store_address_line_1')}}
              {{setting('app_store_address_line_2')}} 
              {{setting('app_store_city')}}, 
              {{setting('app_store_pincode')}}.
              {{setting('app_store_state')}}, {{setting('app_store_country')}},
              <br>
              <b>{{ trans('lang.market_mobile') }}:</b> 
              {{setting('app_store_phone_no')}} &nbsp;&nbsp;
              <b>{{ trans('lang.gstin') }} : </b> {{setting('app_store_gstin')}}
            </span>
         </td>
      </tr>
   </tbody>
</table>
<hr>
<p class="text-right"><b>{{trans('lang.product_stock_report')}}</b></p>
<div class="detail-table">
<span><b>Date : </b> {{date('d M Y')}}</span>
<hr>
<span><b>Available Stock : </b> {{$product->stock}} {{$product->unit}}</span>	
</div>

<table class="report-table">
	<thead>
		<tr>
            <th>{{strtoupper(trans('lang.pro_stock_date'))}}</th>
			<th>{{strtoupper(trans('lang.pro_stock_transaction_type'))}}</th>
			<th>{{strtoupper(trans('lang.pro_stock_quantity'))}}</th>
			<th>{{strtoupper(trans('lang.pro_stock_closing_stock'))}}</th>
			<th>{{strtoupper(trans('lang.pro_stock_description'))}}</th>
        </tr>
	</thead>
	<tbody>
		@foreach($datas as $data)
		<tr>
			<td>{{$data->inventory_track_date}}</td>
			<td>{{$data->inventory_track_category}}</td>
			<td>{{$data->quantity}}</td>
			<td>{{$data->inventory_closing_stock}}</td>
			<td>{{$data->inventory_track_description}}</td>
		</tr>
		@endforeach
		
	</tbody>
</table>