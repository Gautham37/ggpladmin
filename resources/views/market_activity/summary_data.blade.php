<style>

h2 {
	 margin: 5%;
	 text-align: center;
	 font-size: 2rem;
	 font-weight: 100;
}
 .timeline {
	 display: flex;
	 flex-direction: column;
	 width: 100%;
	 margin: 1% auto;
}
 .timeline__event {
	 background: #fff;
	 margin-bottom: 20px;
	 position: relative;
	 display: flex;
	 margin: 5px 0;
	 border-radius: 8px;
	 box-shadow: 0 30px 60px -12px rgba(50, 50, 93, 0.25), 0 18px 36px -18px rgba(0, 0, 0, 0.3), 0 -12px 36px -8px rgba(0, 0, 0, 0.025);
}
 .timeline__event__title {
	 font-size: 16px;
	 line-height: 1.4;
	 text-transform: uppercase;
	 font-weight: 600;
	 color: #9251ac;
	 letter-spacing: 1.5px;
}
 .timeline__event__content {
	 padding: 5px 20px;
}
 .timeline__event__date {
	 color: #f6a4ec;
	 font-size: 13px;
	 font-weight: 600;
	 white-space: nowrap;
}
 .timeline__event__icon {
	 border-radius: 8px 0 0 8px;
	 background: #9251ac;
	 display: flex;
	 align-items: center;
	 justify-content: center;
	 flex-basis: 5%;
	 font-size: 2rem;
	 color: #9251ac;
	 padding: 20px;
}
 .timeline__event__icon i {
	 position: absolute;
	 top: 50%;
	 left: -65px;
	 font-size: 2.5rem;
	 transform: translateY(-50%);
}
 .timeline__event__description {
	 flex-basis: 60%;
}
 .timeline__event:after {
	 content: "";
	 width: 2px;
	 height: 100%;
	 background: #9251ac;
	 position: absolute;
	 top: 52%;
	 left: -3.5rem;
	 z-index: -1;
}
/* .timeline__event:before {
	 content: "";
	 width: 5rem;
	 height: 5rem;
	 position: absolute;
	 background: #f6a4ec;
	 border-radius: 100%;
	 left: -6rem;
	 top: 50%;
	 transform: translateY(-50%);
	 border: 2px solid #9251ac;
}*/
 .timeline__event--type2:before {
	 background: #87bbfe;
	 border-color: #555ac0;
}
 .timeline__event--type2:after {
	 background: #555ac0;
}
 .timeline__event--type2 .timeline__event__date {
	 color: #87bbfe;
}
 .timeline__event--type2 .timeline__event__icon {
	 background: #555ac0;
	 color: #555ac0;
}
 .timeline__event--type2 .timeline__event__title {
	 color: #555ac0;
}
 .timeline__event--type3:before {
	 background: #aff1b6;
	 border-color: #24b47e;
}
 .timeline__event--type3:after {
	 background: #24b47e;
}
 .timeline__event--type3 .timeline__event__date {
	 color: #aff1b6;
}
 .timeline__event--type3 .timeline__event__icon {
	 background: #24b47e;
	 color: #24b47e;
}
 .timeline__event--type3 .timeline__event__title {
	 color: #24b47e;
}
 .timeline__event:last-child:after {
	 content: none;
}
 @media (max-width: 786px) {
	 .timeline__event {
		 flex-direction: column;
	}
	 .timeline__event__icon {
		 border-radius: 4px 4px 0 0;
	}
}
 

</style>

@if(count($data) > 0)
	<div class="timeline">
	@foreach($data as $note)

		<div class="timeline__event  animated fadeInUp delay-3s timeline__event--type1">
	    	<div class="timeline__event__icon ">
	      		<div class="timeline__event__date">
	        		{{$note->created_at->format('d M Y')}}
	      		</div>
	    	</div>
	    	<div class="timeline__event__content ">
		      	<div class="timeline__event__title">
		        	{{$note->action}}
		      	</div>
		      	<div class="timeline__event__description">
		        	<p>{{$note->notes}}<b>@if($note->createdby) - by {{$note->createdby->name}} @endif</b></p>
		      	</div>
	    	</div>
	  	</div>

	@endforeach
	</div>
@else
	
	<p class="text-center">There is no data found</p>

@endif
