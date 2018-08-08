@extends('layout.master');

@section('title')

Setting - Go Cabs

@endsection

@section('content')
        <div class="rightside bg-grey-100">
			<!-- BEGIN PAGE HEADING -->
            <div class="page-head">
				<h1 class="page-title">Manage Settings</h1>
				<!-- BEGIN BREADCRUMB -->
				<a href="add_fare"><button class="btn btn-dark bg-red-600 color-white pull-right">Add Fare Details</button></a>
				<!-- END BREADCRUMB -->
			</div>
			<!-- END PAGE HEADING -->

            <div class="container-fluid">
				<div class="row">
                        <div class="col-lg-12">
							<div class="panel no-border">
                            	<div class="panel-title bg-amber-200">
								<div class="panel-head">Manage Settings Details</div>
								</div>
                                <div class="panel-body no-padding-top bg-white">
									<p class="text-light margin-bottom-30"></p>
									<form class="form-horizontal" role="form" method="post">
									  {{ csrf_field() }}
									  
								  <div class="form-group">
									<label for="" class="col-sm-3 control-label">Assign Driver Allocation</label>
									<div class="col-sm-4">
										<div class="radio radio-theme display-inline-block">
										 <input name="assign_driver_allocation" <?php if($driver_allocation->ad_allocation ==1) {echo "checked=checked";} ?>id="optionsRadios1" checked="checked" type="radio" value=1> 
										 <label for="optionsRadios1">Zone</label>
                                         <input name="assign_driver_allocation" <?php if($driver_allocation->ad_allocation ==2) {echo "checked=checked";} ?> id="optionsRadios2" type="radio"  value=2>
										<label for="optionsRadios2">Nearby</label>
										<input name="assign_driver_allocation" <?php if($driver_allocation->ad_allocation ==3) {echo "checked=checked";} ?> id="optionsRadios3" type="radio"  value=3>
										<label for="optionsRadios3">Zone + Nearby</label>
										</div>
									</div>
								  </div>
								  
								  <p class="text-light margin-bottom-20"></p>
								  
								  <div class="form-group">
									<div class="col-sm-offset-3 col-sm-4 margin-top-10">
                                      <button type="button" class="btn btn-dark bg-grey-400 color-black">Back</button>
									  <button type="submit" class="btn btn-dark bg-red-600 color-white">Save</button>
									</div>
								  </div>
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