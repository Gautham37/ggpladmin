<div class='btn-group btn-group-sm'>
  @can('supplierRequest.show')
  <a data-toggle="tooltip" data-placement="bottom" title="{{trans('lang.view_details')}}" href="{{ route('supplierRequest.show', $id) }}" class='btn btn-link'>
    <i class="fa fa-eye" style="font-size:20px;"></i>
  </a>
  @endcan

  @can('supplierRequest.edit')
    @if($sr_status!=3)
      <a data-toggle="tooltip" data-placement="bottom" title="{{trans('lang.supplier_request_edit')}}" href="{{ route('supplierRequest.edit', $id) }}" class='btn btn-link'>
        <i class="fa fa-edit" style="font-size:20px;"></i>
      </a>
    @endif
  @endcan
  
  @if($sr_status==2) 
    <a data-toggle="tooltip" data-placement="bottom" title="{{trans('lang.supplier_request_convert')}}" href="{{ route('purchaseInvoice.createSRInvoice', $id) }}" class='btn btn-link'>
      <i class="fa fa-forward" style="font-size:20px;"></i>
    </a>
  @endif

  @can('supplierRequest.destroy')
    {!! Form::open(['route' => ['supplierRequest.destroy', $id], 'method' => 'delete']) !!}
      {!! Form::button('<i class="fa fa-trash" style="font-size:20px;"></i>', [
      'type' => 'submit',
      'class' => 'btn btn-link text-danger',
      'onclick' => "return confirm('Are you sure?')"
      ]) !!}
    {!! Form::close() !!}
  @endcan
</div>
