@extends('layout.master');

@section('title')

EditModel - Go Cabs

@endsection

@section('content')
<div class="rightside bg-grey-100">
			<!-- BEGIN PAGE HEADING -->
            <div class="page-head">
						<h1 class="page-title">{{trans('config.lblm_editmodel_heading') }}</h1>
				<!-- BEGIN BREADCRUMB -->
				
				<!-- END BREADCRUMB -->
			</div>
			<!-- END PAGE HEADING -->

            <div class="container-fluid">
				<div class="row">
					<div class="col-lg-12">
                           <div class="panel">
                            <div class="panel-title bg-amber-200">
								<div class="panel-head">{{trans('config.lblm_vehicle_details') }}</div>
							</div>
                            <div class="panel-body">
						@if (session('error_status'))
										<div class="alert alert-danger">
											<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
											{{ session('error_status') }}
										</div>
									@endif						
							@foreach($model_list as $model)
								<form class="form-horizontal" role="form" method="POST">
                                <input type="hidden" name="model_id" value="{{$model->id}}" />
                                 {{ csrf_field() }}
								   <div class="form-group {{ $errors->has('ride_category')? 'has-error':'' }}" >
									<label for="" class="col-sm-2 control-label">{{trans('config.lbl_vehicle_category') }}</label>
									<div class="col-sm-4">
									<input type="hidden" name="ride_category" value="{{$model->ride_category}}">
									  <select disabled class="form-control" name="">
                                        @foreach ($ride_category as $cat)
									  <option    value="{{$cat->id}}" <?php if(old('ride_category',$model->ride_category) == $cat->id) { echo "selected=selected"; } ?>>{{$cat->ride_category}}</option>
									 @endforeach
                                      </select>
									</div>
								  </div>

								  <input type="hidden" name="taxi_brand" value="{{$model->brand_id}}">
								  <div class="form-group {{ $errors->has('ride_category')? 'has-error':'' }}" >
									<label for="" class="col-sm-2 control-label">Brand</label>
									<div class="col-sm-4">
									  <select disabled class="form-control" name="">
                                        @foreach ($brand as $cat)
									  <option    value="{{$cat->id}}" <?php if($model->brand_id == $cat->id) { echo "selected=selected"; } ?>>{{$cat->brand}}</option>
									 @endforeach
                                      </select>
									</div>
								  </div>

								  <div class="form-group {{ $errors->has('model_type')? 'has-error':'' }}">
									<label for="" class="col-sm-2 control-label">Model Name</label>
									<div class="col-sm-4">
									  <input type="text" maxlength="20" class="form-control" id="model_type" value ="{{old('model_type',$model->model)}}" name="model_type" placeholder="" required >
									{!! $errors->first('model_type', '<span class="help-block">:message</span>') !!}
									</div>
									
								  </div>
							
								  <div class="form-group">
									<div class="col-sm-offset-2 col-sm-4 margin-top-10">
									
									  <button type="button" class="btn btn-dark bg-black color-white" onclick="window.location.href='{{ url('/model') }}';">Back</button>
                                      <button type="submit" class="btn btn-dark bg-red-600 color-white">Update</button>
									  <button type="reset" class="btn btn-dark bg-grey-400 color-black">Reset</button>
									  
									
									</div>
								  </div>
								</form>
							 @endforeach
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