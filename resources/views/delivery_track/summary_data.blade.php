<table class="table table-bordered">
	<thead>
		<tr>
			<th>S.NO</th>
			<th>ORDER ID</th>
			<th>DATE</th>
			<th>CUSTOMER</th>
			<th class="text-right">DELIVERY CHARGE</th>
			<th class="text-right">DISTANCE</th>
			<th class="text-right">ORDER AMOUNT</th>
			<th class="text-right">COLLECTABLE AMOUNT</th>
			<th class="text-center">STATUS</th>
			<th class="text-center">PERSON</th>
			<th class="text-center">ACTION</th>
		</tr>
	</thead>
	<tbody>
		@if(count($data) > 0)
			@php $count = 0; @endphp 
			@foreach($data as $order)
				@php $count++; @endphp  
				<tr>
					<td>{{$count}}</td>
					<td><b><a href="{{route('orders.show',$order->id)}}">{{$order->order_code}}</a></b></td>
					<td>{{$order->created_at->format('d M, Y')}}</td>
					<td><b><a href="{{route('markets.view',$order->user->market->id)}}">{{$order->user->market->name}}</a></b></td>
					
					<td class="text-right">
						{{setting('default_currency')}}{{number_format($order->delivery_fee,2,'.','')}}
					</td>
					<td class="text-right">
						{{number_format($order->delivery_distance,2,'.','')}} Km
					</td>
					<td class="text-right">
						{{setting('default_currency')}}{{number_format($order->order_amount,2,'.','')}}
					</td>
					<td class="text-right">
						@if(isset($order->payment) && $order->payment->status == 'paid')
							<b>{{setting('default_currency').'0.00'}}</b>
						@else
							<b>{{setting('default_currency').number_format($order->order_amount,2,'.','')}}</b>
						@endif
					</td>
					<td class="text-center">
						@if($order->deliverytrack)
							<b class="btn btn-sm btn-{{str_replace('_','-',$order->deliverytrack->status)}}">
								{{str_replace('_',' ',strtoupper($order->deliverytrack->status))}}
							</b>
						@else
							<b class="btn btn-sm btn-{{str_replace('_','-',$order->status)}}">
								{{str_replace('_',' ',strtoupper($order->status))}}
							</b>
						@endif
					</td>
					<td class="text-center">
						@if($order->deliverytrack)
							<a href="{{route('markets.view',$order->deliverytrack->user->market->id)}}"><b>{{$order->deliverytrack->user->market->name}}</b></a>
						@endif
					</td>
					<td class="text-center">
						@if($order->deliverytrack)
							<button data-id="{{$order->deliverytrack->id}}" data-category="update" class="btn btn-sm btn-primary assign-btn"> Edit </button>
						@else
							<button data-id="{{$order->id}}" data-category="store" class="btn btn-sm btn-primary assign-btn"> Assign </button>
						@endif
					</td>
				</tr>
			@endforeach
		@else
			<tr>
				<td colspan="10"> No Orders Available</td>
			</tr>	
		@endif
	</tbody>
</table>

<script>
	
	$('.assign-btn').click(function() {
		$('#deliveryModal').modal('show');
		var id 		  = $(this).data('id');
		var category  = $(this).data('category');
		var url       = '{!!  route('deliveryTracker.index') !!}';
	    var token     = "{{csrf_token()}}";
	    $.ajax({
	        type: 'GET',
	        data: {
	            '_token': token,
	            'type': 'delivery_assign',
	            'order_id': id,
	            'category': category
	        },
	        url: url,
	        success: function (response) {
	           $('.modal-content').html(response.data);
	        }
	    });

	});

</script>

