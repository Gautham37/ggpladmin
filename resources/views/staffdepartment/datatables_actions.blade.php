<div class='btn-group btn-group-sm'>
  @can('staffdepartment.show')
  <a data-toggle="tooltip" data-placement="bottom" title="{{trans('lang.view_details')}}" href="{{ route('staffdepartment.show', $id) }}" class='btn btn-link'>
    <i class="fa fa-eye"></i>
  </a>
  @endcan

  @can('staffdepartment.edit')
  <a data-toggle="tooltip" data-placement="bottom" title="{{trans('lang.staffdepartment_edit')}}" href="{{ route('staffdepartment.edit', $id) }}" class='btn btn-link'>
    <i class="fa fa-edit"></i>
  </a>
  @endcan

  @can('staffdepartment.destroy')
{!! Form::open(['route' => ['staffdepartment.destroy', $id], 'method' => 'delete']) !!}
  {!! Form::button('<i class="fa fa-trash"></i>', [
  'type' => 'submit',
  'class' => 'btn btn-link text-danger',
  'onclick' => "return confirm('Are you sure?')"
  ]) !!}
{!! Form::close() !!}
  @endcan
</div>
