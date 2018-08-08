@extends('layout.master');

@section('title')

Add Vehicle Type - Go Cabs

@endsection

@section('content')
<div class="rightside bg-grey-100">
			<!-- BEGIN PAGE HEADING -->
            <div class="page-head">
				<h1 class="page-title">Add Vehicle Type</h1>
				<!-- BEGIN BREADCRUMB -->
				
				<!-- END BREADCRUMB -->
			</div>
			<!-- END PAGE HEADING -->

        <div class="container-fluid">
				<div class="row">
					<div class="col-lg-12">
                           <div class="panel">
                            <div class="panel-title bg-amber-200">
								<div class="panel-head">Add New Vehicle Type</div>
							</div>
                            <div class="panel-body">
								<form class="form-horizontal" role="form" method="POST" enctype="multipart/form-data">
                                
                                 {{ csrf_field() }}
								   <div class="form-group {{ $errors->has('ride_category')? 'has-error':'' }}" >
									<label for="" class="col-sm-2 control-label">{{trans('config.lbl_vehicle_category') }}</label>
									<div class="col-sm-4">
									  <select class="form-control validate" name="ride_category" id="ride_categoy">
                                        @foreach ($ride_category as $cat)
									  <option    value="{{$cat->id}}" <?php if(old('ride_category') == $cat->id) { echo "selected=selected"; } ?>>{{$cat->ride_category}}</option>
									 @endforeach
                                      </select>
									</div>
								  </div>
								 	<div class="form-group {{ $errors->has('car_type')? 'has-error':'' }}">
									<label for="" class="col-sm-2 control-label">Vehicle Type</label>
									<div class="col-sm-4">
									  <input type="text" maxlength="20" class="form-control validate" id="car_type" value ="{{old('car_type')}}" name="car_type" placeholder="" required >
									{!! $errors->first('car_type', '<span class="help-block">:message</span>') !!}
									</div>
									</div>

									<div class="form-group  {{ $errors->has('taxi_capacity')? 'has-error':'' }}">
										<label for=""
											   class="col-sm-2 control-label">{{ trans('config.lblv_capacity') }}</label>
										<div class="col-sm-4">
											<input type="text" class="form-control validate numeric" value="{{old('taxi_capacity')}}" id=""
												   placeholder="" name="taxi_capacity" maxlength="3" required >
											{!! $errors->first('taxi_capacity', '<span class="help-block">:message</span>') !!}
										</div>
									</div>
									
									
                                
								<div class="form-group {{ $errors->has('companydriver_share')? 'has-error':'' }}">
									<label for="" class="col-sm-2 control-label">Company's Share</label>
									<div class="col-sm-4">
									  <input type="text" class="form-control validate numeric" id="companydriver_share" value ="{{old('companydriver_share')}}" maxlength="3" name="companydriver_share" placeholder="" required >
									{!! $errors->first('companydriver_share', '<span class="help-block">:message</span>') !!}
									</div>
									<div class="col-sm-1"><label>%</label></div>
								</div>

								<div class="form-group {{ $errors->has('attacheddriver_share')? 'has-error':'' }}">
									<label for="" class="col-sm-2 control-label">Attached Vehicle share</label>
									<div class="col-sm-4">
									  <input type="text" class="form-control validate numeric" id="attacheddriver_share" value ="{{old('attacheddriver_share')}}" maxlength="3" name="attacheddriver_share" placeholder="" required > 
									{!! $errors->first('attacheddriver_share', '<span class="help-block">:message</span>') !!}
									</div>
									<div class="col-sm-2"><label>%</label></div> 	
									
								</div>

								<div class="form-group {{ $errors->has('franchise_share')? 'has-error':'' }}">
									<label for="" class="col-sm-2 control-label">Franchise's Share</label>
									<div class="col-sm-4">
									  <input type="text" class="form-control validate numeric" id="franchise_share" value ="{{old('franchise_share')}}" maxlength="3" name="franchise_share" placeholder="" required > 
									{!! $errors->first('franchise_share', '<span class="help-block">:message</span>') !!}
									</div>
									<div class="col-sm-2"><label>%</label></div> 	
									
								</div>	

								<div class="form-group" id="vehical_color">
										<label for="" class="col-sm-2 control-label">{{ trans('config.lbl_vehicle_board') }}</label>
										<div class="col-sm-4">
											<div class="radio radio-theme display-inline-block">
											 <input name="taxi_status" id="optionsRadios1" checked="checked" type="radio" value='1'>
											 <label for="optionsRadios1">{{ trans('config.lbl_whit') }}</label>
											 <input name="taxi_status" id="optionsRadios2" type="radio" value="2">
											<label for="optionsRadios2">{{ trans('config.lbl_yelw') }}</label>
											</div>
										</div>
									</div>

                                  <div class="form-group  {{ $errors->has('yellow_caricon')? 'has-error':'' }}">
									<label for="" class="col-sm-2 control-label">Selected Vehicle icon</label>
									<div class="col-sm-4">
									  <input type="file" class="form-control validate" id="yellow_caricon" name="yellow_caricon" onChange="validateImagewithdimension('yellow_caricon')">
                                      <span>Only .jpg,.png are allowed</span><br>
                                      <span>Only Height:50 Width:100 size images are allowed</span>

                                      	{!! $errors->first('yellow_caricon ', '<span class="help-block">:message</span>') !!}
									</div>
								  </div>
								   
                                  <div class="form-group  {{ $errors->has('grey_caricon')? 'has-error':'' }}">
									<label for="" class="col-sm-2 control-label">UnSelected Vehicle icon</label>
									<div class="col-sm-4">
									  <input type="file" value="" class="form-control validate" id="grey_caricon" name="grey_caricon" onChange="validateImagewithdimension('grey_caricon')">
                                      <span>Only .jpg,.png are allowed</span><br>
                                      <span>Only Height:50 Width:100 size images are allowed</span>
                                      	{!! $errors->first('grey_caricon', '<span class="help-block">:message</span>') !!}
									</div>
								  </div>
								   
                                  <!-- <div class="form-group  {{ $errors->has('black_caricon')? 'has-error':'' }}">
									<label for="" class="col-sm-2 control-label">Black Vehicle icon</label>
									<div class="col-sm-4">
									  <input type="file" onChange="validateImagewithdimension('black_caricon')" id="black_caricon" class="form-control" name="black_caricon">
                                      <span>Only .jpg,.png are allowed</span><br>
                                      <span>Only Height:50 Width:100 size images are allowed</span>
                                      	{!! $errors->first('black_caricon', '<span class="help-block">:message</span>') !!}
									</div>
								  </div> -->
									
								  @if (session('error_status'))
										<div class="alert alert-danger">
											<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
											{{ session('error_status') }}
										</div>
									@endif
								  <div class="form-group">
									<div class="col-sm-offset-2 col-sm-4 margin-top-10">
									
									  <button type="button" class="btn btn-dark bg-black color-white" onclick="window.location.href='{{ url('/type') }}';">Back</button>
                                      <button type="submit" id="button1" class="btn btn-dark bg-red-600 color-white">Add</button>
									  <button type="reset" class="btn btn-dark bg-grey-400 color-black">Reset</button>
									  
									
									</div>
								  </div>
								</form>
                                  
                                  
                                  
								 
                            </div>
						</div>
					</div><!-- /.col -->
				</div><!-- /.row -->
				
				<!-- /.row -->
				<script>
				 // Remove The base base editing
				var cat=$("#ride_categoy").val();
				
				if(cat==1){
					$("#vehical_color").show();
				}else{
					$("#vehical_color").hide();
					$("#optionsRadios1").val('0');
					$("#optionsRadios2").val('0');
				}

				
				$("#button1").click(function(e){
					var isvalid = true;
					$(".validate").each(function () { 
						if ($.trim($(this).val()) == '') {
							isValid = false;
							$(this).css({
								"border": "1px solid red",
								"background": ""
							});
							if (isValid == false)
								e.preventDefault();
						}
						else {
							$(this).css({
								"border": "2px solid green",
								"background": ""
							});
							return true;
						}
					});
					var a=$("#companydriver_share").val();
					var b=$("#attacheddriver_share").val();
					var c=$("#franchise_share").val();
					var d = (+a) + (+b) + (+c);
					console.log(d);
					if(d != 100){

						$("#companydriver_share").css({
                        "border": "1px solid red",
                        "background": ""
                    		});
						$("#attacheddriver_share").css({
                        "border": "1px solid red",
                        "background": ""
                    		});
						$("#franchise_share").css({
                        "border": "1px solid red",
                        "background": ""
                    		});
						bootbox.alert('Total Share percentage value should be 100');
						return false;
					}
					if(d == 100){

						$("#companydriver_share").css({
                        "border": "1px solid green",
                        "background": ""
                    		});
						$("#attacheddriver_share").css({
                        "border": "1px solid green",
                        "background": ""
                    		});
						$("#franchise_share").css({
                        "border": "1px solid green",
                        "background": ""
                    		});
						return true;
					}
				});

				</script>
				<!-- /.row -->
				
				<!-- BEGIN FOOTER -->
				@include('includes.footer')
				<!-- END FOOTER -->
            </div><!-- /.container-fluid -->
 </div>
@endsection