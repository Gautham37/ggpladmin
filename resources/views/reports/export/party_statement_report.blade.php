<style>
	body {
		font-size: 12px;
		font-family: Arial, Helvetica, sans-serif;
	}
	.text-right {
		text-align: right;
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
<p class="text-right"><b>Party Ledger Report</b></p>
<div class="detail-table">
	<span><b>Date : </b> {{date('d M Y', strtotime($start_date))}} - {{date('d M Y', strtotime($end_date))}} </span>
	<hr>
	<span><b>Party Name : </b> {{ $market->name }} </span><br>
    @if($market->balance > 0)
        <span><b>Total Transferable: </b> <span style="font-family: DejaVu Sans; sans-serif;">&#8377;</span> {{ number_format($market->balance,2,'.','') }} </span>
    @else
        <span><b>Total Receivable: </b> <span style="font-family: DejaVu Sans; sans-serif;">&#8377;</span> {{ number_format(abs($balance),2,'.','') }} </span>
    @endif
</div>
<br>
<table class="report-table">
	<thead>
		<tr>
          <th>DATE</th>
          <th>TRANSACTION TYPE</th>
          <th>TRANSACTION NO</th>
          <th>CREDIT</th>
          <th>DEBIT</th>
          <th>BALANCE</th>
        </tr>
	</thead>
	<tbody>

	   @foreach($datas as $data)
		<tr>
			<td>{{strip_tags($data->date)}}</td>
			<td>{{strip_tags($data->category)}}</td>
			<td>{{strip_tags($data->transaction_no)}}</td>
			<td> 
				@if($data->credit!='') 
					<span style="font-family: DejaVu Sans; sans-serif;">&#8377;</span> {{str_replace("₹","",strip_tags($data->credit))}}
				@endif	
			</td>
			<td> 
				@if($data->debit!='') 
					<span style="font-family: DejaVu Sans; sans-serif;">&#8377;</span> {{str_replace("₹","",strip_tags($data->debit))}}
				@endif	
			</td>
			<td> 
				@if($data->closing_balance!='') 
					<span style="font-family: DejaVu Sans; sans-serif;">&#8377;</span> {{str_replace("₹","",strip_tags($data->closing_balance))}}
				@endif	
			</td>
		</tr>
		@endforeach
	    
	</tbody>
</table>