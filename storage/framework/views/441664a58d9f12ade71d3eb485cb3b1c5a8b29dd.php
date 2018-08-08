;

<?php $__env->startSection('title'); ?>

AddRating - GO Cab

<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<div class="rightside bg-grey-100">
			<!-- BEGIN PAGE HEADING -->
            <div class="page-head">
				<h1 class="page-title"><?php echo e(trans('config.lblr_add_rating')); ?></h1>
				<!-- BEGIN BREADCRUMB -->
				
				<!-- END BREADCRUMB -->
			</div>
			<!-- END PAGE HEADING -->

            <div class="container-fluid">
				<div class="row">
					<div class="col-lg-12">
                           <div class="panel">
                            <div class="panel-title bg-amber-200">
								<div class="panel-head"><?php echo e(trans('config.lblr_rating_info')); ?></div>
							</div>
                            <div class="panel-body">
								<form class="form-horizontal" action="" role="form" method="post">
									 <?php echo e(csrf_field()); ?>

								  <div class="form-group <?php echo e($errors->has('rating_id')? 'has-error':''); ?>">
									<label for="" class="col-sm-2 control-label"><?php echo e(trans('config.lblr_rating_id')); ?></label>
									<div class="col-sm-4">
											<select class="form-control" id="rating_id" name="rating_id">
												<option value="1" <?php if(old('rating_id')==1){ echo "selected=selected";} ?>>1</option>
												<option value="2" <?php if(old('rating_id')==3){ echo "selected=selected";} ?>>2</option>
												<option value="3" <?php if(old('rating_id')==3){ echo "selected=selected";} ?>>3</option>
												<option value="4" <?php if(old('rating_id')==4){ echo "selected=selected";} ?>>4</option>
												<option value="5" <?php if(old('rating_id')==5){ echo "selected=selected";} ?>>5</option>
											</select>
									 
									  <?php echo $errors->first('rating_id', '<span class="help-block">:message</span>'); ?>

									</div>
								  </div>
								  
								   <div class="form-group <?php echo e($errors->has('rating_reason')? 'has-error':''); ?>">
									<label for="" class="col-sm-2 control-label"><?php echo e(trans('config.lblr_rating_reason')); ?></label>
									<div class="col-sm-4">
									  <input type="text" maxlength="40" class="form-control" id="rating_reason" name="rating_reason" value ="<?php echo e(old('rating_reason')); ?>" placeholder="" >
									  <?php echo $errors->first('rating_reason', '<span class="help-block">:message</span>'); ?>

									</div>
								  </div>
								  
								  
								  <div class="form-group">
									<div class="col-sm-offset-2 col-sm-4 margin-top-10">
									
										<button type="button" class="btn btn-dark bg-black color-white" onclick="window.location.href='<?php echo e(url('/manage_rating')); ?>';">Back</button>
										  <button type="submit" class="btn btn-dark bg-red-600 color-white">Add</button>
										  <button type="reset" class="btn btn-dark bg-grey-400 color-black">Reset</button>
									  </div>
								  </div>
								  <?php if(session('error_status')): ?>
										<div class="alert alert-danger">
											<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
											<?php echo e(session('error_status')); ?>

										</div>
									<?php endif; ?>
								</form>
								<div id="status"></div>
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