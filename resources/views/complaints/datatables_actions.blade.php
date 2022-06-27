<div class='btn-group btn-group-sm'>
  <!--@can('categories.show')-->
  <!--<a data-toggle="tooltip" data-placement="bottom" title="{{trans('lang.view_details')}}" href="{{ route('categories.show', $id) }}" class='btn btn-link'>-->
  <!--  <i class="fa fa-eye" style="font-size:20px;"></i>-->
  <!--</a>-->
  <!--@endcan-->

  @can('complaints.edit')
  <a data-toggle="tooltip" data-placement="bottom" title="{{trans('lang.complaint_edit')}}" href="{{ route('complaints.edit', $id) }}" class='btn btn-link'>
    <i class="fa fa-edit" style="font-size:20px;"></i>
  </a>
  @endcan
  
  <!--@can('complaints.comments')-->
  <!--@if($staff_members!='')-->
  <!--<a data-placement="bottom" title="{{trans('lang.complaint_comments')}}" onclick="complaintsComments(this);" data-id="{{$id}}"  data-toggle="modal" data-target="#complaintsCommentsModal" class='btn btn-sm btn-link'>-->
  <!--  <i class="fa fa-comments" style="font-size:20px;"></i>-->
  <!--</a>-->
  <!--@endif-->
  <!--@endcan-->
  
 @can('complaints.comments')
 @if($staff_members!='')
  <a data-toggle="tooltip" data-placement="bottom" title="{{trans('lang.complaint_comments')}}" href="{{route('complaints.comments', ['id' => $id])}}" class='btn btn-link'>
    <i class="fa fa-comments" style="font-size:20px;"></i>
  </a>
 @endif
 @endcan
 
  @can('complaints.closeComplaints')
 @if($staff_members!='')
  <a data-toggle="tooltip" data-placement="bottom" title="{{trans('lang.complaint_close_complaints')}}" href="{{route('complaints.closeComplaints', ['id' => $id])}}" class='btn btn-link'>
    <i class="fa fa-close" style="font-size:20px;"></i>
  </a>
 @endif
 @endcan

</div>
 