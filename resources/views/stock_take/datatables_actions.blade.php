<div class="dropdown show text-center">
  <a class="btn  btn-sm" href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
    <i class="fa fa-ellipsis-v"></i>
  </a>

  <div class="dropdown-menu" aria-labelledby="dropdownMenuLink">

    @can('stockTake.edit')
      @if($status!='approved')
        <a href="{{ route('stockTake.edit', $id) }}" class='dropdown-item'> <i class="fa fa-edit"></i> Edit</a>
      @endif  
    @endcan
    
    @can('stockTake.show')
        <a href="{{ route('stockTake.show', $id) }}" class='dropdown-item'> <i class="fa fa-eye"></i> View </a>
    @endcan

    @can('stockTake.destroy')
        <a data-id="{{$id}}" data-url="{{route('stockTake.destroy', $id)}}"  onclick="deleteItemLoad(this)" class="dropdown-item"><i class="fa fa-trash"></i> Delete </a>
    @endcan

  </div>
</div>
