<div class="dropdown show text-center">
  <a class="btn  btn-sm" href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
    <i class="fa fa-ellipsis-v"></i>
  </a>

  <div class="dropdown-menu" aria-labelledby="dropdownMenuLink">

    @can('orders.edit')
        <!-- <a href="{{ route('orders.edit', $id) }}" class='dropdown-item'> <i class="fa fa-edit"></i> Edit</a> -->
    @endcan
    
    @can('orders.show')
        <a href="{{ route('orders.show', $id) }}" class='dropdown-item'> <i class="fa fa-eye"></i> View </a>
    @endcan

    @can('orders.destroy')
        <a data-id="{{$id}}" data-url="{{route('orders.destroy', $id)}}"  onclick="deleteItemLoad(this)" class="dropdown-item"><i class="fa fa-trash"></i> Delete </a>
    @endcan

  </div>
</div>
