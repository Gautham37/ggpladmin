  <!--Inventory-->
  <div class="modal fade" id="productInventoryModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        
        <div class="modal-header">
          <h4 class="modal-title" id="myModalLabel">Update Product Inventory</h4>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        </div>
        
        {!! Form::open(['route' => 'inventory.store', 'class' => 'inventory-form']) !!}

        <div class="modal-body">
       
          <div class="row">
              
              <div class="form-group">
                
              </div>
              
            <div class="clearfix"></div>
          </div>
        </div>

        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
          <button type="submit" class="btn btn-primary inventory-form-submit">Save changes</button>
        </div>

        {!! Form::close() !!}

      </div>
    </div>
  </div>