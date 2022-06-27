<div class='btn-group btn-group-sm'>
  @can('specialOffers.show')
  <a data-toggle="tooltip" data-placement="bottom" title="{{trans('lang.view_details')}}" href="{{ route('specialOffers.show', $id) }}" class='btn btn-link'>
    <i class="fa fa-eye"></i>
  </a>
  @endcan

  @can('specialOffers.edit')
  <a data-toggle="tooltip" data-placement="bottom" title="{{trans('lang.special_offers_edit')}}" href="{{ route('specialOffers.edit', $id) }}" class='btn btn-link'>
    <i class="fa fa-edit"></i>
  </a>
  @endcan

  @can('specialOffers.destroy')
    {!! Form::open(['route' => ['specialOffers.destroy', $id], 'method' => 'delete']) !!}
      {!! Form::button('<i class="fa fa-trash"></i>', [
      'type' => 'submit',
      'class' => 'btn btn-link text-danger',
      'onclick' => "return confirm('Are you sure?')"
      ]) !!}
    {!! Form::close() !!}
  @endcan
</div>
