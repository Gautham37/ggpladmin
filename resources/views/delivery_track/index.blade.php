@extends('layouts.app')

@section('content')

<!-- Content Header (Page header) -->
<div class="content-header">
  <div class="container-fluid">
    <div class="row mb-2">
      <div class="col-sm-6">
        <h1 class="m-0 text-dark">Delivery Tracker<small class="ml-3 mr-3">|</small><small>Delivery Tracker Management</small></h1>
      </div><!-- /.col -->
      <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
          <li class="breadcrumb-item"><a href="{{url('/dashboard')}}"><i class="fa fa-dashboard"></i> Dashboard</a></li>
          <li class="breadcrumb-item"><a href="{!! route('deliveryTracker.index') !!}">Delivery Tracker</a>
          </li>
          <li class="breadcrumb-item active">Delivery List</li>
        </ol>
      </div><!-- /.col -->
    </div><!-- /.row -->
  </div><!-- /.container-fluid -->
</div>
<!-- /.content-header -->

<div class="content">
  <div class="clearfix"></div>
  @include('flash::message')
  <div class="card">
    <div class="card-header">
      <ul class="nav nav-tabs align-items-end card-header-tabs w-100">
        <li class="nav-item">
          <a class="nav-link active" href="{!! url()->current() !!}"><i class="fa fa-list mr-2"></i>Delivery List</a>
        </li>
      </ul>
    </div>
    <div class="card-body">
      

        <div class="row">  

        </div>

        <div class="row">
            <div class="col-lg-12">
                <div class="table-responsive">
                  <div id="delivery-data"></div>  
                </div>
            </div>
        </div>



      <div class="clearfix"></div>
    </div>
  </div>
</div>

<!-- large modal -->
<div class="modal fade" id="deliveryModal" tabindex="-1" role="dialog" aria-labelledby="basicModal" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
        Loading....
    </div>
  </div>
</div>

@endsection


@push('scripts_lib')

  <script type="text/javascript">
    /*$(function () {  
      var i = 1;
      var table = $('.data-table').DataTable({
          processing: true,
          serverSide: true,
          ajax: "{{route('attendance.index')}}?year="+$('#year').val()+"&month="+$('#month').val()+"&userId="+$('#user_id').val(),
          columns: [
            @if(auth()->user()->hasAllPermissions('attendance.summary'))
                {data: 'name', name: 'name', orderable: false, searchable: true, class: "text-left"},
            @endif
              {data: 'date', name: 'date', orderable: false, searchable: true, class: "text-center"},
              {data: 'clock_in_time', name: 'clock_in_time', orderable: false, searchable: true, class: "text-center"},
              {data: 'clock_out_time', name: 'clock_out_time', orderable: false, searchable: true, class: "text-center"},
              {data: 'production', name: 'production', orderable: false, searchable: true, class: "text-center"},
              {data: 'break', name: 'break', orderable: false, searchable: true, class: "text-center"},
              {data: 'overtime', name: 'overtime', orderable: false, searchable: true, class: "text-center"},
          ],
          rowGroup: {
             dataSrc: 'date'
          },
      });*/
      
        $('#apply-filter').click(function () {
            $('#apply-filter').html('<i class="fa fa-spinner fa-spin"></i>');
            $('#apply-filter').attr("disabled", true); 
            showTable();
            /*table.ajax.url("{{route('attendance.index')}}?year="+$('#year').val()+"&month="+$('#month').val()+"&userId="+$('#user_id').val()).load(function() {
                $('#apply-filter').html('<i class="fa fa-filter"></i> Search');
                $('#apply-filter').attr("disabled", false);    
            });*/   
        });
      
    /*});*/

      function showTable() {
          var url   = "{!!route('deliveryTracker.index')!!}";
          var token = "{{ csrf_token() }}";
          $.ajax({
              type: 'GET',
              data: {
                  '_token': token,
                  'type': 'summarydata'
              },
              url: url,
              success: function (response) {
                  $('#delivery-data').html(response.data);
              }
          });
      }
      showTable();


      $('#attendance-data').on('click', '.view-attendance',function () {
          var attendanceID = $(this).data('attendance-id');
          var url = '{!!route('attendance.show',':attendanceID')!!}';
          url = url.replace(':attendanceID', attendanceID);
          console.log(url);

          $('#attendanceModal').modal('show');
          $.ajax({
              type: 'GET',
              data: { },
              url: url,
              success: function (response) {
                 $('.modal-content').html(response);
              }
          });
      });

      $('#attendance-data').on('click', '.edit-attendance',function (event) {
          var attendanceDate = $(this).data('attendance-date');
          var userData       = $(this).closest('tr').children('td:first');
          var userID         = userData[0]['firstChild']['nextSibling']['firstChild']['nextSibling']['dataset']['employeeId'];
          var year           = $('#year').val();
          var month          = $('#month').val();

          var url = '{!! route('attendance.mark', [':userid',':day',':month',':year',]) !!}';
          url = url.replace(':userid', userID);
          url = url.replace(':day', attendanceDate);
          url = url.replace(':month', month);
          url = url.replace(':year', year);

          $('#attendanceModal').modal('show');
          $.ajax({
              type: 'GET',
              data: {
                  userid: userID,
                  day: attendanceDate,
                  month: month,
                  year: year
              },
              url: url,
              success: function (response) {
                 $('.modal-content').html(response);
              }
          });

      });

      $('.punch-btn').click(function() {

          var type = $(this).data('type');

          if(type=='punch-in') {
            
            var break_type = $('#break_type').val();  
            $('.punch-btn').html('<i class="fa fa-spinner fa-spin"></i>');
            $('.punch-btn').attr("disabled", true);
            $.ajax({
                url: "{{route('attendance.punch')}}",
                type: 'POST',
                data: {
                  '_token':"{{ csrf_token() }}",
                  punchtype: type,
                  break_type: break_type
                }         
            }).done(function(results) {

                iziToast.success({
                    backgroundColor: 'linear-gradient(to right, #44a08d, #093637)',
                    messageColor: '#fff',
                    timeout: 3000, 
                    icon: 'fa fa-check', 
                    position: "bottomRight", 
                    iconColor:'#fff',
                    message: 'Punch In Succesfully'
                });
                setTimeout(function () { location.reload(); }, 1000);

            }).fail(function(results) {

                  iziToast.success({
                      backgroundColor: 'linear-gradient(to right, #ff4b1f, #ff9068)', 
                      messageColor: '#fff', 
                      timeout: 3000, 
                      icon: 'fa fa-remove', 
                      position: "bottomRight", 
                      iconColor:'#fff', 
                      message: results.statusText
                  });
                  setTimeout(function () { location.reload(); }, 1000);
            });

          } else {

              var break_type = $('#break_type').val();

              iziToast.show({
              theme: 'dark',
              overlay: true,
              icon: 'fa fa-sign-out',
              title: "{{auth()->user()->name}}",
              message: 'Are you sure to Punch Out!',
              position: 'center', // bottomRight, bottomLeft, topRight, topLeft, topCenter, bottomCenter
              progressBarColor: 'rgb(0, 255, 184)',
              buttons: [
                  ['<button>Yes</button>', function (instance, toast) {
                      

                    $('.punch-btn').html('<i class="fa fa-spinner fa-spin"></i>');
                    $('.punch-btn').attr("disabled", true);
                    $.ajax({
                        url: "{{route('attendance.punch')}}",
                        type: 'POST',
                        data: {
                          '_token':"{{ csrf_token() }}",
                          punchtype: type,
                          break_type: break_type
                        }         
                    }).done(function(results) {

                        iziToast.success({
                            backgroundColor: 'linear-gradient(to right, #44a08d, #093637)',
                            messageColor: '#fff',
                            timeout: 3000, 
                            icon: 'fa fa-check', 
                            position: "bottomRight", 
                            iconColor:'#fff',
                            message: 'Punch Out Succesfully'
                        });
                        setTimeout(function () { location.reload(); }, 1000);

                    }).fail(function(results) {

                          iziToast.success({
                              backgroundColor: 'linear-gradient(to right, #ff4b1f, #ff9068)', 
                              messageColor: '#fff', 
                              timeout: 3000, 
                              icon: 'fa fa-remove', 
                              position: "bottomRight", 
                              iconColor:'#fff', 
                              message: results.statusText
                          });
                          setTimeout(function () { location.reload(); }, 1000);
                    });


                  }, true], // true to focus
                  ['<button>Close</button>', function (instance, toast) {
                      instance.hide({
                          transitionOut: 'fadeOutUp',
                          onClosing: function(instance, toast, closedBy){
                              console.info('closedBy: ' + closedBy); // The return will be: 'closedBy: buttonName'
                          }
                      }, toast, 'buttonName');
                  }]
              ],
              onOpening: function(instance, toast){
                  console.info('callback abriu!');
              },
              onClosing: function(instance, toast, closedBy){
                  console.info('closedBy: ' + closedBy); // tells if it was closed by 'drag' or 'button'
              }
          });

          }  

      });


    function enableAttendance(elem) {
        var id = $(elem).data('id');

        iziToast.show({
            theme: 'dark',
            icon: 'fa fa-toggle-on',
            overlay: true,
            title: 'Enable',
            message: 'Are you sure?',
            position: 'center', // bottomRight, bottomLeft, topRight, topLeft, topCenter, bottomCenter
            progressBarColor: 'yellow',
            backgroundColor: 'linear-gradient(to right, #f0ca0d, #f89500)', 
            messageColor: '#fff', 
            buttons: [
                ['<button>Yes</button>', function (instance, toast) {
                   
                    var url = "{!!url('attendance/enable')!!}";
                    var token = "{{ csrf_token() }}";
                    $.ajax({
                        type: 'POST',
                        data: {
                            '_token': token,
                            id: id
                        },
                        url: url,
                        success: function (response) {
                            $('#attendanceModal').modal('hide');
                            iziToast.success({
                                backgroundColor: 'linear-gradient(to right, #44a08d, #093637)',
                                messageColor: '#fff',
                                timeout: 3000, 
                                icon: 'fa fa-check', 
                                position: "topRight", 
                                iconColor:'#fff',
                                message: 'Enabled Sucessfully'
                            });
                            instance.hide({transitionOut: 'fadeOutUp'}, toast, 'buttonName'); 
                            showTable();
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


            
    }

  </script>

@endpush


