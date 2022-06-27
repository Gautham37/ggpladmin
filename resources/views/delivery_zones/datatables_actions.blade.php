<div class='btn-group btn-group-sm'>
  @can('deliveryZones.show')
  <a data-toggle="tooltip" data-placement="bottom" title="{{trans('lang.view_details')}}" href="{{ route('deliveryZones.show', $id) }}" class='btn btn-link'>
    <i class="fa fa-eye" style="font-size:20px;"></i>
  </a>
  @endcan

  @can('deliveryZones.edit')
  <a data-toggle="tooltip" data-placement="bottom" title="{{trans('lang.delivery_zones_edit')}}" href="{{ route('deliveryZones.edit', $id) }}" class='btn btn-link'>
    <i class="fa fa-edit" style="font-size:20px;"></i>
  </a>
  @endcan

  @can('deliveryZones.destroy')
{!! Form::open(['route' => ['deliveryZones.destroy', $id], 'method' => 'delete']) !!}
  {!! Form::button('<i class="fa fa-trash" style="font-size:20px;"></i>', [
  'type' => 'submit',
  'class' => 'btn btn-link text-danger',
  'onclick' => "return confirm('Are you sure?')"
  ]) !!}
{!! Form::close() !!}
  @endcan
</div>
