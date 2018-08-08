;

<?php $__env->startSection('title'); ?>

EditModel - Go Cabs

<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<div class="rightside bg-grey-100">
			<!-- BEGIN PAGE HEADING -->
            <div class="page-head">
						<h1 class="page-title"><?php echo e(trans('config.lblm_editmodel_heading')); ?></h1>
				<!-- BEGIN BREADCRUMB -->
				
				<!-- END BREADCRUMB -->
			</div>
			<!-- END PAGE HEADING -->

            <div class="container-fluid">
				<div class="row">
					<div class="col-lg-12">
                           <div class="panel">
                            <div class="panel-title bg-amber-200">
								<div class="panel-head"><?php echo e(trans('config.lblm_vehicle_details')); ?></div>
							</div>
                            <div class="panel-body">
						<?php if(session('error_status')): ?>
										<div class="alert alert-danger">
											<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
											<?php echo e(session('error_status')); ?>

										</div>
									<?php endif; ?>						
							<?php $__currentLoopData = $model_list; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $model): $__env->incrementLoopIndices(); $loop = $__env->getFirstLoop(); ?>
								<form class="form-horizontal" role="form" method="POST">
                                <input type="hidden" name="model_id" value="<?php echo e($model->id); ?>" />
                                 <?php echo e(csrf_field()); ?>

								   <div class="form-group <?php echo e($errors->has('ride_category')? 'has-error':''); ?>" >
									<label for="" class="col-sm-2 control-label"><?php echo e(trans('config.lbl_vehicle_category')); ?></label>
									<div class="col-sm-4">
									<input type="hidden" name="ride_category" value="<?php echo e($model->ride_category); ?>">
									  <select disabled class="form-control" name="">
                                        <?php $__currentLoopData = $ride_category; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $cat): $__env->incrementLoopIndices(); $loop = $__env->getFirstLoop(); ?>
									  <option    value="<?php echo e($cat->id); ?>" <?php if(old('ride_category',$model->ride_category) == $cat->id) { echo "selected=selected"; } ?>><?php echo e($cat->ride_category); ?></option>
									 <?php endforeach; $__env->popLoop(); $loop = $__env->getFirstLoop(); ?>
                                      </select>
									</div>
								  </div>

								  <input type="hidden" name="taxi_brand" value="<?php echo e($model->brand_id); ?>">
								  <div class="form-group <?php echo e($errors->has('ride_category')? 'has-error':''); ?>" >
									<label for="" class="col-sm-2 control-label">Brand</label>
									<div class="col-sm-4">
									  <select disabled class="form-control" name="">
                                        <?php $__currentLoopData = $brand; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $cat): $__env->incrementLoopIndices(); $loop = $__env->getFirstLoop(); ?>
									  <option    value="<?php echo e($cat->id); ?>" <?php if($model->brand_id == $cat->id) { echo "selected=selected"; } ?>><?php echo e($cat->brand); ?></option>
									 <?php endforeach; $__env->popLoop(); $loop = $__env->getFirstLoop(); ?>
                                      </select>
									</div>
								  </div>

								  <div class="form-group <?php echo e($errors->has('model_type')? 'has-error':''); ?>">
									<label for="" class="col-sm-2 control-label">Model Name</label>
									<div class="col-sm-4">
									  <input type="text" maxlength="20" class="form-control" id="model_type" value ="<?php echo e(old('model_type',$model->model)); ?>" name="model_type" placeholder="" required >
									<?php echo $errors->first('model_type', '<span class="help-block">:message</span>'); ?>

									</div>
									
								  </div>
							
								  <div class="form-group">
									<div class="col-sm-offset-2 col-sm-4 margin-top-10">
									
									  <button type="button" class="btn btn-dark bg-black color-white" onclick="window.location.href='<?php echo e(url('/model')); ?>';">Back</button>
                                      <button type="submit" class="btn btn-dark bg-red-600 color-white">Update</button>
									  <button type="reset" class="btn btn-dark bg-grey-400 color-black">Reset</button>
									  
									
									</div>
								  </div>
								</form>
							 <?php endforeach; $__env->popLoop(); $loop = $__env->getFirstLoop(); ?>
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