@php
ob_start();
 
@endphp

<link href="{{ URL::asset("public/css/report.css") }}" rel="stylesheet" />

<div class="row" class="tbl_grid_report" >
@if(count($driver_share)>0)
<!--show the data report-->
<table class="table" cellspacing="5" cellpadding="10"  style="font-size:11px;" >

<tr class="logo_header_row">
	<!--<td  align="left" colspan="6"><img src="/var/www/html/goapp/public/css/vehicle_icon/report_logo.png"></td>-->
	<td colspan="6"  align="center"><h2>Driver's Total Share Details</h2></td>
</tr>

<tr valign="top" align="center">
	<th align="left">Date of Ride</th>
	<th align="left">Driver Name &amp; ID	</th>
	<th align="left">Vehicle Number</th>
	<th align="left">Total share amount of the Driver</th>
	@if($franchise_id == 0)<th align="left">Total share amount of the Company</th>@endif
	<th align="left">Total Share amount of the Franchise</th>
</tr>

	@foreach($driver_share as $list)
	<tr>
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
	<div>No Ride are available</div>
@endif
</div>
		
			
