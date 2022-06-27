<div class='btn-group btn-group-sm'>
  @can('purchaseReturn.show')
  <a data-toggle="tooltip" data-placement="bottom" title="{{trans('lang.view_details')}}" href="{{ route('purchaseReturn.show', $id) }}" class='btn btn-link'>
    <i class="fa fa-eye" style="font-size:20px;"></i>
  </a>
  @endcan

  @can('purchaseReturn.edit')
  <a data-toggle="tooltip" data-placement="bottom" title="{{trans('lang.purchase_return_edit')}}" href="{{ route('purchaseReturn.edit', $id) }}" class='btn btn-link'>
    <i class="fa fa-edit" style="font-size:20px;"></i>
  </a>
  @endcan

  @can('purchaseReturn.destroy')
{!! Form::open(['route' => ['purchaseReturn.destroy', $id], 'method' => 'delete']) !!}
  {!! Form::button('<i class="fa fa-trash" style="font-size:20px;"></i>', [
  'type' => 'submit',
  'class' => 'btn btn-link text-danger',
  'onclick' => "return confirm('Are you sure?')"
  ]) !!}
{!! Form::close() !!}
  @endcan
</div>
