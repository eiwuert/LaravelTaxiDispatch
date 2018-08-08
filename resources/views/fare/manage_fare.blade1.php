@extends('layout.master');

@section('title')

Manage Fare - Wrydes

@endsection

@section('content')
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

	<div class="row">
        <div class="col-lg-12">
				
			<div class="form-horizontal" >
				 {{ csrf_field() }}

				<div class="form-group margin-bottom-20 col-md-4 margin-right-10">
				   	<select class=" form-control" name="VehicleCategory" id="VehicleCategory" >
							<option value="">--Select Vehicle Category--</option>
						 @foreach ($ride_category as $cat)
					  <option    value="{{$cat->id}}" {{ session('cf_model') == $cat->id ? "selected=selected":''}} >{{$cat->ride_category}}</option>
					   @endforeach
					</select>
				</div>
                                    
                <div class="form-group margin-bottom-20 col-md-4 margin-right-10">
					<select class=" form-control" name="VehicleType" id="VehicleType" >
							<option value="">--Select Vehicle Type--</option>
						 @foreach ($cartype as $cat)
					  <option    value="{{$cat->id}}" {{ session('cf_model') == $cat->id ? "selected=selected":''}} >{{$cat->car_type}} 
					  @if($cat->car_board ==1)
					  (W)
					  @endif
					  @if($cat->car_board ==2)
					  (Y)
					  @endif
					  </option>
					   @endforeach
					</select>
				</div>
                                   
                <div class="form-group margin-bottom-20 col-md-4 margin-right-10">
					<select class=" form-control" name="FareType" id="FareType" >
							<option value="">--Select Fare Type--</option>
						<option value="1">Base fare</option>
						<option value="2">Morning fare</option>
						<option value="3">Night fare</option>
						<option value="4">Peak fare</option>
						<option value="5">Special fare</option>
					</select>
				</div>
                                    
                <div class="form-group margin-bottom-20 col-md-4 margin-right-10">
					<input  type="text" class="form-control timepicker " id="start_time_fare" name="FromTime" placeholder="From Time">
				</div>
                                   
                <div class="form-group margin-bottom-20 col-md-4 margin-right-10">
					<input  type="text" class="form-control timepicker " id="end_time_fare" name="ToTime" placeholder="To Time">
				</div>
                       
                <div class="form-group margin-bottom-20 col-md-4 margin-right-10">
          			<input type="submit" class="btn btn-dark bg-red-600 color-white pull-right" id="button_submit" value="Search" />
          		</div> 

				</div>
			</div>
			</div>

	<!-- END OF FILTER-->
	<div class="row">
                        <div class="col-lg-12">
							<div class="panel no-border">
                            	<div class="panel-title bg-amber-200">
								<div class="panel-head">Fare List</div>
								</div>
                                <div class="panel-body no-padding-top bg-white">
									<p class="text-light margin-bottom-30"></p>
									 <form id="change-status" action="#" method="POST">
									 	<table class="table table-bordered display" id="multicheck_active">
										<thead>
											<tr>
												<th class="vertical-middle">Select</th>
												<th class="vertical-middle">{{trans("config.lbl_vehicle_category") }}</th>
												<th class="vertical-middle">Vehicle Type</th>
												<th class="vertical-middle">Fare Type</th>
											
                                                <th class="vertical-middle">Minimum Kilometre Fare</th>
													<th class="vertical-middle">Ride Fare</th>
                                                <!--<th class="vertical-middle">Kilometre Fare</th>-->
                                                <th class="vertical-middle">Fare/Min of ride</th>
                                                <th class="vertical-middle">Vehicle Waiting Charge/Min</th>
												
                                                <th class="vertical-middle">Time slot </th>
                                                <th class="vertical-middle">Action</th>
											</tr>
										</thead>
										<tbody>

   					<div id="MainContent">
   						
   					</div>
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
                                    
                                    
                                    
                                    <div class="form-group ">
									<label for="" class="col-sm-2 control-label no-padding">Change Status</label>
									<div class="col-sm-2 no-padding">
									  <select class="form-control" name="status" id="ch_status">
                                        <option value="1">Activate</option>
										<option value="0">In-Active</option>
                                      </select>
									</div>
									<div class="col-sm-2 ">
									<button class="btn btn-danger" type="submit">Change</button>
								  </div>
								  </div>
						</form>
                            </div>
                        </div><!-- /.col -->
						
						
                    </div><!-- /. row -->
		    </div><!-- /.row -->
				
				</div>
				<!-- /.row -->
				
				<!-- /.row -->
<!--Remove The base base editing -->
		<script>
		
		</script>
		
			<!-- BEGIN FOOTER -->
				
				<!-- END FOOTER -->
            </div><!-- /.container-fluid -->
        </div>
		 <script src="http://52.35.102.74/goapp/public/js/farejs.js" type="text/javascript"></script>


@endsection