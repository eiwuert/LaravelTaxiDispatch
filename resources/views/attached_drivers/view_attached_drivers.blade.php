
@extends('layout.master');

@section('title')

View Attached Vehicles - Go Cabs

@endsection

@section('content')

<div class="rightside bg-grey-100">
			<!-- BEGIN PAGE HEADING -->
            <div class="page-head">
				<h1 class="page-title">View Attached Vehicles</h1>
				<!-- BEGIN BREADCRUMB -->
				<a href="{{url("/")}}/manage_attached_drivers" class="btn btn-dark bg-black color-white pull-right">Back</a>
				<!-- END BREADCRUMB -->
			</div>
			<!-- END PAGE HEADING -->
		@php 
      $dob = date('m-d-Y',strtotime($data->dob)); 
      $insurance_expiration_date = date('m-d-Y',strtotime($data->insurance_expiration_date));
      @endphp
            <div class="container-fluid">
				<div class="row">
                        <div class="col-lg-12">
							<div class="panel no-border">
                            	<div class="panel-title bg-amber-200">
								<div class="panel-head">Driver Details</div>
								</div>
                                <div class="panel-body no-padding-top bg-white">
									<p class="text-light margin-bottom-30"></p>
											<table class="table table-bordered ">
										<thead>
											<tr>
												<th class="vertical-middle">Name</th>
												<th class="vertical-middle">Value </th>
												
											</tr>
										</thead>
										<tbody>

										<tr> 
												
												<td class="vertical-middle">Ride Category</td>
								<td class="vertical-middle">
									@if($data->ride_category == 1) Go Cab @endif
									@if($data->ride_category == 2) Auto @endif
								</td>
												
											</tr>

											<tr> 
												
												<td class="vertical-middle">Franchise Name</td>
								<td class="vertical-middle">
									@if($data->isfranchise == 0)
										Go App 
									@endif

									@if($data->isfranchise == 1)
										{{$data->getfranchise->company_name}} 
									@endif
								</td>
												
											</tr>

											<tr> 
												
												<td class="vertical-middle">Driver ID</td>
								<td class="vertical-middle">{{$driver[0]->driver_id}}</td>
												
											</tr>

											<tr> 
											<?php $key = hash('sha256', 'wrydes');
							$iv = substr(hash('sha256', 'dispatch'), 0, 16);
							$output = openssl_decrypt(base64_decode($data->password), "AES-256-CBC", $key, 0, $iv);
							?>

												
												<td class="vertical-middle">Password</td>
												<td class="vertical-middle"><?php echo $output; ?></td>
												
											</tr> 

											<tr> 
												
												<td class="vertical-middle">First Name</td>
												<td class="vertical-middle">{{$data->firstname}}</td>
												
											</tr>

											
											
											<tr> 

											<tr> 
												
												<td class="vertical-middle">Last Name</td>
									<td class="vertical-middle">{{$data->lastname}}</td>
												
											</tr>

											<tr> 
												
												<td class="vertical-middle">Email</td>
								<td class="vertical-middle">{{$data->email}}</td>
												
											</tr>
											
											<tr> 
												
												<td class="vertical-middle">Mobile Number</td>
												<td class="vertical-middle">{{$data->mobile}}</td>
												
											</tr>
											
											
											 
											 <tr> 
												
												<td class="vertical-middle">License ID</td>
												<td class="vertical-middle">{{$data->licenseid}}</td>
												
											</tr>


											<tr> 
												
												<td class="vertical-middle">Gender</td>
												<td class="vertical-middle">{{$data->gender}}</td>
												
											</tr>

											<tr> 
												
												<td class="vertical-middle">Date of Birth</td>
												<td class="vertical-middle">{{$dob}}</td>
												
											</tr>

											

											

											<tr> 
												
												<td class="vertical-middle">Address</td>
												<td class="vertical-middle">{{$data->address}}</td>
												
											</tr>
											<tr> 
												
												<td class="vertical-middle">Country</td>
												<td class="vertical-middle">{{$data->country_name->name}}</td>
												
											</tr>

											<tr> 
												
												<td class="vertical-middle">State</td>
												<td class="vertical-middle">{{$data->state_name->name}}</td>
												
											</tr>

											

											

											<tr> 
												
												<td class="vertical-middle">Driver Profile</td>
												<td class="vertical-middle">
												<img height="350" width="450" src="{{url("/")}}/public{{$data->profile_photo}}"></td>
												
											</tr>

											<tr> 
												
												<td class="vertical-middle">License Image</td>
												<td class="vertical-middle">
												<img height="350" width="450" src="{{url("/")}}/public{{$data->license}}"></td>
												
											</tr>

										</tbody>
									</table>
                            </div>
                        </div><!-- /.col -->
                    </div><!-- /. row -->
		    </div><!-- /.row -->
				
				<!-- /.row -->
				


				<div class="row">
                        <div class="col-lg-12">
							<div class="panel no-border">
                            	<div class="panel-title bg-amber-200">
								<div class="panel-head">Vehicle Details</div>
								</div>
                                <div class="panel-body no-padding-top bg-white">
									<p class="text-light margin-bottom-30"></p>
											<table class="table table-bordered ">
										<thead>
											<tr>
												<th class="vertical-middle">Name</th>
												<th class="vertical-middle">Value </th>
												
											</tr>
										</thead>
										<tbody>
											<tr> 
												
												<td class="vertical-middle">Vehicle Number</td>
												<td class="vertical-middle">{{$data->car_no}}</td>
												
											</tr>

											<tr> 
												
												<td class="vertical-middle">Vehicle Photo</td>
												<td class="vertical-middle">
												<img height="350" width="450" src="{{url("/")}}/public{{$data->vehical_image}}"></td>
												
											</tr>

											<tr> 
												
												<td class="vertical-middle">Brand</td>
												<td class="vertical-middle">{{$data->brand_name->brand}}</td>
												
											</tr><tr> 
												
												<td class="vertical-middle">Model</td>
												<td class="vertical-middle">{{$data->model_name->model}}</td>
												
											</tr><tr> 
												
												<td class="vertical-middle">Type</td>
												<td class="vertical-middle">
												@foreach($cartype as $ct)
													@if($ct->id == $data->car_type)
														{{$ct->car_type}}
														@if($ct->car_board == 1) W @endif
														@if($ct->car_board == 2) Y @endif
													@endif
												@endforeach
												</td>
												
											</tr><tr> 
												
												<td class="vertical-middle">Capacity</td>
												<td class="vertical-middle">
													@foreach($cartype as $ct)
													@if($ct->id == $data->car_type)
														{{$ct->capacity}}
													@endif
												@endforeach
												</td>
												
											</tr><tr> 
												
												<td class="vertical-middle">RC Number</td>
												<td class="vertical-middle">{{$data->rc_no}}</td>
												
											</tr>
											<tr> 
												
												<td class="vertical-middle">RC Image</td>
												<td class="vertical-middle">
												<img height="350" width="450" src="{{url("/")}}/public{{$data->rc_image}}"</td>
												
											</tr>

											<tr> 
												
												<td class="vertical-middle">Insurance Image</td>
												<td class="vertical-middle">
												<img height="350" width="450" src="{{url("/")}}/public{{$data->insurance_image}}"</td>
												
											</tr>

											<tr> 
												
												<td class="vertical-middle">Insurance Expiry Date</td>
												<td class="vertical-middle">{{$insurance_expiration_date}}</td>
												
											</tr>
											
										</tbody>
									</table>
                            </div>
                        </div><!-- /.col -->
                    </div><!-- /. row -->
		    </div><!-- /.row -->







				<!-- /.row -->
				
				<!-- BEGIN FOOTER -->
				@include('includes.footer')
				<!-- END FOOTER -->
            </div><!-- /.container-fluid -->
        </div>
 @endsection