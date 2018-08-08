;

<?php $__env->startSection('title'); ?>

Add Tax - Go Cabs

<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
 <div class="rightside bg-grey-100">
			<!-- BEGIN PAGE HEADING -->
            <div class="page-head">
				<h1 class="page-title">Add Tax Details</h1>
				<!-- BEGIN BREADCRUMB -->
				
				<!-- END BREADCRUMB -->
			</div>
			<!-- END PAGE HEADING -->
		
            <div class="container-fluid">
				<div class="row">
					<div class="col-lg-12">
                           <div class="panel">
                            <div class="panel-title bg-amber-200">
								<div class="panel-head">Tax Information</div>
							</div>
							<div class="panel-body">
								<form class="form-horizontal" role="form" method="post">
								     <?php echo e(csrf_field()); ?>

								<div class="form-group <?php echo e($errors->has('tax_name')? 'has-error':''); ?>">
									<label for="" class="col-sm-2 control-label">Tax Name</label>
									<div class="col-sm-4">
									  <input type="text" maxlength="30" class="form-control" id="tax_name" value ="<?php echo e(old('tax_name')); ?>" name="tax_name" placeholder="" required > 
									<?php echo $errors->first('tax_name', '<span class="help-block">:message</span>'); ?>

									</div>
									
									
								</div>	
                                 <div class="form-group <?php echo e($errors->has('tax_percentage')? 'has-error':''); ?>">
									<label for="" class="col-sm-2 control-label">Tax (%)</label>
									<div class="col-sm-4">
									  <input type="text" class="form-control number" id="tax_percentage" value ="<?php echo e(old('tax_percentage')); ?>" maxlength="5" name="tax_percentage" placeholder="" required > 
									<?php echo $errors->first('tax_percentage', '<span class="help-block">:message</span>'); ?>

									</div>
									<div class="col-sm-2"><label>%</label></div> 	
									
								</div>	 
                                  <div class="form-group  <?php echo e($errors->has('country')? 'has-error':''); ?>">
									<label for="" class="col-sm-2 control-label">Country</label>
									<div class="col-sm-4">
									  <select data-placeholder="Select driver" class="chosen-select-deselect form-control" name="country" id="country">
                                      <option value="">--Select Country--</option>
                                        <?php $__currentLoopData = $country_list; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $country): $__env->incrementLoopIndices(); $loop = $__env->getFirstLoop(); ?>
									  <option    value="<?php echo e($country->id); ?>" <?php if(old('country') ==$country->id) { echo "selected=selected"; } ?>><?php echo e($country->name); ?></option>
									    <?php endforeach; $__env->popLoop(); $loop = $__env->getFirstLoop(); ?>
                                      </select>
                                      	<?php echo $errors->first('country', '<span class="help-block">:message</span>'); ?>

                                     </div>
								  </div>
                                  
                                  <div class="form-group  <?php echo e($errors->has('state')? 'has-error':''); ?>">
									<label for="" class="col-sm-2 control-label">State</label>
									<div class="col-sm-4">
									  <select class="form-control "  name="state" id="state">
                                     <option value="">--Select State--</option>
                                      </select>
                                      	<?php echo $errors->first('state', '<span class="help-block">:message</span>'); ?>

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
									<button type="button" class="btn btn-dark bg-black color-white" onclick="window.location.href='<?php echo e(url('/manage-tax')); ?>';">Back</button>
									  <button type="submit" class="btn btn-dark bg-red-600 color-white">Add</button>
									  	<button type="reset" class="btn btn-dark bg-grey-400 color-black">Reset</button>
									</div>
								  </div>
								</form>
                            </div>
						</div>
					</div><!-- /.col -->
				</div><!-- /.row -->
				
				<!-- /.row -->
				
				<!-- /.row -->
		

				<!-- ============= example -->

			<!-- date AND Time picker implementation -->

		<script type="text/javascript">
			

$('.number').keypress(function(event) {
	console.log(event.which);
  if ((event.which != 46 || $(this).val().indexOf('.') != -1) && (event.which < 48 || event.which > 57)) {
    event.preventDefault();
  }
});
</script>
			
					<!-- BEGIN FOOTER -->
				<?php echo $__env->make('includes.footer', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
				<!-- END FOOTER -->
            </div><!-- /.container-fluid -->
        </div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layout.master', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>