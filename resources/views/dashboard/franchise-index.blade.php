<div class="rightside bg-grey-100">
			<!-- BEGIN PAGE HEADING -->
            @php

        $franchise_share = number_format($franchise_share, 2, '.', ',');  
        $total_driver_share = number_format($total_driver_share, 2, '.', ',');   

        @endphp 
<div class="page-head bg-grey-100">
				<!-- <h1 class="page-title">{{ Config::get('common.dashboard') }}<small>Welcome to WrydesDispatch administration</small></h1> -->
				
                <div class="btn bg-grey-600 padding-10-20 no-border color-white pull-right border-radius-5 hidden-xs no-shadow margin-left-10">Total Franchise balance<i class="fa fa-dollar margin-left-10 "></i> <span><strong>{{$franchise_share}}</strong></span></div>
                <div class="btn bg-yellow-600 padding-10-20 no-border pull-right border-radius-5 hidden-xs no-shadow margin-left-10">Total Drivers Share Amount<i class="fa fa-dollar margin-left-10 "></i> <span><strong>{{$total_driver_share}}</strong></span></div>
			</div>
            
            
			<!-- END PAGE HEADING -->

            <div class="container-fluid">
				<div class="row">
					<div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
						<div class="panel bg-grey-500">
							<div class="panel-body padding-15-20">
								<div class="clearfix">
									<div class="pull-left">
										<div class="color-white font-size-26 font-roboto font-weight-600" data-toggle="counter" data-start="0" data-from="0" data-to="143" data-speed="500" data-refresh-interval="10">{{count($total_drivers)}}</div>
										<div class="display-block color-white font-weight-600"><i class="ion-person-add"></i> TOTAL DRIVERS</div>
									</div>
									<div class="pull-right">
										<i class="font-size-36 color-white ion-person-add"></i>
									</div>
								</div>
								<!-- <div class="progress progress-animation progress-xs margin-top-25 margin-bottom-5">
									<div class="progress-bar bg-grey-600" role="progressbar" aria-valuenow="72" aria-valuemin="0" aria-valuemax="100" style="width: 0%;">
									</div>
								</div>
								<div class="font-size-11 clearfix color-white font-weight-600">
									<div class="pull-left">PROGRESS</div>
									<div class="pull-right">72%</div>
								</div> -->
							</div>
						</div><!-- /.panel -->
					</div><!-- /.col -->
								
					<div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
						<div class="panel bg-deep-orange-400">
							<div class="panel-body padding-15-20">
								<div class="clearfix">
									<div class="pull-left">
										<div class="color-white font-size-26 font-roboto font-weight-600" data-toggle="counter" data-start="0" data-from="0" data-to="10" data-speed="500" data-refresh-interval="10">{{count($total_cars)}} </div>
										<div class="display-block color-red-50 font-weight-600"><i class="fa fa-cab"></i> TOTAL CARS</div>
									</div>
									<div class="pull-right">
										<i class="font-size-36 color-red-100 fa fa-cab"></i>
									</div>
								</div>
								<!-- <div class="progress progress-animation progress-xs margin-top-25 margin-bottom-5">
									<div class="progress-bar bg-red-100" role="progressbar" aria-valuenow="80" aria-valuemin="0" aria-valuemax="100" style="width: 0%;">
									</div>
								</div>
								<div class="font-size-11 clearfix color-red-50 font-weight-600">
									<div class="pull-left">PROGRESS</div>
									<div class="pull-right">80%</div>
								</div> -->
							</div>
						</div><!-- /.panel -->
					</div><!-- /.col -->
								
					<div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
						<div class="panel bg-blue-400">
							<div class="panel-body padding-15-20">
								<div class="clearfix">
									<div class="pull-left">
										<div class="color-white font-size-26 font-roboto font-weight-600" data-toggle="counter" data-start="0" data-from="0" data-to="43" data-speed="500" data-refresh-interval="10">{{$today_wryde[0]->ride_count}}</div>
										<div class="display-block color-blue-50 font-weight-600"><i class="fa fa-users"></i> TODAY'S TOTAL RIDE</div>
									</div>
									<div class="pull-right">
										<i class="font-size-36 color-blue-100 fa fa-users"></i>
									</div>
								</div>
								<!-- <div class="progress progress-animation progress-xs margin-top-25 margin-bottom-5">
									<div class="progress-bar bg-blue-100" role="progressbar" aria-valuenow="45" aria-valuemin="0" aria-valuemax="100" style="width: 0%;">
									</div>
								</div>
								<div class="font-size-11 clearfix color-blue-50 font-weight-600">
									<div class="pull-left">PROGRESS</div>
									<div class="pull-right">45%</div>
								</div> -->
							</div>
						</div><!-- /.panel -->
					</div><!-- /.col -->
								
					
				</div><!-- /.row -->

                <!-- /.row -->
					
				<div class="row">
                        <div class="col-lg-12">
							<div class="panel no-border ">
                                <div class="panel-body no-padding-top bg-white">
									<h3 class="color-grey-700">Rides on track</h3>
										<!-- START OF FILTER-->
									<div class="dashboard-f-filter">
										<div class="pull-right col-lg-8 no-padding">
											<form method="get" action="" name="filter">
											 {{ csrf_field() }}
											 <table class="table-responsive dashboard-table-filter" style="padding: 4px;">
												 <tr>
													<td width='33%' style="position: relative; top: 5px;"> <label>Search Vehicle & Driver ID</label></td>
													<td  width='50%'> <select class="chosen-select-deselect form-control" name="vehicle-number" >
															<option value="">ALL</option>
															 @foreach ($active_vehicles as $vehicle)
														<option value="{{$vehicle->vehicle_id}}" {{ session('d_vehiclenum') == $vehicle->vehicle_id ? "selected=selected":''}} >{{$vehicle->car_no}} - {{$vehicle->car_type}} ({{$vehicle->car_board}}) </option>
															   @endforeach
															
														</select>
												</td>
													<td  width='17%'><input type="submit" class="btn btn-dark bg-red-600 color-white" value="Search" /></td>
												 </tr>
											 </table>
											</form>
										</div>
										<div class="clearfix"></div>
									</div>
								<!-- END OF FILTER-->
									<div id="map" class="height-460 margin-bottom-30"></div>
									
									
										
                            </div>
                        </div><!-- /.col -->
                    </div><!-- /. row -->
		    </div>
            
        
            <div class="row">
                <div class="col-lg-12">
									<div class="panel no-border ">
						            <div class="panel-body no-padding-top bg-white">
					<!--<h3 class="color-grey-700">Individual Drivers Total Share and Company Profit</h3>-->
													<div class="col-lg-12 ">	
														<!--<h4 class="color-grey-700">Individual Drivers Total Share Amount</h4>	-->
														<div id="vehiecle_ride_details" style="height:400px;"></div>
														
													</div>
												</div>
						    </div><!-- /.col -->
         		 	</div>
                    <!-- /. row -->
            </div>
            
            
            <div class="row">
                <div class="col-lg-12">
									<div class="panel no-border ">
						            <div class="panel-body no-padding-top bg-white">
					<!--<h3 class="color-grey-700">Individual Drivers Total Share and Company Profit</h3>-->
													<div class="col-lg-12 ">	
														<h4 class="color-grey-700">Individual Drivers Total Share Amount</h4>		
														<div id="driver_profit" style="height:400px;"></div>
													
													</div>
												</div>
						    </div><!-- /.col -->
         		 	</div>
                    <!-- /. row -->
            </div>
            
              <div class="row">
                <div class="col-lg-6">
									<div class="panel no-border ">
						            <div class="panel-body no-padding-top bg-white">
				
													<div class="col-lg-12 ">	
														<h4 class="color-grey-700">Vehicle Count based on Vehicle type</h4>		
														<div id="vehicle_type_list" style="height:400px;width:100%"></div>
													</div>
												</div>
						    	</div><!-- /.col -->
         		 		</div>
                    <!-- /. row -->
               <div class="col-lg-6">
									<div class="panel no-border ">
						            <div class="panel-body no-padding-top bg-white">
			
													<div class="col-lg-12 ">	
														<h4 class="color-grey-700">Count Based on Ride Status</h4>		
														<div id="vehicle_type_share" style="height:400px;width:100%"></div>
													</div>
												</div>
						    	</div><!-- /.col -->
         		 		</div>
                    <!-- /. row -->
            	</div>
            
            
				<!-- BEGIN FOOTER -->
				@include('includes.footer')
				<!-- END FOOTER -->
            </div>
                <!-- /.row -->
<!-- --------DASHBOARD MAP CONFIGURATION ------------->	
<!-- dashboard -->

		@include('dashboard.report')
<!-----------END OF MAP CONFIGURATION------------------>			
				<!-- END FOOTER -->
            </div><!-- /.container-fluid -->
        </div><!-- /.rightside -->
    </div><!-- /.wrapper -->
