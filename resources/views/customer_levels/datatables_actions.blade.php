<div class='btn-group btn-group-sm'>
  @can('CustomerLevels.show')
  <a data-toggle="tooltip" data-placement="bottom" title="{{trans('lang.view_details')}}" href="{{ route('CustomerLevels.show', $id) }}" class='btn btn-link'>
    <i class="fa fa-eye" style="font-size:20px;"></i>
  </a>
  @endcan

  @can('CustomerLevels.edit')
  <a data-toggle="tooltip" data-placement="bottom" title="{{trans('lang.customer_levels_edit')}}" href="{{ route('CustomerLevels.edit', $id) }}" class='btn btn-link'>
    <i class="fa fa-edit" style="font-size:20px;"></i>
  </a>
  @endcan

  @can('CustomerLevels.destroy')
    {!! Form::open(['route' => ['CustomerLevels.destroy', $id], 'method' => 'delete']) !!}
      {!! Form::button('<i class="fa fa-trash" style="font-size:20px;"></i>', [
      'type' => 'submit',
      'class' => 'btn btn-link text-danger',
      'onclick' => "return confirm('Are you sure?')"
      ]) !!}
    {!! Form::close() !!}
  @endcan
</div>
