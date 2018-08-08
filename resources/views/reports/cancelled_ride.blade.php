@extends('layout.master');

@section('title')

Cancelled Rides Report - Go Cabs

@endsection

@section('content')
<style>
.panel > .panel-body {
     /*overflow: scroll !important;*/
}
.dialogBorder{
    left: 100px !important;
}
.report_filter .chosen-container-single .chosen-single span{
    text-align: center !important;
}

table.filter_report th {
    background-color: #293c4e;
    color: #fff;
}

.no-record{
	    border: 2px solid #000;
    padding: 30px;
    position: relative;
    top: 39px;
    z-index: 9999;
    text-transform: uppercase;
    margin-bottom: 59px;
    font-size: 30px;
    text-align: right;
}
</style>

@php
	$estatus='&export=excel';
	$pestatus='&export=pdf';
@endphp
@if(count(app('request')->input()) == 0)
	@php	
		$estatus='?export=excel';
		$pestatus='?export=pdf';
	@endphp
@endif
 <div class="rightside bg-grey-100">
			<!-- BEGIN PAGE HEADING -->
           <!--  <div class="page-head">
				<h1 class="page-title">Cancelled Rides Details</h1>
				
			</div> -->
			<!-- END PAGE HEADING -->

            <div class="container-fluid">
				<div class="row">
					<div class="col-lg-12">
             <div class="panel">
              <div class="panel-title bg-amber-200">
								<div class="panel-head">Cancelled Rides Report</div>
							</div>
            <div class="panel-body">
    
	      <!-- START OF FILTER-->
	<form name="searchfare" action="" class="report_filter" method="get">
	<div class="row" style="margin:auto">
        <div class="col-lg-12">
				
			<div class="form-horizontal" >
				 {{ csrf_field() }}

				 				<div class="form-group margin-bottom-20 col-md-3 margin-right-10">
									 	<select class="chosen-select-deselect form-control" name="franchise" id="franchise" @if($role != 1) disabled @endif >
											<option value="">--Select Your Franchise--</option>
                                            
                                            @foreach ($franchise as $r)
                                            <option value="{{$r->id}}"
                                        @if($r->id == $franchise_id) selected="selected" @endif
                                            >{{$r->company_name}}</option>
                                            @endforeach

									</select>
								</div>

								<div class="form-group margin-bottom-20 col-md-3 margin-right-10">
									 	<select class="chosen-select-deselect form-control" name="vehicle" id="vehicle" >
											<option value="">--Vehicle Number--</option>
										 @foreach ($vehicle_list as $vehicle)
										<option    value="{{$vehicle->id}}"  {{ app('request')->input('vehicle') == $vehicle->id ? "selected=selected":''}} >{{$vehicle->car_no }}</option>
										 @endforeach
									</select>
								</div>
								
								<div class="form-group margin-bottom-20 col-md-3 margin-right-10">
									 	<select class="chosen-select-deselect form-control" name="driver" id="driver" >
											<option value="">--Driver Name--</option>
										 @foreach ($driver_list as $driver)
										<option    value="{{$driver->id}}"  {{ app('request')->input('driver') == $driver->id ? "selected=selected":''}} >{{$driver->driver_id  }}</option>
										 @endforeach
									</select>
								</div>
								
								<div class="form-group margin-bottom-20 col-md-3 margin-right-10">
									 	<select class="chosen-select-deselect form-control" name="passenger" id="passenger" >
											<option value="">--Passenger Name--</option>
										 @foreach ($passenger_list as $passenger)
										<option    value="{{$passenger->id}}"  {{ app('request')->input('passenger') == $passenger->id ? "selected=selected":''}} >{{$passenger->name }}</option>
										 @endforeach
									</select>
								</div>
								
									<div class="form-group margin-bottom-20 col-md-3 margin-right-10">
									 	<input type="text" name="trip_id" class="form-control" placeholder="Trip ID"  value="{{ app('request')->input('trip_id')}}">
								</div>
									<div class="form-group margin-bottom-20 col-md-3 margin-right-10">
									 	<input type="text" value="{{ app('request')->input('from_date')}}" readonly="" name="from_date" class="form-control from_date symval"  placeholder="Start From Date" >
								</div>
								
									<div class="form-group margin-bottom-20 col-md-3 margin-right-10">
									 	 <input type="text" value="{{ app('request')->input('to_date')}}" readonly="" name="to_date" class="form-control to_date symval" placeholder="Start To Date" >
								</div>
								
 							<div class="form-group margin-bottom-20 col-md-3 margin-right-10 " ></div>
                <div class="form-group margin-bottom-20 col-md-3 pull-right" style=" right: -17px; position: relative;">
          				<input type="submit" class="btn btn-dark bg-red-600 color-white" id="button_submit" value="Search" />
          				<button type="button" class="btn btn-dark bg-grey-400 color-black" onclick="window.parent.location='{{ URL::to('/cancel_rides')}}'" >Reset</button>
          		</div> 

				</div>
			</div>
			</div>
	</form>
	<!-- END OF FILTER-->
	
<div id="vehiecle_ride_details" style="height:500px;"></div>
<div class="row">
<div class="expt_btn pull-right">
 <a href="{{Request::fullUrl()}}{{$estatus}}" @if(count($cancelled_list)== 0) {{"disabled=disabled"}}@endif class="margin-bottom-20 btn btn-dark bg-red-600 color-white">Download EXCEL</a> <a href="{{Request::fullUrl()}}{{$pestatus}}" @if(count($cancelled_list)== 0) {{"disabled=disabled"}}@endif  class="margin-bottom-20 btn btn-dark bg-red-600 color-white">Download PDF</a>
</div>
</div>
<div class="row  tbl_grid_report"  >
@if(count($cancelled_list)>0)

<!--show the data report-->
<table class="filter_report table" cellspacing="0" cellpadding="10" widthn="100%" style="font-size:11px;" >
<tr valign="top" align="center">
	<th align="left">Trip ID</th>
	<th align="left">Driver Name &amp; ID	</th>
	<th align="left">Passenger Name &amp; ID</th>
	<th align="left">Vehicle Number &amp; Type</th>
	<th align="left">Ride Date</th>
	<th align="left">Booking Type	</th>
	<th align="left">Pickup Location</th>
	<th align="left">Drop Location</th>
	
	<th align="left">Cancelled By</th>
	<th align="left" width="20%">Cancelled Reason</th>
</tr>
<tr>
	@foreach($cancelled_list as $list)
		<td>{{$list->reference_id}}</td>
		<td>{{$list->driver_name}}</td>
		<td>{{$list->passanger_name}}</td>
		<td>{{$list->car_no}}</td>
		<td>{{$list->date_of_ride}}</td>
		<td>{{$list->ride_type}}</td>
		<td>{{$list->source_location}}</td>
		<td>{{$list->destination_location}}</td>
		<td>{{$list->Cancelled_ByD}}{{$list->Cancelled_ByC}}</td>
		<td>{{$list->cancel_notes}}</td>
		
</tr>
	@endforeach

</table>

@else
	<div class="no-record">No Ride are available</div>
	
@endif
</div>
		<script src="{{ URL::asset("public/js/amcharts.js") }}" type="text/javascript"></script>
    <script src="{{ URL::asset("public/js/serial.js") }}" type="text/javascript"></script>
    <script src="{{ URL::asset("public/js/light.js") }}"></script>

<script>
		/*********** LAST THREE MONTH RIDE DETAILS ******/

 AmCharts.makeChart( "vehiecle_ride_details", {
  "type": "serial",
  "theme": "none",
  "autoMarginOffset": 40,
	"marginRight": 70,
	"marginTop": 70,
  "dataDateFormat": "YYYY-MM-DD",
  "valueAxes": [ {
    "id": "v1",
    "axisAlpha": 0,
    "position": "left",
    "ignoreAxisWidth": false,
    "title": "Total Number of Rides"
  } ],
  "balloon": {
    "borderThickness": 1,
    "shadowAlpha": 0
  },
  "graphs": [ {
    "id": "g1",
    "balloon": {
      "drop": true,
      "adjustBorderColor": false,
      "color": "#ffffff",
      "type": "smoothedLine"
    },
    "fillAlphas": 0.2,
    "bullet": "round",
    "bulletBorderAlpha": 1,
    "bulletColor": "#FFFFFF",
    "bulletSize": 5,
    "hideBulletsCount": 50,
    "lineThickness": 2,
    "title": "red line",
    "useLineColorForBulletBorder": true,
    "valueField": "value",
    "balloonText": "<span style='font-size:18px;'>[[value]]</span>"
  } ],
  "chartCursor": {
    "valueLineEnabled": true,
    "valueLineBalloonEnabled": true,
    "cursorAlpha": 0,
    "zoomable": false,
    "valueZoomable": true,
    "valueLineAlpha": 0.5
  },
  "chartScrollbar": {
						"enabled": true
					},
  "valueScrollbar": {
    "autoGridCount": true,
    "color": "#000000",
    "scrollbarHeight": 50
  },
  "categoryField": "date",
  "categoryAxis": {
    "parseDates": true,
    "dashLength": 1,
    "minorGridEnabled": true
  },
  "export": {
    "enabled": true
  },
  "titles": [
		{
			"id": "successful_rides_chart",
			"size": 18,
			"text": ""
		}
	],
	"dataProvider": [ 
						@if(count($cancelled_ride_graph) > 0)
					  @foreach($cancelled_ride_graph as $ride_details)
			  			{
						"date": "{{$ride_details->ride_date}}",
   					 "value": {{$ride_details->ride_count}}
						},
					@endforeach	
					@else
							{
						"date": "<?php echo date('Y-m-d'); ?>",
   					 "value": 0
						},
						@endif
	
		]
 
} );

</script>
				<!-- BEGIN FOOTER -->
				@include('includes.footer')
				<!-- END FOOTER -->
            </div><!-- /.container-fluid -->
        </div>
  
@endsection
