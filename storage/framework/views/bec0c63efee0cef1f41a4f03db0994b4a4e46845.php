;

<?php $__env->startSection('title'); ?>

View Vehicle Type - Go Cabs

<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>

<div class="rightside bg-grey-100">
<!-- BEGIN PAGE HEADING -->
	<div class="page-head">
	<h1 class="page-title">View vehicle type</h1>
	<!-- BEGIN BREADCRUMB -->
		<a href="<?php echo e(url("/")); ?>/type" class="btn btn-dark bg-black color-white pull-right" >Back</a>
	<!-- END BREADCRUMB -->
	</div>
<!-- END PAGE HEADING -->
	
<div class="container-fluid">
<div class="row">
		<div class="col-lg-12">
			<div class="panel no-border">
				<div class="panel-title bg-amber-200">
				<div class="panel-head">view vehicle details</div>
				</div>
				<div class="panel-body no-padding-top bg-white">
					<p class="text-light margin-bottom-30"></p>
					<form id="change-type-status" action="#" method="POST">
						<table class="table table-bordered display" >

							<tbody>

							<tr>
							<td class="vertical-middle">Vehicle Category</td>
							<td class="vertical-middle">
							<?php $__currentLoopData = $ride_category; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $rd): $__env->incrementLoopIndices(); $loop = $__env->getFirstLoop(); ?>
							<?php if($rd->id == $list->ride_category): ?>
							<?php echo e($rd->ride_category); ?>

							<?php endif; ?>
							<?php endforeach; $__env->popLoop(); $loop = $__env->getFirstLoop(); ?>
							</td>
							</tr>

							<tr>
							<td class="vertical-middle">Vehicle Type</td>
							<td class="vertical-middle"><?php echo e($list->car_type); ?> 
							<?php if($list->car_board == 1): ?> (W) <?php endif; ?>
							<?php if($list->car_board == 2): ?> (Y) <?php endif; ?>
							</td>
							</tr>

							<tr>
							<td class="vertical-middle">Vehicle Capacity</td>
							<td class="vertical-middle"><?php echo e($list->capacity); ?></td>
							</tr>

							<tr>
							<td class="vertical-middle">Selected Car Icon</td>
							<td class="vertical-middle">
								<img src="<?php echo e(url("/")); ?>/public<?php echo e($list->yellow_caricon); ?>">
							</td>
							</tr>

							<tr>
							<td class="vertical-middle">Unselected Car Icon</td>
							<td class="vertical-middle">
								<img src="<?php echo e(url("/")); ?>/public<?php echo e($list->grey_caricon); ?>">
							</td>
							</tr>

							<!-- <tr>
							<td class="vertical-middle">Black Car Icon</td>
							<td class="vertical-middle">
								<img src="<?php echo e(url("/")); ?>/public<?php echo e($list->black_caricon); ?>">
							</td>
							</tr> -->
							
							<tr>
							<td class="vertical-middle">Company's Share</td>
							<td class="vertical-middle"><?php echo e($list->companydriver_share); ?> %</td>
							</tr>

							<tr>
							<td class="vertical-middle">Attached Vehicle share</td>
							<td class="vertical-middle"><?php echo e($list->attacheddriver_share); ?> %</td>
							</tr>

							<tr>
							<td class="vertical-middle">Franchise's Share</td>
							<td class="vertical-middle"><?php echo e($list->franchise_share); ?> %</td>
							</tr>

							</tbody>
						</table>
					
						</form>
			</div>
			
		</div><!-- /.col -->
		
		
	</div><!-- /. row -->
</div><!-- /.row -->

<!-- /.row -->

<!-- /.row -->

<!-- BEGIN FOOTER -->
<?php echo $__env->make('includes.footer', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
<!-- END FOOTER -->
</div>
</div>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('layout.master', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>