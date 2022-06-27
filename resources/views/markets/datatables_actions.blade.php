<div class="dropdown show text-center">
  <a class="btn  btn-sm" href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
    <i class="fa fa-ellipsis-v"></i>
  </a>

  <div class="dropdown-menu" aria-labelledby="dropdownMenuLink">

    @can('markets.edit')
        <a href="{{ route('markets.edit', $id) }}" class='dropdown-item'> <i class="fa fa-edit"></i> Edit</a>
    @endcan
    
    @can('markets.show')
        <a href="{{ route('markets.show', $id) }}" class='dropdown-item'> <i class="fa fa-eye"></i> View </a>
    @endcan

    @can('markets.destroy')
        <a data-id="{{$id}}" data-url="{{route('markets.destroy', $id)}}"  onclick="deleteItemLoad(this)" class="dropdown-item"><i class="fa fa-trash"></i> Delete </a>
    @endcan

  </div>
</div>