<?php 
ob_start();
error_reporting(-1);
ini_set('display_errors', 'On');
ini_set('memory_limit', '-1');
ini_set('max_execution_time', 0); 
  ?>

<link href="<?php echo e(URL::asset("public/css/report.css")); ?>" rel="stylesheet" />
<div class="row" class="tbl_grid_report" >
<?php if(count($driver_share)>0): ?>
<!--show the data report-->
<table class="p_table" cellspacing="5" cellpadding="10" >
<tr class="p_logo_header_row">
	<td  align="left" colspan="2"><img src="<?php echo e(asset('public/css/vehicle_icon/report_logo.png')); ?>"></td>
	<td colspan="4"  align="center"><h2>Driver's Total Share Details</h2></td>
</tr>
</table>
<table class="filter_report" cellspacing="0" cellpadding="10"  style="font-size:11px;" >
<tr valign="top" align="center">
<th align="left">Date of Ride</th>
	<th align="left">Driver Name &amp; ID	</th>
	<th align="left">Vehicle Number</th>
	<th align="left">Total share amount of the Driver</th>
	<?php if($franchise_id == 0): ?><th align="left">Total share amount of the Company</th><?php endif; ?>
	<th align="left">Total Share amount of the Franchise</th>
</tr>

	<?php $__currentLoopData = $driver_share; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $list): $__env->incrementLoopIndices(); $loop = $__env->getFirstLoop(); ?>
	<tr>
		<td><?php echo e($list->date_of_ride); ?></td>
		<td><?php echo e($list->driver_name); ?></td>
		<td><?php echo e($list->car_no); ?></td>
		<td><?php echo e($list->driver_share); ?></td>
		<?php if($franchise_id == 0): ?><td><?php echo e($list->company_share); ?></td><?php endif; ?>
		<td><?php echo e($list->franchise_share); ?></td>
		</tr>
	<?php endforeach; $__env->popLoop(); $loop = $__env->getFirstLoop(); ?>
	<tr>
		<td></td>
		<td></td>
		<td></td>
		<td>Total : <?php echo e($tot_drivershare); ?></td>
		<?php if($franchise_id == 0): ?><td>Total : <?php echo e($tot_companyshare); ?></td><?php endif; ?>
		<td>Total : <?php echo e($tot_franchiseshare); ?></td>
	</tr>
</table>
<?php else: ?>
	<div>No Ride are available</div>
<?php endif; ?>
</div>
		
			
