<div class='btn-group btn-group-sm'>
  @can('expensesCategory.show')
  <a data-toggle="tooltip" data-placement="bottom" title="{{trans('lang.view_details')}}" href="{{ route('expensesCategory.show', $id) }}" class='btn btn-link'>
    <i class="fa fa-eye" style="font-size:20px;"></i>
  </a>
  @endcan

  @can('expensesCategory.edit')
  <a data-toggle="tooltip" data-placement="bottom" title="{{trans('lang.category_edit')}}" href="{{ route('expensesCategory.edit', $id) }}" class='btn btn-link'>
    <i class="fa fa-edit" style="font-size:20px;"></i>
  </a>
  @endcan

  @can('expensesCategory.destroy')
    {!! Form::open(['route' => ['expensesCategory.destroy', $id], 'method' => 'delete']) !!}
      {!! Form::button('<i class="fa fa-trash" style="font-size:20px;"></i>', [
      'type' => 'submit',
      'class' => 'btn btn-link text-danger',
      'onclick' => "return confirm('Are you sure?')"
      ]) !!}
    {!! Form::close() !!}
  @endcan
</div>
