<div class="dropdown show text-center">
  <a class="btn  btn-sm" href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
    <i class="fa fa-ellipsis-v"></i>
  </a>

  <div class="dropdown-menu" aria-labelledby="dropdownMenuLink">

    @can('emailnotifications.edit')
      @if($status=='draft')
        <a href="{{ route('emailnotifications.edit', $id) }}" class='dropdown-item'> <i class="fa fa-edit"></i> Edit</a>
      @endif
    @endcan
    
    @can('emailnotifications.show')
        <!-- <a href="{{ route('emailnotifications.show', $id) }}" class='dropdown-item'> <i class="fa fa-eye"></i> View </a> -->
    @endcan

    @can('emailnotifications.destroy')
        <a data-id="{{$id}}" data-url="{{route('emailnotifications.destroy', $id)}}"  onclick="deleteItemLoad(this)" class="dropdown-item"><i class="fa fa-trash"></i> Delete </a>
    @endcan

  </div>
</div>
