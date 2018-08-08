;

<?php $__env->startSection('title'); ?>

Manage Type - Go Cabs

<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>

<div class="rightside bg-grey-100">
<!-- BEGIN PAGE HEADING -->
	<div class="page-head">
	<h1 class="page-title"><?php echo e(trans('config.lblt_heading')); ?></h1>
	<!-- BEGIN BREADCRUMB -->
		<a href="<?php echo e(url("/")); ?>/type/add"><button class="btn btn-dark bg-red-600 color-white pull-right" >Add Vehicle Type</button></a>
	<!-- END BREADCRUMB -->
	</div>
<!-- END PAGE HEADING -->
	<!-- START OF FILTER-->
		<div class="f_filter container-fluid">
			<div class="pull-right col-lg-3 no-padding">
				<form method="get" action="" name="filter">
				 <?php echo e(csrf_field()); ?>

				 <div class="input-group">
				   	<select class=" form-control" name="ride_category" >
							<option value="">--Select Vehicle Type--</option>
						 <?php $__currentLoopData = $ride_category; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $cat): $__env->incrementLoopIndices(); $loop = $__env->getFirstLoop(); ?>
					  <option    value="<?php echo e($cat->id); ?>" <?php echo e(session('cf_type') == $cat->id ? "selected=selected":''); ?> ><?php echo e($cat->ride_category); ?></option>
					   <?php endforeach; $__env->popLoop(); $loop = $__env->getFirstLoop(); ?>
					</select>
				   <span class="input-group-btn">
						<input type="submit" class="btn btn-dark bg-red-600 color-white pull-right" value="Search" />
				   </span>
				</div>
				</form>
			</div>
			<div class="clearfix"></div>
		</div>
	<!-- END OF FILTER-->
<div class="container-fluid">
<div class="row">
		<div class="col-lg-12">
			<div class="panel no-border">
				<div class="panel-title bg-amber-200">
				<div class="panel-head"><?php echo e(trans('config.lblt_list_type')); ?></div>
				</div>
				<div class="panel-body no-padding-top bg-white">
					<p class="text-light margin-bottom-30"></p>
					<form id="change-type-status" action="#" method="POST">
						<table class="table table-bordered display" >
						<thead>
							<tr>
								<!-- <th class="vertical-middle"><?php echo e(trans('config.lbl_select')); ?></th> -->
								<th class="vertical-middle"><?php echo e(trans('config.lbl_vehicle_category')); ?></th>
								<th class="vertical-middle"><?php echo e(trans('config.lblt_type_name')); ?></th>
								<!-- <th class="vertical-middle"><?php echo e(trans('config.lbl_status')); ?></th> -->
								<th class="vertical-middle"><?php echo e(trans('config.lbl_action')); ?></th>
							</tr>
						</thead>
						<tbody>
						<?php $__currentLoopData = $type_list; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $type): $__env->incrementLoopIndices(); $loop = $__env->getFirstLoop(); ?>
							<tr> 
								<!-- <td class="vertical-middle"><input type="checkbox" value="<?php echo e($type->id); ?>"></td> -->
								<td class="vertical-middle"><?php echo e($type->getvehicle_name->ride_category); ?></td>				
								<td class="vertical-middle"><?php echo e($type->car_type); ?> <?php if($type->car_board == 1) { echo "(W)"; } elseif($type->car_board == 2) { echo "(Y)"; } ?></td>
									
								<!-- <td class="vertical-middle"><i class='<?php echo e(($type->status == 1) ? "fa fa-check-circle active" : "fa fa-times-circle inactive"); ?>' aria-hidden='true'></i></td> -->
								<td class="vertical-middle">
									<a href="<?php echo e(url('/type/')); ?>/<?php echo e($type->id); ?>/edit" title="Edit"><i class="fa fa-edit fa-2x"></i></a>
									<a href="<?php echo e(url('/type/')); ?>/<?php echo e($type->id); ?>/view" title="View"><i class="fa fa-eye fa-2x"></i></a>
									<!-- <?php if($type->status == 1): ?>
									<a href="#"  class="btn btn-danger btn-circle" title="Inactivate"  onclick="deactivate(<?php echo e($type->id); ?>,'ajax_deactive_type','Car Type');"><i class="glyphicon glyphicon-remove"></i></a>
									<?php else: ?>
									<a href="#" class="btn btn-success btn-circle" title="Activate"  onclick="activate(<?php echo e($type->id); ?>,'ajax_active_type','Car Type');"><i class="glyphicon glyphicon-ok"></i></a>
									<?php endif; ?> -->
									</td>
							</tr>
						<?php endforeach; $__env->popLoop(); $loop = $__env->getFirstLoop(); ?>
						</tbody>
					</table>
					<!-- <table cellspacing="0" cellpadding="0" class="note ma_0 noti" width="100%">
						  <tbody>
							<tr>
							  <td class="">
							  <i class="fa fa-check-circle active" aria-hidden="true"></i><span> <?php echo e(trans('config.lbl_active')); ?></span>
							  <i class="fa fa-times-circle inactive" aria-hidden="true"></i><span> <?php echo e(trans('config.lbl_block')); ?></span></td>
							  
							</tr>
						  </tbody>
						</table> -->
						
						<p class="text-light margin-bottom-30"></p>
						<!-- <div class="form-group ">
							<label for="" class="col-sm-2 control-label no-padding">Change Status</label>
							<div class="col-sm-2 no-padding">
								<select class="form-control" name="status" id="ch_status">
								<option value="1"><?php echo e(trans('config.lbl_activate')); ?></option>
								<option value="0"><?php echo e(trans('config.lbl_block')); ?></option>
								</select>
							</div>
							<div class="col-sm-2 ">
								<button class="btn btn-danger" type="submit">Change</button>
							</div>
						</div> -->
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
</div><!-- /.container-fluid -->
</div>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layout.master', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>