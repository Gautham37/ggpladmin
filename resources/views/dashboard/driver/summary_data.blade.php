<div class="panel panel-default">
    <div class="panel-heading" style="background:linear-gradient(45deg,#48934B 0%,#FE9E60 100%);" >

        @if($active_delivery)
        <div class="row">
            <div class="col-md-2">
                <span class="text-dark"><b>Ongoing Order</b></span>
                <br>
                <span class="float-left">
                    <lottie-player style="width: 100px;" src="{{url('/images/animation/84633-tracking-order.json')}}" background="transparent"  speed="1" loop autoplay></lottie-player>
                </span>
            </div>
            <div class="col-md-4">
                <h4 class="mt-3">
                    @if(isset($active_delivery->order->payment) && $active_delivery->order->payment->status == 'paid')
                        <b>{{setting('default_currency').'0.00'}}</b>
                    @else
                        <b>{{setting('default_currency').number_format($active_delivery->order->order_amount,2,'.','')}}</b>
                    @endif
                     - Collectable
                </h4>
            </div>
            <div class="col-md-6">
                <p class="text-white"><b>Order ID :</b> {{$active_delivery->order->order_code}}</p>

                <p class="text-white"> <i class="fa fa-calendar"></i> <b>Assigned Date : </b> {{$active_delivery->order->created_at->format('M d, Y h:i A')}}</p>

                <p class="text-white"> <i class="fa fa-road"></i> <b>Distance : </b> {{$active_delivery->order->delivery_distance}} KM</p>

                <p class="text-white"><b> <i class="fa fa-map-marker"></i> Location : </b> 
                    @if($active_delivery->category=='sales_invoice'))
                        {{
                            $active_delivery->salesinvoice->market->address_line_1.','.
                            $active_delivery->salesinvoice->market->address_line_2.','.
                            $active_delivery->salesinvoice->market->town.','.
                            $active_delivery->salesinvoice->market->city.','.
                            $active_delivery->salesinvoice->market->state.'-'.
                            $active_delivery->salesinvoice->market->pincode
                        }}
                    @elseif($active_delivery->category=='online_order')
                        {{
                            $active_delivery->order->deliveryAddress->address_line_1.','.
                            $active_delivery->order->deliveryAddress->address_line_2.','.
                            $active_delivery->order->deliveryAddress->town.','.
                            $active_delivery->order->deliveryAddress->city.','.
                            $active_delivery->order->deliveryAddress->state.'-'.
                            $active_delivery->order->deliveryAddress->pincode
                        }}
                    @endif
                </p>
            </div>
            <div class="col-md-12">
                <br>
                <div id="map" class="col-lg-12" style="width:100%; height:320px;"></div>

            </div>
        </div>
        @endif
        
        <!-- <span class="text-dark"><b>Ongoing Order</b></span>
        <span class="text-white float-right"><b>Order ID # GGPL-0012</b></span>
        <br>
        <span class="float-left">
            <lottie-player style="width: 100px;" src="{{url('/images/animation/84633-tracking-order.json')}}" background="transparent"  speed="1" loop autoplay></lottie-player>
        </span>
        <span class="float-left">
            <h4>{{setting('default_currency')}}240.00 - Collectable</h4>
        </span>
        <span class="text-white float-right"> <i class="fa fa-calendar"></i> <b>Assigned Date : </b> 10-12-2022 06:00 AM</span>
        <br>
        <span class="text-white float-right"> <i class="fa fa-road"></i> <b>Distance : </b> 10 KM</span>
        <br>
        <span class="text-white float-right"><b> <i class="fa fa-map-marker"></i> Location : </b> 78, Thottiyapalaya, Tiruppur - 641604</span>
        <br> -->
        
    </div>
</div>

<br><br>

<div class="row">
    <div class="col-md-12">

        <div class="tile" id="tile-1">

          <!-- Nav tabs -->
          <ul class="nav nav-tabs nav-justified" role="tablist">
            <div class="slider"></div>
              <li class="nav-item">
                <a class="nav-link active" id="assigned-tab" data-toggle="tab" href="#assigned" role="tab" aria-controls="assigned" aria-selected="false"><i class="fa fa-tasks"></i> Assigned</a>
              </li>
              <li class="nav-item">
                <a class="nav-link" id="delivered-tab" data-toggle="tab" href="#delivered" role="tab" aria-controls="delivered" aria-selected="false"><i class="fa fa-handshake-o"></i> Delivered</a>
              </li>
          </ul>

          <!-- Tab panes -->
          <div class="tab-content">

            <div class="tab-pane fade show active" id="assigned" role="tabpanel" aria-labelledby="assigned-tab" style="height:350px; overflow-y: scroll;">
                    
                <div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">

                    @if(count($data) > 0)
                        @foreach($data as $delivery)
                    
                            <div class="panel panel-default">
                                <div class="panel-heading" role="tab" id="assigned{{$delivery->id}}" role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                                    
                                    <span class="text-dark">
                                        <b>
                                            Order ID #
                                            @if($delivery->category=='sales_invoice'))
                                                {{$delivery->salesinvoice->code}}
                                            @elseif($delivery->category=='online_order')
                                                {{$delivery->order->order_code}}
                                            @endif

                                            @if($delivery->active==1) 
                                                &nbsp;&nbsp;<span class="text-green blink_me"> <b>Active</b> </span>
                                            @endif

                                        </b>
                                    </span>
                                    <span class="text-info float-right"><b>{{$delivery->created_at->format('M d, Y h:i A')}}</b></span>
                                    <br>
                                    <span class="text-success">
                                        <b>
                                            Delivery Location : 
                                            @if($delivery->category=='sales_invoice'))
                                                {{
                                                    $delivery->salesinvoice->market->address_line_1.','.
                                                    $delivery->salesinvoice->market->address_line_2.','.
                                                    $delivery->salesinvoice->market->town.','.
                                                    $delivery->salesinvoice->market->city.','.
                                                    $delivery->salesinvoice->market->state.'-'.
                                                    $delivery->salesinvoice->market->pincode
                                                }}
                                            @elseif($delivery->category=='online_order')
                                                {{
                                                    $delivery->order->deliveryAddress->address_line_1.','.
                                                    $delivery->order->deliveryAddress->address_line_2.','.
                                                    $delivery->order->deliveryAddress->town.','.
                                                    $delivery->order->deliveryAddress->city.','.
                                                    $delivery->order->deliveryAddress->state.'-'.
                                                    $delivery->order->deliveryAddress->pincode
                                                }}
                                            @endif
                                        </b>
                                    </span>
                                    <span class="text-info float-right">
                                        @if($delivery->status=='assigned')
                                            <button class="btn btn-sm delivery-btn-accept status-btn" data-id="{{$delivery->id}}" data-status="accepted"> Accept </button>
                                            <button class="btn btn-sm delivery-btn-reject status-btn" data-id="{{$delivery->id}}" data-status="rejected"> Reject </button>
                                        @endif
                                        @if($delivery->status=='rejected')
                                            <button class="btn btn-sm btn-danger"> Rejected </button>
                                        @endif
                                    </span>

                                </div>
                                <div id="collapseOne" class="panel-collapse collapse" role="tabpanel" aria-labelledby="assigned{{$delivery->id}}">
                                    <div class="panel-body">
                                        
                                        <table class="table table-bordered text-center">
                                            <thead>
                                                <tr>
                                                    <th>Product</th>
                                                    <th>Price</th>
                                                    <th>Quantity</th>
                                                    <th>Tax</th>
                                                    <th>Total</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($delivery->order->productOrders as $product_order)
                                                <tr>
                                                    <td>{{$product_order->product_name}}</td>
                                                    <td>{{ setting('default_currency').number_format($product_order->unit_price,'2','.','') }}</td>
                                                    <td>{{number_format($product_order->quantity,3,'.','')}} {{$product_order->uom->name}}</td>
                                                    <td>
                                                        {{ setting('default_currency').number_format($product_order->tax_amount,'2','.','') }} 
                                                        ({{$product_order->tax}}%)
                                                    </td>
                                                    <td>
                                                        {{ setting('default_currency').number_format($product_order->amount,'2','.','') }}
                                                    </td>
                                                </tr>
                                                @endforeach
                                                @if($delivery->order->redeem_amount > 0)
                                                    <tr>
                                                        <td colspan="4">Redeem Discount</td>
                                                        <td>{{ setting('default_currency').number_format($delivery->order->redeem_amount,'2','.','') }}</td>
                                                    </tr>
                                                @endif
                                                @if($delivery->order->coupon_amount > 0)
                                                    <tr>
                                                        <td colspan="4">Coupon Discount</td>
                                                        <td>{{ setting('default_currency').number_format($delivery->order->coupon_amount,'2','.','') }}</td>
                                                    </tr>
                                                @endif
                                                @if($delivery->order->delivery_fee > 0)
                                                    <tr>
                                                        <td colspan="4">Delivery Charge</td>
                                                        <td>{{ setting('default_currency').number_format($delivery->order->delivery_fee,'2','.','') }}</td>
                                                    </tr>
                                                @endif
                                                @if($delivery->order->contribution_amount > 0)
                                                    <tr>
                                                        <td colspan="4">Charity Contribution</td>
                                                        <td>{{ setting('default_currency').number_format($delivery->order->contribution_amount,'2','.','') }}</td>
                                                    </tr>
                                                @endif
                                                    <tr>
                                                        <td colspan="4">Total</td>
                                                        <td>{{ setting('default_currency').number_format($delivery->order->order_amount,'2','.','') }}</td>
                                                    </tr>
                                            </tbody>
                                        </table>

                                    </div>
                                </div>
                            </div>

                        @endforeach
                    @endif

                </div>

            </div>

            <div class="tab-pane fade" id="delivered" role="tabpanel" aria-labelledby="delivered-tab" style="height:350px; overflow-y: scroll;">
                
            </div>

          </div>

        </div>

    </div>
</div>

<script>

    var marker = false;
    var fullTime = 0;
    var pathTime = 0;

    var map = new google.maps.Map(document.getElementById("map"), {
        zoom: 4,
        //center: new google.maps.LatLng(39.31252574424125, -100.65206356448482),
        mapTypeId: google.maps.MapTypeId.ROADMAP
    });
    var directionsDisplay = new google.maps.DirectionsRenderer();
    var directionsService = new google.maps.DirectionsService();
    directionsDisplay.setOptions({suppressMarkers: true, suppressInfoWindows: true});
    directionsDisplay.setMap(map);
    var request = {
        origin: new google.maps.LatLng({{ setting('app_store_latitude') }}, {{ setting('app_store_longitude') }}),
        destination: new google.maps.LatLng({{ setting('app_store_latitude') }}, {{ setting('app_store_longitude') }}),
        travelMode: google.maps.DirectionsTravelMode.DRIVING
    };
    directionsService.route(request, function (response, status) {
        if (status == google.maps.DirectionsStatus.OK) {
            directionsDisplay.setDirections(response);
            updateMarker(response.routes[0].legs[0], 0);
            /*setInterval(function () {
                updateMarker(response.routes[0].legs[0], 5);
            }, 5000);*/
            console.log(response);
        }
    });

    function updateMarker(legs, delta) {
        if (marker) marker.setMap(null);
        var markerIcon = '{{ asset('img/auto.png') }}';
        if (pathTime + delta > 0) pathTime = pathTime + delta;
        else {
            marker = new google.maps.Marker({position: legs.steps[0].lat_lngs[0], map: map});
            marker.setIcon(markerIcon);
            return;
        }
        if (pathTime > fullTime) pathTime = fullTime;
        var points = getDirectionPoints(legs);
        var countPoints = 0;
        for (i = 0; i < points.length; i++) countPoints = countPoints + points[i];
        var curentPoint = parseInt(pathTime * countPoints / fullTime);
        var step = getStep(points, curentPoint);
        marker = new google.maps.Marker({position: legs.steps[step[0]].lat_lngs[step[1] - 1], map: map});
        marker.setIcon(markerIcon);
    }

    function getStep(points, curentPoint) {
        for (var i = 0; i < points.length; i++) {
            curentPoint = curentPoint - points[i];
            if (curentPoint <= 0) return [i, curentPoint + points[i]];
        }
    }

    function getDirectionPoints(data) {
        var points = [];
        for (var i = 0; i < data.steps.length; i++) {
            if (data.steps[i].lat_lngs) {
                points.push(data.steps[i].lat_lngs.length);
            }
        }
        return points;
    }

</script>


<script>

    $('.status-btn').click(function() {
        var id      = $(this).data('id');
        var status  = $(this).data('status');
        var url     = '{!!  route('deliveryTracker.update', [':deliveryID']) !!}';
        url         = url.replace(':deliveryID', id);
        var token   = "{{csrf_token()}}";

        $('.delivery-btn-accept').html('<i class="fa fa-spinner fa-spin"></i>');
        $('.delivery-btn-accept').attr("disabled", true);

        $('.delivery-btn-reject').html('<i class="fa fa-spinner fa-spin"></i>');
        $('.delivery-btn-reject').attr("disabled", true);

        $.ajax({
            type: 'POST',
            data: {
                '_token': token,
                '_method': 'PATCH',
                'status': status,
                'type': 'driver-update',
            },
            url: url,
            success: function (response) {
                if(response.status=='success') {
                    iziToast.success({
                        backgroundColor: 'linear-gradient(to right, #44a08d, #093637)',
                        messageColor: '#fff',
                        timeout: 3000, 
                        icon: 'fa fa-check', 
                        position: "bottomRight", 
                        iconColor:'#fff',
                        message: response.data
                    });
                } else {
                    iziToast.success({
                        backgroundColor: 'linear-gradient(to right, #ff4b1f, #ff9068)', 
                        messageColor: '#fff', 
                        timeout: 3000, 
                        icon: 'fa fa-remove', 
                        position: "bottomRight", 
                        iconColor:'#fff', 
                        message: response.data
                    });
                }
                $('.delivery-btn-accept').html('Accept');
                $('.delivery-btn-accept').attr("disabled", false);
                $('.delivery-btn-reject').html('Rejected');
                $('.delivery-btn-reject').attr("disabled", false);
                showTable();
            }
        });
    });


    $("#tile-1 .nav-tabs a").click(function() {
      var position = $(this).parent().position();
      var width = $(this).parent().width();
        $("#tile-1 .slider").css({"left":+ position.left,"width":width});
    });
    var actWidth = $("#tile-1 .nav-tabs").find(".active").parent("li").width();
    var actPosition = $("#tile-1 .nav-tabs .active").position();
    $("#tile-1 .slider").css({"left":+ actPosition.left,"width": actWidth});
</script>

