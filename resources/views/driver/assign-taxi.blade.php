@extends('layout.master');

@section('title')

AssignVehicle - Wrydes

@endsection

@section('content')
<div class="rightside bg-grey-100">
			<!-- BEGIN PAGE HEADING -->
            <div class="page-head">
				<h1 class="page-title">{{ trans('config.lbla_assignvehicle') }}</h1>
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
								<form class="form-horizontal" role="form" method="post">
								     {{ csrf_field() }}

								     <!-- <div class="form-group">
								     	<label for="" class="col-sm-2 control-label">Company Type</label>
								     	<div class="col-sm-4">
	                                        <div class="radio radio-theme display-inline-block">
	                                            <input name="CompanyType" id="franchiseyes" value="1" type="radio">
	                                            <label for="franchiseyes">Franchise</label>
	                                            <input name="CompanyType" id="franchiseno" value="0" checked="checked" type="radio">
	                                            <label for="franchiseno">Go</label>
	                                        </div>
                                    	</div>
								     </div>

								<div id="franchise" style="display: block;">
                                    <div class="form-group">
                                        <label for="" class="col-sm-2 control-label">Franchise</label>
                                        <div class="col-sm-4">
                                        <select class="form-control " name="franchise" id="">
                                            <option value="">--Select Your Franchise--</option>
                                            <option value="1">Zeus Taxi Service</option>
                                            <option value="2">AD Taxi Service</option>
                                        </select>
                                    </div>
                                    </div>
                                </div> -->

									<div class="form-group {{ $errors->has('ride_category')? 'has-error':'' }}" >
									<label for="" class="col-sm-2 control-label">{{trans('config.lbl_vehicle_category') }}</label>
									<div class="col-sm-4">
									  <select class="form-control" name="ride_category" id="VCategory">
                                        @foreach ($ride_category as $cat)
									  <option    value="{{$cat->id}}" <?php if(old('ride_category') == $cat->id) { echo "selected=selected"; } ?>>{{$cat->ride_category}}</option>
									 @endforeach
                                      </select>
									</div>
								  </div>

           <div class="form-group"" id="Test">
				<label for="" class="col-sm-2 control-label">Driver Name</label>
					<div class="col-sm-4">
						<input type="hidden" name="t_driver" id="t_driver" value="{{old('driver_name')}}" />
					  <select class="chosen-select-deselect form-control" tabindex="1" name="driver_name" id="DriverNameDyn">
					  <option value="">Select Driver Id</option>
					  @foreach ($driver_list as $driver)
					  <option    value="{{$driver->id}}" <?php if(old('driver_name') ==$driver->id) { echo "selected=selected"; } ?>>{{$driver->driver_id}} -- {{$driver->firstname}} {{$driver->lastname}}</option>
					    @endforeach
		               </select>
					</div>
		</div>

                                  
                              <div class="form-group" style="display:none">
									<label for="" class="col-sm-2 control-label">Vehicle Status</label>
									<div class="col-sm-4">
										<div class="radio radio-theme display-inline-block">
										 <input name="taxi_status" id="optionsRadios1" checked="checked" type="radio" value='1'>
										 <label for="optionsRadios1">Owner of Vehicle</label>
                                         <input name="taxi_status" id="optionsRadios2" type="radio" value="2">
										<label for="optionsRadios2">Employee</label>
										</div>
									</div>
								  </div>
                                  
                                  <div class="form-group {{ $errors->has('car_number')? 'has-error':'' }}">
									<label for="" class="col-sm-2 control-label">Select Vehicle No.</label>
									<div class="col-sm-4">
									<input type="hidden" name="t_vehicle" id="t_vehicle" value="{{old('car_number')}}" />
									  <select id="CarNumberDyn" name="car_number" class="chosen-select-deselect form-control">
                                       <option value="">Select Vehicle No</option>
									  @foreach ($carlist as $car_list)
									<option  value="{{$car_list->id}}" <?php if(old('car_number') ==$car_list->id) { echo "selected=selected"; } ?>>{{$car_list->car_no}} --
										  @foreach($cartype as $ct)
										  	@if($ct->id == $car_list->car_type)
										  	{{$ct->car_type}}
										  	@if($ct->car_board == 1) (W) @endif
										  	@if($ct->car_board == 2) (Y) @endif
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
									  <select data-placeholder="Select driver" class="chosen-select-deselect form-control" name="country" id="country">
                                      <option value="">--Select Country--</option>
                                        @foreach ($country_list as $country)
									  <option    value="{{$country->id}}" <?php if(old('country') ==$country->id) { echo "selected=selected"; } ?>>{{$country->name}}</option>
									    @endforeach
                                      </select>
                                      	{!! $errors->first('country', '<span class="help-block">:message</span>') !!}
                                     </div>
								  </div>
                                  
                                  <div class="form-group  {{ $errors->has('state')? 'has-error':'' }}">
									<label for="" class="col-sm-2 control-label">State</label>
									<div class="col-sm-4">
									  <select class="form-control "  name="state" id="state">
                                     <option value="">--Select State--</option>
                                      </select>
                                      	{!! $errors->first('state', '<span class="help-block">:message</span>') !!}
									</div>
								  </div>
                                  
                                  <div class="form-group  {{ $errors->has('city')? 'has-error':'' }}">
									<label for="" class="col-sm-2 control-label">City</label>
									<div class="col-sm-4">
									  <select class="form-control" name="city" id="city">
                                      <option value="">--Select City--</option>
                                      </select>
                                      	{!! $errors->first('city', '<span class="help-block">:message</span>') !!}
									</div>
								  </div>
                                  	
								  @if (session('error_status'))
										<div class="alert alert-danger">
											<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
											{{ session('error_status') }}
										</div>
									@endif
									
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
				
				<script type="text/javascript">
					
					$(document).ready(function(){

						// var CType = $("input[name='CompanyType']:checked").val();
						// console.log(CType);
						getvehicle_driver_list(1);
					});
				</script>
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
