<div class='btn-group btn-group-sm'>
  @can('salesReturn.show')
  <a data-toggle="tooltip" data-placement="bottom" title="{{trans('lang.view_details')}}" href="{{ route('salesReturn.show', $id) }}" class='btn btn-link'>
    <i class="fa fa-eye" style="font-size:20px;"></i>
  </a>
  @endcan

  @can('salesReturn.edit')
  <a data-toggle="tooltip" data-placement="bottom" title="{{trans('lang.sales_return_edit')}}" href="{{ route('salesReturn.edit', $id) }}" class='btn btn-link'>
    <i class="fa fa-edit" style="font-size:20px;"></i>
  </a>
  @endcan

  @can('salesReturn.destroy')
{!! Form::open(['route' => ['salesReturn.destroy', $id], 'method' => 'delete']) !!}
  {!! Form::button('<i class="fa fa-trash" style="font-size:20px;"></i>', [
  'type' => 'submit',
  'class' => 'btn btn-link text-danger',
  'onclick' => "return confirm('Are you sure?')"
  ]) !!}
{!! Form::close() !!}
  @endcan
</div>
