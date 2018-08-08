@extends('layout.master');

@section('title')

Manage Model - Go Cabs

@endsection

@section('content')
<div class="rightside bg-grey-100">
			<!-- BEGIN PAGE HEADING -->
            <div class="page-head">
				<h1 class="page-title">{{trans('config.lblm_heading') }}</h1>
				<!-- BEGIN BREADCRUMB -->
				<a href="{{url("/")}}/model/add"><button class="btn btn-dark bg-red-600 color-white pull-right" >Add Vehicle Model</button></a>
				<!-- END BREADCRUMB -->
			</div>
			<!-- END PAGE HEADING -->
	<!-- START OF FILTER-->
		<div class="f_filter container-fluid">
			<div class="pull-right col-lg-3 no-padding">
				<form method="get" action="" name="filter">
				 {{ csrf_field() }}
				 <div class="input-group">
				   	<select class=" form-control" name="ride_category" >
							<option value="">--Select Vehicle Type--</option>
						 @foreach ($ride_category as $cat)
					  <option    value="{{$cat->id}}" {{ session('cf_model') == $cat->id ? "selected=selected":''}} >{{$cat->ride_category}}</option>
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
								<div class="panel-head">Manage Vehicle Models</div>
								</div>
                                <div class="panel-body no-padding-top bg-white">
												

								

									<p class="text-light margin-bottom-30"></p>
									<form id="change-model-status" action="#" method="POST">
											<table class="table table-bordered display" >
										<thead>
											<tr>
												<!-- <th class="vertical-middle">{{trans('config.lbl_select') }}</th> -->
												<th class="vertical-middle">{{trans('config.lbl_vehicle_category') }}</th>
												<th class="vertical-middle">{{trans('config.lbl_vehicle_brnad') }}</th>
												 <th class="vertical-middle">{{trans('config.lblm_model_name') }}</th>
												<th class="vertical-middle">{{trans('config.lbl_action') }}</th>
											</tr>
										</thead>
										<tbody>
										@foreach($model_list as $model)
											<tr> 
												<!-- <td class="vertical-middle"><input type="checkbox" value="{{$model->id}}"></td> -->
												<td class="vertical-middle">{{$model->getvehicle_name->ride_category}}</td>
												<td class="vertical-middle">{{$model->getbrand->brand}}</td>
												<td class="vertical-middle">{{$model->model}}</td>
												<!-- <td class="vertical-middle"><i class='{{ ($model->status == 1) ? "fa fa-check-circle active" : "fa fa-times-circle inactive" }}' aria-hidden='true'></i></td> -->
												<td class="vertical-middle">
													<a href="{{ url('/model/') }}/{{$model->id}}/edit" title="Edit"><i class="fa fa-edit fa-2x"></i></a>
													<!-- @if($model->status == 1)
													<a href="#" class="btn btn-danger btn-circle" title="Inactivate" onclick="deactivate({{ $model->id}},'ajax_deactive_model','Model Details');"><i class="glyphicon glyphicon-remove"></i></a>
													@else
													<a href="#" class="btn btn-success btn-circle" title="Activate"  onclick="activate({{ $model->id}},'ajax_active_model','Model Details');"><i class="glyphicon glyphicon-ok"></i></a>
													@endif -->
													</td>
											</tr>
										@endforeach
										</tbody>
									</table>
							<!-- <table cellspacing="0" cellpadding="0" class="note ma_0 noti" width="100%">
						  <tbody>
							<tr>
							  <td class="">
							  <i class="fa fa-check-circle active" aria-hidden="true"></i><span> {{trans('config.lbl_active') }}</span>
							  <i class="fa fa-times-circle inactive" aria-hidden="true"></i><span> {{trans('config.lbl_block') }}</span></td>
							  
							</tr>
						  </tbody>
						</table> -->
						
						<p class="text-light margin-bottom-30"></p>
						<!-- <div class="form-group ">
							<label for="" class="col-sm-2 control-label no-padding">Change Status</label>
							<div class="col-sm-2 no-padding">
								<select class="form-control" name="status" id="ch_status">
								<option value="1">{{trans('config.lbl_activate')}}</option>
								<option value="0">{{trans('config.lbl_block') }}</option>
								</select>
							</div>
							<div class="col-sm-2 ">
								<button class="btn btn-danger" type="submit">Change</button>
							</div>
						</div> -->
						</form>
                            </div>
							
                        </div><!-- /.col -->
						
						
                    </div><!-- /. row -->
		    </div><!-- /.row -->
				
				<!-- /.row -->
				
				<!-- /.row -->
				
				<!-- BEGIN FOOTER -->
				@include('includes.footer')
				<!-- END FOOTER -->
            </div><!-- /.container-fluid -->
        </div>
@endsection
