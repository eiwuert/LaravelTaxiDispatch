;

<?php $__env->startSection('title'); ?>
View Fare - Go Cabs 
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
 <div class="rightside bg-grey-100">
			<!-- BEGIN PAGE HEADING -->
            <div class="page-head">
				<h1 class="page-title">View Fare Details</h1>
				<!-- BEGIN BREADCRUMB -->
			
				 <a href="<?php echo e(url('/manage_fare')); ?>" class="btn btn-dark bg-black color-white pull-right">Back</a>
				<!-- END BREADCRUMB -->
			</div>
			<!-- END PAGE HEADING -->
		
            <div class="container-fluid">
				<div class="row">
					<div class="col-lg-12">
					
                           <div class="panel">
                            <div class="panel-title bg-amber-200">
								<div class="panel-head">Fare Information</div>
							</div>
							<div class="panel-body">
							<div class="table-responsive"> 
						<table class="table table-bordered">
						<tr>
								<td><label for="Taxi No" class="view_label"><?php echo e(trans('config.lblf_franchis')); ?></label></td>
								<td><?php echo e($fare_list->get_franchise->company_name); ?></td>
							</tr>
							<tr>
								<td><label for="Taxi No" class="view_label"><?php echo e(trans('config.lbl_vehicle_category')); ?></label></td>
								<td><?php echo e($fare_list->getvehicle_name->ride_category); ?></td>
							</tr>
							<tr>
								<td><label for="Taxi-Type" class="view_label">Vehicle Type</label></td>
								<td><?php echo e($fare_list->getcartype_name->car_type); ?> <?php if($fare_list->getcartype_name->car_board == 1) { echo "(W)"; } elseif($fare_list->getcartype_name->car_board == 2) { echo "(Y)"; } ?></td>
							</tr>
							<tr>
								<td><label for="Ride Name" class="mylabel">Fare Type</label></td>
								<td><?php echo e($fare_list->fare_type == 1 ? "Base Fare":""); ?>

												<?php echo e($fare_list->fare_type == 2 ? "Morning Time":""); ?>

												<?php echo e($fare_list->fare_type == 3 ? "Night Time":""); ?>

												<?php echo e($fare_list->fare_type == 4 ? "Peek Time":""); ?>

												<?php echo e($fare_list->fare_type == 5 ? "Special Time":""); ?></td>
							</tr>
							<tr>
								<td><label for="Minimum-Km" class="view_label">Booking Type</label></td>
								<td><?php echo e($fare_list->booking_type == 1 ? 'Flat rate':''); ?></td>
							</tr>
							<?php if($fare_list->fare_type !=4 && $fare_list->fare_type !=5): ?>
							<tr>
								<td><label for="Minimum-Km" class="view_label">Minimum Km</label></td>
								<td><?php echo e($fare_list->min_km); ?></td>
							</tr>
							<tr>
								<td><label for="Minimum-Fare" class="mylabel">Minimum Fare</label></td>
								<td><?php echo e($fare_list->min_fare_amount); ?></td>
							</tr>
							<!--<tr>
								<td><label for="Ride-Each-Km" class="view_label">Fare/by KM</label></td>
								<td><?php echo e($fare_list->ride_each_km); ?></td>
							</tr>-->
							<tr>
								<td><label for="Ride Fare" class="mylabel">Ride Fare</label></td>
								<td><?php echo e($fare_list->ride_fare); ?></td>
							</tr>
							<!-- <tr>
								<td><label for="Cancellation-Fee" class="view_label">Cancellation Fee</label></td>
								<td><?php echo e($fare_list->cancellation_fee); ?></td>
							</tr> -->
							<tr>
								<td><label for="Taxi-Waiting-Charge/min" class="mylabel">Vehicle Waiting Charge/min</label></td>
							<td><?php echo e($fare_list->waiting_time); ?> Min /<?php echo e($fare_list->waiting_charge); ?></td>
							</tr>
							<tr>
								<td><label for="Fare/min-of-Ride" class="view_label">Fare/min of Ride</label></td>
								<td><?php echo e($fare_list->distance_time); ?> Min /<?php echo e($fare_list->distance_fare); ?></td>
							</tr>
							<?php endif; ?>
							<?php if($fare_list->fare_type ==4 || $fare_list->fare_type ==5): ?>
							<tr>
								<td><label for="Fare-Start-Time-From" class="mylabel">Fare value (%)</label></td>
								<td><?php echo e($fare_list->fare_percent); ?></td>
							</tr>
							<tr>
								<td><label for="Fare-Start-Time-From" class="mylabel">Morning Start Time</label></td>
								<td><?php echo e(date('h:i a',strtotime($fare_list->ride_start_time))); ?></td>
							</tr>
							<tr>
								<td><label for="Fare-End-Time-To" class="view_label">Morning Fare End Time</label></td>
							<td><?php echo e(date('h:i a',strtotime($fare_list->ride_end_time))); ?></td>
							</tr>
							
							<tr>
								<td><label for="Fare-Start-Time-From" class="mylabel">Evening Start Time</label></td>
								<td><?php echo e(date('h:i a',strtotime($fare_list->nit_start_time))); ?></td>
							</tr>
							<tr>
								<td><label for="Fare-End-Time-To" class="view_label">Evening Fare End Time</label></td>
							<td><?php echo e(date('h:i a',strtotime($fare_list->nit_end_time))); ?></td>
							</tr>
								<?php endif; ?>
							<!-- <tr>
								<td><label for="Status" class="mylabel">Status</label></td>
								<td><?php echo e($fare_list->status); ?></td>
							</tr>
							
							<tr>
								<td><label for="Status" class="mylabel">Create By</label></td>
								<td><?php echo e($fare_list->getcreated_by->first_name); ?></td>
							</tr>
							<tr>
								<td><label for="Status" class="mylabel">Created Time</label></td>
								<td><?php echo e(date('d M,Y h:i a',strtotime($fare_list->created_date))); ?></td>
							</tr>
							<tr>
								<td><label for="Status" class="mylabel">Updated By</label></td>
								<td><?php echo e($fare_list->getupdated_by->first_name); ?></td>
							</tr>
							<tr>
								<td><label for="Status" class="mylabel">Updated Time</label></td>
								<td><?php echo e(date('d M,Yh:i a',strtotime($fare_list->updated_date))); ?></td>
							</tr> -->
						</table>
					</div>
						
                            </div>
						</div>
					</div><!-- /.col -->
				</div><!-- /.row -->
				
				<!-- /.row -->
				
				<!-- /.row -->
		
					<!-- BEGIN FOOTER -->
				<?php echo $__env->make('includes.footer', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
				<!-- END FOOTER -->
            </div><!-- /.container-fluid -->
        </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layout.master', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>