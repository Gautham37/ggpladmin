<div class='btn-group btn-group-sm'>
  @can('deliveryChallan.show')
  <a data-toggle="tooltip" data-placement="bottom" title="{{trans('lang.view_details')}}" href="{{ route('deliveryChallan.show', $id) }}" class='btn btn-link'>
    <i class="fa fa-eye" style="font-size:20px;"></i>
  </a>
  @endcan

  @can('deliveryChallan.edit')
  <a data-toggle="tooltip" data-placement="bottom" title="{{trans('lang.delivery_challan_edit')}}" href="{{ route('deliveryChallan.edit', $id) }}" class='btn btn-link'>
    <i class="fa fa-edit" style="font-size:20px;"></i>
  </a>
  @endcan

  @can('deliveryChallan.destroy')
    {!! Form::open(['route' => ['deliveryChallan.destroy', $id], 'method' => 'delete']) !!}
      {!! Form::button('<i class="fa fa-trash" style="font-size:20px;"></i>', [
      'type' => 'submit',
      'class' => 'btn btn-link text-danger',
      'onclick' => "return confirm('Are you sure?')"
      ]) !!}
    {!! Form::close() !!}
  @endcan
</div>
