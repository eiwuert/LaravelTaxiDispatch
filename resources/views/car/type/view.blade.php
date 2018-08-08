@extends('layout.master');

@section('title')

View Vehicle Type - Go Cabs

@endsection

@section('content')

<div class="rightside bg-grey-100">
<!-- BEGIN PAGE HEADING -->
	<div class="page-head">
	<h1 class="page-title">View vehicle type</h1>
	<!-- BEGIN BREADCRUMB -->
		<a href="{{url("/")}}/type" class="btn btn-dark bg-black color-white pull-right" >Back</a>
	<!-- END BREADCRUMB -->
	</div>
<!-- END PAGE HEADING -->
	
<div class="container-fluid">
<div class="row">
		<div class="col-lg-12">
			<div class="panel no-border">
				<div class="panel-title bg-amber-200">
				<div class="panel-head">view vehicle details</div>
				</div>
				<div class="panel-body no-padding-top bg-white">
					<p class="text-light margin-bottom-30"></p>
					<form id="change-type-status" action="#" method="POST">
						<table class="table table-bordered display" >

							<tbody>

							<tr>
							<td class="vertical-middle">Vehicle Category</td>
							<td class="vertical-middle">
							@foreach($ride_category as $rd)
							@if($rd->id == $list->ride_category)
							{{$rd->ride_category}}
							@endif
							@endforeach
							</td>
							</tr>

							<tr>
							<td class="vertical-middle">Vehicle Type</td>
							<td class="vertical-middle">{{$list->car_type}} 
							@if($list->car_board == 1) (W) @endif
							@if($list->car_board == 2) (Y) @endif
							</td>
							</tr>

							<tr>
							<td class="vertical-middle">Vehicle Capacity</td>
							<td class="vertical-middle">{{$list->capacity}}</td>
							</tr>

							<tr>
							<td class="vertical-middle">Selected Car Icon</td>
							<td class="vertical-middle">
								<img src="{{url("/")}}/public{{$list->yellow_caricon}}">
							</td>
							</tr>

							<tr>
							<td class="vertical-middle">Unselected Car Icon</td>
							<td class="vertical-middle">
								<img src="{{url("/")}}/public{{$list->grey_caricon}}">
							</td>
							</tr>

							<!-- <tr>
							<td class="vertical-middle">Black Car Icon</td>
							<td class="vertical-middle">
								<img src="{{url("/")}}/public{{$list->black_caricon}}">
							</td>
							</tr> -->
							
							<tr>
							<td class="vertical-middle">Company's Share</td>
							<td class="vertical-middle">{{$list->companydriver_share}} %</td>
							</tr>

							<tr>
							<td class="vertical-middle">Attached Vehicle share</td>
							<td class="vertical-middle">{{$list->attacheddriver_share}} %</td>
							</tr>

							<tr>
							<td class="vertical-middle">Franchise's Share</td>
							<td class="vertical-middle">{{$list->franchise_share}} %</td>
							</tr>

							</tbody>
						</table>
					
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
</div>
</div>

@endsection