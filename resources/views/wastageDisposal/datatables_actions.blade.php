
<div class="dropdown show text-center">
  
  <a class="btn  btn-sm" href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
    <i class="fa fa-ellipsis-v"></i>
  </a>

  <div class="dropdown-menu" aria-labelledby="dropdownMenuLink">

    @can('wastageDisposal.create')
        @if(isset($disposal) && $disposal['quantity'] < $quantity)
          <a onclick="inventoryAction(this);" data-id="{{$id}}" data-type="dispose"  class='dropdown-item'> <i class="fa fa-recycle"></i> Dispose </a>
        @endif
        @if(!$disposal)
          <a onclick="inventoryAction(this);" data-id="{{$id}}" data-type="dispose"  class='dropdown-item'> <i class="fa fa-recycle"></i> Dispose </a>
        @endif
    @endcan

    @can('inventory.edit')
        @if(!$disposal)
          <a onclick="inventoryAction(this);" data-id="{{$id}}" data-type="edit" class='dropdown-item'> <i class="fa fa-edit"></i> Edit</a>
        @endif
    @endcan
    
    @can('inventory.destroy')
        <a data-id="{{$id}}" data-url="{{route('inventory.destroy', $id)}}"  onclick="deleteItemLoad(this)" class="dropdown-item"><i class="fa fa-trash"></i> Delete </a>
    @endcan

  </div>
</div>
