@extends('layout.master');

@section('title')

EditVehicle - Wrydes

@endsection

@section('content')
<div class="rightside bg-grey-100">
			<!-- BEGIN PAGE HEADING -->
            <div class="page-head">
				<h1 class="page-title">{{ trans('config.lblv_edit_vehicle_details') }}</h1>
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
                           
                               
								<form class="form-horizontal" role="form" method="POST" enctype="multipart/form-data">
                                 {{ csrf_field() }}
								    {{ csrf_field() }}
								       @if (session('error_status'))
										<div class="alert alert-danger">
											<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
											{{ session('error_status') }}
										</div>
									@endif
                                 <input type="hidden" name="taxi_id" value="{{$taxidetails->id}}"/>
								<div class="form-group {{ $errors->has('ride_category')? 'has-error':'' }}" >
									<label for="" class="col-sm-2 control-label">{{trans('config.lbl_vehicle_category') }}</label>
									<div class="col-sm-4">
									  <select class="form-control" 
									  @if($taxidetails->status ==1) disabled='disabled' @endif
									    name="ride_category" id="VehicleCategory" onChange="getmodel_type_list(this.value);">
										@foreach ($ride_category as $cat)
									  <option    value="{{$cat->id}}" <?php if(old('ride_category',$taxidetails->ride_category) == $cat->id) { echo "selected=selected"; } ?>>{{$cat->ride_category}}</option>
									 @endforeach
									  </select>
									</div>
								 </div>
								 
								 <div class="form-group  {{ $errors->has('vehicle_image')? 'has-error':'' }}">
									<label for="" class="col-sm-2 control-label">Vehicle Image</label>
									<div class="col-sm-4">
									  <input type="file" class="form-control" id="vehicle_image" onChange="validateImage('vehicle_image')" name="vehicle_image">
										<span>{{ trans('config.lbl_valid_img_format') }}</span>
                                      	{!! $errors->first('vehicle_image', '<span class="help-block">:message</span>') !!}
									</div>
								  </div>

								  @if($taxidetails->isfranchise ==1)
								  @php 
								  $r = 1;
								  @endphp
                                           @foreach($franchise as $fra)
                                           @if($fra->id == $taxidetails->franchise_id) 
                                           @php $red = $fra->company_name; @endphp @endif
                                           @endforeach
                                           @else 
                                           @php $red = 'Go'; 
                                           $r = 2; @endphp 
                                            @endif
								   <div class="form-group ">
                                        <label for="franchise" class="col-sm-2 control-label">Franchise</label>
										<div class="col-sm-4">
										<div class="radio radio-theme display-inline-block">
										<input name="Franchise" id="franchiseyes" @if($r ==1)checked="checked"@endif type="radio" value="1" >
                                            <label for="franchiseyes">Franchise</label>
                                            <input name="Franchise" id="franchiseno" @if($r ==2)checked="checked"@endif type="radio" value="0" >
                                            <label for="franchiseno">Go</label>
										
                                         </div>
                                        </div></div>

                                        <div id="franchise">
                                    <div class="form-group">
                                        <label for="" class="col-sm-2 control-label">Franchise</label>
                                        <div class="col-sm-4">
                                        <select class="form-control " name="franchise" id="">
                                            <option value="0">--Select Your Franchise--</option>
                                            @foreach ($franchise as $r)
                                            <option value="{{$r->id}}" @if($taxidetails->franchise_id == $r->id) selected="selected" @endif>{{$r->company_name}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    </div>
                                </div>

								  <div class="form-group ">
                                        <label for="" class="col-sm-2 control-label"></label>
										<div class="col-sm-4">
                                        <img src="{{ URL::asset('public'.$taxidetails->vehical_image)}}" class="img-responsive"/>
                                        </div>
								  </div>


								  <div class="form-group  {{ $errors->has('taxi_no')? 'has-error':'' }}">
									<label for="" class="col-sm-2 control-label">{{ trans('config.lblv_vehicle') }}</label>
									<div class="col-sm-4">
									  <input type="text" class="form-control alphanumeric" id="" value="{{old('taxi_no',$taxidetails->car_no)}}" placeholder="" name="taxi_no" required>
										{!! $errors->first('taxi_no', '<span class="help-block">:message</span>') !!}
                                    </div>
                                  </div>
								    <div class="form-group  {{ $errors->has('taxi_type')? 'has-error':'' }}">
									<label for="" class="col-sm-2 control-label">{{ trans('config.lbl_vehicle_type') }}</label>
									<div class="col-sm-4">
									<input type="hidden" name="t_type" id="t_type" value="{{old('taxi_type')}}"/>
									  <select class="form-control" @if($taxidetails->status ==1) disabled='disabled' @endif name="taxi_type" id="VehicleType">
                                        <option value="">--Select Vehicle Type--</option>
                                        
                                      @foreach($cartype as $type)
									  <option    value="{{$type->id}}" <?php if(old('taxi_type',$taxidetails->car_type) ==$type->id) { echo "selected=selected"; } ?>>{{$type->car_type}} 

									  @if($type->car_board == 1) (W) @endif
									  @if($type->car_board == 2) (Y) @endif


									  </option>
									 @endforeach
                                      </select>
                                      	{!! $errors->first('taxi_type', '<span class="help-block">:message</span>') !!}
									</div>
								  </div>    
								  <div class="form-group  {{ $errors->has('taxi_brand')? 'has-error':'' }}">
										<label for="" class="col-sm-2 control-label">{{ trans('config.lbl_vehicle_brnad') }}</label>
									<div class="col-sm-4">
									  <select class="form-control " name="taxi_brand" id="VehicleBrand">
                                      <option value="">--Select Vehicle Brand--</option>
                                      @foreach ($carbrand as $brand)
									  <option    value="{{$brand->id}}" <?php if(old('taxi_brand',$taxidetails->brand) ==$brand->id) { echo "selected=selected"; } ?>>{{$brand->brand}}</option>
									 @endforeach
                                      </select>
                                      	{!! $errors->first('taxi_brand', '<span class="help-block">:message</span>') !!}
									</div>


								  </div>
								  <div id="onModel">
								  <div class="form-group  {{ $errors->has('taxi_model')? 'has-error':'' }}">
									<label for="" class="col-sm-2 control-label">{{ trans('config.lbl_vehicle_model') }}</label>
									<div class="col-sm-4">
									<input type="hidden"  value="{{old('taxi_model')}}"/>
									  <select class="form-control" @if($taxidetails->status ==1) disabled='disabled' @endif >
									  @foreach($carmodel as $brand)
									  <option value="{{$brand->id}}">{{$brand->model}}</option>
									  @endforeach
                                        
                                         
                                      </select>
                                      	{!! $errors->first('taxi_model', '<span class="help-block">:message</span>') !!}
									</div>
								  </div>
								  </div>

								  <div id="ofModel">
								  <div class="form-group  {{ $errors->has('taxi_model')? 'has-error':'' }}">
									<label for="" class="col-sm-2 control-label">{{ trans('config.lbl_vehicle_model') }}</label>
									<div class="col-sm-4">
									<input type="hidden" name="t_type" id="t_type" value="{{old('taxi_model')}}"/>
									  <select class="form-control" id="taxi-model" name="taxi_model">
									  @foreach($carmodel as $brand)
									  <option value="{{$brand->id}}">{{$brand->model}}</option>
									  @endforeach
                                        
                                         
                                      </select>
                                      	{!! $errors->first('taxi_model', '<span class="help-block">:message</span>') !!}
									</div>
								  </div>
								  </div>
								                              
                                  <!-- <div class="form-group  {{ $errors->has('taxi_capacity')? 'has-error':'' }}">
									<label for="" class="col-sm-2 control-label">{{ trans('config.lblv_capacity') }}</label>
									<div class="col-sm-4">
									  <input type="text" class="form-control" value="{{old('taxi_capacity',$taxidetails->capacity)}}"  id="" placeholder="" name="taxi_capacity" required maxlength="2">
                                      	{!! $errors->first('taxi_capacity', '<span class="help-block">:message</span>') !!}
									</div>
								  </div> -->
                                  
                                  <div class="form-group  {{ $errors->has('country')? 'has-error':'' }}">
									<label for="" class="col-sm-2 control-label">{{ trans('config.lbl_country') }}</label>
									<div class="col-sm-4">
									  <select class="form-control " name="country" id="country">
                                        @foreach ($country_list as $country)
									  <option    value="{{$country->id}}" <?php if(old('country',$taxidetails->country) ==$country->id) { echo "selected=selected"; } ?>>{{$country->name}}</option>
									    @endforeach
                                      </select>
                                      	{!! $errors->first('country', '<span class="help-block">:message</span>') !!}
                                     </div>
								  </div>
                                  
                                  <div class="form-group  {{ $errors->has('state')? 'has-error':'' }}">
									<label for="" class="col-sm-2 control-label">{{ trans('config.lbl_state') }}</label>
									<div class="col-sm-4">
                                    <input type="hidden" value="{{old('state',$taxidetails->state)}}" id="temp_state"/>
									  <select class="form-control "  name="state" id="state">
                                     
                                      </select>
                                      	{!! $errors->first('state', '<span class="help-block">:message</span>') !!}
									</div>
								  </div>
                                  
                                  <div class="form-group  {{ $errors->has('city')? 'has-error':'' }}">
										<label for="" class="col-sm-2 control-label">{{ trans('config.lbl_state') }}</label>
									<div class="col-sm-4">
                                        <input type="hidden" value="{{old('city',$taxidetails->city)}}" id="temp_city"/>
									  <select class="form-control" name="city" id="city">
                                      </select>
                                      	{!! $errors->first('city', '<span class="help-block">:message</span>') !!}
									</div>
								  </div>
                                  
                                  <div class="form-group  {{ $errors->has('rc_book_image')? 'has-error':'' }}">
									<label for="" class="col-sm-2 control-label">{{ trans('config.lblv_rc_image') }}</label>
									<div class="col-sm-4">
									  <input type="file" class="form-control" id="rc_book_image" onChange="validateImage('rc_book_image')" name="rc_book_image">
										<span>{{ trans('config.lbl_valid_img_format') }}</span>
                                      	{!! $errors->first('rc_book_image', '<span class="help-block">:message</span>') !!}
									</div>
								  </div>
								   <div class="form-group ">
                                        <label for="" class="col-sm-2 control-label"></label>
										<div class="col-sm-4">
                                        <img src="{{ URL::asset('public'.$taxidetails->rc_image)}}" class="img-responsive"/>
                                        </div>
								  </div>
                                  
								  <div class="form-group  {{ $errors->has('rc_number')? 'has-error':'' }}">
									<label for="" class="col-sm-2 control-label">{{ trans('config.lblv_rc_number') }}</label>
									<div class="col-sm-4">
									  <input type="text"  class="form-control alphanumeric" maxlength="20"   value="{{old('rc_number',$taxidetails->rc_no)}}" id="" name="rc_number" placeholder="" required>
                                      	{!! $errors->first('rc_number', '<span class="help-block">:message</span>') !!}
									</div>
								  </div>
                                  
                                  <div class="form-group  {{ $errors->has('insurance_image')? 'has-error':'' }}">
									<label for="" class="col-sm-2 control-label">{{ trans('config.lblv_insurance_img') }}</label>
									<div class="col-sm-4">
									  <input type="file" class="form-control"  id="Insurance" onChange="validateImage('Insurance')"  name="insurance_image">
                                         <span>{{ trans('config.lbl_valid_img_format') }}</span>
                                      	{!! $errors->first('insurance_image', '<span class="help-block">:message</span>') !!}
                                        
									</div>
								  </div>
                                   <div class="form-group ">
                                        <label for="" class="col-sm-2 control-label"></label>
										<div class="col-sm-4">
                                        <img src="{{ URL::asset('public'.$taxidetails->insurance_image)}}" class="img-responsive"/>
                                        </div>
								  </div>
								  
								   <div class="form-group  {{ $errors->has('insurance_expiry_date')? 'has-error':'' }}">
									<label for="" class="col-sm-2 control-label">{{ trans('config.lblv_insurance_expiry') }}</label>
									<div class="col-sm-4">
									  <input class="form-control datepicker"  value="{{old('insurance_expiry_date',date("m/d/Y",strtotime($taxidetails->insurance_expiration_date)))}}" name="insurance_expiry_date" id="datepicker" placeholder="" required="" type="text">
										{!! $errors->first('insurance_expiry_date', '<span class="help-block">:message</span>') !!}
                                    </div>
								  </div>
                                  
                                  <div class="form-group">
									<div class="col-sm-offset-2 col-sm-4 margin-top-10">
									  <button type="button" class="btn btn-dark bg-black color-white" onclick="window.location.href='{{ url('/taxi') }}';">{{trans('config.lbl_back') }}</button>
                                      <button type="submit" class="btn btn-dark bg-red-600 color-white">{{trans('config.lbl_update') }}</button>
									  <button type="reset" class="btn btn-dark bg-grey-400 color-black">{{trans('config.lbl_reset') }}</button> 
                                     	</div>
								  </div>
								</form>
                              
                            </div>
						</div>
					</div><!-- /.col -->
				</div><!-- /.row -->
			   <script>
		$(document).ready(function(){
    		var franchise = $("input[name='Franchise']:checked").val();

        if(franchise == 1){
            $("#franchise").show();
        }
        else{
            $("#franchise").hide();
        }

        $("#franchiseyes").click(function(){
            $("#franchise").show();
        });
        $("#franchiseno").click(function(){
            $("#franchise").hide();
        });
		});
		</script>	
				<!-- /.row -->
				
				<!-- /.row -->
				
					<!-- BEGIN FOOTER -->
				@include('includes.footer')
				<!-- END FOOTER -->
            </div><!-- /.container-fluid -->
        </div>
	

@endsection