;

<?php $__env->startSection('title'); ?>

Manage Brand - Go Cabs

<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<div class="rightside bg-grey-100">
			<!-- BEGIN PAGE HEADING -->
            <div class="page-head">
				<h1 class="page-title"><?php echo e(trans('config.lblb_heading')); ?></h1>
				<!-- BEGIN BREADCRUMB -->
				<a href="<?php echo e(url("/")); ?>/brand/add"><button class="btn btn-dark bg-red-600 color-white pull-right" >Add Brand</button></a>
				<!-- END BREADCRUMB -->
			</div>
			<!-- END PAGE HEADING -->

            <div class="container-fluid">
				<div class="row">
                        <div class="col-lg-12">
							<div class="panel no-border">
                            	<div class="panel-title bg-amber-200">
								<div class="panel-head">Manage Brands</div>
								</div>
                                <div class="panel-body no-padding-top bg-white">
									<p class="text-light margin-bottom-30"></p>
										 <form id="change-brand-status" action="#" method="POST">
											<table class="table table-bordered display" >
										<thead>
											<tr>
												<!-- <th class="vertical-middle">Select</th> -->
												<th class="vertical-middle">Brand Name</th>
												 <!-- <th class="vertical-middle">Status</th> -->
												<th class="vertical-middle">Action</th>
											</tr>
										</thead>
										<tbody>
										<?php $__currentLoopData = $brand_list; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $brand): $__env->incrementLoopIndices(); $loop = $__env->getFirstLoop(); ?>
											<tr> 
													<!-- <td class="vertical-middle"><input type="checkbox" value="<?php echo e($brand->id); ?>"></td> -->
												<td class="vertical-middle"><?php echo e($brand->brand); ?></td>
												<!-- <td class="vertical-middle"><i class='<?php echo e(($brand->status == 1) ? "fa fa-check-circle active" : "fa fa-times-circle inactive"); ?>' aria-hidden='true'></i></td> -->
												<td class="vertical-middle">
													<a href="<?php echo e(url('/brand/')); ?>/<?php echo e($brand->id); ?>/edit" title="Edit"><i class="fa fa-edit fa-2x"></i>
													<!-- </a>
													<?php if($brand->status == 1): ?>
													<a href="#" class="btn btn-danger btn-circle" title="Inactivate" onclick="deactivate(<?php echo e($brand->id); ?>,'ajax_deactive_brand','Brand Details');"><i class="glyphicon glyphicon-remove"></i></a>
													<?php else: ?>
													<a href="#" class="btn btn-success btn-circle" title="Activate" onclick="activate(<?php echo e($brand->id); ?>,'ajax_active_brand','Brand Details');"><i class="glyphicon glyphicon-ok"></i></a> 
													<?php endif; ?>-->
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