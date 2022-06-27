<div class='btn-group btn-group-sm'>

  @can('products.stock')
    <a data-placement="bottom" title="Adjust Stock" onclick="adjustInventoryStock(this);" data-id="{{$id}}" data-toggle="modal" data-target="#productStockModal" class='btn btn-sm btn-link'>
      <i class="fa fa-database" style="font-size:20px;"></i>
    </a>
  @endcan
  
</div>
