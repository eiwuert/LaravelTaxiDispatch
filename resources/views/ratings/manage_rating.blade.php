@extends('layout.master');

@section('title')

Manage Rating - GO Cab

@endsection

@section('content')
<div class="rightside bg-grey-100">
			<!-- BEGIN PAGE HEADING -->
            <div class="page-head">
				<h1 class="page-title">{{ trans('config.lblr_heading') }}</h1>
				<!-- BEGIN BREADCRUMB -->
				<a href="{{url("/")}}/rating/add"><button class="btn btn-dark bg-red-600 color-white pull-right" >{{ trans('config.lblr_add_rating') }}</button></a>
				<!-- END BREADCRUMB -->
			</div>
			<!-- END PAGE HEADING -->

            <div class="container-fluid">
				<div class="row">
                        <div class="col-lg-12">
							<div class="panel no-border">
                            	<div class="panel-title bg-amber-200">
								<div class="panel-head">{{ trans('config.lblr_manage_list') }}</div>
								</div>
                                <div class="panel-body no-padding-top bg-white">
									<p class="text-light margin-bottom-30"></p>
										 <form id="change-brand-status" action="#" method="POST">
											<table class="table table-bordered display" >
										<thead>
											<tr>
												<th class="vertical-middle">{{ trans('config.lblr_rating') }}</th>
												<th class="vertical-middle">{{ trans('config.lblr_rating_reason') }}</th>
												<th class="vertical-middle">{{ trans('config.lbl_action') }}</th>
											</tr>
										</thead>
										<tbody>
										@foreach($rating_list as $rating)
											<tr> 
											
												<td class="vertical-middle">{{$rating->rating}}</td>
												<td class="vertical-middle">{{$rating->reason}}</td>
										
												<td class="vertical-middle">
													<!--<a href="{{ url('/rating/') }}/{{$rating->id}}/edit"><i class="fa fa-close fa-2x"></i>-->
													<a data-toggle="tooltip" href="javascript:void(0);" onclick="delete1({{ $rating->id}},'rating/delete','rating reason');" title="Delete" data-original-title="Delete"><i class="fa fa-trash fa-2x"></i>
													
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
