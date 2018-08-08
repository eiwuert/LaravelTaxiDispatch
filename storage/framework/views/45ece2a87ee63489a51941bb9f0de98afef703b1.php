<?php 
ob_start();
error_reporting(-1);
ini_set('display_errors', 'On');
ini_set('memory_limit', '-1');
ini_set('max_execution_time', 0); 
  ?>

<link href="<?php echo e(URL::asset("public/css/report.css")); ?>" rel="stylesheet" />
<div class="row" class="tbl_grid_report" >
<?php if(count($success_list)>0): ?>
<!--show the data report-->
<table class="p_table" cellspacing="5" cellpadding="10" >
<tr class="p_logo_header_row">
	<td  align="left" colspan="2"><img src="<?php echo e(asset('public/css/vehicle_icon/report_logo.png')); ?>"></td>
	<td colspan="4"  align="center"><h2>Success Ride Details</h2></td>
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
	<th align="left"  width="13%">Driver's Share</th>
	<th align="left"  width="13%">Franchise Share</th>
	<th align="left"  width="13%">Company Share</th>
</tr>

	<?php $__currentLoopData = $success_list; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $list): $__env->incrementLoopIndices(); $loop = $__env->getFirstLoop(); ?>
	<tr>
		<td><?php echo e($list->reference_id); ?></td>
		<td><?php echo e($list->driver_name); ?></td>
		<td><?php echo e($list->passanger_name); ?></td>
		<td><?php echo e($list->car_no); ?></td>
		<td><?php echo e($list->date_of_ride); ?></td>
		<td><?php echo e($list->ride_type); ?></td>
		<td><?php echo e($list->source_location); ?></td>
		<td><?php echo e($list->destination_location); ?></td>
		<td><?php echo e($list->payment_type); ?></td>
		<td><?php echo e($list->driver_share); ?></td>
		<td><?php echo e($list->franchise_share); ?></td>
		<td><?php echo e($list->company_share); ?></td>
		</tr>
	<?php endforeach; $__env->popLoop(); $loop = $__env->getFirstLoop(); ?>

</table>
<?php else: ?>
	<div>No Ride are available</div>
<?php endif; ?>
</div>
		
			
