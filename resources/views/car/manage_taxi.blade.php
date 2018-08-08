@extends('layout.master');

@section('title')

ManageVehicle - Wrydes

@endsection

@section('content')
<?php
if(isset($_COOKIE['tabstatus'])){
	
	//$tabstatus=$_COOKIE['tabstatus']!='' ? $_COOKIE['tabstatus'] : '';
	$tabstatus="3";
}else{
	$tabstatus="3";
}

?>


<div class="rightside bg-grey-100">
			<!-- BEGIN PAGE HEADING -->
            <div class="page-head">
				<h1 class="page-title">Manage Vehicle</h1>
				<!-- BEGIN BREADCRUMB -->
					@if(Session::get('user_role')  ==1)
				<a href="{{url('/')}}/taxi/add" class="btn btn-dark bg-red-600 color-white pull-right">{{trans('config.lblv_btn_add_vichle') }}</a>
				@endif
				<!-- END BREADCRUMB -->
			</div>
			
			<!-- START OF FILTER-->
    <div class="f_filter container-fluid">
        <div class="pull-right col-lg-3 no-padding">
            <form method="GET" action="" name="filter">
                {{ csrf_field() }}
                <div class="input-group">
                    <select class=" form-control" name="ride_category">
                        <option value="">--Vehicle Category--</option>
                        <option value="0">All Category</option>
                        @foreach ($ride_category as $cat)
                        <option value="{{$cat->id}}" {{ session(
                        'cf_driver') == $cat->id ? "selected=selected":''}} >{{$cat->ride_category}}</option>
                        @endforeach
                    </select>
                    <span class="input-group-btn">
						<input type="submit" class="btn btn-dark bg-red-600 color-white pull-right" value="Search"/>
				   </span>
                </div>
            </form>
        </div>
        <div class="clearfix"></div>
    </div>
    <!-- END OF FILTER-->
    
                                
			<!-- END PAGE HEADING -->

           <div class="container-fluid">
				<div class="row">
                        <div class="col-lg-12">
							<div class="panel no-border">
                            	<div class="panel-title bg-amber-200">
									<div class="panel-head">{{ trans('config.lblv_heading') }}</div>
								</div>
                                
                                <div class="panel-body bg-white">
                                
                                
                                
                                <div class="clearfix"></div>
                                <ul class="nav nav-tabs tab-grey bg-grey-100">
									<li class='{{$tabstatus =="3" ? "active":""}}'><a href="#un_assigned" id="glyphicons-tab" data-toggle="tab" aria-controls=
Change Status"glyphicons">{{ trans('config.lblv_unassigned_vehicle') }}</a></li>
									<li class='{{$tabstatus =="1" || $tabstatus =="" ? "active":""}}'><a href="#v_assigned" id="fontawesome-tab" data-toggle="tab" aria-controls="fontawesome" aria-expanded="true">{{ trans('config.lblv_assigned_vehicle') }}</a></li>
									<li class='{{$tabstatus =="2" ? "active":""}}'><a href="#v_blocked" id="ionicons-tab" data-toggle="tab" aria-controls="ionicons">{{ trans('config.lblv_blocked_vehicle') }}</a></li>
								
									</ul>
								<div class="tab-content">
                                <div id="v_assigned" aria-labelledBy="fontawesome-tab" class="panel-body padding-md table-responsive tab-pane in {{$tabstatus =='1' || $tabstatus =='' ? 'active':''}}">
									<p class="text-light margin-bottom-30"></p>
									 <form id="active-taxi" action="#" method="POST">
											<table class="table table-bordered" id="multicheck_active">
										<thead>
											<tr>
												<th class="vertical-middle" style="width:50px">{{ trans('config.lbl_select') }}</th>
												<th class="vertical-middle">{{ trans('config.lbl_vehicle_category') }}</th>
												<th class="vertical-middle">{{ trans('config.lblv_vehicle') }}</th>
												<th class="vertical-middle">{{ trans('config.lbl_vehicle_type') }}</th>
                                              	<th class="vertical-middle">{{ trans('config.lbl_vehicle_brnad') }}</th>
                                                <th class="vertical-middle">{{ trans('config.lbl_vehicle_model') }}</th>
                                                
                                                <!-- <th class="vertical-middle">{{ trans('config.lbl_city') }}</th> -->
												<th class="vertical-middle">{{ trans('config.lblv_driver_name_id') }}</th>
												<th class="vertical-middle">Franchise</th>
										      <th class="vertical-middle">{{ trans('config.lbl_action') }}</th>
											</tr>
										</thead>
										<tbody>
										
										@foreach($active_list as $car)
											<tr> 
												<td class="vertical-middle" ><input type="checkbox" value="{{$car->id}}"></td>
												<th class="vertical-middle">{{$car->getvehicle_name->ride_category}}</th>
												<td class="vertical-middle">{{$car->car_no}}</td>
												<td class="vertical-middle">{{$car->type_name->car_type}} <?php if($car->type_name->car_board == 1) { echo "(W)"; } elseif($car->type_name->car_board == 2) { echo "(Y)"; } ?></td>
												<td class="vertical-middle">{{$car->brand_name->brand}}</td>
												<td class="vertical-middle">{{$car->model_name->model}}</td>
                                       
												
												<td class="vertical-middle">
												
												
													@foreach($driver as $drivers)
														@if($drivers->id == $car->driver_id)
														{{$drivers->firstname}} -- {{$drivers->driver_id}}
														@endif
													@endforeach
												
												</td>
												<td class="vertical-middle">
                                            @if($car->isfranchise ==1)
                                           @foreach($franchise as $fra)
                                           @if($fra->id == $car->franchise_id) {{$fra->company_name}} @endif
                                           @endforeach
                                           @else 
                                           Go
                                            @endif
                                        </td>
												<td class="vertical-middle">
												<a href="{{ url('/taxi/') }}/{{$car->getcarid($car->id)}}/view" title="View"><i class="fa fa-eye fa-2x"></i></i></a>
												@if(Session::get('user_role')  ==1)<a href="{{ url('/taxi/') }}/{{$car->getcarid($car->id)}}/edit" title="Edit"><i class="fa fa-edit fa-2x"></i></a>
												<a href="#" title="Block" onclick="deactivate({{$car->getcarid($car->id)}},'taxi/ajax_nonactive_taxi','vehicle ?','3');"><i class="fa fa-close fa-2x"></i></a>
													@endif
												
												</td>
											</tr>
										@endforeach	
										</tbody>
									</table>
                                    
                                    <p class="text-light margin-bottom-30"></p>
                                    
                         @if(Session::get('user_role')  ==1)           
                                    
                                    <div class="form-group ">
									<label for="" class="col-sm-12 control-label no-padding">Change Status</label>
									<div class="col-sm-2 no-padding">
									  <select class="form-control">
                                        <option>Blocked</option>
                                      </select>
									</div>
									<div class="col-sm-2 ">
									<button class="btn btn-danger" type="submit">Change</button>
								  </div>
								  </div>
								  @endif
								  </form>
                                </div><!-- /.panel-body -->
								
								<div id="v_blocked" aria-labelledBy="ionicons-tab" class="panel-body padding-md table-responsive tab-pane {{$tabstatus =='2' ? 'active':''}}">
								  <p class="text-light margin-bottom-30"></p>
								   <form id="blocked-taxi" action="#" method="POST">
											<table class="table table-bordered" id="multicheck_block">
										<thead>
											<tr>
												<th class="vertical-middle" style="width:50px">{{ trans('config.lbl_select') }}</th>
												<th class="vertical-middle">{{ trans('config.lbl_vehicle_category') }}</th>
												<th class="vertical-middle">{{ trans('config.lblv_vehicle') }}</th>
											
                                              	<th class="vertical-middle">{{ trans('config.lbl_vehicle_type') }}</th>
                                             	<th class="vertical-middle">{{ trans('config.lbl_vehicle_brnad') }}</th>
                                                <th class="vertical-middle">{{ trans('config.lbl_vehicle_model') }}</th>
                                               
                                                <!-- <th class="vertical-middle">{{ trans('config.lbl_city') }}</th> -->
                                                <th class="vertical-middle">Franchise</th>
											    <th class="vertical-middle">{{ trans('config.lbl_action') }}</th>
											</tr>
										</thead>
										<tbody>
										@foreach($blocked_list as $car)
											<tr> 
												<td class="vertical-middle">
												<input type="checkbox" value="{{$car->id}}"></td>
												<th class="vertical-middle">{{$car->getvehicle_name->ride_category}}</th>
												<td class="vertical-middle">{{$car->car_no}}</td>
												<td class="vertical-middle">{{$car->type_name->car_type}} <?php if($car->type_name->car_board == 1) { echo "(W)"; } elseif($car->type_name->car_board == 2) { echo "(Y)"; } ?></td>
												<td class="vertical-middle">{{$car->brand_name->brand}}</td>
												<td class="vertical-middle">{{$car->model_name->model}}</td>
												
										<td class="vertical-middle">
                                            @if($car->isfranchise ==1)
                                           @foreach($franchise as $fra)
                                           @if($fra->id == $car->franchise_id) {{$fra->company_name}} @endif
                                           @endforeach
                                           @else 
                                           Go
                                            @endif
                                        </td>
												<td class="vertical-middle">
												<a href="{{ url('/taxi/') }}/{{$car->id}}/view" title="View"><i class="fa fa-eye fa-2x"></i></i></a>
												@if(Session::get('user_role')  ==1)<a href="{{ url('/taxi/') }}/{{$car->id}}/edit"  title="Edit"><i class="fa fa-edit fa-2x"></i></a>
												<a href="#"  title="Block" onclick="activate({{ $car->id}},'taxi/ajax_block_nonactive_taxi','vehicle ?','2');"><i class="fa fa-check fa-2x"></i></a>
												&nbsp;<a href="#" data-toggle="tooltip" title="Delete"
                                                         onclick="delete1({{ $car->id}},'delete_taxi','Vehicle');"><i
                                                            class="fa fa-trash fa-2x"></i></a>
												@endif
												</td>
												</tr>
										@endforeach	
										</tbody>
									</table>
                                    
                                    <p class="text-light margin-bottom-30"></p>
                        @if(Session::get('user_role')  ==1)     
                                   <div class="form-group ">
									<label for="" class="col-sm-12 control-label no-padding">Change Status</label>
									<div class="col-sm-2 no-padding">
									  <select class="form-control">
                                        <option>Activate</option>
                                      </select>
									</div>
									<div class="col-sm-2 ">
									<button class="btn btn-danger" type="submit">Change</button>
								  </div>
								  </div>
								  @endif
								   </form>
								</div>
								
								<div id="un_assigned" aria-labelledBy="glyphicons-tab" class="panel-body padding-md table-responsive tab-pane {{$tabstatus =='3'  ? 'active':''}}">
										  <p class="text-light margin-bottom-30"></p>
										 <form id="non-active-taxi" action="#" method="POST">
											<table class="table table-bordered multicheck_box" id="multicheck_inactive">
										<thead>
											<tr>
												<th class="vertical-middle" style="width:50px">{{ trans('config.lbl_select') }}</th>
												<th class="vertical-middle">{{ trans('config.lbl_vehicle_category') }}</th>
												<th class="vertical-middle">{{ trans('config.lblv_vehicle') }}</th>
						                      	<th class="vertical-middle">{{ trans('config.lbl_vehicle_type') }}</th>
                                             
                                              	<th class="vertical-middle">{{ trans('config.lbl_vehicle_brnad') }}</th>
                                                <th class="vertical-middle">{{ trans('config.lbl_vehicle_model') }}</th>
                                                <!-- <th class="vertical-middle">{{ trans('config.lbl_city') }}</th> -->
                                                <th class="vertical-middle">Franchise</th>
											    <th class="vertical-middle">{{ trans('config.lbl_action') }}</th>
											</tr>
										</thead>
										<tbody>
										@foreach($inactive_list as $car)
											<tr> 
												<td class="vertical-middle"><input id="checkbox13" type="checkbox" value="{{$car->id}}"></td>
												 <td class="vertical-middle">{{$car->getvehicle_name->ride_category}}</td>
												<td class="vertical-middle">{{$car->car_no}}</td>
												<td class="vertical-middle">{{$car->type_name->car_type}} <?php if($car->type_name->car_board == 1) { echo "(W)"; } elseif($car->type_name->car_board == 2) { echo "(Y)"; } ?></td>
												<td class="vertical-middle">{{$car->brand_name->brand}}</td>
												<td class="vertical-middle">{{$car->model_name->model}}</td>
                                         
												
												<td class="vertical-middle">
                                            @if($car->isfranchise ==1)
                                           @foreach($franchise as $fra)
                                           @if($fra->id == $car->franchise_id) {{$fra->company_name}} @endif
                                           @endforeach
                                           @else 
                                           Go
                                            @endif
                                        </td>
												<td class="vertical-middle">
												<a href="{{ url('/taxi/') }}/{{$car->id}}/view" title="View"><i class="fa fa-eye fa-2x"></i></i></a>
												@if(Session::get('user_role')  ==1)<a href="{{ url('/taxi/') }}/{{$car->id}}/edit" title="Edit"><i class="fa fa-edit fa-2x"></i></a>
												<a href="#"  title="Block" onclick="block_list({{ $car->id}},'taxi/ajax_nonactive_block_taxi','vehicle ?','3');"><i class="fa fa-close fa-2x"></i></a>
												&nbsp;<a href="#" data-toggle="tooltip" title="Delete"
                                                         onclick="delete1({{ $car->id}},'delete_taxi','Vehicle');"><i
                                                            class="fa fa-trash fa-2x"></i></a>
                         @endif
												</td>
												
												</tr>
										@endforeach	
										</tbody>
									</table>
                                  
                                    <p class="text-light margin-bottom-30"></p>
                                    
                                   @if(Session::get('user_role')  ==1) 
                                   <input type="hidden" name="ver_block" value="1">
                                    <div class="form-group ">
									<label for="" class="col-sm-12 control-label no-padding">Change Status</label>
									<div class="col-sm-2 no-padding">
									  <select class="form-control">
                                        <option>Blocked</option>
                                      </select>
									</div>
									<div class="col-sm-2 ">
									<button class="btn btn-danger" type="submit">Change</button>
								  </div>
								  </div>
								  @endif
								    </form>
									</div>
                                </div><!-- /.tab-content -->
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
