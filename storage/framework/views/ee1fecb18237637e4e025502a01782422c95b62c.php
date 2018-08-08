;

<?php $__env->startSection('title'); ?>

AddBrand - Go Cabs

<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<div class="rightside bg-grey-100">
			<!-- BEGIN PAGE HEADING -->
            <div class="page-head">
				<h1 class="page-title">Add Brand</h1>
				<!-- BEGIN BREADCRUMB -->
				
				<!-- END BREADCRUMB -->
			</div>
			<!-- END PAGE HEADING -->

            <div class="container-fluid">
				<div class="row">
					<div class="col-lg-12">
                           <div class="panel">
                            <div class="panel-title bg-amber-200">
								<div class="panel-head">Brand Type</div>
							</div>
                            <div class="panel-body">
								<form class="form-horizontal" action="" role="form" method="post">
									 <?php echo e(csrf_field()); ?>

								  <div class="form-group <?php echo e($errors->has('brand_name')? 'has-error':''); ?>">
									<label for="" class="col-sm-2 control-label">Brand Name</label>
									<div class="col-sm-4">
									  <input type="text" class="form-control" id="brand" name="brand_name" value ="<?php echo e(old('brand_name')); ?>" maxlength="20" placeholder="" >
									  <?php echo $errors->first('brand_name', '<span class="help-block">:message</span>'); ?>

									</div>
								  </div>
								  
								  
								  <div class="form-group">
									<div class="col-sm-offset-2 col-sm-4 margin-top-10">
									
										<button type="button" class="btn btn-dark bg-black color-white" onclick="window.location.href='<?php echo e(url('/brand')); ?>';">Back</button>
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