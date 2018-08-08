
;

<?php $__env->startSection('title'); ?>

Update Attached Vehicles - Go Cabs

<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<div class="rightside bg-grey-100">
			<!-- BEGIN PAGE HEADING -->
    <div class="page-head">
	<h1 class="page-title">Edit Attached Vehicles</h1>
				<!-- BEGIN BREADCRUMB -->				
				<!-- END BREADCRUMB -->
			</div>
			<!-- END PAGE HEADING -->
		<?php if(Session::has('attached_driver_added')): ?>
           <div class="alert alert-success"><strong><?php echo e(Session::get('attached_driver_added')); ?></strong></div>
        <?php endif; ?>

<div class="container-fluid">
	<div class="row">
		<div class="col-lg-12">
			<div class="panel">
				<div class="panel-title bg-amber-200">
					<div class="panel-head">Driver & Taxi Information</div>
					</div>
					<div class="panel-body">
					<form name="form" class="form-horizontal" method="post" action="<?php echo e(url("/")); ?>/UpdateAttachedDrivers" role="form" enctype="multipart/form-data">
					<input type="hidden" name="_token" value="<?php echo e(csrf_token()); ?>">
					<input type="hidden" name="id" value="<?php echo e($data->id); ?>">

	<div class="col-lg-6">
	<h4>Driver Details:</h4>
	<p class="text-light margin-bottom-30"></p>
		
								<!--<div class="form-group">
                                    <label for="" class="col-sm-4 control-label"> Company Type</label>
                                    <div class="col-sm-8">
                                    <label class="control-label"> <?php if($data->isfranchise == 1): ?> Franchise <?php else: ?> Go <?php endif; ?></label>
                                    <div class="radio radio-theme display-inline-block" style="display: none !important;">
                                            <input name="Franchise" <?php if($data->isfranchise == 1): ?>
                                             checked="checked" <?php endif; ?> id="franchiseyes"  type="radio" 
                                                 value="1"  >
                                            <label for="franchiseyes">Franchise</label>
                                            <input name="Franchise" id="franchiseno" <?php if($data->isfranchise == 0): ?> checked="checked" <?php endif; ?> type="radio" value="0" >
                                            <label for="franchiseno">Go</label>
                                        </div>
                                    </div>
                                </div>-->

                                <?php  
                                if($data->isfranchise == 1){
                                	$fran_id = $data->franchise_id;
                                }
                                else{
                                $fran_id = 0;
                                }
                                 ?>
								<div id="franchise">
                                    <div class="form-group">
                                        <label for="" class="col-sm-4 control-label">Franchise</label>
                                        <div class="col-sm-8">
                                        <select class="chosen-select-deselect form-control" name="franchise" id="franchise_select" disabled>
                                        	
                                            <option value="">--Select Your Franchise--</option>
                                            
                                            <?php $__currentLoopData = $franchise; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $r): $__env->incrementLoopIndices(); $loop = $__env->getFirstLoop(); ?>
                                            <option value="<?php echo e($r->id); ?>"
                                        <?php if($r->id == $fran_id): ?> selected="selected" <?php endif; ?>
                                            ><?php echo e($r->company_name); ?></option>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getFirstLoop(); ?>
                                        </select>
                                    </div>
                                    </div>
                                </div>
                                
                                
	

	<div class="form-group  <?php echo e($errors->has('country')? 'has-error':''); ?>">
                                    <label for="" class="col-sm-4 control-label">Vehicle Category</label>
                                    <div class="col-sm-8">
                                        <select <?php if($data->profile_status ==1): ?>  <?php endif; ?> class="form-control validate " name="ridetype" id="VehicleCategory">  
           <?php $__currentLoopData = $rc; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $rcd): $__env->incrementLoopIndices(); $loop = $__env->getFirstLoop(); ?>
           <option value="<?php echo e($rcd->id); ?>" <?php if($rcd->id == $data->ride_category): ?> selected="selected"<?php endif; ?>><?php echo e($rcd->ride_category); ?></option>
           <?php endforeach; $__env->popLoop(); $loop = $__env->getFirstLoop(); ?>

                                         </select>
                                        <?php echo $errors->first('country', '<span class="help-block">:message</span>'); ?>

                                    </div>
                                </div>

	    <div class="form-group">
		<label for="" class="col-sm-4 control-label">Driver First Name</label>
		<div class="col-sm-8">
		  <input name="driver_first_name" maxlength="15" type="text" class="form-control validate" value="<?php echo e($data->firstname); ?>" >
		</div>
	  </div>
	  
	  <div class="form-group">
		<label for="" class="col-sm-4 control-label">Driver Last Name</label>
		<div class="col-sm-8">
		  <input name="driver_last_name" maxlength="15" type="text" class="form-control validate" value="<?php echo e($data->lastname); ?>" >
		</div>
	  </div>
	  
	  <div class="form-group">
		<label for="" class="col-sm-4 control-label">Email</label>
		<div class="col-sm-8">
		  <input name="email" type="email" maxlength="40" class="form-control" value="<?php echo e($data->email); ?>">
		</div>
	  </div>
	  
	  <div class="form-group">
		<label for="" class="col-sm-4 control-label">Gender</label>
		<div class="col-sm-8">
			<div class="radio radio-theme display-inline-block">
			 <input name="gender" value="male" id="optionsRadios1" checked="checked" type="radio">
			 <label for="optionsRadios1">Male</label>
             <input name="gender" value="female" id="optionsRadios2" type="radio">
			<label for="optionsRadios2">Female</label>
			</div>
		</div>
	  </div>

	  <div class="form-group">
    <label for="" class="col-sm-4 control-label"></label>
    <div class="col-sm-8">						  
	<img height="200" width="300" src="<?php echo e(url("/")); ?>/public<?php echo e($data->profile_photo); ?>">
	</div>
	</div>

	  <div class="form-group">
        <label for="" class="col-sm-4 control-label">Driver Photo</label>
        <div class="col-sm-8">
            <input type="file" name="profilepicture" id="profilepicture" onChange="validateImage('profilepicture')" class="form-control">
            <span>Only .jpg,.png are allowed</span>
        </div>
    </div>
      <?php  

      $dob = date('m-d-Y',strtotime($data->dob)); 
      $insurance_expiration_date = date('m-d-Y',strtotime($data->insurance_expiration_date));
       ?>
      <div class="form-group">
		<label for="" class="col-sm-4 control-label">Date of Birth</label>
		<div class="col-sm-8">
		  <input name="dob" class="form-control validate datepicker1" id="datepicker" placeholder="" required="" type="text" value="<?php echo e($dob); ?>">
		</div>
	  </div>
	  
	  <div class="form-group">
		<label for="" class="col-sm-4 control-label">Mobile Number</label>
		<div class="col-sm-8">
		  <input type="tel" name="mobile_number" class="form-control validate" value="<?php echo e($data->mobile); ?>">
		</div>
	  </div>
	  
	  <div class="form-group">
		<label for="" class="col-sm-4 control-label">Password</label>
		<div class="col-sm-8">	
					
		  <input type="text" name="password" maxlength="15" class="form-control validate" value="<?php echo e($password); ?>" >
		 
		</div>
	  </div>
	  
	  <!--<div class="form-group">
		<label for="" class="col-sm-4 control-label">Confirm Password</label>
		<div class="col-sm-8">
		
		  <input type="text" name="confirm_password" class="form-control validate" value="<?php echo e($password); ?>">
		  
		</div>
	  </div>-->

	  <div class="form-group">
		<label for="" class="col-sm-4 control-label">License ID</label>
		<div class="col-sm-8">
		
		  <input type="text" name="licenseid" maxlength="20" class="form-control validate" value="<?php echo e($data->licenseid); ?>">
		  
		</div>
	  </div>
	  
	  	<div class="form-group  <?php echo e($errors->has('country')? 'has-error':''); ?>">
		<label for="" class="col-sm-4 control-label">Country</label>
		<div class="col-sm-8">
		  <select class="form-control validate " name="country" id="country">
          <option value="">--Select Country--</option>
            <?php $__currentLoopData = $country_list; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $country): $__env->incrementLoopIndices(); $loop = $__env->getFirstLoop(); ?>
		  <option    value="<?php echo e($country->id); ?>" 
		 <?php if($country->id ==$data->country) { echo "selected=selected"; } ?>><?php echo e($country->name); ?></option>
		    <?php endforeach; $__env->popLoop(); $loop = $__env->getFirstLoop(); ?>
          </select>
          	<?php echo $errors->first('country', '<span class="help-block">:message</span>'); ?>

         </div>
	  </div>
      
      <div class="form-group  <?php echo e($errors->has('state')? 'has-error':''); ?>">
		<label for="" class="col-sm-4 control-label">State</label>
		<div class="col-sm-8">
		  <select class="form-control validate "  name="state" id="state">
         <option value="">--Select State--</option>
         <input type="hidden" value="<?php echo $data->state;  ?>" id="temp_state"/>
          </select>
          	<?php echo $errors->first('state', '<span class="help-block">:message</span>'); ?>

		</div>
	  </div>
      
      <div class="form-group  <?php echo e($errors->has('city')? 'has-error':''); ?>">
		<label for="" class="col-sm-4 control-label">City</label>
		<div class="col-sm-8">
		  <select class="form-control validate" name="city" id="city">
          <option value="">--Select City--</option>
          <input type="hidden" value="<?php echo $data->city;  ?>" id="temp_city"/>
          </select>
          	<?php echo $errors->first('city', '<span class="help-block">:message</span>'); ?>

		</div>
	  </div>
      
     <div class="form-group">
	<label for="" class="col-sm-4 control-label">Address</label>
	<div class="col-sm-8">
	  <textarea rows="3" name="address" class="form-control validate" ><?php echo e($data->address); ?></textarea>
	<div id="address" ></div></div>
	</div>

	<div class="form-group">
    <label for="" class="col-sm-4 control-label"></label>
    <div class="col-sm-8">						  
	<img height="200" width="300" src="<?php echo e(url("/")); ?>/public<?php echo e($data->license); ?>">
	</div>
	</div>


	  <div class="form-group">
		<label for="" class="col-sm-4 control-label">Driver License Number</label>
		<div class="col-sm-8">
		  <input type="file" name="driver_license_number" id="driver_license_number" class="form-control" onChange="validateImage('driver_license_number')">
		   <span>Only .jpg,.png are allowed</span>
		</div>
	  </div>
      
      
</div>

<div class="col-lg-6">
	<h4>Vehicle Details:</h4>
	<p class="text-light margin-bottom-30"></p>
	   <div class="form-group">
		<label for="" class="col-sm-4 control-label">Vehicle Number</label>
		<div class="col-sm-8">
		  <input type="text" maxlength="20" name="taxi_number" class="form-control validate" value="<?php echo e($data->car_no); ?>" >
		</div>
	  </div>
	<input type="hidden" name="VehicleType_old" class="form-control validate" value="<?php echo e($data->car_type); ?>_<?php echo e($data->car_board); ?>" >
	  <div class="form-group">
         <label for="" class="col-sm-4 control-label">Vehicle Type</label>
        <div class="col-sm-8">
			
			<select <?php if($data->profile_status ==1): ?>  <?php endif; ?> name="taxi_type" id="VehicleType" class="form-control validate">
			<option disabled selected value> -- select an Type -- </option>
									
				<?php $__currentLoopData = $cartype; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $car_types): $__env->incrementLoopIndices(); $loop = $__env->getFirstLoop(); ?>
				<option  value="<?php echo e($car_types->id); ?>"
				<?php if($data->car_type == $car_types->id){ echo "selected=selected";} 
				if($data->car_type == $car_types->car_type && $data->car_board == $car_types->car_board){ echo "selected=selected";}?>>
				<?php echo e($car_types->car_type); ?> <?php if($car_types->car_board == 1): ?> (W) <?php endif; ?>
				<?php if($car_types->car_board == 2): ?> (Y) <?php endif; ?></option>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getFirstLoop(); ?>
            </select>
		</div>
    </div>
	   
	   <div class="form-group">
    <label for="" class="col-sm-4 control-label"></label>
    <div class="col-sm-8">						  
	<img height="200" width="300" src="<?php echo e(url("/")); ?>/public<?php echo e($data->vehical_image); ?>">
	</div>
	</div>

	  <div class="form-group">
        <label for="" class="col-sm-4 control-label">Vehicle Photo</label>
        <div class="col-sm-8">
            <input type="file" name="vehiclepicture" id="taxipicture" onChange="validateImage('taxipicture')" class="form-control">
            <span>Only .jpg,.png are allowed</span>
        </div>
    </div>


	<div class="form-group">
         <label for="" class="col-sm-4 control-label">Vehicle Brand</label>
        <div class="col-sm-8">
			
			<select  name="taxi_brand" class="form-control validate" id="VehicleBrand">
			<option disabled selected value> -- select an Brand -- </option>
									
				<?php $__currentLoopData = $carbrand; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $car_brands): $__env->incrementLoopIndices(); $loop = $__env->getFirstLoop(); ?>
				<option  value="<?php echo e($car_brands->id); ?>"
			<?php if($data->brand == $car_brands->id){ echo "selected=selected";} ?>
				><?php echo e($car_brands->brand); ?></option>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getFirstLoop(); ?>
            </select>
		</div>
    </div>
    <div class="form-group">
         <label for="" class="col-sm-4 control-label">Vehicle Model</label>
        <div class="col-sm-8">
			
			<select  name="taxi_model" class="form-control validate" id="taxi-model">
			<?php $__currentLoopData = $carmodel; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $cm): $__env->incrementLoopIndices(); $loop = $__env->getFirstLoop(); ?>
			<?php if($cm->id == $data->model): ?>			
			<option selected value="<?php echo e($cm->id); ?>"> <?php echo e($cm->model); ?> </option>
			<?php endif; ?>
			<?php endforeach; $__env->popLoop(); $loop = $__env->getFirstLoop(); ?>
			
									
				
            </select>
		</div>
    </div>
    
								  
	<!--  <div class="form-group">
									<label for="" class="col-sm-4 control-label">Capacity</label>
									<div class="col-sm-8">
									  <input type="text" name="capacity" class="form-control validate" value="<?php echo e($data->capacity); ?>">
									</div>
								  </div> -->
					 <div class="form-group">
									<label for="" class="col-sm-4 control-label">RC Number</label>
									<div class="col-sm-8">
									  <input maxlength="20" type="text" name="rc_number" class="form-control validate" value="<?php echo e($data->rc_no); ?>">
									</div>
								  </div>

								  <div class="form-group">
								    <label for="" class="col-sm-4 control-label"></label>
								    <div class="col-sm-8">						  
									<img height="200" width="300" src="<?php echo e(url("/")); ?>/public<?php echo e($data->rc_image); ?>">
									</div>
									</div>

								  <div class="form-group">
									<label for="" class="col-sm-4 control-label">RC Image</label>
									<div class="col-sm-8">
									  <input type="file" name="rc_image" id="rc_image" onChange="validateImage('rc_image')" class="form-control">
									  <span>Only .jpg,.png are allowed</span>
									</div>
								  </div>

								  <div class="form-group">
								    <label for="" class="col-sm-4 control-label"></label>
								    <div class="col-sm-8">						  
									<img height="200" width="300" src="<?php echo e(url("/")); ?>/public<?php echo e($data->insurance_image); ?>">
									</div>
									</div>

                                  <div class="form-group">
									<label for="" class="col-sm-4 control-label">Insurance Image</label>
									<div class="col-sm-8">
									  <input type="file" name="insurance_image" id="insurance_image" class="form-control" onChange="validateImage('insurance_image')">
									  <span>Only .jpg,.png are allowed</span>
									</div>
								  </div>
								  <?php 
								  //echo $insurance_expiration_date; exit;
								  $d = explode('-', $insurance_expiration_date);
            					  $de = $d[0]."/".$d[1]."/".$d[2];
								  $exdate = date('m/d/Y',strtotime($insurance_expiration_date));
								   ?>
                                  <div class="form-group">
									<label for="" class="col-sm-4 control-label">Insurance Expiry Date</label>
									<div class="col-sm-8">
									  <input type="text" class="form-control validate datepicker" name="insurance_exp_date" id="datepicker1" class="form-control validate" value="<?php echo e($de); ?>">
									</div>
 
								  </div>
                            </div>
                            
							
			<div class="col-md-12">
				<div class="form-group pull-right">
					<div class="col-sm-12 margin-top-10 ">
						<a  href="<?php echo e(url("/")); ?>/manage_attached_drivers" class="btn btn-dark bg-black color-white">Back</a>
					  <button type="reset" class="btn btn-dark bg-grey-400 color-black">Reset</button>
					  <button id="button" type="submit" class="btn btn-dark bg-red-600 color-white">Update</button>
					  
					</div>
				  </div>
			</div>
			</form>
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
        </div><!-- /.rightside -->
<!-- Added for Client side Javascript Form Validation -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js">
</script>
	<script>
	$(document).ready(function()
    {

    	/* var franchise = $("input[name='Franchise']:checked").val();
        if(franchise == 1){
            $("#franchise").show();
            $("#franchise_select").addClass("form-control");
             
        }
        else{
            $("#franchise").hide();
        }

        $("#franchiseyes").click(function(){
            $("#franchise").show();
            $("#franchise_select").addClass("validate");
        });
        $("#franchiseno").click(function(){
        	$("#franchise_select").removeClass("validate");
            $("#franchise").hide();
        }); */

     $('#button').click(function(e)
     {
      var isvalid=true;
      $(".validate").each(function()
      {
        if ($.trim($(this).val()) == '') {
               isValid = false;
               $(this).css({
                   "border": "1px solid red",
                   "background": ""
               });
       if (isValid == false)
           e.preventDefault();
           }
           else {
               $(this).css({
                   "border": "2px solid green",
                   "background": ""
               });
           }
       });
		   });
		});
	</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layout.master', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>