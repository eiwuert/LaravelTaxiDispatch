@php
ob_start();
 @endphp

<link href="{{ URL::asset("public/css/report.css") }}" rel="stylesheet" />

<div class="row" class="tbl_grid_report" >
@if(count($rejected_list)>0)
<!--show the data report-->
<table class="p_table" cellspacing="5" cellpadding="10" >
<tr class="p_logo_header_row">
	<td  align="left" colspan="6"><img src="{{asset('public/css/vehicle_icon/report_logo.png')}}"></td>
	<td colspan="2"  align="center"><h2>Rejected Ride Details</h2></td>
</tr>
</table>
<table class="filter_report" cellspacing="0" cellpadding="10"  style="font-size:11px;" style="overflow: hidden; overflow: inherit;width:100%" >
<tr valign="top" align="center">
	<th align="left" width="10%">Trip ID</th>
	<th align="left"  width="10%">Driver Name &amp; ID	</th>
	<th align="left"  width="10%">Passenger Name &amp; ID</th>
	<th align="left"  width="10%">Vehicle Number &amp; Type</th>
	<th align="left"  width="10%">Ride Date</th>
	<th align="left"  width="10%">Booking Type	</th>
	<th align="left"  width="15%">Pickup Location</th>
	<th align="left"  width="15%">Drop Location</th>
</tr>

	@foreach($rejected_list as $list)
	<tr>
		<td>{{$list->reference_id}}</td>
		<td>{{$list->driver_name}}</td>
		<td>{{$list->passanger_name}}</td>
		<td>{{$list->car_no}}</td>
		<td>{{$list->date_of_ride}}</td>
			<td>{{$list->ride_type}}</td>
		<td>{{$list->source_location}}</td>
		<td>{{$list->destination_location}}</td>
		</tr>
	@endforeach

</table>
@else
	<div>No Ride are available</div>
@endif
</div>
		
			