;

<?php $__env->startSection('title'); ?>

View Taxi - Go Cabs

<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
 <div class="rightside bg-grey-100">
			<!-- BEGIN PAGE HEADING -->
            <div class="page-head">
				<h1 class="page-title">View Offer Details 

				</h1>
				 <a href="<?php echo e(url("/")); ?>/manage_offers" class="btn btn-dark bg-black color-white pull-right">Back</a>
				
				<!-- BEGIN BREADCRUMB -->
				
				<!-- END BREADCRUMB -->
			</div>
			<!-- END PAGE HEADING -->
			
            <div class="container-fluid">
				<div class="row">
					<div class="col-lg-12">
					
                           <div class="panel">
                            <div class="panel-title bg-amber-200">
								<div class="panel-head">Offer Information</div>
							</div>
							<div class="panel-body">
							<div class="table-responsive"> 
						<table class="table table-bordered" style="max-width: 50%;">
							<tr>
								<td style="width: 43%;"><label  class="view_label">Coupon Category</label></td>
								<td>
								<?php if($offers->coupon_basedon == 1): ?> Customer Ride Count <?php endif; ?>
								<?php if($offers->coupon_basedon == 2): ?> Customer Ride Value <?php endif; ?>
								<?php if($offers->coupon_basedon == 3): ?> Vehicle Category <?php endif; ?>
								<?php if($offers->coupon_basedon == 4): ?> Limited Users <?php endif; ?>
								<?php if($offers->coupon_basedon == 5): ?> All Users <?php endif; ?>
								<?php if($offers->coupon_basedon == 6): ?> Free Ride <?php endif; ?></td>
							</tr>

							<tr>
								<td><label  class="view_label">
								<?php if($offers->coupon_basedon != 3): ?>Coupon Category Value <?php endif; ?>
								<?php if($offers->coupon_basedon == 3): ?> Coupon Vehicle Type <?php endif; ?>
								</label></td>
								<td><?php if($offers->coupon_basedon == 3): ?><?php echo e($offers->getcarname->car_type); ?> 
									  	<?php if($offers->getcarname->car_board == 1): ?> (W) <?php endif; ?>
									  	<?php if($offers->getcarname->car_board == 2): ?> (Y) <?php endif; ?>
								<?php endif; ?>
								<?php if($offers->coupon_basedon != 3): ?><?php echo e($offers->coupon_typevalue); ?> <?php endif; ?>
								</td>
							</tr>

							<tr>
								<td><label  class="view_label">Coupon Code</label></td>
								<td><?php echo e($offers->coupon_code); ?> </td>
							</tr>

							<tr>
								<td><label  class="view_label">Coupon Type</label></td>
								<td><?php if($offers->coupon_type == 2): ?>
                                        Offer
                                        <?php endif; ?>
                                        <?php if($offers->coupon_type == 1): ?>
                                        Cash Back
                                        <?php endif; ?>

                                        </td>
							</tr>

							<?php if($offers->coupon_basedon == 3): ?>
							<tr>
								<td><label  class="view_label">Vehicle Type</label></td>
								<td><?php echo e($offers->getcarname->car_type); ?>

								<?php if($offers->getcarname->car_board == 1): ?> (W) <?php endif; ?>
								<?php if($offers->getcarname->car_board == 2): ?> (Y) <?php endif; ?>
								</td>
							</tr>
							<?php endif; ?>

							<tr>
								<td><label  class="view_label">Coupon Amount</label></td>
								<td><?php echo e($offers->coupon_value); ?></td>
							</tr>

							<tr>
								<td><label  class="view_label">Coupon Description</label></td>
								<td><?php echo e($offers->coupon_desc); ?></td>
							</tr>

							<tr>
								<td><label  class="view_label">Offer Usage Count</label></td>
								<td><?php echo e($offers->usage_count); ?></td>
							</tr>

							<?php 
                                        $fromdate = date("d-m-Y", strtotime($offers->valid_from)); 
                                        $todate = date("d-m-Y", strtotime($offers->valid_to)); 
                            ?>
							<tr>
								<td><label  class="view_label">Offer From</label></td>
								<td><?php echo e($fromdate); ?></td>
							</tr>

							<tr>
								<td><label  class="view_label">Offer Expiry Date</label></td>
								<td><?php echo e($todate); ?></td>
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