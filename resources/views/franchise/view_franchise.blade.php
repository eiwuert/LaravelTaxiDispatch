@extends('layout.master');

@section('title')
View Franchise - Go Cabs 
@endsection

@section('content')
 <div class="rightside bg-grey-100">
			<!-- BEGIN PAGE HEADING -->
            <div class="page-head">
				<h1 class="page-title">View Franchise Details</h1>
				<!-- BEGIN BREADCRUMB -->
			
				 <a href="{{ url('/manage_franchise') }}" class="btn btn-dark bg-black color-white pull-right">Back</a>
				<!-- END BREADCRUMB -->
			</div>
			<!-- END PAGE HEADING -->
		
            <div class="container-fluid">
				<div class="row">
					<div class="col-lg-12">
					
                           <div class="panel">
                            <div class="panel-title bg-amber-200">
								<div class="panel-head">Franchise Information</div>
							</div>
							<div class="panel-body">
							<div class="table-responsive"> 
						<table class="table table-bordered">

							<tr>
								<td>
									First Name
								</td>
								<td>
									{{$data->first_name}}
								</td>
							</tr>

							<tr>
								<td>
									Last Name
								</td>
								<td>
									{{$data->last_name}}
								</td>
							</tr>

							<tr>
								<td>
									Email
								</td>
								<td>
									{{$data->email}}
								</td>
							</tr>

							<tr>
								<td>
									Mobile Number
								</td>
								<td>
									{{$data->mobile}}
								</td>
							</tr>

							<tr>
								<td>
									Company Name 
								</td>
								<td>
									{{$data->company_name}}
								</td>
							</tr>

							<tr>
								<td>
									Company Address
								</td>
								<td>
									{{$data->company_address}}
								</td>
							</tr>

							<tr>
								<td>
									Service Tax Image
								</td>
								<td>
									<img src="{{url('/')}}/public{{$data->service_tax_image}}" height="200" width="300">
								</td>
							</tr>

							<tr>
								<td>
									Service Tax Number
								</td>
								<td>
									{{$data->service_tax_number}}
								</td>
							</tr>

							<tr>
								<td>
									Country
								</td>
								<td>
									{{$data->country_name->name}}
								</td>
							</tr>

							<tr>
								<td>
									State
								</td>
								<td>
									{{$data->state_name->name}}
								</td>
							</tr>

							<tr>
								<td>
									City
								</td>
								<td>
									{{$data->city_name->name}}
								</td>
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
