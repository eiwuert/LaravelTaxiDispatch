;

<?php $__env->startSection('title'); ?>

Manage Tax - Go Cabs

<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
        <div class="rightside bg-grey-100">
			<!-- BEGIN PAGE HEADING -->
            <div class="page-head">
				<h1 class="page-title">Manage Tax Details</h1>
				<!-- BEGIN BREADCRUMB -->
				<a href="add-tax"><button class="btn btn-dark bg-red-600 color-white pull-right">Add Tax</button></a>
				<!-- END BREADCRUMB -->
			</div>
			<!-- END PAGE HEADING -->

            <div class="container-fluid">
				<div class="row">
                        <div class="col-lg-12">
							<div class="panel no-border">
                            	<div class="panel-title bg-amber-200">
								<div class="panel-head">Tax List</div>
								</div>
                                <div class="panel-body no-padding-top bg-white">
									<p class="text-light margin-bottom-30"></p>
									 <form id="change-tax-status" action="#" method="POST">
											<table class="table table-bordered display" id="">
										<thead>
											<tr>
												<!-- <th class="vertical-middle">Select</th> -->
												<th class="vertical-middle">Tax Name</th>
												<th class="vertical-middle">Percentage (%)</th>
												<th class="vertical-middle">Country</th>
												<!-- <th class="vertical-middle">State</th> -->
                                                <th class="vertical-middle">Status</th>
                                                <th class="vertical-middle">Action</th>
											</tr>
										</thead>
										<tbody>
										
										<?php $__currentLoopData = $tax_list; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $tax): $__env->incrementLoopIndices(); $loop = $__env->getFirstLoop(); ?>
										<tr> 
												<!-- <td class="vertical-middle"><input type="checkbox" value="<?php echo e($tax->id); ?>"></td> -->
												<td class="vertical-middle"><?php echo e($tax->tax_name); ?></td>
												<td class="vertical-middle"><?php echo e($tax->percentage); ?></td>
												<td class="vertical-middle"><?php echo e($tax->country_name->name); ?></td>
												
												
                                               <td class="vertical-middle"><i class='<?php echo e(($tax->status == 1) ? "fa fa-check-circle active" : "fa fa-times-circle inactive"); ?>' aria-hidden='true'></i></td>
												<td class="vertical-middle">
													<a href="<?php echo e(url('/edit-tax/')); ?>/<?php echo e($tax->id); ?>/edit" data-toggle="tooltip" title="Edit"><i class="fa fa-edit fa-2x"></i></a>
													<?php if($tax->status == 1): ?>
													<a href="#" data-toggle="tooltip" title="Block" onclick="deactivate(<?php echo e($tax->id); ?>,'ajax_deactive_tax','Tax Details');"><i class="fa fa-close fa-2x"></i></a>
													<?php else: ?>
													<a href="#" data-toggle="tooltip" title="Activate"  onclick="activate(<?php echo e($tax->id); ?>,'ajax_activate_tax','Tax Details');"><i class="fa fa-check fa-2x"></i></a>
													<?php endif; ?>
													<!-- Delete tax -->
													<a href="#" data-toggle="tooltip" title="Delete" onclick="delete1(<?php echo e($tax->id); ?>,'deletetax','Tax');"><i class="fa fa-trash fa-2x"></i></a>
													<!-- Delete tax end -->
													</td>
											</tr>
												<?php endforeach; $__env->popLoop(); $loop = $__env->getFirstLoop(); ?>
											
										</tbody>
									</table>
									<table cellspacing="0" cellpadding="0" class="note ma_0 noti" width="100%">
						  <tbody>
							<tr>
							  <td class="">
							  <i class="fa fa-check-circle active" aria-hidden="true"></i><span> Active</span>
							  <i class="fa fa-times-circle inactive" aria-hidden="true"></i><span> In-Active</span></td>
							  
							</tr>
						  </tbody>
						</table>
						
						  <p class="text-light margin-bottom-30"></p>
                                    
                                    
                                    
                                    <!-- <div class="form-group ">
									<label for="Change" class="col-sm-1 control-label no-padding">Change</label>
									<div class="col-sm-2 no-padding">
									  <select class="form-control" name="status" id="ch_status">
                                        <option value="1">Activate</option>
										<option value="0">In-Active</option>
                                      </select>
									</div>
									<div class="col-sm-2 ">
									<button class="btn btn-danger" <?php if(count($tax_list)==0): ?> <?php echo e('disabled=disabled'); ?> <?php endif; ?> type="submit">Change</button>
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