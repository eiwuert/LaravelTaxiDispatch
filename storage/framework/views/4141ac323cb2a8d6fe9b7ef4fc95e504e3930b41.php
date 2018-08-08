;

<?php $__env->startSection('title'); ?>

Manage Attached Vehicles - Go Cabs

<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>

		<!-- BEGIN RIGHTSIDE -->

        <div class="rightside bg-grey-100">

			<!-- BEGIN PAGE HEADING -->

            <div class="page-head">

				<h1 class="page-title">    <?php if(Session::get('user_role')  ==1): ?><?php echo e(trans('config.lblm_manage_attach_driver')); ?><?php else: ?><?php echo e(trans('config.lblfm_manage_attach_driver')); ?> <?php endif; ?></h1>

				<!-- BEGIN BREADCRUMB -->
					<?php if(Session::get('user_role')  ==1): ?>
				<a href="add_attached_drivers"><button class="btn btn-dark bg-red-600 color-white pull-right">
				Add Attached Vehicles</button></a>
					<?php endif; ?>
				<!-- END BREADCRUMB -->

			</div>

			<!-- END PAGE HEADING -->

<!-- START OF FILTER-->
    <!-- <div class="f_filter container-fluid">
        <div class="col-lg-4 no-padding pull-right">
            <form method="GET" action="" name="filter">
                <?php echo e(csrf_field()); ?>

                <div class="input-group">
                    <select class=" form-control margin-right-30" name="ride_category">
                        <option value="">--Vehicle Category--</option>
                        <option value="0" <?php echo e(session(
                        'cf_driver') == 0 ? "selected=selected":''); ?>>All Category</option>
                        <?php $__currentLoopData = $ride_category; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $cat): $__env->incrementLoopIndices(); $loop = $__env->getFirstLoop(); ?>
                        <option value="<?php echo e($cat->id); ?>" <?php echo e(session(
                        'cf_driver') == $cat->id ? "selected=selected":''); ?> ><?php echo e($cat->ride_category); ?></option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getFirstLoop(); ?>
                    </select>

                    <select class=" form-control margin-right-30" name="franchise">
                        <option value="">--Franchise--</option>
                        <option value="0" <?php echo e(session(
                        'cf_franchise') == 0 ? "selected=selected":''); ?>>All Franchise</option>
                        <?php $__currentLoopData = $franchise; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $cat): $__env->incrementLoopIndices(); $loop = $__env->getFirstLoop(); ?>
                        <option value="<?php echo e($cat->id); ?>" <?php echo e(session(
                        'cf_franchise') == $cat->id ? "selected=selected":''); ?> ><?php echo e($cat->company_name); ?></option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getFirstLoop(); ?>
                    </select>

                    <span class="input-group-btn">
            <input type="submit" class="btn btn-dark bg-red-600 color-white margin-right-10" value="Search"/>
           </span>
           <span class="input-group-btn">
						<button type="button" class="btn btn-dark bg-grey-400 color-black " onclick="window.parent.location='http://52.35.102.74/goapp/manage_attached_drivers'">Reset</button>
				   </span>
                </div>
            </form>
        </div>
        <div class="clearfix"></div>
    </div> -->
    <!-- END OF FILTER-->
<!-- new Table start -->


<div class="container-fluid">

          <form name="searchfare" action="" method="get">
        <div class="row">
                            
              <div class="col-lg-12 no-padding">
              
            <div class="pull-right">

                <div class="form-group col-md-3 margin-left-10">
                    <select class=" form-control" id="VehicleType" name="ctype">
                        
                        <option value="0" <?php echo e(session(
                        'cf_ctype') == 0 ? "selected=selected":''); ?>>All Vehicle Type</option>
                        <?php $__currentLoopData = $ctype; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $cat): $__env->incrementLoopIndices(); $loop = $__env->getFirstLoop(); ?>
                        <option value="<?php echo e($cat->id); ?>" <?php echo e(session(
                        'cf_ctype') == $cat->id ? "selected=selected":''); ?> ><?php echo e($cat->car_type); ?>

                        <?php if($cat->car_board == 1): ?> W <?php endif; ?>
                        <?php if($cat->car_board == 2): ?> Y <?php endif; ?>
                        </option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getFirstLoop(); ?>
                    </select>
                </div>

                <div class="form-group col-md-3">
                    <select class=" form-control" id="VehicleCategory"  name="ride_category">
                        
                        <option value="0" <?php echo e(session(
                        'cf_driver') == 0 ? "selected=selected":''); ?>>All Vehicle Category</option>
                        <?php $__currentLoopData = $ride_category; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $cat): $__env->incrementLoopIndices(); $loop = $__env->getFirstLoop(); ?>
                        <option value="<?php echo e($cat->id); ?>" <?php echo e(session(
                        'cf_driver') == $cat->id ? "selected=selected":''); ?> ><?php echo e($cat->ride_category); ?></option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getFirstLoop(); ?>
                    </select>
                </div>

                <div class="form-group  col-md-3">
                    <select class="chosen-select-deselect form-control" name="Franchise" <?php if($role == 3): ?>
                        disabled="" <?php endif; ?>>
                        <option value="">--Franchise--</option>
                        <?php $__currentLoopData = $franchise; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $cat): $__env->incrementLoopIndices(); $loop = $__env->getFirstLoop(); ?>
                        <option value="<?php echo e($cat->id); ?>"
                        <?php if($franchis_id == $cat->id & Session::get('user_role')  ==3): ?>
                        selected="selected" <?php endif; ?>
                         ><?php echo e($cat->company_name); ?></option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getFirstLoop(); ?>
                        
                    </select>
                </div>

                <div class="form-group  col-md-1">
                  <input class="btn btn-dark bg-red-600 color-white " value="Search" type="submit">

                </div>
                <div class="form-group  col-md-1 "> 
                  <button type="button" class="btn btn-dark bg-grey-400 color-black margin-left-20 " onclick="window.parent.location='http://52.35.102.74/goapp/manage_attached_drivers'">Reset</button>
                </div>

              </div>
            </div>

            
            
            </div>
        </form>

				<div class="row">

        <?php if(Session::has('attached_message')): ?>
  <div class="alert alert-danger" >
  <?php echo e(Session::get('attached_message')); ?>

  </div>
    <?php endif; ?>
    

                        <div class="col-lg-12">
							<div class="panel no-border">
                            	<div class="panel-title bg-amber-200">
								<div class="panel-head"></div>
								</div>
								<?php if(Session::has('attached_driver_added')): ?>
						           <div class="alert alert-success"><strong><?php echo e(Session::get('attached_driver_added')); ?></strong></div>
						        <?php endif; ?>
                                <div class="panel-body bg-white">
								<ul class="nav nav-tabs tab-grey bg-grey-100">
									<li class="active"><a href="#fontawesome" id="fontawesome-tab" data-toggle="tab" aria-controls="fontawesome" aria-expanded="true">Active Vehicles</a></li>
									<li><a href="#ionicons" id="ionicons-tab" data-toggle="tab" aria-controls="ionicons">Blocked Vehicles</a></li>
			 						
			 						<li><div id="status" ></div></li>
									</ul>
									
								<div class="tab-content">
                                <div id="fontawesome" aria-labelledBy="fontawesome-tab" class="panel-body padding-md table-responsive tab-pane in active">
									<p class="text-light margin-bottom-30"></p>
											<table class="table table-bordered display"	id="multicheck_inacctive1">
										<thead>
											<tr>
												

												<th class="vertical-middle">Driver Name</th>

												<th class="vertical-middle">Mobile Number</th>

												<th class="vertical-middle">Email</th>

												<th class="vertical-middle">Vehicle Number</th>

                        <th class="vertical-middle">Vehicle Category</th>
												  <?php if(Session::get('user_role')  ==1): ?>  <th class="vertical-middle">Franchise</th><?php endif; ?>

                                                <th class="vertical-middle">Action</th>
                                               
											</tr>
										</thead>
										
										
										<tbody>
											
											<?php $__currentLoopData = $active_driver; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $ad): $__env->incrementLoopIndices(); $loop = $__env->getFirstLoop(); ?>
											
											<tr>  

										
												<td class="vertical-middle"><?php echo e($ad->firstname); ?> -- <?php echo e($ad->driver_id); ?></td>
												<td class="vertical-middle"><?php echo e($ad->mobile); ?></td>
												<td class="vertical-middle"><?php echo e($ad->email); ?></td>
												<td class="vertical-middle"><?php echo e($ad->car_no); ?> -- <?php echo e($ad->car_type); ?>

												<?php if($ad->car_board == 1): ?>(W)<?php endif; ?>
												<?php if($ad->car_board == 2): ?>(Y)<?php endif; ?>
												</td>

												<td class="vertical-middle">
												<?php if($ad->ride_category == 1): ?> Go Cab <?php endif; ?>
												<?php if($ad->ride_category == 2): ?> Go Auto <?php endif; ?>
												
												</td>
                         <?php if(Session::get('user_role')  ==1): ?>   <td class="vertical-middle">
                                            <?php if($ad->isfranchise ==1): ?>
                                           <?php $__currentLoopData = $franchise; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $fra): $__env->incrementLoopIndices(); $loop = $__env->getFirstLoop(); ?>
                                           <?php if($fra->id == $ad->franchise_id): ?> <?php echo e($fra->company_name); ?> <?php endif; ?>

                                           <?php endforeach; $__env->popLoop(); $loop = $__env->getFirstLoop(); ?>
                                           <?php else: ?> 
                                           Go
                                            <?php endif; ?>
                                        </td>
                         <?php endif; ?>               
  <td class="vertical-middle">
  <a data-toggle="tooltip" title="View" href="view_attached_driver/<?php echo e($ad->id); ?>"><i class="fa fa-eye fa-2x"></i></a>&nbsp;
  <?php if(Session::get('user_role')  ==1): ?>
  <a data-toggle="tooltip" title="Edit" href="edit_attached_driver/<?php echo e($ad->id); ?>"><i class="fa fa-edit fa-2x"></i></a>&nbsp;
 <a data-toggle="tooltip" title="Block" href="#" onclick="deactivate(<?php echo e($ad->id); ?>,'BlockAttached','Attached Driver');"  ><i class="fa fa-close fa-2x"></i></a>
 <a data-toggle="tooltip" title="Review" href="<?php echo e(url('/')); ?>/review/<?php echo e($ad->id); ?>" data-toggle="tooltip" title="" data-original-title="Review Details">
                                                <i class="fa fa-star fa-2x"></i></a>
<?php endif; ?>

 </td>

											</tr>
											
											<?php endforeach; $__env->popLoop(); $loop = $__env->getFirstLoop(); ?>
											
										</tbody>
									</table>
                                    
                                    <p class="text-light margin-bottom-30"></p>
                                
                       							
                                </div><!-- /.panel-body -->
								
								<div id="ionicons" aria-labelledBy="ionicons-tab" class="panel-body padding-md table-responsive tab-pane">
								  <p class="text-light margin-bottom-30"></p>
											<table class="table table-bordered display" id="multichecgk_active1">
										<thead>
											<tr>
												

												<th class="vertical-middle">Driver Name</th>

												<th class="vertical-middle">Mobile Number</th>

												<th class="vertical-middle">Email</th>

												<th class="vertical-middle">Vehicle Number</th>

												<th class="vertical-middle">Vehicle Category</th>
                         <?php if(Session::get('user_role')  ==1): ?>  <th class="vertical-middle">Franchise</th><?php endif; ?>
                                                <th class="vertical-middle">Action</th>
                                                <!-- <th class="vertical-middle">Status</th> -->
											</tr>
										</thead>
										<tbody>
											
										<?php $__currentLoopData = $blocked_driver; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $bd): $__env->incrementLoopIndices(); $loop = $__env->getFirstLoop(); ?>	
										
											<tr>  

												
												
												<td class="vertical-middle"><?php echo e($bd->firstname); ?> -- <?php echo e($bd->driver_id); ?></td>

												<td class="vertical-middle"><?php echo e($bd->mobile); ?></td>

											

                                                <td class="vertical-middle"><?php echo e($bd->email); ?></td>
												<td class="vertical-middle"><?php echo e($bd->car_no); ?> -- <?php echo e($bd->car_type); ?>

												<?php if($bd->car_board == 1): ?>(W)<?php endif; ?>
												<?php if($bd->car_board == 2): ?>(Y)<?php endif; ?>
												</td>

												<td class="vertical-middle">
													<?php if($bd->ride_category == 1): ?> Go Cab <?php endif; ?>
												<?php if($bd->ride_category == 2): ?> Go Auto <?php endif; ?>
												</td>
                         <?php if(Session::get('user_role')  ==1): ?>  <td class="vertical-middle">
                                            <?php if($bd->isfranchise ==1): ?>
                                           <?php $__currentLoopData = $franchise; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $fra): $__env->incrementLoopIndices(); $loop = $__env->getFirstLoop(); ?>
                                           <?php if($fra->id == $bd->franchise_id): ?> <?php echo e($fra->company_name); ?> <?php endif; ?>

                                           <?php endforeach; $__env->popLoop(); $loop = $__env->getFirstLoop(); ?>
                                           <?php else: ?> 
                                           Go
                                            <?php endif; ?>
                                        </td>
                                   <?php endif; ?>
      <td class="vertical-middle">
      <a data-toggle="tooltip" title="View" href="view_attached_driver/<?php echo e($bd->id); ?>"><i class="fa fa-eye fa-2x"></i></a>
      <?php if(Session::get('user_role')  ==1): ?>
      <a data-toggle="tooltip" title="Edit" href="edit_attached_driver/<?php echo e($bd->id); ?>"><i class="fa fa-edit fa-2x"></i></a>

<a data-toggle="tooltip" title="Activate" href="#" onclick="activate(<?php echo e($bd->id); ?>,'ActivateAttached','Attached Driver');" ><i class="fa fa-check fa-2x"></i>
 &nbsp;
 <!-- <a data-toggle="tooltip" title="Delete" href="#" onclick="delete1(<?php echo e($bd->id); ?>,'DeleteAttached','Driver');" ><i class="fa fa-trash fa-2x"></i>
            </a> -->
            <a href="<?php echo e(url('/')); ?>/review/<?php echo e($ad->id); ?>" data-toggle="tooltip" title="" data-original-title="Review Details">
                                                <i class="fa fa-star fa-2x"></i></a>
<?php endif; ?>
 </td>

											</tr>

											
											<?php endforeach; $__env->popLoop(); $loop = $__env->getFirstLoop(); ?>
										</tbody>
									</table>
                                    
                                    <p class="text-light margin-bottom-30"></p>
                                    
                             
								</div>
								
								
                                </div><!-- /.tab-content -->
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


<!-- New Table Ends -->

            
        </div><!-- /.rightside -->


        <script>

 	$(document).ready(function () {

    var inactive_table1 = $('#multicheck_inactive1').DataTable({
  "iDisplayLength": 25,
    'columnDefs': [
     {
      'targets': 0,
      'checkboxes': {
         'selectRow': true
      }
     }
    ],
    'select': {
     'style': 'multi'
    },
    'order': [[1, 'asc']]
   });     
 var active_table1 = $('#multicheck_active1').DataTable({
  "iDisplayLength": 25,
    'columnDefs': [
     {
      'targets': 0,
      'checkboxes': {
         'selectRow': true
      }
     }
    ],
    'select': {
     'style': 'multi'
    },
    'order': [[1, 'desc']]
   });       
 		
        $('#ButtonActivate').click(function(){  

          bootbox.confirm({
      message: "Do you want to Activate the driver ?",
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
          // Start
           var a =0;
           console.log('this');
           $("input:checked").each(function (e) {
        var form = this;
      var curdata=[];
      var cur_status=$("#ch_status").val();
   
      var rows_selected = active_table1.column(0).checkboxes.selected();

           $.each(rows_selected, function(index, rowId){
                var n = rowId.lastIndexOf("=");
            var n=rowId.substr(n)
            var cur_val = n.replace('"', '').replace('"', '').replace('=', '').replace('>', '');
             curdata.push(cur_val);
                
            });
            console.log(curdata.length);  
            console.log(curdata[0]);  
             
            var i = 0;
            console.log(curdata[i]); 
            if(curdata.length == 0) //tell you if the array is empty  
                {  
                     bootbox.alert("Please Select atleast cx one driver");  
                }  
                else  
                {  
                    $.ajaxSetup({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                        }
                    })
                    var erd = curdata.length-1;
                    for(i=0;i<curdata.length;i++){
                     $.ajax({  
                          url:'ActivateAttachedDriver',  
                          method:'POST',  
                          data:{data_id:curdata[i]},  
                          success:function(data)  
                          {  
                              
                          }  
                     }); 

                     if(i == erd){
                              console.log('success');
                              bootbox.alert('<div class="ajax_status"><i class="fa fa-check-circle-o success" aria-hidden="true"></i>'+
               'Activated successfully' +' </div>');
                          document.cookie = "tabstatus=2";
                          setTimeout(function(){ window.location.reload(); }, 1000);
                            } 
                 }
                 
                }    
                a = 2;
    }); 
if(a == 0){
  bootbox.alert("Please Select atleast one driver");
}


// End 
}
}
});
         
      });  




           $('#ButtonBlock').click(function(){

            bootbox.confirm({
      message: "Do you want to Block the driver ?",
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
            // Start
            console.log('clicked');
            var a = 0;
           $("input:checked").each(function (e) {
        var form = this;
      var curdata=[];
      var cur_status=$("#ch_status").val();
    
      var rows_selected = inactive_table1.column(0).checkboxes.selected();
        
           $.each(rows_selected, function(index, rowId){
                var n = rowId.lastIndexOf("=");
            var n=rowId.substr(n)
            var cur_val = n.replace('"', '').replace('"', '').replace('=', '').replace('>', '');
             curdata.push(cur_val);
                
            });
              
             
            var i = 0;
           
            if(curdata.length == 0) //tell you if the array is empty  
                {  
                     bootbox.alert("Please Select atleast cx one driver");  
                     return;
                }  
                else  
                {  
                    $.ajaxSetup({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                        }
                    })
                    var erd = curdata.length-1;
                    console.log(erd);
                    for(i=0;i<curdata.length;i++){
                     $.ajax({  
                          url:'BlockDriver',  
                          method:'POST',  
                          data:{data_id:curdata[i]},  
                          success:function(data)  
                          {  

                          }  
                     });  
                     if(i == erd){
                              console.log('success');
                              bootbox.alert('<div class="ajax_status"><i class="fa fa-check-circle-o success" aria-hidden="true"></i>'+
                  'blocked successfully'+' </div>');
                             document.cookie = "tabstatus=2";
                             setTimeout(function(){ window.location.reload(); }, 1000);
                            }
                 }
                 
                }   
                a = 2;
                 
    });
if(a == 0){
  bootbox.alert("Please Select atleast one driver");
}

// End 
}
}
});
           
      });

});
function changestatus(str) {
    if (str == "") {
        document.getElementById("status").innerHTML = "";
        return;
    } else { 
        if (window.XMLHttpRequest) {
            // code for IE7+, Firefox, Chrome, Opera, Safari
            xmlhttp = new XMLHttpRequest();
        } else {
            // code for IE6, IE5
            xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
        }
        xmlhttp.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {
                document.getElementById("status").innerHTML = this.responseText;
            }
        };
        xmlhttp.open("GET","changestatus?"+str,true);
        xmlhttp.send();
    }
}
</script>

<?php $__env->stopSection(); ?>


<?php echo $__env->make('layout.master', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>