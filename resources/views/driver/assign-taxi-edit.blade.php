 @extends('layout.master');

@section('title')

Update AssignVehicle - Wrydes

@endsection

@section('content') 
<div class="rightside bg-grey-100">
			<!-- BEGIN PAGE HEADING -->
            <div class="page-head">
					<h1 class="page-title">Edit Assigned Vehicle</h1>
				<!-- BEGIN BREADCRUMB -->
				
				<!-- END BREADCRUMB -->
			</div>
			<!-- END PAGE HEADING -->
		
            <div class="container-fluid">
				<div class="row">
					<div class="col-lg-12">
                           <div class="panel">
                            <div class="panel-title bg-amber-200">
								<div class="panel-head">Assigned Vehicle Information</div>
							</div>
                            <div class="panel-body">
								<form class="form-horizontal" role="form" method="post">
								     {{ csrf_field() }}
									 <input type="hidden" value="{{$taxidetails->id}}" name="assign_taxi_id" id="assign_taxi_id"/>
									       	
								  @if (session('error_status'))
										<div class="alert alert-danger">
											<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
											{{ session('error_status') }}
										</div>
									@endif 
								<div class="form-group {{ $errors->has('ride_category')? 'has-error':'' }}" >
									<label for="" class="col-sm-2 control-label">{{trans('config.lbl_vehicle_category') }}</label>
									<div class="col-sm-4">
									  <select disabled class="form-control" name="ride_category" onChange="getvehicle_driver_list(this.value);" >
										@foreach ($ride_category as $cat)
									  <option    value="{{$cat->id}}" <?php if(old('ride_category',$taxidetails->ride_category) == $cat->id) { echo "selected=selected"; } ?>>{{$cat->ride_category}}</option>
									 @endforeach
									  </select>
									</div>
								 </div>
                                   <div class="form-group {{ $errors->has('driver_name')? 'has-error':'' }}">
									<label for="" class="col-sm-2 control-label">Driver Name</label>
									<div class="col-sm-4">
										<input type="hidden" name="t_driver" id="t_driver" value="{{old('driver_name')}}" />
									  <select data-placeholder="Select driver" class="chosen-select-deselect form-control"  tabindex="1" name="driver_name" id="driver_name">
									  <option value="">Select Driver Name</option>
									  @foreach ($driver_list as $driver)
									  <option    value="{{$driver->id}}" <?php if(old('driver_name',$taxidetails->driver_id) ==$driver->id) { echo "selected=selected"; } ?>>{{$driver->driver_id}}
									   -- {{$driver->firstname}}{{$driver->lastname}}</option>
									    @endforeach
                                       </select>
									     	{!! $errors->first('driver_name', '<span class="help-block">:message</span>') !!}
									</div>
								  </div>
                                  
                              <div class="form-group" style="display:none">
									<label for="" class="col-sm-2 control-label">Taxi Status</label>
									<div class="col-sm-4">
										<div class="radio radio-theme display-inline-block">
										 <input name="taxi_status" id="optionsRadios1" <?php if(old('taxi_status',$taxidetails->owner_ship) ==1) { echo "checked=checked"; } ?>  type="radio" value='1'>
										 <label for="optionsRadios1">Owner of taxi</label>
                                         <input name="taxi_status" id="optionsRadios2" type="radio" value="2" <?PHP if(old('taxi_status',$taxidetails->owner_ship) == 2 ) { echo "checked=checked"; } ?> />
										<label for="optionsRadios2">Employee</label>
										</div>
									</div>
								  </div>
                                  
                                  <div class="form-group {{ $errors->has('car_number')? 'has-error':'' }}">
									<label for="" class="col-sm-2 control-label">Select Vehicle No.</label>
									<div class="col-sm-4">
										<input type="hidden" name="t_vehicle" id="t_vehicle" value="{{old('car_number')}}" />
									  <select data-placeholder="Select driver" name="car_number" id="car_number" class="chosen-select-deselect form-control">
                                       
									  @foreach ($carlist as $car_list)
									  <option  value="{{$car_list->id}}" <?php if(old('car_number',$taxidetails->car_num) ==$car_list->id) { echo "selected=selected"; } ?>>{{$car_list->car_no}} -- 
									  @foreach($cartype as $car_type)
									  @if($car_type->id == $car_list->car_type)
									   {{$car_type->car_type}}
									   @if($car_type->car_board == 1) (w) @else (Y) @endif
									  @endif
									  @endforeach
									  </option>
									    @endforeach

									    @foreach ($full_list as $full_list)
									  <option  value="{{$full_list->id}}" <?php if(old('car_number',$taxidetails->car_num) ==$full_list->id) { echo "selected=selected"; } ?>>{{$full_list->car_no}} -- 
									  @foreach($cartype as $car_type)
									  @if($car_type->id == $full_list->car_type)
									   {{$car_type->car_type}}
									   @if($car_type->car_board == 1) (w) @else (Y) @endif
									  @endif
									  @endforeach
									  </option>
									    @endforeach
                                      </select>
									  	{!! $errors->first('car_number', '<span class="help-block">:message</span>') !!}
									</div>
								  </div>
								  
								   <div class="form-group  {{ $errors->has('country')? 'has-error':'' }}">
									<label for="" class="col-sm-2 control-label">Country</label>
									<div class="col-sm-4">
									  <select class="chosen-select-deselect form-control" name="country" id="country">
                                        @foreach ($country_list as $country)
									  <option    value="{{$country->id}}" <?php if(old('country',$taxidetails->country) ==$country->id) { echo "selected=selected"; } ?>>{{$country->name}}</option>
									    @endforeach
                                      </select>
                                      	{!! $errors->first('country', '<span class="help-block">:message</span>') !!}
                                     </div>
								  </div>
                                  
                                  <div class="form-group  {{ $errors->has('state')? 'has-error':'' }}">
									<label for="" class="col-sm-2 control-label">State</label>
									<div class="col-sm-4">
                                    <input type="hidden" value="{{old('state',$taxidetails->state)}}" id="temp_state"/>
									  <select class="form-control "  name="state" id="state">
                                     
                                      </select>
                                      	{!! $errors->first('state', '<span class="help-block">:message</span>') !!}
									</div>
								  </div>
                                  
                                  <div class="form-group  {{ $errors->has('city')? 'has-error':'' }}">
									<label for="" class="col-sm-2 control-label">City</label>
									<div class="col-sm-4">
                                        <input type="hidden" value="{{old('city',$taxidetails->city)}}" id="temp_city"/>
									  <select class="form-control" name="city" id="city">
                                      </select>
                                      	{!! $errors->first('city', '<span class="help-block">:message</span>') !!}
									</div>
								  </div>
                           
									
								  <div class="form-group">
									<div class="col-sm-offset-2 col-sm-4 margin-top-10">
									<button type="button" class="btn btn-dark bg-black color-white" onclick="window.location.href='{{ url('/manage_assign_taxi') }}';">Back</button>
									  <button type="submit" class="btn btn-dark bg-red-600 color-white">Assign</button>
									  	<button type="reset" class="btn btn-dark bg-grey-400 color-black">Reset</button>
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
				<footer class="bg-white">
					<div class="pull-left">
						<span class="pull-left margin-right-15">&copy; 2016 Wrydes. All Rights Reserved.</span>
					</div>
				</footer>
				<!-- END FOOTER -->
            </div><!-- /.container-fluid -->
        </div>
@endsection