@php
ob_start();
error_reporting(-1);
ini_set('display_errors', 'On');
ini_set('memory_limit', '-1');
ini_set('max_execution_time', 0); 
 @endphp

<link href="{{ URL::asset("public/css/report.css") }}" rel="stylesheet" />
<div class="row" class="tbl_grid_report" >
@if(count($total_list)>0)
<!--show the data report-->
<table class="p_table" cellspacing="5" cellpadding="10" >
<tr class="p_logo_header_row">
	<td  align="left" colspan="2"><img src="{{asset('public/css/vehicle_icon/report_logo.png')}}"></td>
	<td colspan="4"  align="center"><h2>Total Ride Details</h2></td>
</tr>
</table>
<table class="filter_report" cellspacing="0" cellpadding="10"  style="font-size:11px;" >
<tr valign="top" align="center">
	<th align="left" width="10%">Trip ID</th>
	<th align="left"  width="10%">Driver Name &amp; ID	</th>
	<th align="left"  width="10%">Passenger Name &amp; ID</th>
	<th align="left"  width="10%">Vehicle Number &amp; Type</th>
	<th align="left"  width="9%">Ride Date</th>
	<th align="left"  width="8%">Booking Type</th>
	<th align="left"  width="13%">Pickup Location</th>
	<th align="left"  width="13%">Drop Location</th>
	<th align="left"  width="13%">Payment Type</th>
	<th align="left">Total Fare</th>
	<th align="left">Paid By Cash</th>
	<th align="left">Paid By e-Wallet</th>
</tr>

	@foreach($total_list as $list)
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
		<td>{{$list->total_amount}}</td>
		<td>{{$list->paid_cash}}</td>
		<td>{{$list->paid_taximoney}}</td>
		</tr>
	@endforeach

</table>
@else
	<div>No Ride are available</div>
@endif
</div>
		
			
