
@extends('layout.master');

@section('title')

View Driver Details - Wrydes

@endsection

@section('content')
<div class="rightside bg-grey-100">
			<!-- BEGIN PAGE HEADING -->
            <div class="page-head">
				<h1 class="page-title">View Driver Details</h1>
				<!-- BEGIN BREADCRUMB -->
				<a href="{{url("/")}}/manage_driver" class="btn btn-dark bg-black color-white pull-right">Back</a>
				<!-- END BREADCRUMB -->
			</div>
			<!-- END PAGE HEADING -->

            <div class="container-fluid">
				<div class="row">
                        <div class="col-lg-12">
							<div class="panel no-border">
                            	<div class="panel-title bg-amber-200">
								<div class="panel-head">Driver Details</div>
								</div>
                                <div class="panel-body no-padding-top bg-white">
									<p class="text-light margin-bottom-30"></p>
											<table class="table table-bordered ">
										<thead>
											<tr>
												<th class="vertical-middle">Name</th>
												<th class="vertical-middle">Value </th>
												
											</tr>
										</thead>
										<tbody>
										@foreach($datas as $data)
											<tr> 
												
												<td class="vertical-middle">FirstName</td>
												<td class="vertical-middle">{{$data->firstname}}</td>
												
											</tr>
											<tr> 
												
												<td class="vertical-middle">LastName</td>
									<td class="vertical-middle">{{$data->lastname}}</td>
												
											</tr>

											@if($data->isfranchise ==1)
        @foreach($franchise as $fra)
            @if($fra->id == $data->franchise_id) 
                @php
                $is = $fra->company_name
                @endphp
            @endif
        @endforeach
    @else 
        @php
        $is = 'Go';
        @endphp
    @endif

											<tr> 
												
												<td class="vertical-middle">Franchise</td>
								<td class="vertical-middle">{{$is}}</td>
												
											</tr>

											<tr> 
												
												<td class="vertical-middle">Email</td>
								<td class="vertical-middle">{{$data->email}}</td>
												
											</tr>
											
											@php
											$key = hash('sha256', 'wrydes');
											$iv = substr(hash('sha256', 'dispatch'), 0, 16);
											$output1 = openssl_decrypt(base64_decode($data->password), "AES-256-CBC", $key, 0, $iv);
											@endphp
											<tr> 
												
												<td class="vertical-middle">Password</td>
								<td class="vertical-middle">{{$output1}}</td>
												
											</tr>

											<tr> 
												
												<td class="vertical-middle">Driver ID</td>
								<td class="vertical-middle">{{$data->driver_id}}</td>
												
											</tr>
											 
											 <tr> 
												
												<td class="vertical-middle">Driver Photo</td>
												<td class="vertical-middle">
												<img height="350" width="350" src="{{url("/")}}/public{{$data->profile_photo}}"></td>
												
											</tr>

											<tr> 
												
												<td class="vertical-middle">Gender</td>
												<td class="vertical-middle">{{$data->gender}}</td>
												
											</tr>

											<tr> 
												
												<td class="vertical-middle">Date of Birth</td>
												<td class="vertical-middle">{{$data->dob}}</td>
												
											</tr>

											<tr> 
												
												<td class="vertical-middle">Mobile Number</td>
												<td class="vertical-middle">{{$data->mobile}}</td>
												
											</tr>

											<tr> 
												
												<td class="vertical-middle">Address</td>
												<td class="vertical-middle">{{$data->address}}</td>
												
											</tr>

											<tr> 
												
												<td class="vertical-middle">Country</td>
												<td class="vertical-middle">{{$data->country_name->name}}</td>
												
											</tr>

											<tr> 
												
												<td class="vertical-middle">State</td>
												<td class="vertical-middle">{{$data->state_name->name}}</td>
												
											</tr>

											

											<tr> 
												
												<td class="vertical-middle">License ID</td>
												<td class="vertical-middle">{{$data->licenseid}}</td>
												
											</tr>

											<tr> 
												
												<td class="vertical-middle">License Image</td>
												<td class="vertical-middle">
												<img height="350" width="350" src="{{url("/")}}/public{{$data->license}}"></td>
												
											</tr>
										</tbody>
										@endforeach
									</table>
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