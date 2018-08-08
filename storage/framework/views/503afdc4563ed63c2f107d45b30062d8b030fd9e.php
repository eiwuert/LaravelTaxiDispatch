;

<?php $__env->startSection('title'); ?>

Total Rides Report - Go Cabs

<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<style>
.panel > .panel-body {
     /*overflow: scroll !important;*/
}
.dialogBorder{
    left: 100px !important;
}
.report_filter .chosen-container-single .chosen-single span{
    text-align: center !important;
}

table.filter_report th {
    background-color: #293c4e;
    color: #fff;
}

.no-record{
	    border: 2px solid #000;
    padding: 30px;
    position: relative;
    top: 39px;
    z-index: 9999;
    text-transform: uppercase;
    margin-bottom: 59px;
    font-size: 30px;
    text-align: center
}
</style>

<?php 
	$estatus='&export=excel';
	$pestatus='&export=pdf';
 ?>
<?php if(count(app('request')->input()) == 0): ?>
	<?php 	
		$estatus='?export=excel';
		$pestatus='?export=pdf';
	 ?>
<?php endif; ?>
 <div class="rightside bg-grey-100">
			<!-- BEGIN PAGE HEADING -->
            <!-- <div class="page-head">
				<h1 class="page-title">Total Rides Details</h1>
			</div> -->
			<!-- END PAGE HEADING -->

            <div class="container-fluid">
				<div class="row">
					<div class="col-lg-12">
             <div class="panel">
              <div class="panel-title bg-amber-200">
								<div class="panel-head">Total Rides Report</div>
							</div>
            <div class="panel-body">
    
	      <!-- START OF FILTER-->
	<form name="searchfare" action="" class="report_filter" method="get">
	<div class="row" style="margin:auto">
        <div class="col-lg-12">
				
			<div class="form-horizontal" >
				 <?php echo e(csrf_field()); ?>


				 			
				 			<div class="form-group margin-bottom-20 col-md-3 margin-right-10">
									 	<select class="chosen-select-deselect form-control" name="franchise" id="franchise" <?php if($role != 1): ?> disabled <?php endif; ?> >
											<option value="">--Select Your Franchise--</option>
                                            
                                            <?php $__currentLoopData = $franchise; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $r): $__env->incrementLoopIndices(); $loop = $__env->getFirstLoop(); ?>
                                            <option value="<?php echo e($r->id); ?>"
                                        <?php if($r->id == $franchise_id): ?> selected="selected" <?php endif; ?>
                                            ><?php echo e($r->company_name); ?></option>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getFirstLoop(); ?>

									</select>
								</div>

						
								
								<div class="form-group margin-bottom-20 col-md-3 margin-right-10">
									 	<select class="chosen-select-deselect form-control" name="vehicle" id="vehicle" >
											<option value="">--Vehicle Number--</option>
										 <?php $__currentLoopData = $vehicle_list; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $vehicle): $__env->incrementLoopIndices(); $loop = $__env->getFirstLoop(); ?>
										<option    value="<?php echo e($vehicle->id); ?>"  <?php echo e(app('request')->input('vehicle') == $vehicle->id ? "selected=selected":''); ?> ><?php echo e($vehicle->car_no); ?></option>
										 <?php endforeach; $__env->popLoop(); $loop = $__env->getFirstLoop(); ?>
									</select>
								</div>
								
								<div class="form-group margin-bottom-20 col-md-3 margin-right-10">
									 	<select class="chosen-select-deselect form-control" name="driver" id="driver" >
											<option value="">--Driver Name--</option>
										 <?php $__currentLoopData = $driver_list; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $driver): $__env->incrementLoopIndices(); $loop = $__env->getFirstLoop(); ?>
										<option    value="<?php echo e($driver->id); ?>"  <?php echo e(app('request')->input('driver') == $driver->id ? "selected=selected":''); ?> ><?php echo e($driver->driver_id); ?></option>
										 <?php endforeach; $__env->popLoop(); $loop = $__env->getFirstLoop(); ?>
									</select>
								</div>
								
								<div class="form-group margin-bottom-20 col-md-3 margin-right-10">
									 	<select class="chosen-select-deselect form-control" name="passenger" id="passenger" >
											<option value="">--Passenger Name--</option>
										 <?php $__currentLoopData = $passenger_list; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $passenger): $__env->incrementLoopIndices(); $loop = $__env->getFirstLoop(); ?>
										<option    value="<?php echo e($passenger->id); ?>"  <?php echo e(app('request')->input('passenger') == $passenger->id ? "selected=selected":''); ?> ><?php echo e($passenger->name); ?></option>
										 <?php endforeach; $__env->popLoop(); $loop = $__env->getFirstLoop(); ?>
									</select>
								</div>
								
									<div class="form-group margin-bottom-20 col-md-3 margin-right-10">
									 	<input type="text" name="trip_id" class="form-control" placeholder="Trip ID"  value="<?php echo e(app('request')->input('trip_id')); ?>">
								</div>
									<div class="form-group margin-bottom-20 col-md-3 margin-right-10">
									 	<input type="text" value="<?php echo e(app('request')->input('from_date')); ?>" readonly="" name="from_date" class="form-control from_date symval"  placeholder="Start From Date" >
								</div>
								
									<div class="form-group margin-bottom-20 col-md-3 margin-right-10">
									 	 <input type="text" value="<?php echo e(app('request')->input('to_date')); ?>" readonly="" name="to_date" class="form-control to_date symval" placeholder="Start To Date" >
								</div>
								
 							<div class="form-group margin-bottom-20 col-md-3 margin-right-10 " ></div>
                <div class="form-group margin-bottom-20 col-md-3 pull-right" style=" right: -17px; position: relative;">
          				<input type="submit" class="btn btn-dark bg-red-600 color-white" id="button_submit" value="Search" />
          				<button type="button" class="btn btn-dark bg-grey-400 color-black" onclick="window.parent.location='<?php echo e(URL::to('/total_rides')); ?>'" >Reset</button>
          		</div> 

				</div>
			</div>
			</div>
	</form>
	<!-- END OF FILTER-->
	
<div id="vehiecle_ride_details" style="height:500px;"></div>
<div class="row">
<div class="expt_btn pull-right">
 <a href="<?php echo e(Request::fullUrl()); ?><?php echo e($estatus); ?>" <?php if(count($total_list)== 0): ?> <?php echo e("disabled=disabled"); ?><?php endif; ?> class="margin-bottom-20 btn btn-dark bg-red-600 color-white">Download EXCEL</a> <a href="<?php echo e(Request::fullUrl()); ?><?php echo e($pestatus); ?>" <?php if(count($total_list)== 0): ?> <?php echo e("disabled=disabled"); ?><?php endif; ?>  class="margin-bottom-20 btn btn-dark bg-red-600 color-white">Download PDF</a>
</div>
</div>
<div class="row  tbl_grid_report"  >
<?php if(count($total_list)>0): ?>

<!--show the data report-->
<table class="filter_report table" cellspacing="0" cellpadding="10" widthn="100%" style="font-size:11px;" >
<tr valign="top" align="center">
	<th align="left">Trip ID</th>
	<th align="left">Driver Name &amp; ID	</th>
	<th align="left">Passenger Name &amp; ID</th>
	<th align="left">Vehicle Number &amp; Type</th>
	<th align="left">Ride date</th>
	<th align="left">Booking Type</th>
	<th align="left">Ride Status</th>
	<th align="left">Pickup Location</th>
	<th align="left">Drop Location</th>
	<th align="left">Payment Type</th>
	<th align="left">Total Fare</th>
	<th align="left">Paid By Cash</th>
	<th align="left">Paid By e-Wallet</th>
	<th align="left">Paid By POS</th>
</tr>
<tr>
	<?php $__currentLoopData = $total_list; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $list): $__env->incrementLoopIndices(); $loop = $__env->getFirstLoop(); ?>
		<td><?php echo e($list->reference_id); ?></td>
		<td><?php echo e($list->driver_name); ?></td>
		<td><?php echo e($list->passanger_name); ?></td>
		<td><?php echo e($list->car_no); ?></td>
		<td><?php echo e($list->date_of_ride); ?></td>
		<td><?php echo e($list->ride_type); ?></td>
		<td><?php echo e($list->ride_status); ?></td>
		<td><?php echo e($list->source_location); ?></td>
		<td><?php echo e($list->destination_location); ?></td>
		<td><?php echo e($list->payment_type); ?></td>
		<td><?php echo e($list->total_amount); ?></td>
		<td><?php echo e($list->paid_cash); ?></td>
		<td><?php echo e($list->paid_taximoney); ?></td>
		<td><?php echo e($list->paid_pos); ?></td>
	
		
</tr>
	<?php endforeach; $__env->popLoop(); $loop = $__env->getFirstLoop(); ?>

</table>

<?php else: ?>
	<div class="no-record">No Ride are available</div>
	
<?php endif; ?>
</div>
		<script src="<?php echo e(URL::asset("public/js/amcharts.js")); ?>" type="text/javascript"></script>
    <script src="<?php echo e(URL::asset("public/js/serial.js")); ?>" type="text/javascript"></script>
    <script src="<?php echo e(URL::asset("public/js/light.js")); ?>"></script>

<script>
		/*********** LAST THREE MONTH RIDE DETAILS ******/

 AmCharts.makeChart( "vehiecle_ride_details", {
  "type": "serial",
  "theme": "none",
  "autoMarginOffset": 40,
	"marginRight": 70,
	"marginTop": 70,
  "dataDateFormat": "YYYY-MM-DD",
  "valueAxes": [ {
    "id": "v1",
    "axisAlpha": 0,
    "position": "left",
    "ignoreAxisWidth": false,
    "title": "Total Number of Rides"
  } ],
  "balloon": {
    "borderThickness": 1,
    "shadowAlpha": 0
  },
  "graphs": [ {
    "id": "g1",
    "balloon": {
      "drop": true,
      "adjustBorderColor": false,
      "color": "#ffffff",
      "type": "smoothedLine"
    },
    "fillAlphas": 0.2,
    "bullet": "round",
    "bulletBorderAlpha": 1,
    "bulletColor": "#FFFFFF",
    "bulletSize": 5,
    "hideBulletsCount": 50,
    "lineThickness": 2,
    "title": "red line",
    "useLineColorForBulletBorder": true,
    "valueField": "value",
    "balloonText": "<span style='font-size:18px;'>[[value]]</span>"
  } ],
  "chartCursor": {
    "valueLineEnabled": true,
    "valueLineBalloonEnabled": true,
    "cursorAlpha": 0,
    "zoomable": false,
    "valueZoomable": true,
    "valueLineAlpha": 0.5
  },
  "chartScrollbar": {
						"enabled": true
					},
  "valueScrollbar": {
    "autoGridCount": true,
    "color": "#000000",
    "scrollbarHeight": 50
  },
  "categoryField": "date",
  "categoryAxis": {
    "parseDates": true,
    "dashLength": 1,
    "minorGridEnabled": true
  },
  "export": {
    "enabled": true
  },
  "titles": [
		{
			"id": "successful_rides_chart",
			"size": 18,
			"text": ""
		}
	],
	"dataProvider": [ 
						<?php if(count($total_ride_graph) > 0): ?>
					  <?php $__currentLoopData = $total_ride_graph; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $ride_details): $__env->incrementLoopIndices(); $loop = $__env->getFirstLoop(); ?>
			  			{
						"date": "<?php echo e($ride_details->ride_date); ?>",
   					 "value": <?php echo e($ride_details->ride_count); ?>

						},
					<?php endforeach; $__env->popLoop(); $loop = $__env->getFirstLoop(); ?>	
					<?php else: ?>

					
					{
						"date": "<?php echo $datet; ?>",
   					 "value": 0
						},

					
							
						<?php endif; ?>
	
		]
 
} );

</script>
				<!-- BEGIN FOOTER -->
				<?php echo $__env->make('includes.footer', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
				<!-- END FOOTER -->
            </div><!-- /.container-fluid -->
        </div>
  
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layout.master', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>