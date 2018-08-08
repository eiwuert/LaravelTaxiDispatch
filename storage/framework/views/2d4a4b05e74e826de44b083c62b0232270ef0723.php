;

<?php $__env->startSection('title'); ?>

AddModel - Go Cabs

<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<div class="rightside bg-grey-100">
			<!-- BEGIN PAGE HEADING -->
            <div class="page-head">
				<h1 class="page-title"><?php echo e(trans('config.lblm_addmodel_heading')); ?></h1>
				<!-- BEGIN BREADCRUMB -->
				
				<!-- END BREADCRUMB -->
			</div>
			<!-- END PAGE HEADING -->

            <div class="container-fluid">
				<div class="row">
					<div class="col-lg-12">
                           <div class="panel">
                            <div class="panel-title bg-amber-200">
								<div class="panel-head"><?php echo e(trans('config.lblm_addmodel_heading')); ?></div>
							</div>
                            <div class="panel-body">
								<form class="form-horizontal" role="form" method="POST">
                                
                                 <?php echo e(csrf_field()); ?>

								  <div class="form-group <?php echo e($errors->has('ride_category')? 'has-error':''); ?>" >
									<label for="" class="col-sm-2 control-label"><?php echo e(trans('config.lbl_vehicle_category')); ?></label>
									<div class="col-sm-4">
									  <select class="form-control" name="ride_category">
                                        <?php $__currentLoopData = $ride_category; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $cat): $__env->incrementLoopIndices(); $loop = $__env->getFirstLoop(); ?>
									  <option    value="<?php echo e($cat->id); ?>" <?php if(old('ride_category') == $cat->id) { echo "selected=selected"; } ?>><?php echo e($cat->ride_category); ?></option>
									 <?php endforeach; $__env->popLoop(); $loop = $__env->getFirstLoop(); ?>
                                      </select>
									</div>
								  </div>

								  <div class="form-group <?php echo e($errors->has('model_brand')? 'has-error':''); ?>" >
									<label for="" class="col-sm-2 control-label">Select Brand</label>
									<div class="col-sm-4">
									  <select class="form-control" name="model_brand">
                                        <?php $__currentLoopData = $brand; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $cat): $__env->incrementLoopIndices(); $loop = $__env->getFirstLoop(); ?>
									  <option    value="<?php echo e($cat->id); ?>" <?php if(old('model_brand') == $cat->id) { echo "selected=selected"; } ?>><?php echo e($cat->brand); ?></option>
									 <?php endforeach; $__env->popLoop(); $loop = $__env->getFirstLoop(); ?>
                                      </select>
									</div>
								  </div>

								   <div class="form-group <?php echo e($errors->has('model_type')? 'has-error':''); ?>">
									<label for="" class="col-sm-2 control-label"><?php echo e(trans('config.lblm_model_name')); ?></label>
									<div class="col-sm-4">
									  <input type="text" maxlength="20" class="form-control" id="model_type" value ="<?php echo e(old('model_type')); ?>" name="model_type" placeholder="" required >
									<?php echo $errors->first('model_type', '<span class="help-block">:message</span>'); ?>

									</div>
									
								  </div>

								  

								  <?php if(session('error_status')): ?>
										<div class="alert alert-danger">
											<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
											<?php echo e(session('error_status')); ?>

										</div>
									<?php endif; ?>
								  <div class="form-group">
									<div class="col-sm-offset-2 col-sm-4 margin-top-10">
									
									  <button type="button" class="btn btn-dark bg-black color-white" onclick="window.location.href='<?php echo e(url('/model')); ?>';"><?php echo e(trans('config.lbl_back')); ?></button>
                                      <button type="submit" class="btn btn-dark bg-red-600 color-white"><?php echo e(trans('config.lbl_add')); ?></button>
									  <button type="reset" class="btn btn-dark bg-grey-400 color-black"><?php echo e(trans('config.lbl_reset')); ?></button>
									  
									
									</div>
								  </div>
								</form>
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