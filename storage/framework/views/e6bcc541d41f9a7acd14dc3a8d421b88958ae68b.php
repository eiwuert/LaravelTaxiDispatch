;

<?php $__env->startSection('title'); ?>

Edit Vehicle-Type - Go Cabs

<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<div class="rightside bg-grey-100">
			<!-- BEGIN PAGE HEADING -->
            <div class="page-head">
				<h1 class="page-title">Edit Vehicle Type</h1>
				<!-- BEGIN BREADCRUMB -->
				
				<!-- END BREADCRUMB -->
			</div>
			<!-- END PAGE HEADING -->

            <div class="container-fluid">
				<div class="row">
					<div class="col-lg-12">
                           <div class="panel">
                            <div class="panel-title bg-amber-200">
								<div class="panel-head">Edit Vehicle Type</div>
							</div>
                            <div class="panel-body">
								<?php if(session('error_status')): ?>
									<div class="alert alert-danger">
										<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
										<?php echo e(session('error_status')); ?>

									</div>
								<?php endif; ?>
								<?php //print_r($car_type); exit; ?>
								<?php $__currentLoopData = $car_type; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $car_type): $__env->incrementLoopIndices(); $loop = $__env->getFirstLoop(); ?>
								<form class="form-horizontal" role="form" method="POST"  enctype="multipart/form-data">
                                  <input type="hidden" name="type_id" value="<?php echo e($car_type->id); ?>"/>
								
                                 <?php echo e(csrf_field()); ?>

								  <div class="form-group <?php echo e($errors->has('ride_category')? 'has-error':''); ?>" >
									<label for="" class="col-sm-2 control-label"><?php echo e(trans('config.lbl_vehicle_category')); ?></label>
									<div class="col-sm-4">
									  <select class="form-control" name="ride_category" id="vehicle_category" disabled>
                                        <?php $__currentLoopData = $ride_category; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $cat): $__env->incrementLoopIndices(); $loop = $__env->getFirstLoop(); ?>
                                        <?php if($cat->id == $car_type->ride_category): ?>
									  <option value="<?php echo e($cat->id); ?>" ><?php echo e($cat->ride_category); ?></option>
									  <?php endif; ?>
									 <?php endforeach; $__env->popLoop(); $loop = $__env->getFirstLoop(); ?>
                                      </select>
									</div>
								  </div>
								 	 <div class="form-group <?php echo e($errors->has('car_type')? 'has-error':''); ?>">
									<label for="" class="col-sm-2 control-label">Vehicle Type</label>
									<div class="col-sm-4">
									  <input type="text" maxlength="20" class="form-control" id="car_type" value ="<?php echo e(old('car_type',$car_type->car_type)); ?>" name="car_type" placeholder="" required >
									<?php echo $errors->first('car_type', '<span class="help-block">:message</span>'); ?>

									</div>
									</div>

									<div class="form-group  <?php echo e($errors->has('taxi_capacity')? 'has-error':''); ?>">
                                    <label for=""
                                           class="col-sm-2 control-label"><?php echo e(trans('config.lblv_capacity')); ?></label>
                                    <div class="col-sm-4">
                                        <input type="text" class="form-control numeric" value="<?php echo e($car_type->capacity); ?>" id=""
                                               placeholder="" name="taxi_capacity" maxlength="3">
                                        <?php echo $errors->first('taxi_capacity', '<span class="help-block">:message</span>'); ?>

                                    </div>
                                </div>
                                
                                
                                <?php if($car_type->ride_category == 1){
                                	echo '<div class="form-group" id="vehical_color">
										<label for="vehical_color" class="col-sm-2 control-label">Vehicle Color</label>
										<div class="col-sm-4">
											<div class="radio radio-theme display-inline-block">
											 <input name="taxi_status" id="optionsRadios1"';
											 if($car_type->car_board == 1){echo 'selected=selected';} echo'  type="radio" value="1">
											 <label for="optionsRadios1">White</label>
											 <input name="taxi_status" id="optionsRadios2"';
											 if($car_type->car_board == 2){echo 'selected=selected';} echo'  type="radio" value="2">
											<label for="optionsRadios2">Yellow</label>
											</div>
										</div>
									</div>';
                                } ?>
									<div class="form-group" id="vehical_color">
										<label for="" class="col-sm-2 control-label"><?php echo e(trans('config.lbl_vehicle_board')); ?></label>
										<div class="col-sm-4">
											<div class="radio radio-theme display-inline-block">
											 <input name="taxi_status" id="optionsRadios1" <?php if(old('car_board',$car_type->car_board) == 1) { echo "checked=checked"; } ?> type="radio" value='1'>
											 <label for="optionsRadios1"><?php echo e(trans('config.lbl_whit')); ?></label>
											 <input name="taxi_status" id="optionsRadios2" <?php if(old('car_board',$car_type->car_board) == 2) { echo "checked=checked"; } ?> type="radio" value="2">
											<label for="optionsRadios2"><?php echo e(trans('config.lbl_yelw')); ?></label>
											</div>
										</div>
									</div>
									
										

									<div class="form-group <?php echo e($errors->has('companydriver_share')? 'has-error':''); ?>">
									<label for="" class="col-sm-2 control-label">Company's Share</label>
									<div class="col-sm-4">
									  <input type="text" class="form-control numeric" id="companydriver_share" value ="<?php echo e(old('companydriver_share',$car_type->companydriver_share)); ?>" maxlength="3" name="companydriver_share" placeholder="" required >
									<?php echo $errors->first('companydriver_share', '<span class="help-block">:message</span>'); ?>

									</div>
									<div class="col-sm-1"><label>%</label></div>
								</div>
								<div class="form-group <?php echo e($errors->has('attacheddriver_share')? 'has-error':''); ?>">
									<label for="" class="col-sm-2 control-label">Attached Vehicle share</label>
									<div class="col-sm-4">
									  <input type="text" class="form-control numeric" id="attacheddriver_share" value ="<?php echo e(old('attacheddriver_share',$car_type->attacheddriver_share)); ?>" maxlength="3" name="attacheddriver_share" placeholder="" required > 
									<?php echo $errors->first('attacheddriver_share', '<span class="help-block">:message</span>'); ?>

									</div>
									<div class="col-sm-1"><label>%</label></div> 	
									
								</div>

								<div class="form-group <?php echo e($errors->has('franchise_share')? 'has-error':''); ?>">
									<label for="" class="col-sm-2 control-label">Franchise's Share</label>
									<div class="col-sm-4">
									  <input type="text" class="form-control numeric" id="franchise_share" value ="<?php echo e(old('franchise_share',$car_type->franchise_share)); ?>" maxlength="3" name="franchise_share" placeholder="" required > 
									<?php echo $errors->first('franchise_share', '<span class="help-block">:message</span>'); ?>

									</div>
									<div class="col-sm-2"><label>%</label></div> 	
									
								</div>


									<div class="form-group  <?php echo e($errors->has('yellow_caricon')? 'has-error':''); ?>">
									<label for="" class="col-sm-2 control-label">Selected Caricon</label>
									<div class="col-sm-4">
									  <input type="file" class="form-control" id="yellow_caricon" name="yellow_caricon" onChange="validateImagewithdimension('yellow_caricon')">
                                      <span>Only .jpg,.png are allowed</span><br>
                                      <span>Only Height:50 Width:100 size images are allowed</span>
                                      	<?php echo $errors->first('yellow_caricon ', '<span class="help-block">:message</span>'); ?>

									</div>
								  </div>
								   <div class="form-group ">
                                        <label for="" class="col-sm-2 control-label"></label>
										<div class="col-sm-4">
                                        <img src="<?php echo e(URL::asset('public'.$car_type->yellow_caricon)); ?>" class="img-responsive"/>
                                        </div>
								  </div>
                                  <div class="form-group  <?php echo e($errors->has('grey_caricon')? 'has-error':''); ?>">
									<label for="" class="col-sm-2 control-label">Un Selected Caricon</label>
									<div class="col-sm-4">
									  <input type="file" class="form-control" id="grey_caricon" name="grey_caricon" onChange="validateImagewithdimension('grey_caricon')">
                                      <span>Only .jpg,.png are allowed</span><br>
                                      <span>Only Height:50 Width:100 size images are allowed</span>
                                      	<?php echo $errors->first('grey_caricon', '<span class="help-block">:message</span>'); ?>

									</div>
								  </div>
								  <div class="form-group ">
                                        <label for="" class="col-sm-2 control-label"></label>
										<div class="col-sm-4">
                                        <img src="<?php echo e(URL::asset('public'.$car_type->grey_caricon)); ?>" class="img-responsive"/>
                                        </div>
								  </div>
								   
                                  <!-- <div class="form-group  <?php echo e($errors->has('black_caricon')? 'has-error':''); ?>">
									<label for="" class="col-sm-2 control-label">Black Caricon</label>
									<div class="col-sm-4">
									  <input type="file" class="form-control" id="black_caricon" onChange="validateImagewithdimension('black_caricon')"  name="black_caricon">
                                      <span>Only .jpg,.png are allowed</span><br>
                                      <span>Only Height:50 Width:100 size images are allowed</span>
                                      	<?php echo $errors->first('black_caricon', '<span class="help-block">:message</span>'); ?>

									</div>
								</div> -->


								<!-- <div class="form-group ">
                                        <label for="" class="col-sm-2 control-label"></label>
										<div class="col-sm-4">
                                        <img src="<?php echo e(URL::asset('public'.$car_type->black_caricon)); ?>" class="img-responsive"/>
                                        </div>
								  </div> -->
							
								  <div class="form-group">
									<div class="col-sm-offset-2 col-sm-4 margin-top-10">
									
									  <button type="button" class="btn btn-dark bg-black color-white" onclick="window.location.href='<?php echo e(url('/type')); ?>';">Back</button>
                                      <button type="submit" id="button1" class="btn btn-dark bg-red-600 color-white">Update</button>
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
				<script>
				 // Remove The base base editing
				var cat=$("#ride_categoy").val();
				if(cat==1){
					$("#vehical_color").show();
				}else{
					$("#vehical_color").hide();
					$("#optionsRadios1").val('0');
					$("#optionsRadios2").val('0');
				}

				$("#button1").click(function(){
					
					var a=$("#companydriver_share").val();
					var b=$("#attacheddriver_share").val();
					var c=$("#franchise_share").val();
					var d = (+a) + (+b) + (+c);
					console.log(d);
					if(d != 100){

						$("#companydriver_share").css({
                        "border": "1px solid red",
                        "background": ""
                    		});
						$("#attacheddriver_share").css({
                        "border": "1px solid red",
                        "background": ""
                    		});
						$("#franchise_share").css({
                        "border": "1px solid red",
                        "background": ""
                    		});
						bootbox.alert('Total Share percentage value should be 100');
						return false;
					}
					if(d == 100){

						$("#companydriver_share").css({
                        "border": "1px solid green",
                        "background": ""
                    		});
						$("#attacheddriver_share").css({
                        "border": "1px solid green",
                        "background": ""
                    		});
						$("#franchise_share").css({
                        "border": "1px solid green",
                        "background": ""
                    		});
						return true;
					}
				});

				</script>
				<!-- /.row -->
				
				<!-- BEGIN FOOTER -->
				<?php echo $__env->make('includes.footer', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
				<!-- END FOOTER -->
            </div><!-- /.container-fluid -->
        </div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layout.master', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>