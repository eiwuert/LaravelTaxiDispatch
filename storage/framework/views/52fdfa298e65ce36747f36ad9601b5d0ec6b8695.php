;

<?php $__env->startSection('title'); ?>
View Franchise - Go Cabs 
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
 <div class="rightside bg-grey-100">
			<!-- BEGIN PAGE HEADING -->
            <div class="page-head">
				<h1 class="page-title">View Franchise Details</h1>
				<!-- BEGIN BREADCRUMB -->
			
				 <a href="<?php echo e(url('/manage_franchise')); ?>" class="btn btn-dark bg-black color-white pull-right">Back</a>
				<!-- END BREADCRUMB -->
			</div>
			<!-- END PAGE HEADING -->
		
            <div class="container-fluid">
				<div class="row">
					<div class="col-lg-12">
					
                           <div class="panel">
                            <div class="panel-title bg-amber-200">
								<div class="panel-head">Franchise Information</div>
							</div>
							<div class="panel-body">
							<div class="table-responsive"> 
						<table class="table table-bordered">

							<tr>
								<td>
									First Name
								</td>
								<td>
									<?php echo e($data->first_name); ?>

								</td>
							</tr>

							<tr>
								<td>
									Last Name
								</td>
								<td>
									<?php echo e($data->last_name); ?>

								</td>
							</tr>

							<tr>
								<td>
									Email
								</td>
								<td>
									<?php echo e($data->email); ?>

								</td>
							</tr>

							<tr>
								<td>
									Mobile Number
								</td>
								<td>
									<?php echo e($data->mobile); ?>

								</td>
							</tr>

							<tr>
								<td>
									Company Name 
								</td>
								<td>
									<?php echo e($data->company_name); ?>

								</td>
							</tr>

							<tr>
								<td>
									Company Address
								</td>
								<td>
									<?php echo e($data->company_address); ?>

								</td>
							</tr>

							<tr>
								<td>
									Service Tax Image
								</td>
								<td>
									<img src="<?php echo e(url('/')); ?>/public<?php echo e($data->service_tax_image); ?>" height="200" width="300">
								</td>
							</tr>

							<tr>
								<td>
									Service Tax Number
								</td>
								<td>
									<?php echo e($data->service_tax_number); ?>

								</td>
							</tr>

							<tr>
								<td>
									Country
								</td>
								<td>
									<?php echo e($data->country_name->name); ?>

								</td>
							</tr>

							<tr>
								<td>
									State
								</td>
								<td>
									<?php echo e($data->state_name->name); ?>

								</td>
							</tr>

							<tr>
								<td>
									City
								</td>
								<td>
									<?php echo e($data->city_name->name); ?>

								</td>
							</tr>
							
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