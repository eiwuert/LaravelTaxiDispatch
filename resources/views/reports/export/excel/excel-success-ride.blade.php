@php
ob_start();
 
@endphp

<link href="{{ URL::asset("public/css/report.css") }}" rel="stylesheet" />

<div class="row" class="tbl_grid_report" >
@if(count($success_list)>0)
<!--show the data report-->
<table class="table" cellspacing="5" cellpadding="10"  style="font-size:11px;" >

<tr class="logo_header_row">
	<!--<td  align="left" colspan="6"><img src="/var/www/html/goapp/public/css/vehicle_icon/report_logo.png"></td>-->
	<td colspan="12"  align="center"><h2>Rejected Ride Details</h2></td>
</tr>

<tr valign="top" align="center">
	<th align="left">Trip ID</th>
	<th align="left">Driver Name &amp; ID	</th>
	<th align="left">Passenger Name &amp; ID</th>
	<th align="left">Vehicle Number &amp; Type</th>
	<th align="left">Ride Date</th>
	<th align="left">Booking Type	</th>
	<th align="left">Pickup Location</th>
	<th align="left">Drop Location</th>
	<th align="left"  width="13%">Payment Type</th>
	<th align="left"  width="13%">Driver's Share</th>
	<th align="left"  width="13%">Franchise Share</th>
	<th align="left"  width="13%">Company Share</th>
</tr>

	@foreach($success_list as $list)
	<tr>
		<td>{{$list->reference_id}}</td>
		<td>{{$list->driver_name}}</td>
		<td>{{$list->passanger_name}}</td>
		<td>{{$list->car_no}}</td>
		<td>{{$list->date_of_ride}}</td>
		<td>{{$list->ride_type}}</td>
		<td>{{$list->source_location}}</td>
		<td>{{$list->destination_location}}</td>
		<td>{{$list->payment_type}}</td>
		<td>{{$list->driver_share}}</td>
		<td>{{$list->franchise_share}}</td>
		<td>{{$list->company_share}}</td>
		</tr>
	@endforeach

</table>
@else
	<div>No Ride are available</div>
@endif
</div>
		
			
