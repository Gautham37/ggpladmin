<style>
	.text-center {
		text-align:center;
	}
</style>
<?php
	$total = $product->stock;
	$rows  = round($total / 3);  
?>
<table cellpadding="23">
	@for($i=0; $i<$rows; $i++)
	<tr>
		<td class="text-center">				
			{!! DNS1D::getBarcodeHTML($product->bar_code, "C128",1.4,45) !!}
			<span>{{$product->bar_code}}</span>
		</td>
		<td class="text-center">				
			{!! DNS1D::getBarcodeHTML($product->bar_code, "C128",1.4,45) !!}
			<span>{{$product->bar_code}}</span>
		</td>
		<td class="text-center">				
			{!! DNS1D::getBarcodeHTML($product->bar_code, "C128",1.4,45) !!}
			<span>{{$product->bar_code}}</span>
		</td>		
	</tr>
	@endfor
</table>