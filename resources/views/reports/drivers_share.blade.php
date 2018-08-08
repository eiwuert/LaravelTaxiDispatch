@extends('layout.master');

@section('title')

Share Details Report - Go Cabs

@endsection

@section('content')
<style>
.panel > .panel-body {
    /* overflow: scroll !important;*/
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
    text-align: center
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
				<h1 class="page-title">Share Details</h1>
				<!-- BEGIN BREADCRUMB -->
				
				<!-- END BREADCRUMB -->
			<!--</div> -->
			<!-- END PAGE HEADING -->

            <div class="container-fluid">
				<div class="row">
					<div class="col-lg-12">
             <div class="panel">
              <div class="panel-title bg-amber-200">
								<div class="panel-head">Share Details Report</div>
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
									 	<input type="text" value="{{ app('request')->input('from_date')}}" readonly="" name="from_date" class="form-control from_date symval"  placeholder="Start From Date" >
								</div>
								
									<div class="form-group margin-bottom-20 col-md-3 margin-right-10">
									 	 <input type="text" value="{{ app('request')->input('to_date')}}" readonly="" name="to_date" class="form-control to_date symval" placeholder="Start To Date" >
								</div>
								
 							<div class="form-group margin-bottom-20 col-md-3 margin-right-10 " ></div>
                <div class="form-group margin-bottom-20 col-md-3 pull-right" style=" right: -17px; position: relative;">
          				<input type="submit" class="btn btn-dark bg-red-600 color-white" id="button_submit" value="Search" />
          				<button type="button" class="btn btn-dark bg-grey-400 color-black" onclick="window.parent.location='{{ URL::to('/total_rides')}}'" >Reset</button>
          		</div> 

				</div>
			</div>
			</div>
	</form>
	<!-- END OF FILTER-->
	
<div class="row">
<div class="expt_btn pull-right">
 <a href="{{Request::fullUrl()}}{{$estatus}}" @if(count($driver_share)== 0) {{"disabled=disabled"}}@endif class="margin-bottom-20 btn btn-dark bg-red-600 color-white">Download EXCEL</a> <a href="{{Request::fullUrl()}}{{$pestatus}}" @if(count($driver_share)== 0) {{"disabled=disabled"}}@endif  class="margin-bottom-20 btn btn-dark bg-red-600 color-white">Download PDF</a>
</div>
</div>
<div class="row  tbl_grid_report"  >
@if(count($driver_share)>0)

<!--show the data report-->
<table class="filter_report table" cellspacing="0" cellpadding="10" widthn="100%" style="font-size:11px;" >
<tr valign="top" align="center">
	<th align="left">Date of Ride</th>
	<th align="left">Driver Name &amp; ID	</th>
	<th align="left">Vehicle Number</th>
	<th align="left">Total share amount of the Driver</th>
	@if($franchise_id == 0)<th align="left">Total share amount of the Company</th>@endif
	<th align="left">Total Share amount of the Franchise</th>

</tr>
<tr>
	@foreach($driver_share as $list)
		<td>{{$list->date_of_ride}}</td>
		<td>{{$list->driver_name}}</td>
		<td>{{$list->car_no}}</td>
		<td>{{$list->driver_share}}</td>
		@if($franchise_id == 0)<td>{{$list->company_share}}</td>@endif
		<td>{{$list->franchise_share}}</td>
</tr>
	@endforeach
<tr>
		<td></td>
		<td></td>
		<td></td>
		<td>Total : {{$tot_drivershare}}</td>
		@if($franchise_id == 0)<td>Total : {{$tot_companyshare}}</td>@endif
		<td>Total : {{$tot_franchiseshare}}</td>
</tr>
</table>

@else
	<div class="no-record">No Ride are available</div>
	
@endif
</div>

				<!-- BEGIN FOOTER -->
				@include('includes.footer')
				<!-- END FOOTER -->
            </div><!-- /.container-fluid -->
        </div>
  
@endsection
