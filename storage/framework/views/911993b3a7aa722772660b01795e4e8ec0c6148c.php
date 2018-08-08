;

<?php $__env->startSection('title'); ?>

Share Details Report - Go Cabs

<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
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

<?php 
	$estatus='&export=excel';
	$pestatus='&export=pdf';
 ?>
<?php if(count(app('request')->input()) == 0): ?>
	<?php 	
		$estatus='?export=excel';
		$pestatus='?export=pdf';
	 ?>
<?php endif; ?>
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
				 <?php echo e(csrf_field()); ?>


				 				<div class="form-group margin-bottom-20 col-md-3 margin-right-10">
									 	<select class="chosen-select-deselect form-control" name="franchise" id="franchise" <?php if($role != 1): ?> disabled <?php endif; ?> >
											<option value="">--Select Your Franchise--</option>
                                            
                                            <?php $__currentLoopData = $franchise; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $r): $__env->incrementLoopIndices(); $loop = $__env->getFirstLoop(); ?>
                                            <option value="<?php echo e($r->id); ?>"
                                        <?php if($r->id == $franchise_id): ?> selected="selected" <?php endif; ?>
                                            ><?php echo e($r->company_name); ?></option>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getFirstLoop(); ?>

									</select>
								</div>


								<div class="form-group margin-bottom-20 col-md-3 margin-right-10">
									 	<select class="chosen-select-deselect form-control" name="vehicle" id="vehicle" >
											<option value="">--Vehicle Number--</option>
										 <?php $__currentLoopData = $vehicle_list; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $vehicle): $__env->incrementLoopIndices(); $loop = $__env->getFirstLoop(); ?>
										<option    value="<?php echo e($vehicle->id); ?>"  <?php echo e(app('request')->input('vehicle') == $vehicle->id ? "selected=selected":''); ?> ><?php echo e($vehicle->car_no); ?></option>
										 <?php endforeach; $__env->popLoop(); $loop = $__env->getFirstLoop(); ?>
									</select>
								</div>
								
								<div class="form-group margin-bottom-20 col-md-3 margin-right-10">
									 	<select class="chosen-select-deselect form-control" name="driver" id="driver" >
											<option value="">--Driver Name--</option>
										 <?php $__currentLoopData = $driver_list; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $driver): $__env->incrementLoopIndices(); $loop = $__env->getFirstLoop(); ?>
										<option    value="<?php echo e($driver->id); ?>"  <?php echo e(app('request')->input('driver') == $driver->id ? "selected=selected":''); ?> ><?php echo e($driver->driver_id); ?></option>
										 <?php endforeach; $__env->popLoop(); $loop = $__env->getFirstLoop(); ?>
									</select>
								</div>
								
								<div class="form-group margin-bottom-20 col-md-3 margin-right-10">
									 	<input type="text" value="<?php echo e(app('request')->input('from_date')); ?>" readonly="" name="from_date" class="form-control from_date symval"  placeholder="Start From Date" >
								</div>
								
									<div class="form-group margin-bottom-20 col-md-3 margin-right-10">
									 	 <input type="text" value="<?php echo e(app('request')->input('to_date')); ?>" readonly="" name="to_date" class="form-control to_date symval" placeholder="Start To Date" >
								</div>
								
 							<div class="form-group margin-bottom-20 col-md-3 margin-right-10 " ></div>
                <div class="form-group margin-bottom-20 col-md-3 pull-right" style=" right: -17px; position: relative;">
          				<input type="submit" class="btn btn-dark bg-red-600 color-white" id="button_submit" value="Search" />
          				<button type="button" class="btn btn-dark bg-grey-400 color-black" onclick="window.parent.location='<?php echo e(URL::to('/total_rides')); ?>'" >Reset</button>
          		</div> 

				</div>
			</div>
			</div>
	</form>
	<!-- END OF FILTER-->
	
<div class="row">
<div class="expt_btn pull-right">
 <a href="<?php echo e(Request::fullUrl()); ?><?php echo e($estatus); ?>" <?php if(count($driver_share)== 0): ?> <?php echo e("disabled=disabled"); ?><?php endif; ?> class="margin-bottom-20 btn btn-dark bg-red-600 color-white">Download EXCEL</a> <a href="<?php echo e(Request::fullUrl()); ?><?php echo e($pestatus); ?>" <?php if(count($driver_share)== 0): ?> <?php echo e("disabled=disabled"); ?><?php endif; ?>  class="margin-bottom-20 btn btn-dark bg-red-600 color-white">Download PDF</a>
</div>
</div>
<div class="row  tbl_grid_report"  >
<?php if(count($driver_share)>0): ?>

<!--show the data report-->
<table class="filter_report table" cellspacing="0" cellpadding="10" widthn="100%" style="font-size:11px;" >
<tr valign="top" align="center">
	<th align="left">Date of Ride</th>
	<th align="left">Driver Name &amp; ID	</th>
	<th align="left">Vehicle Number</th>
	<th align="left">Total share amount of the Driver</th>
	<?php if($franchise_id == 0): ?><th align="left">Total share amount of the Company</th><?php endif; ?>
	<th align="left">Total Share amount of the Franchise</th>

</tr>
<tr>
	<?php $__currentLoopData = $driver_share; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $list): $__env->incrementLoopIndices(); $loop = $__env->getFirstLoop(); ?>
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
	<div class="no-record">No Ride are available</div>
	
<?php endif; ?>
</div>

				<!-- BEGIN FOOTER -->
				<?php echo $__env->make('includes.footer', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
				<!-- END FOOTER -->
            </div><!-- /.container-fluid -->
        </div>
  
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layout.master', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>