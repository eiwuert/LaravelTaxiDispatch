;

<?php $__env->startSection('title'); ?>

Edit Fare - Go Cabs

<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
 <div class="rightside bg-grey-100">
			<!-- BEGIN PAGE HEADING -->
            <div class="page-head">
				<h1 class="page-title">Update Fare Details</h1>
				<!-- BEGIN BREADCRUMB -->
				
				<!-- END BREADCRUMB -->
			</div>
			<!-- END PAGE HEADING -->
	
            <div class="container-fluid">
				<div class="row">
					<div class="col-lg-12">
                           <div class="panel">
                            <div class="panel-title bg-amber-200">
								<div class="panel-head">Fare Information</div>
							</div>
							<div class="panel-body">
								<form class="form-horizontal" role="form" method="post" action="">
								   <?php if(session('error_status')): ?>
										<div class="alert alert-danger">
											<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
											<?php echo e(session('error_status')); ?>

										</div>
									<?php endif; ?>
								 <?php echo e(csrf_field()); ?>

								 <input type="hidden" name="fare_id" value="<?php echo e($fare_list->fare_id); ?>" />
								 <input type="hidden" name="taxt_type" value="<?php echo e($fare_list->car_id); ?>" />
								  <input type="hidden" name="ride_category" value="<?php echo e($fare_list->ride_category); ?>" />
								  
								  <div class="form-group" >
									<label for="" class="col-sm-2 control-label"><?php echo e(trans('config.lblf_franchis')); ?></label>
									<div class="col-sm-4">
										<label for="" class=" control-label"><?php echo e($fare_list->get_franchise->company_name); ?></label>
										
									  </select>
									</div>
								 </div>
								 
								 <div class="form-group <?php echo e($errors->has('ride_category')? 'has-error':''); ?>" >
									<label for="" class="col-sm-2 control-label"><?php echo e(trans('config.lbl_vehicle_category')); ?></label>
									<div class="col-sm-4">
										<label for="" class=" control-label"><?php echo e($fare_list->getvehicle_name->ride_category); ?></label>
										
									  </select>
									</div>
								 </div>
								  <div class="form-group <?php echo e($errors->has('taxt_type')? 'has-error':''); ?>">
									<label for="" class="col-sm-2 control-label">Vehicle Type</label>
									<div class="col-sm-4">
									<label for="" class=" control-label"><?php echo e($fare_list->getcartype_name->car_type); ?>

									<?php if($fare_list->getcartype_name->car_board == 1): ?>
									(W)
									<?php endif; ?>
									<?php if($fare_list->getcartype_name->car_board == 2): ?>
									(Y)
									<?php endif; ?>
									</label>
									</div>
								  </div>
								  
								  <div class="form-group">
									<label for="" class="col-sm-2 control-label">Booking Type</label>
									<div class="col-sm-4">
									<label for="" class=" control-label"><?php echo e($fare_list->booking_type == 1 ? 'Flat rate':''); ?>

									
								   <input type="hidden" name="booking_type" value="<?php echo e($fare_list->booking_type); ?>" />
									
									</label>
									</div>
								  </div>
								  
								   <input type="hidden" name="fare_type" value="<?php echo e($fare_list->fare_type); ?>" />
								 
								   <div class="form-group <?php echo e($errors->has('fare_type')? 'has-error':''); ?>">
									<label for="" class="col-sm-2 control-label">Fare Type</label>
									  <div class="col-sm-4">
										<label for="" class=" control-label">
												<?php echo e($fare_list->fare_type == 1 ? "Base Fare":""); ?>

												<?php echo e($fare_list->fare_type == 2 ? "Morning Time":""); ?>

												<?php echo e($fare_list->fare_type == 3 ? "Night Time":""); ?>

												<?php echo e($fare_list->fare_type == 4 ? "Peek Time":""); ?>

												<?php echo e($fare_list->fare_type == 5 ? "Special Time":""); ?>

										</label>
										</div>
									</div>
								<?php if($fare_list->fare_type ==1): ?>
                                  <div class="form-group <?php echo e($errors->has('minimum_km')? 'has-error':''); ?>">
									<label for="" class="col-sm-2 control-label">Minimum Km</label>
									<div class="col-sm-4">
									  <input type="text" class="form-control number" id="minimum_km" value ="<?php echo e(old('minimum_km',$fare_list->min_km)); ?>" name="minimum_km" placeholder="" required maxlength="2">
									<?php echo $errors->first('minimum_km', '<span class="help-block">:message</span>'); ?>

									</div>
									
								  </div>
                                  <div class="form-group <?php echo e($errors->has('minimum_fare')? 'has-error':''); ?>">
									<label for="" class="col-sm-2 control-label">Minimum Fare</label>
									<div class="col-sm-4">
									  <input type="text" class="form-control number" id="" value ="<?php echo e(old('minimum_fare',$fare_list->min_fare_amount)); ?>" name="minimum_fare" placeholder="" required maxlength="6">
										<?php echo $errors->first('minimum_fare', '<span class="help-block">:message</span>'); ?>

									</div>
								
								  </div>
								  
								    <!--<div class="form-group <?php echo e($errors->has('ride_each_km')? 'has-error':''); ?>">
									<label for="" class="col-sm-2 control-label">Fare/by KM</label>
									<div class="col-sm-4">
									  <input type="text" class="form-control number" id="ride_each_km" value ="<?php echo e(old('ride_each_km',$fare_list->ride_each_km)); ?>" name="ride_each_km" placeholder="" required maxlength="2">
									<?php echo $errors->first('ride_each_km', '<span class="help-block">:message</span>'); ?>

									</div>
									
								  </div>-->
                                  <div class="form-group <?php echo e($errors->has('ride_fare')? 'has-error':''); ?>">
									<label for="" class="col-sm-2 control-label">Ride Fare/km</label>
									<div class="col-sm-4">
									  <input type="text" class="form-control number" id="" value ="<?php echo e(old('ride_fare',$fare_list->ride_fare)); ?>" name="ride_fare" placeholder="" required maxlength="6">
										<?php echo $errors->first('ride_fare', '<span class="help-block">:message</span>'); ?>

									</div>
								
								  </div>
								  
                                  <!-- <div class="form-group <?php echo e($errors->has('cancel_fee')? 'has-error':''); ?>">
									<label for="" class="col-sm-2 control-label">Cancellation Fee</label>
									<div class="col-sm-4">
									  <input type="text" class="form-control number" id=""  value ="<?php echo e(old('cancel_fee',$fare_list->cancellation_fee)); ?>" name="cancel_fee" placeholder=""  maxlength="3">
										<?php echo $errors->first('cancel_fee', '<span class="help-block">:message</span>'); ?>								
								</div>
								  </div> -->
                                  <!-- <div class="form-group <?php echo e($errors->has('fare_below_minkm')? 'has-error':''); ?>">
									<label for="" class="col-sm-2 control-label">Fare Below Min Km Range</label>
									<div class="col-sm-4">
									  <input type="text" class="form-control number" value ="<?php echo e(old('fare_below_minkm')); ?>" name="fare_below_minkm" id="" placeholder="" required maxlength="6">
									<?php echo $errors->first('fare_below_minkm', '<span class="help-block">:message</span>'); ?>								
								</div>
								</div>
                                 <div class="form-group">
									<label for="" class="col-sm-2 control-label">Fare Above Min Km Range</label>
									<div class="col-sm-4">
									  <input type="text" class="form-control" id="" placeholder="" required>
									</div>
								  </div>-->
								  
								   <div class="form-group <?php echo e($errors->has('waiting_time_fare')? 'has-error':''); ?>">
									<label for="" class="col-sm-2 control-label">Vehicle Waiting Charge/min</label>
									
										<!--<div class="col-sm-2">
											<select name="waiting_time" class="form-control" required>
													<option value="1" <?php if(old('waiting_time',$fare_list->waiting_time) == 1) { echo "selected=selected"; } ?>>1 Min</option>
												<option value="2" <?php if(old('waiting_time',$fare_list->waiting_time) ==2) { echo "selected=selected"; } ?>>2 Min</option>
												<option value="3" <?php if(old('waiting_time',$fare_list->waiting_time) == 3) { echo "selected=selected"; } ?>>3 Min</option>
												<option value="4" <?php if(old('waiting_time',$fare_list->waiting_time) == 4) { echo "selected=selected"; } ?>>4 Min</option>
												<option value="5" <?php if(old('waiting_time',$fare_list->waiting_time) == 5) { echo "selected=selected"; } ?>>5 Min</option>
											</select>
										</div>-->
										<div class="col-sm-4">
											<input type="text" maxlength="6" class="form-control number" value ="<?php echo e(old('waiting_time_fare',$fare_list->waiting_charge)); ?>" id="" name="waiting_time_fare"  required>
										<?php echo $errors->first('waiting_time_fare', '<span class="help-block">:message</span>'); ?>

										</div>
									
									
								  </div>
								    <div class="form-group <?php echo e($errors->has('distance_fare')? 'has-error':''); ?> ">
									<label for="" class="col-sm-2 control-label">Fare/min of Ride</label>
								
										<!--<div class="col-sm-2">
											<select name="distance_time" class="form-control" required>
												<option value="1" <?php if(old('distance_time',$fare_list->distance_time) == 1) { echo "selected=selected"; } ?>>1 Min</option>
												<option value="2" <?php if(old('distance_time',$fare_list->distance_time) ==2) { echo "selected=selected"; } ?>>2 Min</option>
												<option value="3" <?php if(old('distance_time',$fare_list->distance_time) == 3) { echo "selected=selected"; } ?>>3 Min</option>
												<option value="4" <?php if(old('distance_time',$fare_list->distance_time) == 4) { echo "selected=selected"; } ?>>4 Min</option>
												<option value="5" <?php if(old('distance_time',$fare_list->distance_time) == 5) { echo "selected=selected"; } ?>>5 Min</option>
											</select>
										</div>-->
										<div class="col-sm-4">
										  <input type="text" maxlength="6" class="form-control number" value ="<?php echo e(old('distance_fare',$fare_list->distance_fare)); ?>" id="" name="distance_fare" required>
										<?php echo $errors->first('distance_fare', '<span class="help-block">:message</span>'); ?>

										</div>
									
									</div>
									
									<?php endif; ?>
									<?php if($fare_list->fare_type ==4 || $fare_list->fare_type ==5 ): ?>
										<!--start time-->
								<div class="time_available">
								
								 <div class="form-group <?php echo e($errors->has('fare_value')? 'has-error':''); ?> ">
									<label for="" class="col-sm-2 control-label">Fare value (%)</label>
								
										<div class="col-sm-4">
									
											<select name="fare_value" class="form-control" >
												<?php $j=0.9;
											for($i=1;$i<=11;$i++) {
											$j=number_format($j+0.1, 1);
											 ?>
											<option value="<?php echo e($j); ?>" <?php if(old('fare_value',number_format($fare_list->fare_percent, 1)) == $j) { echo 'selected=selected'; } ?>><?php echo e($j); ?></option>
										<?php } ?>
											</select>
									<?php echo $errors->first('fare_value', '<span class="help-block">:message</span>'); ?>

										</div>
									
									</div>
								
								
								<div class="time_dafault form-group <?php echo e($errors->has('mstart_time')? 'has-error':''); ?>">
								<label for="" class="col-sm-2 control-label">Morning Start Time</label>
									<div class="col-sm-4">
									<div class="input-group">                                            
																              <input type="text" class="form-control timepicker" value ="<?php echo e(old('start_time_fare',date('h:i a',strtotime($fare_list->ride_start_time)))); ?>" value ="<?php echo e(old('mstart_time')); ?>" name="mstart_time" id="start_time_fare" onkeydown="return false;" readonly />
																              <div class="input-group-addon">
																                  <i class="fa fa-clock-o"></i>
																              </div>
																          </div>
										<?php echo $errors->first('mstart_time', '<span class="help-block">:message</span>'); ?>

								</div>
								</div> 
                                  
                 <div class="time_dafault form-group <?php echo e($errors->has('mend_time')? 'has-error':''); ?>">
									<label for="" class="col-sm-2 control-label">Morning End Time</label>
									<div class="col-sm-4">
									  <div class="input-group">                                            
                                                <input type="text" class="form-control timepicker" name="mend_time" id="end_time_fare" value ="<?php echo e(old('mend_time',date('h:i a',strtotime($fare_list->ride_end_time)))); ?>" onkeydown="return false;" readonly />
                                                <div class="input-group-addon">
                                                    <i class="fa fa-clock-o"></i>
                                                </div>
                                            </div>
											<?php echo $errors->first('mend_time', '<span class="help-block">:message</span>'); ?>

									</div>
									
								  </div> 
								  
								  
								  	<div class="time_dafault form-group <?php echo e($errors->has('estart_time')? 'has-error':''); ?>">
								<label for="" class="col-sm-2 control-label">Evening Start Time</label>
									<div class="col-sm-4">
									<div class="input-group">                                            
																              <input type="text" class="form-control timepicker" value ="<?php echo e(old('estart_time',date('h:i a',strtotime($fare_list->nit_start_time)))); ?>" name="estart_time" id="start_time_fare" onkeydown="return false;" readonly />
																              <div class="input-group-addon">
																                  <i class="fa fa-clock-o"></i>
																              </div>
																          </div>
										<?php echo $errors->first('estart_time', '<span class="help-block">:message</span>'); ?>

								</div>
								</div> 
                                  
                 <div class="time_dafault form-group <?php echo e($errors->has('eend_time')? 'has-error':''); ?>">
									<label for="" class="col-sm-2 control-label">Evening End Time</label>
									<div class="col-sm-4">
									  <div class="input-group">                                            
                                                <input type="text" class="form-control timepicker" value ="<?php echo e(old('eend_time',date('h:i a',strtotime($fare_list->nit_end_time)))); ?>" name="eend_time" id="end_time_fare" onkeydown="return false;" readonly />
                                                <div class="input-group-addon">
                                                    <i class="fa fa-clock-o"></i>
                                                </div>
                                            </div>
											<?php echo $errors->first('eend_time', '<span class="help-block">:message</span>'); ?>

									</div>
									
								  </div> 
                      </div>
                   <!--end time-->  
                   <?php endif; ?>
                   
									
                               
                             
                                  <div class="form-group">

									<div class="col-sm-offset-2 col-sm-4 margin-top-10">
									
									  <button type="button" class="btn btn-dark bg-black color-white" onclick="window.location.href='<?php echo e(url('/manage_fare')); ?>';">Back</button>
                                      <button type="submit" class="btn btn-dark bg-red-600 color-white">Update</button>
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
		
		<link rel="stylesheet" media="all" type="text/css" href="http://code.jquery.com/ui/1.11.0/themes/smoothness/jquery-ui.css" />
		<link rel="stylesheet" media="all" type="text/css" href="http://localhost/date_pic/dist/jquery-ui-timepicker-addon.css" />	
				<!-- ============= example -->

			<!-- date AND Time picker implementation -->

		<script type="text/javascript">
$('.select_box_readonly').attr("disabled", true); 
$('.number').keypress(function(event) {
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