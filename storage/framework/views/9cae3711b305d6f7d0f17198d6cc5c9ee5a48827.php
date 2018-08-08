;

<?php $__env->startSection('title'); ?>

Manage Fare - Go Cabs

<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>


    <div class="rightside bg-grey-100">
			<!-- BEGIN PAGE HEADING -->
            <div class="page-head">
				<h1 class="page-title">Manage Fare Details</h1>
				<!-- BEGIN BREADCRUMB -->
				<a href="add_fare"><button class="btn btn-dark bg-red-600 color-white pull-right">Add Fare Details</button></a>
				<!-- END BREADCRUMB -->
			</div>
			<!-- END PAGE HEADING -->

            <div class="container-fluid">

            <!-- START OF FILTER-->
	<form name="searchfare" action="" method="get">
	<div class="row">
        <div class="col-lg-12">
				
			<div class="form-horizontal" >
				 <?php echo e(csrf_field()); ?>


				<div class="form-group margin-bottom-20 col-md-4 margin-right-10">
				   	<select class="chosen-select-deselect form-control" name="franchise_id" id="franchise_id" >
							<option value="">--Select Franchise--</option>
						 <?php $__currentLoopData = $franchise; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $fr): $__env->incrementLoopIndices(); $loop = $__env->getFirstLoop(); ?>
					  <option    value="<?php echo e($fr->id); ?>"  <?php echo e(app('request')->input('franchise_id') == $fr->id ? "selected=selected":''); ?> ><?php echo e($fr->company_name); ?></option>
					   <?php endforeach; $__env->popLoop(); $loop = $__env->getFirstLoop(); ?>
					</select>
				</div>

				<div class="form-group margin-bottom-20 col-md-4 margin-right-10">
				   	<select class=" form-control" name="VehicleCategory" id="VehicleCategory" >
							<option value="">--Select Vehicle Category--</option>
						 <?php $__currentLoopData = $ride_category; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $cat): $__env->incrementLoopIndices(); $loop = $__env->getFirstLoop(); ?>
					  <option    value="<?php echo e($cat->id); ?>"  <?php echo e(app('request')->input('VehicleCategory') == $cat->id ? "selected=selected":''); ?> ><?php echo e($cat->ride_category); ?></option>
					   <?php endforeach; $__env->popLoop(); $loop = $__env->getFirstLoop(); ?>
					</select>
				</div>
                                    
                <div class="form-group margin-bottom-20 col-md-4 margin-right-10">
					<select class=" form-control" name="VehicleType" id="VehicleType" >
							<option value="">--Select Vehicle Type--</option>
						 <?php $__currentLoopData = $cartype; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $cat): $__env->incrementLoopIndices(); $loop = $__env->getFirstLoop(); ?>
					  <option    value="<?php echo e($cat->id); ?>" <?php echo e(app('request')->input('VehicleType') == $cat->id ? "selected=selected":''); ?> ><?php echo e($cat->car_type); ?> 
					  <?php if($cat->car_board ==1): ?>
					  (W)
					  <?php endif; ?>
					  <?php if($cat->car_board ==2): ?>
					  (Y)
					  <?php endif; ?>
					  </option>
					   <?php endforeach; $__env->popLoop(); $loop = $__env->getFirstLoop(); ?>
					</select>
				</div>
                                   
                <div class="form-group margin-bottom-20 col-md-4 margin-right-10">
					<select class=" form-control" name="FareType" id="FareType" >
							<option value="">--Select Fare Type--</option>
						<option value="1" <?php echo e(app('request')->input('FareType') == 1 ? "selected=selected":''); ?>>Base fare</option>
						<!-- <option value="2">Morning fare</option>
						<option value="3">Night fare</option> -->
						<option value="4" <?php echo e(app('request')->input('FareType') == 4 ? "selected=selected":''); ?>>Peak fare</option>
						<option value="5" <?php echo e(app('request')->input('FareType') == 5 ? "selected=selected":''); ?>>Special fare</option>
					</select>
				</div>

				<!-- <div class="form-group margin-bottom-20 col-md-4 margin-right-10">

				<input type="text" class="form-control timepicker" name="fromtime" placeholder="From time">
				</div>

				<div class="form-group margin-bottom-20 col-md-4 margin-right-10">

				<input type="text" class="form-control timepicker" name="totime" placeholder="To time">
				</div> -->

				<div class="form-group margin-bottom-20 col-md-4 margin-right-10">
					<input  type="text" class="form-control timepicker " id="start_time_fare" name="start_time_fare" placeholder="From Time" value="<?php echo e(app('request')->input('start_time_fare')); ?>">
				</div>
                                   
                <div class="form-group margin-bottom-20 col-md-4 margin-right-10">
					<input  type="text" class="form-control timepicker " id="end_time_fare" name="end_time_fare" placeholder="To Time" value="<?php echo e(app('request')->input('end_time_fare')); ?>">
                 </div>                   
                
			
                      
                <div class="form-group col-md-2 pull-right">
          			<input type="submit" class="btn btn-dark bg-red-600 color-white " id="button_submit" value="Search" />
          			<button type="button" class="btn btn-dark bg-grey-400 color-black " onclick="window.parent.location='http://52.35.102.74/goapp/manage_fare'">Reset</button>
          		</div> 

				</div>
			</div>
			</div>
	</form>
	<!-- END OF FILTER-->

				<div class="row">
                        <div class="col-lg-12">
							<div class="panel no-border">
                            	<div class="panel-title bg-amber-200">
								<div class="panel-head">Fare List</div>
								
								</div>
                                <div class="panel-body no-padding-top bg-white">
									<p class="text-light margin-bottom-30"></p>
											<table class="table table-bordered display" id="">
										<thead>
											<tr>
												<!-- <th class="vertical-middle">Select</th> -->
												<th class="vertical-middle"><?php echo e(trans("config.lbl_vehicle_category")); ?></th>
												<th class="vertical-middle">Vehicle Type</th>
												<th class="vertical-middle">Fare Type</th>
											
                                                <th class="vertical-middle">Minimum Kilometre Fare</th>
													<th class="vertical-middle">Ride Fare</th>
                                                <!--<th class="vertical-middle">Kilometre Fare</th>-->
                                                <th class="vertical-middle">Fare/Min of ride</th>
                                                <th class="vertical-middle">Vehicle Waiting Charge/Min</th>
                                                <th class="vertical-middle">Franchise</th>
												
                                                <!--<th class="vertical-middle">Time slot </th>-->
                                                <th class="vertical-middle">Action</th>
											</tr>
										</thead>
										<tbody>
										<?php $__currentLoopData = $farelist; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $fare_list): $__env->incrementLoopIndices(); $loop = $__env->getFirstLoop(); ?>
										<tr> 
											
											<!-- <td >
													<input type="checkbox" value="<?php echo e($fare_list->fare_id); ?>">
											</td> -->
											
											<td class="vertical-middle">
											<?php $__currentLoopData = $ride_category; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $rd): $__env->incrementLoopIndices(); $loop = $__env->getFirstLoop(); ?>
											<?php if($rd->id == $fare_list->ride_category): ?>
											<?php echo e($rd->ride_category); ?>

											<?php endif; ?>
											<?php endforeach; $__env->popLoop(); $loop = $__env->getFirstLoop(); ?>

											</td>
											<td class="vertical-middle">
												<?php echo e($fare_list->car_type); ?>

												<?php echo e($fare_list->car_board == 1 ? "(W)":""); ?>

												<?php echo e($fare_list->car_board == 2 ? "(Y)":""); ?>


											</td>
											<td class="vertical-middle">
												<?php echo e($fare_list->fare_type == 1 ? "Base Fare":""); ?>

												<?php echo e($fare_list->fare_type == 2 ? "Morning Time":""); ?>

												<?php echo e($fare_list->fare_type == 3 ? "Night Time":""); ?>

												<?php echo e($fare_list->fare_type == 4 ? "Peak Time":""); ?>

												<?php echo e($fare_list->fare_type == 5 ? "Special Time":""); ?>

												</td>
												
												<td class="vertical-middle"><?php echo e($fare_list->min_fare_amount); ?></td>
												<td class="vertical-middle"><?php echo e($fare_list->ride_fare); ?></td>
                                                <!--<td class="vertical-middle">{ $fare_list->below_min_km_fare}</td>-->
                                                <td class="vertical-middle"> <?php if($fare_list->fare_type !=4 && $fare_list->fare_type !=5): ?>
                                                <?php echo e($fare_list->distance_fare); ?> / <?php echo e($fare_list->distance_time); ?> Min
                                            			<?php endif; ?>    
                                                 </td>
												<td class="vertical-middle">
												 <?php if($fare_list->fare_type !=4 && $fare_list->fare_type !=5): ?>
												<?php echo e($fare_list->waiting_charge); ?>/ <?php echo e($fare_list->waiting_time); ?> Min
												<?php endif; ?>    
												 </td>
												<!--<td class="vertical-middle">
												<?php echo e(date("h:i a",strtotime($fare_list->ride_start_time))); ?> /
												<?php echo e(date("h:i a",strtotime($fare_list->ride_end_time))); ?>

												</td>-->
												<td class="vertical-middle">
												<?php echo e($fare_list->FranchiseName); ?>

												</td>
												<td class="vertical-middle">
												
												
												<a data-toggle="tooltip" title="View" href="<?php echo e(url("/view_fare/")); ?>/<?php echo e($fare_list->fare_id); ?>"><i class="fa fa-eye fa-2x"></i></i></a>
													
													<a data-toggle="tooltip" title="Edit" href="<?php echo e(url("/edit_fare/")); ?>/<?php echo e($fare_list->fare_id); ?>/edit"><i class="fa fa-edit fa-2x"></i></a>
												<?php if($fare_list->fare_type != 1): ?>
													
													<a href="#" data-toggle="tooltip" title="Delete"  onclick="deletefare(<?php echo e($fare_list->fare_id); ?>);"><i class="fa fa-trash fa-2x"></i></a>
													
												<?php endif; ?>
													</td>
											</tr>
												<?php endforeach; $__env->popLoop(); $loop = $__env->getFirstLoop(); ?>
												</tbody>
									
									</table>

   			<table cellspacing="0" cellpadding="0" class="note ma_0 noti" width="100%">
						  <tbody>
							<tr>
							  <!-- <td class="">
							  <i class="fa fa-check-circle active" aria-hidden="true"></i><span> Active</span>
							  <i class="fa fa-times-circle inactive" aria-hidden="true"></i><span> In-Active</span></td> -->
							  
							</tr>
						  </tbody>
						</table>
						
						  <p class="text-light margin-bottom-30"></p>
                                    
                                    
                                    
                                    <!-- <div class="form-group ">
									<label for="" class="col-sm-2 control-label no-padding">Multiple Fare Delete:</label> -->
									<!--<div class="col-sm-2 no-padding">
									  <select disabled="disabled" class="form-control" name="status" id="ch_status">
                                        <option value="delete1" selected="selected">Delete</option>
										<!-- <option value="ajax_deactive_fare">In-Active</option> -->
                                      <!--</select>
									</div>-->
									<!-- <div class="col-sm-2 ">
									<button class="btn btn-danger" <?php if(count($farelist)==0): ?> <?php echo e('disabled=disabled'); ?> <?php endif; ?> id="FareDelete1" type="submit">Delete Selected</button>
								  </div>
								  </div> -->

                            </div>
                        </div><!-- /.col -->
						
						
                    </div><!-- /. row -->
		    </div><!-- /.row -->
				
				<!-- /.row -->
				
				<!-- /.row -->

				<script type="text/javascript">
					
					$('#FareDelete1').click(function(){

						bootbox.confirm({
			message: "Do you want to Delete the fare ?",
			buttons: {
				confirm: {
					label: 'Yes',
					className: 'btn-success'
				},
				cancel: {
					label: 'No',
					className: 'btn-danger'
				}
			},
			callback: function (result) {
				if(result==true){

					// Start of ajax multiple delete if bootbox true
					var a =0;

           //start of bootbox
           $("input:checked").each(function (e) {
        	var form = this;
      		var curdata=[];
      
   
      		var rows_selected = active_table.column(0).checkboxes.selected();
			//console.log(rows_selected);
           $.each(rows_selected, function(index, rowId){
                var n = rowId.lastIndexOf("=");
            var n=rowId.substr(n)
            var cur_val = n.replace('"', '').replace('"', '').replace('=', '').replace('>', '');
            console.log(cur_val);
             curdata.push(cur_val);
                
            });
           
            //console.log(curdata.length);  
            //console.log(curdata[0]);  
             
            var i = 0;
            var msg=[];
              
            var msg2="Fare deleted successfully";
            console.log(curdata[i]); 

                    $.ajaxSetup({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                        }
                    })
                    var erd = curdata.length-1;
                    for(i=0;i<curdata.length;i++){
                    	console.log(curdata[i]);


                    	//start of ajax
                     $.ajax({  
                          url:'deletefare',  
                          method:'POST',  
                          data:{id:curdata[i]},  
                          datatype: 'json',
                          success:function(data)  
                          {  
                      
                             
                         }
                     }); 
                     // End of ajax

                     if(i == erd){
                              console.log(msg);
                              bootbox.alert('<div class="ajax_status"><i class="fa fa-check-circle-o success" aria-hidden="true"></i>'+
               msg2 +' </div>');
                          document.cookie = "tabstatus=2";
                          setTimeout(function(){ window.location.reload(); }, 3000);
                            }     
                 }
                 
                    
                a = 2;
    }); 
           // End of bootbox
		if(a == 0){
		  bootbox.alert("Please Select atleast one fare");
		}
		// End of ajax multi delete
				}
			}
		});

					});
					 $('#FareDelete').click(function(){  
           var a =0;

           //start of bootbox
           $("input:checked").each(function (e) {
        	var form = this;
      		var curdata=[];
      
   
      		var rows_selected = active_table.column(0).checkboxes.selected();
			//console.log(rows_selected);
           $.each(rows_selected, function(index, rowId){
                var n = rowId.lastIndexOf("=");
            var n=rowId.substr(n)
            var cur_val = n.replace('"', '').replace('"', '').replace('=', '').replace('>', '');
            console.log(cur_val);
             curdata.push(cur_val);
                
            });
           
            //console.log(curdata.length);  
            //console.log(curdata[0]);  
             
            var i = 0;
            var msg=[];
            msg.push('Fare deleted successfully');  

            console.log(curdata[i]); 

                    $.ajaxSetup({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                        }
                    })
                    var erd = curdata.length-1;
                    for(i=0;i<curdata.length;i++){
                    	console.log(curdata[i]);


                    	//start of ajax
                     $.ajax({  
                          url:'deletefare',  
                          method:'POST',  
                          data:{id:curdata[i]},  
                          success:function(data)  
                          {  
                             msg.push(data.Response);
                         }
                     }); 
                     // End of ajax

                     if(i == erd){
                              console.log(msg);
                              bootbox.alert('<div class="ajax_status"><i class="fa fa-check-circle-o success" aria-hidden="true"></i>'+
               msg +' </div>');
                          document.cookie = "tabstatus=2";
                          setTimeout(function(){ window.location.reload(); }, 3000);
                            }     
                 }
                 
                    
                a = 2;
    }); 
           // End of bootbox
		if(a == 0){
		  bootbox.alert("Please Select atleast one fare");
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