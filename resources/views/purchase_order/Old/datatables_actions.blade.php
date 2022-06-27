<div class='btn-group btn-group-sm'>
  @can('purchaseOrder.show')
  <a data-toggle="tooltip" data-placement="bottom" title="{{trans('lang.view_details')}}" href="{{ route('purchaseOrder.show', $id) }}" class='btn btn-link'>
    <i class="fa fa-eye" style="font-size:20px;"></i>
  </a>
  @endcan

  @can('purchaseOrder.edit')
     @if($purchase_status > 0) 
        <a data-toggle="tooltip" data-placement="bottom" title="{{trans('lang.purchase_order_edit')}}" href="{{ route('purchaseOrder.edit', $id) }}" class='btn btn-link'>
          <i class="fa fa-edit" style="font-size:20px;"></i>
        </a>
      @endif
  @endcan

  @can('purchaseInvoice.createPOInvoice')
    @if($purchase_status > 0)
      <a data-toggle="tooltip" data-placement="bottom" title="{{trans('lang.purchase_order_create_invoice')}}" href="{{ route('purchaseInvoice.createPOInvoice', $id) }}" class='btn btn-link'>
        <i class="fa fa-sticky-note-o" style="font-size:20px;"></i>
      </a>
    @endif
  @endcan  

  @can('purchaseOrder.destroy')
{!! Form::open(['route' => ['purchaseOrder.destroy', $id], 'method' => 'delete']) !!}
  {!! Form::button('<i class="fa fa-trash" style="font-size:20px;"></i>', [
  'type' => 'submit',
  'class' => 'btn btn-link text-danger',
  'onclick' => "return confirm('Are you sure?')"
  ]) !!}
{!! Form::close() !!}
  @endcan
</div>
