<div class='btn-group btn-group-sm'>

  @can('qualityGrade.edit')
  <a data-toggle="tooltip" data-placement="bottom" title="{{trans('lang.quality_grade_edit')}}" href="{{ route('qualityGrade.edit', $id) }}" class='btn btn-link'>
    <i class="fa fa-edit" style="font-size:20px;"></i>
  </a>
  @endcan

  @can('qualityGrade.destroy')
{!! Form::open(['route' => ['qualityGrade.destroy', $id], 'method' => 'delete']) !!}
  {!! Form::button('<i class="fa fa-trash" style="font-size:20px;"></i>', [
  'type' => 'submit',
  'class' => 'btn btn-link text-danger',
  'onclick' => "return confirm('Are you sure?')"
  ]) !!}
{!! Form::close() !!}
  @endcan
</div>
 