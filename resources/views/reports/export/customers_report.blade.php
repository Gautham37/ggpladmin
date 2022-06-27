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
	  padding: 3px;
	  border-bottom: 1px solid lightgrey;
	  font-weight: bold;
	}
	table td {
	  padding: 3px;
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
<p class="text-right"><b>{{trans('lang.report_customers')}}</b></p>
<div class="detail-table">
	<span><b>Date : </b> {{date('d M Y', strtotime($start_date))}} - {{date('d M Y', strtotime($end_date))}} </span>
	
</div>

<table class="report-table" style="width:100%;font-size:9px">
	<thead>
		<tr>
             <th class="text-left" >{{strtoupper(trans('lang.customers_name'))}}</th>
             <th class="text-left" >{{strtoupper(trans('lang.customers_name_date'))}}</th>
             <th class="text-left" >{{strtoupper(trans('lang.customers_name_email'))}}</th>
             <th class="text-left" >{{strtoupper(trans('lang.customers_name_phone_number'))}}</th>
             <th class="text-left" >{{strtoupper(trans('lang.customers_name_contact_number'))}}</th>
             <th class="text-left" >{{strtoupper(trans('lang.customers_name_reward_level'))}}</th>
             <th class="text-left" >{{strtoupper(trans('lang.customers_name_transactions'))}}</th>
             <th class="text-left" >{{strtoupper(trans('lang.customers_name_party_group'))}}</th>
             <th class="text-left" >{{strtoupper(trans('lang.customers_name_address'))}}</th>
             
        </tr>
	</thead>
	<tbody>
		@foreach($datas as $data)
		<tr>
			<td class="text-left" >{{$data->name}}</td>
			<td class="text-left" >{{$data->created_at}}</td>
			<td class="text-left" >{{$data->email}}</td>
			<td class="text-left" >{{$data->phone}}</td>
			<td class="text-left" >{{$data->mobile}}</td>
			<td class="text-left" >{{$data->reward_levels}}</td>
			<td class="text-left" >{{$data->total_no_transactions}}</td>
			<td class="text-left" >{{$data->party_group}}</td>
			
			<td class="text-left" >
		     @php if($data->address_line_1!=''){  @endphp
		        {{$data->address_line_1}}, {{$data->address_line_2}}, {{$data->city}}, {{$data->state}} - {{$data->pincode}}.
		     @php }
		        @endphp
		    </td>
		</tr>
		@endforeach
	</tbody>
</table>
