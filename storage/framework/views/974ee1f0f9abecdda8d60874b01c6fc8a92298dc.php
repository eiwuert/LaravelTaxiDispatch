<?php 
ob_start();
 
 ?>

<link href="<?php echo e(URL::asset("public/css/report.css")); ?>" rel="stylesheet" />

<div class="row" class="tbl_grid_report" >
<?php if(count($cancelled_list)>0): ?>
<!--show the data report-->
<table class="table" cellspacing="5" cellpadding="10"  style="font-size:11px;" >

<tr class="logo_header_row">
	<!--<td  align="left" colspan="6"><img src="/var/www/html/goapp/public/css/vehicle_icon/report_logo.png"></td>-->
	<td colspan="8"  align="center"><h2>Rejected Ride Details</h2></td>
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
</tr>

	<?php $__currentLoopData = $cancelled_list; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $list): $__env->incrementLoopIndices(); $loop = $__env->getFirstLoop(); ?>
	<tr>
		<td><?php echo e($list->reference_id); ?></td>
		<td><?php echo e($list->driver_name); ?></td>
		<td><?php echo e($list->passanger_name); ?></td>
		<td><?php echo e($list->car_no); ?></td>
		<td><?php echo e($list->date_of_ride); ?></td>
			<td><?php echo e($list->ride_type); ?></td>
		<td><?php echo e($list->source_location); ?></td>
		<td><?php echo e($list->destination_location); ?></td>
		</tr>
	<?php endforeach; $__env->popLoop(); $loop = $__env->getFirstLoop(); ?>

</table>
<?php else: ?>
	<div>No Ride are available</div>
<?php endif; ?>
</div>
		
			
