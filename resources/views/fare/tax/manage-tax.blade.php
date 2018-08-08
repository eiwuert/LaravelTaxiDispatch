@extends('layout.master');

@section('title')

Manage Tax - Go Cabs

@endsection

@section('content')
        <div class="rightside bg-grey-100">
			<!-- BEGIN PAGE HEADING -->
            <div class="page-head">
				<h1 class="page-title">Manage Tax Details</h1>
				<!-- BEGIN BREADCRUMB -->
				<a href="add-tax"><button class="btn btn-dark bg-red-600 color-white pull-right">Add Tax</button></a>
				<!-- END BREADCRUMB -->
			</div>
			<!-- END PAGE HEADING -->

            <div class="container-fluid">
				<div class="row">
                        <div class="col-lg-12">
							<div class="panel no-border">
                            	<div class="panel-title bg-amber-200">
								<div class="panel-head">Tax List</div>
								</div>
                                <div class="panel-body no-padding-top bg-white">
									<p class="text-light margin-bottom-30"></p>
									 <form id="change-tax-status" action="#" method="POST">
											<table class="table table-bordered display" id="">
										<thead>
											<tr>
												<!-- <th class="vertical-middle">Select</th> -->
												<th class="vertical-middle">Tax Name</th>
												<th class="vertical-middle">Percentage (%)</th>
												<th class="vertical-middle">Country</th>
												<!-- <th class="vertical-middle">State</th> -->
                                                <th class="vertical-middle">Status</th>
                                                <th class="vertical-middle">Action</th>
											</tr>
										</thead>
										<tbody>
										
										@foreach ($tax_list as $tax)
										<tr> 
												<!-- <td class="vertical-middle"><input type="checkbox" value="{{$tax->id}}"></td> -->
												<td class="vertical-middle">{{ $tax->tax_name}}</td>
												<td class="vertical-middle">{{ $tax->percentage}}</td>
												<td class="vertical-middle">{{ $tax->country_name->name}}</td>
												
												
                                               <td class="vertical-middle"><i class='{{ ($tax->status == 1) ? "fa fa-check-circle active" : "fa fa-times-circle inactive" }}' aria-hidden='true'></i></td>
												<td class="vertical-middle">
													<a href="{{ url('/edit-tax/') }}/{{$tax->id}}/edit" data-toggle="tooltip" title="Edit"><i class="fa fa-edit fa-2x"></i></a>
													@if($tax->status == 1)
													<a href="#" data-toggle="tooltip" title="Block" onclick="deactivate({{ $tax->id}},'ajax_deactive_tax','Tax Details');"><i class="fa fa-close fa-2x"></i></a>
													@else
													<a href="#" data-toggle="tooltip" title="Activate"  onclick="activate({{ $tax->id}},'ajax_activate_tax','Tax Details');"><i class="fa fa-check fa-2x"></i></a>
													@endif
													<!-- Delete tax -->
													<a href="#" data-toggle="tooltip" title="Delete" onclick="delete1({{ $tax->id}},'deletetax','Tax');"><i class="fa fa-trash fa-2x"></i></a>
													<!-- Delete tax end -->
													</td>
											</tr>
												@endforeach
											
										</tbody>
									</table>
									<table cellspacing="0" cellpadding="0" class="note ma_0 noti" width="100%">
						  <tbody>
							<tr>
							  <td class="">
							  <i class="fa fa-check-circle active" aria-hidden="true"></i><span> Active</span>
							  <i class="fa fa-times-circle inactive" aria-hidden="true"></i><span> In-Active</span></td>
							  
							</tr>
						  </tbody>
						</table>
						
						  <p class="text-light margin-bottom-30"></p>
                                    
                                    
                                    
                                    <!-- <div class="form-group ">
									<label for="Change" class="col-sm-1 control-label no-padding">Change</label>
									<div class="col-sm-2 no-padding">
									  <select class="form-control" name="status" id="ch_status">
                                        <option value="1">Activate</option>
										<option value="0">In-Active</option>
                                      </select>
									</div>
									<div class="col-sm-2 ">
									<button class="btn btn-danger" @if(count($tax_list)==0) {{'disabled=disabled'}} @endif type="submit">Change</button>
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
