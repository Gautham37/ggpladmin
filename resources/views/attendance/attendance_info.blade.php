
	<div class="modal-header">
		<h6 class="modal-title" id="myModalLabel"> {{$attendance->user->name}} Attendance Details</h6>
		<button type="button" class="close" data-dismiss="modal" aria-label="Close">
	  		<span aria-hidden="true">&times;</span>
		</button>
	</div>

    <div class="modal-body">
        
        <div class="row">
        	
        	<div class="col-md-6">
        		<div class="card">

                <div class="card-header attendance-widget-header">
                  <h3 class="card-title">
                    <span>Timesheet &nbsp;&nbsp;{{$firstClockIn->clock_in_time->format('d M Y')}}</span>
                  </h3>
                </div>

                <div class="card-body">

	        		<div class="punch-det text-center">
	                   <h6><b>Punch In at</b></h6>
	                   <p>{{ $startTime->format(setting('app_time_format')) }}</p>
	                </div>	

	                <div class="punch-info">
	                 	<div class="punch-hours">
	                 		<span>{{$totalTime}} hrs</span>  
	                 	</div>
	              	</div>

	              	<div class="punch-det text-center">
	                   <h6><b>Punch Out at</b></h6>
	                   <p>
	                   		{{ $endTime->format(setting('app_time_format')) }}
	                   		@if($notClockedOut) 
	                   			Not Clocked Out
	                   		@endif
	                   </p>
	                   @if($notClockedOut)
	                   
	                   @else
	                    @if($attendance->clock_in_time->format('d-m-Y')==date('d-m-Y'))
	                   	<p>Wrongly clocked out ? 
	                   	<button data-id="{{$attendance->id}}" onclick="enableAttendance(this);" class="btn btn-secondary btn-sm">Enable</button> </p>
	                   	@endif
	                   @endif
	                </div>


	                <div class="statistics">
                         <div class="row">
                            <div class="col-md-6 col-6 text-center">
                               <div class="stats-box">
                                  <p>Break</p>
                                  <h6>
                                   	{{$break}}
                                  </h6> 
                               </div>
                            </div>
                            <div class="col-md-6 col-6 text-center">
                               <div class="stats-box">
                                  <p>Production</p>
                                  <h6> {{date('H:i:s',strtotime('00:00:00') + $working_hours)}} </h6>
                               </div>
                            </div>
                         </div>
                      </div>
            	</div>

            	</div>	
        	</div>

        	<div class="col-md-6">

        		<div class="card recent-activity">

                    <div class="card-header attendance-widget-header">
                      <h3 class="card-title">
                        <span>Today Activity</span>
                      </h3>
                    </div>

                   <div class="card-body"> 

                      <ul class="res-activity-list">
                                        
		                  @if(isset($attendanceActivity) && count($attendanceActivity) >0)
		                    @foreach($attendanceActivity as $punches)  
		                     <li>
		                        <p class="mb-0">{{ucfirst(str_replace("_"," ",$punches->punch_type))}} at</p>
		                        <p class="res-activity-time">
		                            <i class="fa fa-clock-o"></i>
		                            {{$punches->punch_time->format(setting('app_time_format'))}}
		                            &nbsp;&nbsp;
		                            <a href="javascript:;" data-id="{{$punches->id}}" onclick="editPunch(this);">
		                            	<i class="fa fa-edit text-warning"></i>
		                            </a>
		                        </p>
		                     </li>
		                    @endforeach
		                  @endif

		              	</ul>
                   </div>
                </div>

        	</div>

        </div>

    </div> 

    <div class="modal-footer">

    	@can('attendance.destroy')
    	<a href="javascript:;" class="btn bg-red text-white btn-sm" onclick="deleteAttendance({!!$attendance->id!!})" id="attendance-edit" data-attendance-id="{!!$attendance->id!!}" >
        	<i class="fa fa-times"></i>
        </a>
        @endcan

        @can('attendance.edit')
      	<a href="javascript:;" class="btn bg-yellow text-white btn-sm" onclick="editAttendance({!!$attendance->id!!})" id="attendance-edit" data-attendance-id="{{!!$attendance->id!!}}" >
      		<i class="fa fa-pencil"></i>
      	</a>
      	@endcan

        <button type="button" class="btn btn-default btn-sm" data-dismiss="modal">Close</button>

    </div>

    <script>

	    function deleteAttendance(id){

	    	iziToast.show({
			    theme: 'dark',
			    icon: 'fa fa-trash',
			    overlay: true,
			    title: 'Delete',
			    message: 'Are you sure?',
			    position: 'center', // bottomRight, bottomLeft, topRight, topLeft, topCenter, bottomCenter
			    progressBarColor: 'yellow',
			    backgroundColor: 'linear-gradient(to right, #ff4b1f, #ff9068)', 
                messageColor: '#fff', 
			    buttons: [
			        ['<button>Yes</button>', function (instance, toast) {
			            
			        	var url   = "{{ route('attendance.destroy',':id') }}";
		                url 	  = url.replace(':id', id);
		                var token = "{{ csrf_token() }}";

		                $.ajax({
		                    type: 'POST',
		                    url: url,
		                    data: {'_token': token, '_method': 'DELETE'},
		                    success: function (response) {
		                    	//console.log(response);
		                        //if (response.status == "success") {
		                            iziToast.success({
						                backgroundColor: 'linear-gradient(to right, #44a08d, #093637)',
						                messageColor: '#fff',
						                timeout: 3000, 
						                icon: 'fa fa-check', 
						                position: "topRight", 
						                iconColor:'#fff',
						                message: 'Attendance Deleted Sucessfully'
						            });
		                            showTable();
		                            $('#attendanceModal').modal('hide');
		                            instance.hide({transitionOut: 'fadeOutUp'}, toast, 'buttonName');
		                        /*} else {
		                        	iziToast.success({
					                    backgroundColor: 'linear-gradient(to right, #ff4b1f, #ff9068)', 
					                    messageColor: '#fff', 
					                    timeout: 3000, 
					                    icon: 'fa fa-remove', 
					                    position: "topRight", 
					                    iconColor:'#fff', 
					                    message: response.statusTex
					                });
					                showTable();
		                            $('#attendanceModal').modal('hide');
		                        }*/
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
