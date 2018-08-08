@extends('layout.master');

@section('title')

Manage Assign Vehicle - Wrydes

@endsection

@section('content')
<div class="rightside bg-grey-100">
			<!-- BEGIN PAGE HEADING -->
            <div class="page-head">
				<h1 class="page-title">{{ trans('config.lbla_managervehicletitle') }}</h1>
				<!-- BEGIN BREADCRUMB -->
				<a href="assign_taxi"><button class="btn btn-dark bg-red-600 color-white pull-right">Assign Vehicle</button></a>
				<!-- END BREADCRUMB -->
			</div>
			<!-- END PAGE HEADING -->
<!-- START OF FILTER-->
		<div class="f_filter container-fluid">
			<div class="pull-right col-lg-3 no-padding">
				<form method="GET" action="" name="filter">
				 {{ csrf_field() }}
				 <div class="input-group">
				   	<select class=" form-control" name="ride_category" >
							<option value="">--Select Vehicle Type--</option>
							<option value="">All</option>
						 @foreach ($ride_category as $cat)
					  <option    value="{{$cat->id}}" {{ session('cf_fare') == $cat->id ? "selected=selected":''}} >{{$cat->ride_category}}</option>
					   @endforeach
					</select>
				   <span class="input-group-btn">
						<input type="submit" class="btn btn-dark bg-red-600 color-white pull-right" value="Search" />
				   </span>
				</div>
				</form>
			</div>
			<div class="clearfix"></div>
		</div>
	<!-- END OF FILTER-->
            <div class="container-fluid">
				<div class="row">
                        <div class="col-lg-12">
							<div class="panel no-border">
                            	<div class="panel-title bg-amber-200">
								<div class="panel-head">Assigned Vehicle Information</div>
								</div>
                                <div class="panel-body no-padding-top bg-white">
									<p class="text-light margin-bottom-30"></p>
									 <form id="active-taxi" action="#" method="POST">
											<table class="table table-bordered display">
										<thead>
											<tr>
												
												<th class="vertical-middle">{{ trans('config.lbl_vehicle_category') }}</th>
												<th class="vertical-middle">Driver Name</th>
												<th class="vertical-middle">Vehicle Type</th>
												<th class="vertical-middle">Vehicle Number</th>
                                                <th class="vertical-middle">Country</th>
                                                <th class="vertical-middle">State</th>
                                                <!-- <th class="vertical-middle">City</th> -->
                                                <th class="vertical-middle">Franchise</th>
                                                <th class="vertical-middle">Action</th>
											</tr>
										</thead>
										<tbody>
								<?php //print_r($taxi_assigned_list); exit; ?>
										@foreach($taxi_assigned_list as $list)

											<tr> 
												
													<th class="vertical-middle">{{$list->getvehicle_name->ride_category}}</th>
												<td class="vertical-middle">{{$list->driver_details->firstname}}-{{$list->driver_details->driver_id}}
													</td>

												<td class="vertical-middle">
												@foreach($CarType as $ct)												
												@if($ct->id == $list->taxi_num->car_type)
												{{$ct->car_type}}
												@if($ct->car_board == 1) (W) @endif
										  	@if($ct->car_board == 2) (Y) @endif
												@endif
												@endforeach
												</td>
												<td class="vertical-middle">{{$list->taxi_num->car_no}}</td>
                                                <td class="vertical-middle">{{$list->country_name->name}}</td>
												<td class="vertical-middle">{{$list->state_name->name}}</td>
												
												<td class="vertical-middle">
                                            @if($list->driver_details->isfranchise ==1)
                                           @foreach($franchise as $fra)
                                           @if($fra->id == $list->driver_details->franchise_id) {{$fra->company_name}} @endif

                                           @endforeach
                                           @else 
                                           Go
                                            @endif
                                        </td>
                                                <td class="vertical-middle">
                                                @foreach($assign as $asi)
                                                @if($list->taxi_num->id == $asi->car_num)
												<a href="{{ url('/assign_taxi/') }}/{{$asi->id}}/edit"><i class="fa fa-edit"></i></a>
												@endif
												@endforeach
											</tr>
											@endforeach	
										</tbody>
									</table>
									</form>
                            </div>
                        </div>
                    </div>
		    </div>

				<!-- BEGIN FOOTER -->
				@include('includes.footer')
				<!-- END FOOTER -->
            </div><!-- /.container-fluid -->
        </div>
@endsection