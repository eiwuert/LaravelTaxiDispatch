@extends('layout.master');

@section('title')

View Taxi - Go Cabs

@endsection

@section('content')
 <div class="rightside bg-grey-100">
			<!-- BEGIN PAGE HEADING -->
            <div class="page-head">
				<h1 class="page-title">View Offer Details 

				</h1>
				 <a href="{{url("/")}}/manage_offers" class="btn btn-dark bg-black color-white pull-right">Back</a>
				
				<!-- BEGIN BREADCRUMB -->
				
				<!-- END BREADCRUMB -->
			</div>
			<!-- END PAGE HEADING -->
			
            <div class="container-fluid">
				<div class="row">
					<div class="col-lg-12">
					
                           <div class="panel">
                            <div class="panel-title bg-amber-200">
								<div class="panel-head">Offer Information</div>
							</div>
							<div class="panel-body">
							<div class="table-responsive"> 
						<table class="table table-bordered" style="max-width: 50%;">
							<tr>
								<td style="width: 43%;"><label  class="view_label">Coupon Category</label></td>
								<td>
								@if($offers->coupon_basedon == 1) Customer Ride Count @endif
								@if($offers->coupon_basedon == 2) Customer Ride Value @endif
								@if($offers->coupon_basedon == 3) Vehicle Category @endif
								@if($offers->coupon_basedon == 4) Limited Users @endif
								@if($offers->coupon_basedon == 5) All Users @endif
								@if($offers->coupon_basedon == 6) Free Ride @endif</td>
							</tr>

							<tr>
								<td><label  class="view_label">
								@if($offers->coupon_basedon != 3)Coupon Category Value @endif
								@if($offers->coupon_basedon == 3) Coupon Vehicle Type @endif
								</label></td>
								<td>@if($offers->coupon_basedon == 3){{$offers->getcarname->car_type}} 
									  	@if($offers->getcarname->car_board == 1) (W) @endif
									  	@if($offers->getcarname->car_board == 2) (Y) @endif
								@endif
								@if($offers->coupon_basedon != 3){{$offers->coupon_typevalue}} @endif
								</td>
							</tr>

							<tr>
								<td><label  class="view_label">Coupon Code</label></td>
								<td>{{$offers->coupon_code}} </td>
							</tr>

							<tr>
								<td><label  class="view_label">Coupon Type</label></td>
								<td>@if($offers->coupon_type == 2)
                                        Offer
                                        @endif
                                        @if($offers->coupon_type == 1)
                                        Cash Back
                                        @endif

                                        </td>
							</tr>

							@if($offers->coupon_basedon == 3)
							<tr>
								<td><label  class="view_label">Vehicle Type</label></td>
								<td>{{$offers->getcarname->car_type}}
								@if($offers->getcarname->car_board == 1) (W) @endif
								@if($offers->getcarname->car_board == 2) (Y) @endif
								</td>
							</tr>
							@endif

							<tr>
								<td><label  class="view_label">Coupon Amount</label></td>
								<td>{{$offers->coupon_value}}</td>
							</tr>

							<tr>
								<td><label  class="view_label">Coupon Description</label></td>
								<td>{{$offers->coupon_desc}}</td>
							</tr>

							<tr>
								<td><label  class="view_label">Offer Usage Count</label></td>
								<td>{{$offers->usage_count}}</td>
							</tr>

							<?php 
                                        $fromdate = date("d-m-Y", strtotime($offers->valid_from)); 
                                        $todate = date("d-m-Y", strtotime($offers->valid_to)); 
                            ?>
							<tr>
								<td><label  class="view_label">Offer From</label></td>
								<td>{{$fromdate}}</td>
							</tr>

							<tr>
								<td><label  class="view_label">Offer Expiry Date</label></td>
								<td>{{$todate}}</td>
							</tr>

							
						
						</table>
					</div>
						
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