
;

<?php $__env->startSection('title'); ?>

View Attached Vehicles - Go Cabs

<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>

<div class="rightside bg-grey-100">
			<!-- BEGIN PAGE HEADING -->
            <div class="page-head">
				<h1 class="page-title">View Attached Vehicles</h1>
				<!-- BEGIN BREADCRUMB -->
				<a href="<?php echo e(url("/")); ?>/manage_attached_drivers" class="btn btn-dark bg-black color-white pull-right">Back</a>
				<!-- END BREADCRUMB -->
			</div>
			<!-- END PAGE HEADING -->
		<?php  
      $dob = date('m-d-Y',strtotime($data->dob)); 
      $insurance_expiration_date = date('m-d-Y',strtotime($data->insurance_expiration_date));
       ?>
            <div class="container-fluid">
				<div class="row">
                        <div class="col-lg-12">
							<div class="panel no-border">
                            	<div class="panel-title bg-amber-200">
								<div class="panel-head">Driver Details</div>
								</div>
                                <div class="panel-body no-padding-top bg-white">
									<p class="text-light margin-bottom-30"></p>
											<table class="table table-bordered ">
										<thead>
											<tr>
												<th class="vertical-middle">Name</th>
												<th class="vertical-middle">Value </th>
												
											</tr>
										</thead>
										<tbody>

										<tr> 
												
												<td class="vertical-middle">Ride Category</td>
								<td class="vertical-middle">
									<?php if($data->ride_category == 1): ?> Go Cab <?php endif; ?>
									<?php if($data->ride_category == 2): ?> Auto <?php endif; ?>
								</td>
												
											</tr>

											<tr> 
												
												<td class="vertical-middle">Franchise Name</td>
								<td class="vertical-middle">
									<?php if($data->isfranchise == 0): ?>
										Go App 
									<?php endif; ?>

									<?php if($data->isfranchise == 1): ?>
										<?php echo e($data->getfranchise->company_name); ?> 
									<?php endif; ?>
								</td>
												
											</tr>

											<tr> 
												
												<td class="vertical-middle">Driver ID</td>
								<td class="vertical-middle"><?php echo e($driver[0]->driver_id); ?></td>
												
											</tr>

											<tr> 
											<?php $key = hash('sha256', 'wrydes');
							$iv = substr(hash('sha256', 'dispatch'), 0, 16);
							$output = openssl_decrypt(base64_decode($data->password), "AES-256-CBC", $key, 0, $iv);
							?>

												
												<td class="vertical-middle">Password</td>
												<td class="vertical-middle"><?php echo $output; ?></td>
												
											</tr> 

											<tr> 
												
												<td class="vertical-middle">First Name</td>
												<td class="vertical-middle"><?php echo e($data->firstname); ?></td>
												
											</tr>

											
											
											<tr> 

											<tr> 
												
												<td class="vertical-middle">Last Name</td>
									<td class="vertical-middle"><?php echo e($data->lastname); ?></td>
												
											</tr>

											<tr> 
												
												<td class="vertical-middle">Email</td>
								<td class="vertical-middle"><?php echo e($data->email); ?></td>
												
											</tr>
											
											<tr> 
												
												<td class="vertical-middle">Mobile Number</td>
												<td class="vertical-middle"><?php echo e($data->mobile); ?></td>
												
											</tr>
											
											
											 
											 <tr> 
												
												<td class="vertical-middle">License ID</td>
												<td class="vertical-middle"><?php echo e($data->licenseid); ?></td>
												
											</tr>


											<tr> 
												
												<td class="vertical-middle">Gender</td>
												<td class="vertical-middle"><?php echo e($data->gender); ?></td>
												
											</tr>

											<tr> 
												
												<td class="vertical-middle">Date of Birth</td>
												<td class="vertical-middle"><?php echo e($dob); ?></td>
												
											</tr>

											

											

											<tr> 
												
												<td class="vertical-middle">Address</td>
												<td class="vertical-middle"><?php echo e($data->address); ?></td>
												
											</tr>
											<tr> 
												
												<td class="vertical-middle">Country</td>
												<td class="vertical-middle"><?php echo e($data->country_name->name); ?></td>
												
											</tr>

											<tr> 
												
												<td class="vertical-middle">State</td>
												<td class="vertical-middle"><?php echo e($data->state_name->name); ?></td>
												
											</tr>

											

											

											<tr> 
												
												<td class="vertical-middle">Driver Profile</td>
												<td class="vertical-middle">
												<img height="350" width="450" src="<?php echo e(url("/")); ?>/public<?php echo e($data->profile_photo); ?>"></td>
												
											</tr>

											<tr> 
												
												<td class="vertical-middle">License Image</td>
												<td class="vertical-middle">
												<img height="350" width="450" src="<?php echo e(url("/")); ?>/public<?php echo e($data->license); ?>"></td>
												
											</tr>

										</tbody>
									</table>
                            </div>
                        </div><!-- /.col -->
                    </div><!-- /. row -->
		    </div><!-- /.row -->
				
				<!-- /.row -->
				


				<div class="row">
                        <div class="col-lg-12">
							<div class="panel no-border">
                            	<div class="panel-title bg-amber-200">
								<div class="panel-head">Vehicle Details</div>
								</div>
                                <div class="panel-body no-padding-top bg-white">
									<p class="text-light margin-bottom-30"></p>
											<table class="table table-bordered ">
										<thead>
											<tr>
												<th class="vertical-middle">Name</th>
												<th class="vertical-middle">Value </th>
												
											</tr>
										</thead>
										<tbody>
											<tr> 
												
												<td class="vertical-middle">Vehicle Number</td>
												<td class="vertical-middle"><?php echo e($data->car_no); ?></td>
												
											</tr>

											<tr> 
												
												<td class="vertical-middle">Vehicle Photo</td>
												<td class="vertical-middle">
												<img height="350" width="450" src="<?php echo e(url("/")); ?>/public<?php echo e($data->vehical_image); ?>"></td>
												
											</tr>

											<tr> 
												
												<td class="vertical-middle">Brand</td>
												<td class="vertical-middle"><?php echo e($data->brand_name->brand); ?></td>
												
											</tr><tr> 
												
												<td class="vertical-middle">Model</td>
												<td class="vertical-middle"><?php echo e($data->model_name->model); ?></td>
												
											</tr><tr> 
												
												<td class="vertical-middle">Type</td>
												<td class="vertical-middle">
												<?php $__currentLoopData = $cartype; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $ct): $__env->incrementLoopIndices(); $loop = $__env->getFirstLoop(); ?>
													<?php if($ct->id == $data->car_type): ?>
														<?php echo e($ct->car_type); ?>

														<?php if($ct->car_board == 1): ?> W <?php endif; ?>
														<?php if($ct->car_board == 2): ?> Y <?php endif; ?>
													<?php endif; ?>
												<?php endforeach; $__env->popLoop(); $loop = $__env->getFirstLoop(); ?>
												</td>
												
											</tr><tr> 
												
												<td class="vertical-middle">Capacity</td>
												<td class="vertical-middle">
													<?php $__currentLoopData = $cartype; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $ct): $__env->incrementLoopIndices(); $loop = $__env->getFirstLoop(); ?>
													<?php if($ct->id == $data->car_type): ?>
														<?php echo e($ct->capacity); ?>

													<?php endif; ?>
												<?php endforeach; $__env->popLoop(); $loop = $__env->getFirstLoop(); ?>
												</td>
												
											</tr><tr> 
												
												<td class="vertical-middle">RC Number</td>
												<td class="vertical-middle"><?php echo e($data->rc_no); ?></td>
												
											</tr>
											<tr> 
												
												<td class="vertical-middle">RC Image</td>
												<td class="vertical-middle">
												<img height="350" width="450" src="<?php echo e(url("/")); ?>/public<?php echo e($data->rc_image); ?>"</td>
												
											</tr>

											<tr> 
												
												<td class="vertical-middle">Insurance Image</td>
												<td class="vertical-middle">
												<img height="350" width="450" src="<?php echo e(url("/")); ?>/public<?php echo e($data->insurance_image); ?>"</td>
												
											</tr>

											<tr> 
												
												<td class="vertical-middle">Insurance Expiry Date</td>
												<td class="vertical-middle"><?php echo e($insurance_expiration_date); ?></td>
												
											</tr>
											
										</tbody>
									</table>
                            </div>
                        </div><!-- /.col -->
                    </div><!-- /. row -->
		    </div><!-- /.row -->







				<!-- /.row -->
				
				<!-- BEGIN FOOTER -->
				<?php echo $__env->make('includes.footer', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
				<!-- END FOOTER -->
            </div><!-- /.container-fluid -->
        </div>
 <?php $__env->stopSection(); ?>
<?php echo $__env->make('layout.master', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>