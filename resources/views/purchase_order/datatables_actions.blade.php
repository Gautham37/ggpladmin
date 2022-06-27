<div class="dropdown show text-center">
  <a class="btn  btn-sm" href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
    <i class="fa fa-ellipsis-v"></i>
  </a>

  <div class="dropdown-menu" aria-labelledby="dropdownMenuLink">

    @can('purchaseOrder.edit')
        <a href="{{ route('purchaseOrder.edit', $id) }}" class='dropdown-item'> <i class="fa fa-edit"></i> Edit</a>
    @endcan
    
    @if($status=='accepted')
        <a href="{{ route('purchaseInvoice.create') }}?purchase_order_id={{$id}}" class='dropdown-item'> 
          <i class="fa fa-exchange"></i> Convert to Invoice
        </a>
    @endif

    @can('purchaseOrder.show')
        <a href="{{ route('purchaseOrder.show', $id) }}" class='dropdown-item'> <i class="fa fa-eye"></i> View </a>
    @endcan

    @can('purchaseOrder.destroy')
        <a data-id="{{$id}}" data-url="{{route('purchaseOrder.destroy', $id)}}"  onclick="deleteItemLoad(this)" class="dropdown-item"><i class="fa fa-trash"></i> Delete </a>
    @endcan

  </div>
</div>
