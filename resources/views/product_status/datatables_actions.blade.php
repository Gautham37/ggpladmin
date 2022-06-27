<div class='btn-group btn-group-sm'>

  @can('productStatus.edit')
  <a data-toggle="tooltip" data-placement="bottom" title="{{trans('lang.product_status_edit')}}" href="{{ route('productStatus.edit', $id) }}" class='btn btn-link'>
    <i class="fa fa-edit" style="font-size:20px;"></i>
  </a>
  @endcan

  @can('productStatus.destroy')
{!! Form::open(['route' => ['productStatus.destroy', $id], 'method' => 'delete']) !!}
  {!! Form::button('<i class="fa fa-trash" style="font-size:20px;"></i>', [
  'type' => 'submit',
  'class' => 'btn btn-link text-danger',
  'onclick' => "return confirm('Are you sure?')"
  ]) !!}
{!! Form::close() !!}
  @endcan
</div>
 