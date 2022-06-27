@extends('layouts.app')

@section('content')

<!-- Content Header (Page header) -->
<div class="content-header">
  <div class="container-fluid">
    <div class="row mb-2">
      <div class="col-sm-6">
        <h1 class="m-0 text-dark">Stock Take<small class="ml-3 mr-3">|</small><small>Stock Take Management</small></h1>
      </div><!-- /.col -->
      <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
          <li class="breadcrumb-item"><a href="{{url('/dashboard')}}"><i class="fa fa-dashboard"></i> Dashboard </a></li>
          <li class="breadcrumb-item"><a href="{!! route('stockTake.index') !!}">Stock Take List </a>
          </li>
          <li class="breadcrumb-item active">Purchase Order</li>
        </ol>
      </div><!-- /.col -->
    </div><!-- /.row -->
  </div><!-- /.container-fluid -->
</div>
<!-- /.content-header -->

<div class="content">
  <div class="card">
    
    <div class="card-header d-print-none">

      <ul class="nav nav-tabs align-items-end card-header-tabs w-100">
        <li class="nav-item">
          <a class="nav-link" href="{!! route('stockTake.index') !!}"><i class="fa fa-list mr-2"></i>Stock Take List</a>
        </li>
        <li class="nav-item">
          <a class="nav-link active" href="{!! url()->current() !!}"><i class="fa fa-plus mr-2"></i>Stock Take</a>
        </li>
      </ul>
      
        <div class="dropdown float-right top-action-btn">

          <div class="dropdown float-right top-action-btn">
            <button class="btn btn-primary dropdown-toggle" type="button" data-toggle="dropdown">
              <i class="fa fa-gear"></i> Options <span class="caret"></span>
            </button>
            
            <ul class="dropdown-menu">

              @if($stock_take->status!='approved')
              <a href="#" data-id="{{$stock_take->id}}" data-status="approved" class="dropdown-item stock-take-status"> 
                <i class="fa fa-check"></i> Mark as Approved
              </a>
              @endif

              @can('stockTake.edit')
                  <a href="{{ route('stockTake.edit', $stock_take->id) }}" class='dropdown-item'> 
                    <i class="fa fa-edit"></i> Edit
                  </a>
              @endcan

              @can('stockTake.destroy')
                  <a href="#" data-id="{{$stock_take->id}}" data-url="{{route('stockTake.destroy', $stock_take->id)}}" onclick="deleteItemLoad(this)" class="dropdown-item"><i class="fa fa-trash"></i> Delete </a>
              @endcan

            </ul>

          </div>

          @can('stockTake.print')
          <div class="dropdown float-right top-action-btn">
            <button style="background: #0078d7;border: 1px solid #0078d7;" class="btn btn-primary dropdown-toggle" type="button" data-toggle="dropdown">Download PDF
            <span class="caret"></span></button>
            <ul class="dropdown-menu">
              <li>
                <a target="_blank" id="print-invoice" href='{{route("stockTake.print",[$stock_take->id,"1","download"])}}'>
                  Download Original
                </a>
              </li>
              <li>
                <a target="_blank" id="print-invoice" href='{{route("stockTake.print",[$stock_take->id,"2","download"])}}'>
                  Download Duplicate
                </a>
              </li>
              <li>
                <a target="_blank" id="print-invoice" href='{{route("stockTake.print",[$stock_take->id,"2","download"])}}'>
                  Download Triplicate
                </a>
              </li>
            </ul>
          </div>
          @endcan

          @can('stockTake.print')
          <div class="dropdown float-right top-action-btn">
            <button style="background: #881798;border: 1px solid #881798;" class="btn btn-primary dropdown-toggle" type="button" data-toggle="dropdown">Print PDF
            <span class="caret"></span></button>
            <ul class="dropdown-menu">
              <li>
                <a target="_blank" id="print-invoice" href='{{route("stockTake.print",[$stock_take->id,"1","print"])}}'>
                  Print Original
                </a>
              </li>
              <li>
                <a target="_blank" id="print-invoice" href='{{route("stockTake.print",[$stock_take->id,"2","print"])}}'>
                  Print Duplicate
                </a>
              </li>
              <li>
                <a target="_blank" id="print-invoice" href='{{route("stockTake.print",[$stock_take->id,"2","print"])}}'>
                  Print Triplicate
                </a>
              </li>
            </ul>
          </div>
          @endcan

        </div>
    
    </div>
        
    <div class="card-body">
      


      <div class="row">
        <div class="col-md-12">

          <style>
            table {
              border-collapse:collapse;
              border-spacing: 0;
              font-size: 12px !important;
            }

            tr{
              border: 1px solid #000;
            }
            td {
              border: 1px solid #000;
              padding: 7px;
            }
            p {
              margin-bottom: 0rem;
            }
          </style>

          <table class="table table-bordered view-table-custom-product-item">
            <tbody>
                <tr>
                  <td width="20%">
                    <p><b>Stock Take No</b></p>
                    <p>{{$stock_take->code}}</p>  
                  </td>
                  <td width="20%">
                    <p><b>Date</b></p>
                    <p>{{$stock_take->date->format('d-m-Y')}}</p>
                  </td>
                  <td width="20%">
                    <p><b>Time</b></p>
                    <p>{{$stock_take->created_at->format('h:i A')}}</p>
                  </td>
                  <td width="20%">
                    <p><b>Created By</b></p>
                    <p>{{$stock_take->createdby->name}}</p>  
                  </td>
                  <td width="20%">
                    <p><b>Status</b></p>
                    <p>{{ucfirst($stock_take->status)}}</p>  
                  </td>
                </tr>

            </tbody>
          </table>

          <table class="table table-bordered view-table-custom-product-item">
            <tbody>      

                <tr class="bg-secondary" style="background-color: #28a745 !important;">
                  <th>ITEM</th>
                  <th class="text-center">CODE</th>
                  <th class="text-right">CURRENT STOCK</th>
                  <th class="text-right">COUNTED</th>
                  <th class="text-right">WASTAGE</th>
                  <th class="text-right">MISSING</th>
                  <th class="text-left">NOTES</th>
                </tr>
                @if(count($stock_take->items) > 0)
                  @foreach($stock_take->items as $item)
                    <tr>
                      <td class="text-left">{{$item->product_name}}</td>
                      <td class="text-center">{{$item->product_code}}</td>
                      <td class="text-right">{{number_format($item->current,3,'.','')}} {{$item->currentunit->name}}</td>
                      <td class="text-right">{{number_format($item->counted,3,'.','')}} {{$item->countedunit->name}}</td>
                      <td class="text-right">{{number_format($item->wastage,3,'.','')}} {{$item->wastageunit->name}}</td>
                      <td class="text-right">{{number_format($item->missing,3,'.','')}} {{$item->missingunit->name}}</td>
                      <td class="text-right">{{$item->notes}}</td>
                    </tr>
                  @endforeach
                @endif
                
                <tr>
                  <td colspan="7">
                    
                    <h6><b>Notes</b></h6>
                    <p>{!!$stock_take->notes!!}</p>

                  </td>
                </tr>
                
            </tbody>
          </table>


          <table class="table table-bordered view-table-custom-product-item" style="display: block; overflow-x: auto; white-space: nowrap;">
            <tbody>      

                <tr class="bg-secondary" style="background-color: #28a745 !important;">
                  <th>ITEM</th>
                  <th class="text-center">CODE</th>
                  <th class="text-right">CURRENT STOCK</th>
                  <th class="text-right">COUNTED</th>
                  <th class="text-right" colspan="4">WASTAGE</th>
                  <th class="text-right" colspan="4">MISSING</th>
                  <th class="text-left">NOTES</th>
                </tr>

                <tr>
                  <th colspan="4"></th>
                  
                  <!-- <th class="text-right">COUNTED</th>
                  <th class="text-right">PURCHASE COST</th>
                  <th class="text-right">SALE COST</th>
                  <th class="text-right">DISCOUNT COST</th> -->

                  <th class="text-right bg-danger">WASTAGE</th>
                  <th class="text-right bg-primary">PURCHASE COST</th>
                  <th class="text-right bg-info">SALE COST</th>
                  <th class="text-right bg-warning">DISCOUNT COST</th>

                  <th class="text-right bg-danger">MISSING</th>
                  <th class="text-right bg-primary">PURCHASE COST</th>
                  <th class="text-right bg-info">SALE COST</th>
                  <th class="text-right bg-warning">DISCOUNT COST</th>
                  <th></th>

                </tr>

                @if(count($stock_take->items) > 0)
                  @foreach($stock_take->items as $item)
                    <tr>
                      <td class="text-left">{{$item->product_name}}</td>
                      <td class="text-center">{{$item->product_code}}</td>
                      <td class="text-right">{{number_format($item->current,3,'.','')}} {{$item->currentunit->name}}</td>

                      <td class="text-right">{{ number_format($item->counted,3,'.','')}} {{$item->countedunit->name }}</td>

                      <td class="text-right bg-danger-light">{{number_format($item->wastage,3,'.','')}} {{$item->wastageunit->name}}</td>
                      <td class="text-right bg-primary-light">{{number_format(($item->wastage * $item->product->purchase_price),2,'.','') }}</td>
                      <td class="text-right bg-info-light">{{number_format(($item->wastage * $item->product->price),2,'.','') }}</td>
                      <td class="text-right bg-warning-light">{{number_format(($item->wastage * $item->product->discount_price),2,'.','') }}</td>

                      <td class="text-right bg-danger-light">{{number_format($item->missing,3,'.','')}} {{$item->missingunit->name}}</td>
                      <td class="text-right bg-primary-light">{{number_format(($item->missing * $item->product->purchase_price),2,'.','') }}</td>
                      <td class="text-right bg-info-light">{{number_format(($item->missing * $item->product->price),2,'.','') }}</td>
                      <td class="text-right bg-warning-light">{{number_format(($item->missing * $item->product->discount_price),2,'.','') }}</td>

                      <td class="text-right">{{$item->notes}}</td>
                    </tr>
                  @endforeach
                @endif
                
                <!-- <tr>
                  <td colspan="3">Total</td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                </tr> -->

                <tr>
                  <td colspan="13">
                    
                    <h6><b>Notes</b></h6>
                    <p>{!!$stock_take->notes!!}</p>

                  </td>
                </tr>
                
            </tbody>
          </table>


        </div>
      </div>



      <div class="clearfix"></div>

      <div class="row d-print-none">
        <!-- Back Field -->
        <div class="form-group col-12 text-right">
          <a href="{!! route('stockTake.index') !!}" class="btn btn-default"><i class="fa fa-undo"></i> Back </a>
        </div>
      </div>

      <div class="clearfix"></div>

    </div>

  </div>
</div>

@endsection

@push('scripts')
  <script type="text/javascript">
    $("#printOrder").on("click",function () {
      window.print();
    });


    $('.stock-take-status').click(function() {
      var status = $(this).data('status');
      var id     = $(this).data('id');

      iziToast.show({
          theme: 'dark',
          icon: 'fa fa-trash',
          overlay: true,
          title: 'Are you sure?',
          message: 'It reduces the wastage and missing quantities from product stock and update the new stock.',
          position: 'center', // bottomRight, bottomLeft, topRight, topLeft, topCenter, bottomCenter
          progressBarColor: 'yellow',
          backgroundColor: 'linear-gradient(to right, #ff4b1f, #ff9068)', 
          messageColor: '#fff', 
          buttons: [
              ['<button>Yes</button>', function (instance, toast) {
                 
                  var url       = '{!!  route('stockTake.show', [':stockTakeID']) !!}';
                  url           = url.replace(':stockTakeID', id);
                  var token     = "{{ csrf_token() }}";
                  $.ajax({
                      type: 'POST',
                      url: url,
                      data: {
                        '_token': token,
                        '_method': 'PATCH', 
                        'id': id,
                        'status': status,
                        'type': 'status-update'
                      },
                      success: function (response) {
                          iziToast.success({
                              backgroundColor: 'linear-gradient(to right, #44a08d, #093637)',
                              messageColor: '#fff',
                              timeout: 3000, 
                              icon: 'fa fa-check', 
                              position: "topRight", 
                              iconColor:'#fff',
                              message: status.toUpperCase() + ' Sucessfully'
                          });
                          instance.hide({transitionOut: 'fadeOutUp'}, toast, 'buttonName');  
                          setTimeout(function () { location.reload(); }, 1000);
                      }
                  }); 

              }, true], // true to focus
              ['<button>No</button>', function (instance, toast) {
                  instance.hide({
                      transitionOut: 'fadeOutUp',
                      onClosing: function(instance, toast, closedBy){
                          console.info('closedBy: ' + closedBy); // The return will be: 'closedBy: buttonName'
                      }
                  }, toast, 'buttonName');
              }]
          ]
      });

    });
  </script>
@endpush
