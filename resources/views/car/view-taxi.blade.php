@extends('layout.master');

@section('title')

ViewVehicle - Wrydes

@endsection

@section('content')
 <div class="rightside bg-grey-100">
			<!-- BEGIN PAGE HEADING -->
            <div class="page-head">
				<h1 class="page-title">{{ trans('config.lblt_viewheading') }}
			</h1>
			<button type="button" class="btn btn-dark bg-black color-white pull-right" onclick="window.location.href='{{ url('/taxi') }}';">{{ trans('config.lbl_back') }}</button></h1>
				<!-- BEGIN BREADCRUMB -->
				
				<!-- END BREADCRUMB -->
			</div>
			<!-- END PAGE HEADING -->
			
            <div class="container-fluid">
				<div class="row">
					<div class="col-lg-12">
					
                           <div class="panel">
                            <div class="panel-title bg-amber-200">
								<div class="panel-head">{{ trans('config.lblv_heading') }}</div>
							</div>
							<div class="panel-body">

							<div class="table-responsive"> 
						<table class="table table-bordered">
							<tr>
								<td><label for="Taxi No" class="view_label">{{trans('config.lbl_vehicle_category') }}</label></td>
								<td>{{$taxi_details->getvehicle_name->ride_category}}</td>
							</tr>
								<tr>
							<td><label for="Taxi Type" class="mylabel">Vehicle Type</label></td>
								<td>{{$taxi_details->type_name->car_type}} <?php if($taxi_details->type_name->car_board == 1) { echo "(W)"; } elseif($taxi_details->type_name->car_board == 2) { echo "(Y)"; } ?></td>
							</tr>

							<tr>
								<td><label for="Vehicle Image" class="view_label">Vehicle Image</label></td>
								<td> 
								<img style="height: 350px;  width: auto;"
								 src="{{ URL::asset('public'.$taxi_details->vehical_image)}}" class="img-responsive"/>
                                       </td>
							</tr>

							<tr>
								<td><label for="Taxi No" class="view_label">Vehicle No.</label></td>
								<td>{{$taxi_details->car_no}}</td>
							</tr>

							 @if($taxi_details->isfranchise ==1)
                                           @foreach($franchise as $fra)
                                           @if($fra->id == $taxi_details->franchise_id) 
                                           @php $red = $fra->company_name; @endphp @endif
                                           @endforeach
                                           @else 
                                           @php $red = 'Go'; @endphp 
                                            @endif
							<tr>
								<td><label for="Taxi Brand" class="mylabel">Franchise</label></td>
								<td>{{$red}}</td>
							</tr>

							<tr>
								<td><label for="Taxi Brand" class="mylabel">Vehicle Brand</label></td>
								<td>{{$taxi_details->brand_name->brand}}</td>
							</tr>
							<tr>
								<td><label for="Taxi Model" class="view_label">Vehicle Model</label></td>
								<td>{{$taxi_details->model_name->model}}</td>
							</tr>
						
							<!--<tr>
								<td><label for="Taxi Capacity" class="view_label">Vehicle Capacity</label></td>
								<td>{{$taxi_details->capacity}}</td>
							</tr>-->
							<tr>
								<td><label for="Country" class="mylabel">Country</label></td>
								<td>{{$taxi_details->country_name->name}}</td>
							</tr>
							<tr>
								<td><label for="State" class="view_label">State</label></td>
								<td>{{$taxi_details->state_name->name}}</td>
							</tr>
							
								<td><label for="RC Book Image" class="view_label">RC Book Image</label></td>
								<td> <img style="    height: 350px;  width: auto;" src="{{ URL::asset('public'.$taxi_details->rc_image)}}" class="img-responsive"/>
                                       </td>
							</tr>
                            	<tr>
								<td><label for="RC Number" class="view_label">RC Number</label></td>
								<td>{{$taxi_details->rc_no}}</td>
							</tr>
                            	<tr>
								<td><label for="Car Insurance Image" class="view_label">Car Insurance Image</label></td>
								<td> <img style="    height: 350px;  width: auto;"src="{{ URL::asset('public'.$taxi_details->insurance_image)}}" class="img-responsive"/>
                                       </td>
							</tr>
                            	<tr>
								<td><label for="Car Insurance Expiry Date" class="view_label">Car Insurance Expiry Date</label></td>
								<td>{{date('d M,Y',strtotime($taxi_details->insurance_expiration_date))}}</td>
							<tr>
								<td><label for="Status" class="mylabel">Created By</label></td>
							<td>{{$taxi_details->getcreated_by->first_name	}}</td>
							</tr>
							<tr>
								<td><label for="Status" class="mylabel">Created Time</label></td>
								<td>{{date('d M,Y h:i a',strtotime($taxi_details->created_at))}}</td>
							</tr>
							<tr>
								<td><label for="Status" class="mylabel">Updated By</label></td>
								<td>{{$taxi_details->getupdated_by->first_name	}}</td>
							</tr>
							<tr>
								<td><label for="Status" class="mylabel">Updated Time</label></td>
								<td>{{date('d M,Yh:i a',strtotime($taxi_details->updated_at))}}</td>
							</tr>
						</table>
					</div>
						
                            </div>
						</div>
					</div><!-- /.col -->
				</div><!-- /.row -->
				
				<!-- /.row -->
				
				<!-- /.row -->
		
					<!-- BEGIN FOOTER -->
				@include('includes.footer')
				<!-- END FOOTER -->
            </div><!-- /.container-fluid -->
        </div>
@endsection