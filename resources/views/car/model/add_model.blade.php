@extends('layout.master');

@section('title')

AddModel - Go Cabs

@endsection

@section('content')
<div class="rightside bg-grey-100">
			<!-- BEGIN PAGE HEADING -->
            <div class="page-head">
				<h1 class="page-title">{{trans('config.lblm_addmodel_heading') }}</h1>
				<!-- BEGIN BREADCRUMB -->
				
				<!-- END BREADCRUMB -->
			</div>
			<!-- END PAGE HEADING -->

            <div class="container-fluid">
				<div class="row">
					<div class="col-lg-12">
                           <div class="panel">
                            <div class="panel-title bg-amber-200">
								<div class="panel-head">{{trans('config.lblm_addmodel_heading') }}</div>
							</div>
                            <div class="panel-body">
								<form class="form-horizontal" role="form" method="POST">
                                
                                 {{ csrf_field() }}
								  <div class="form-group {{ $errors->has('ride_category')? 'has-error':'' }}" >
									<label for="" class="col-sm-2 control-label">{{trans('config.lbl_vehicle_category') }}</label>
									<div class="col-sm-4">
									  <select class="form-control" name="ride_category">
                                        @foreach ($ride_category as $cat)
									  <option    value="{{$cat->id}}" <?php if(old('ride_category') == $cat->id) { echo "selected=selected"; } ?>>{{$cat->ride_category}}</option>
									 @endforeach
                                      </select>
									</div>
								  </div>

								  <div class="form-group {{ $errors->has('model_brand')? 'has-error':'' }}" >
									<label for="" class="col-sm-2 control-label">Select Brand</label>
									<div class="col-sm-4">
									  <select class="form-control" name="model_brand">
                                        @foreach ($brand as $cat)
									  <option    value="{{$cat->id}}" <?php if(old('model_brand') == $cat->id) { echo "selected=selected"; } ?>>{{$cat->brand}}</option>
									 @endforeach
                                      </select>
									</div>
								  </div>

								   <div class="form-group {{ $errors->has('model_type')? 'has-error':'' }}">
									<label for="" class="col-sm-2 control-label">{{trans('config.lblm_model_name') }}</label>
									<div class="col-sm-4">
									  <input type="text" maxlength="20" class="form-control" id="model_type" value ="{{old('model_type')}}" name="model_type" placeholder="" required >
									{!! $errors->first('model_type', '<span class="help-block">:message</span>') !!}
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
									
									  <button type="button" class="btn btn-dark bg-black color-white" onclick="window.location.href='{{ url('/model') }}';">{{trans('config.lbl_back') }}</button>
                                      <button type="submit" class="btn btn-dark bg-red-600 color-white">{{trans('config.lbl_add') }}</button>
									  <button type="reset" class="btn btn-dark bg-grey-400 color-black">{{trans('config.lbl_reset') }}</button>
									  
									
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
				@include('includes.footer')
				<!-- END FOOTER -->
            </div><!-- /.container-fluid -->
        </div>
@endsection