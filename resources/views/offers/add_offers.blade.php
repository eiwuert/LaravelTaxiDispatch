@extends('layout.master');

@section('title')

Add Offers - GO Cabs

@endsection

@section('content')
<div class="rightside bg-grey-100">
			<!-- BEGIN PAGE HEADING -->
            <div class="page-head">
				<h1 class="page-title">{{ trans('config.lbladd_offer') }}</h1>
				<!-- BEGIN BREADCRUMB -->
				
				<!-- END BREADCRUMB -->
			</div>
			<!-- END PAGE HEADING -->

            <div class="container-fluid">
				<div class="row">
					<div class="col-lg-12">

						@if (Session::has('error_offers'))
						<div class="alert alert-danger"><strong>{{ Session::get('error_offers') }}</strong></div>

						@endif

						<div class="alert alert-success text-center" id="info">Select a coupon category</div>

                           <div class="panel">
                            <div class="panel-title bg-grey-300">
								<div class="panel-head">{{ trans('config.lbl_offer_manage') }}</div>
							</div>


                            <div class="panel-body">


                            


								<form class="form-horizontal" role="form" method="post" >
								<input type="hidden" name="_token" value="{{ csrf_token() }}">
								  <div class="form-group">
									<label for="" class="col-sm-2 control-label">Coupon Category</label>
									<div class="col-sm-10">
										<div class="radio radio-theme display-inline-block">
										 <input name="CouponCategory" id="optionsRadios1" selected=selected value="1" type="radio">
										 <label for="optionsRadios1">Customer Ride Count</label>
                                         <input name="CouponCategory" id="optionsRadios2" value="2" type="radio">
										<label for="optionsRadios2">Customer Ride Value</label>
										<input name="CouponCategory" id="optionsRadios3" value="3" type="radio">
										<label for="optionsRadios3">Vehicle Category</label><br><br>
										<input name="CouponCategory" id="optionsRadios4" value="4" type="radio">
										<label for="optionsRadios4">Validation User Count</label>
										<input name="CouponCategory" id="optionsRadios5" value="5" type="radio">
										<label for="optionsRadios5">All Users</label>					
										<input name="CouponCategory" id="optionsRadios6" value="6" type="radio">
										<label for="optionsRadios6">Free Ride</label>				
										</div>
									</div>
								  </div>

								  <div id="textcontent" >
                                  <div class="form-group">
									<label for="" class="col-sm-2 control-label">Enter Value</label>
									<div class="col-sm-4">
									  <input type="text" class="form-control" maxlength="6" name="Value" id="Value" placeholder="" >
									</div>
								  </div></div>

								  <div id="selectcontent" >

								  <div class="form-group">
									<label for="" class="col-sm-2 control-label">Select Vehicle Category</label>
									<div class="col-sm-4">
									 <select class="form-control" name="VehicleCategory" id="VehicleCategory">
							          <option value="">--Select Type--</option>
							            @foreach ($ride_type as $ride)
									  <option value="{{$ride->id}}" >{{$ride->ride_category}}</option>
									    @endforeach
							          </select>
									</div>
								  </div>

                                  <div class="form-group">
									<label for="" class="col-sm-2 control-label">Select Vehicle Type</label>
									<div class="col-sm-4">
									 <select class="form-control" name="VehicleType" id="VehicleType">
							          <option value="">--Select Car--</option>
							           
							          </select>
									</div>
								  </div>

								  </div>

                                  <div class="form-group">
									<label for="" class="col-sm-2 control-label">Coupon Code</label>
									<div class="col-sm-4">
									  <input type="text" class="form-control" maxlength="10" style="text-transform:uppercase" name="CouponCode" id="" placeholder="" >
									</div>
								  </div>
                                  <div class="form-group" id="ctype">
									<label for="" class="col-sm-2 control-label">Coupon Type</label>
									<div class="col-sm-4">
									  <select name="CouponType" class="form-control">
                                      <option value="1">Cashback</option>
                                      <option value="2">Offers (%)</option>
                                      </select>
									</div>
								  </div>
								  <div class="form-group" id="cvalue">
									<label for="" class="col-sm-2 control-label">Enter Amount(Rs)</label>
									<div class="col-sm-4">
									  <input type="text" name="CouponValue" maxlength="6" class="form-control symval" id="CouponValue" placeholder="" >
									</div>
								  </div>
                                  <div class="form-group">
									<label for="" class="col-sm-2 control-label">Coupon Description</label>
									<div class="col-sm-4">
									  <textarea name="CouponDescription" style="text-transform:uppercase" class="form-control " rows="3"></textarea>
									</div>
								  </div>
                                  <div class="form-group">
									<label for="" class="col-sm-2 control-label">Coupon Usage Count</label>
									<div class="col-sm-4">
									  <input type="text" maxlength="4" name="CouponUsageCount" class="form-control symval" id="des" placeholder="">
									  <p>*How many times a single user can use the  coupon code</p>
									</div>
								  </div>
                                  <div class="form-group">
									<label for="" class="col-sm-2 control-label">Valid From</label>
									<div class="col-sm-4">
									  <input type="text" name="ValidFrom" class="form-control datepicker symval" id="datepicker" placeholder="" >
									</div>
								  </div>
                                  <div class="form-group">
									<label for="" class="col-sm-2 control-label">Valid To</label>
									<div class="col-sm-4">
									  <input type="text" name="ValidTo" class="form-control datepicker symval" id="datepicker1" placeholder="" >
									</div>
								  </div>
                                  
								  <div class="form-group">
									<div class="col-sm-offset-2 col-sm-4 margin-top-10">
									  <button type="reset" class="btn btn-dark bg-grey-400 color-black">Reset</button>
									  <button type="submit" id="buttonsubmit" class="btn btn-dark bg-red-600 color-white">Add Coupon</button>
									</div>
								  </div>
								</form>
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
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
	   <script type="text/javascript">
		/*	jQuery(function() {
				jQuery("#taxi-brand").customselect();
				jQuery("#taxi-type").customselect();
				jQuery("#taxi-model").customselect();
                jQuery("#country").customselect();
                 jQuery("#state").customselect();
                  jQuery("#city").customselect();
			});*/
		
			$(document).ready(function(){

				$("#buttonsubmit").click(function(e){
					var isvalid = true;
            	$(".symval").each(function () {
	                if ($.trim($(this).val()) == '') {
	                    isValid = false;
	                    $(this).css({
	                        "border": "1px solid red",
	                        "background": ""
	                    });
	                    if (isValid == false)
	                        e.preventDefault();
	                }
	                else {
	                    $(this).css({
	                        "border": "2px solid green",
	                        "background": ""
	                    });
	                    return true;
	                }
            	});

				});
				$('#info').hide();
				

				$('#Value').keypress(function (e) {
					var specialKeys = new Array();
						 specialKeys.push(8); //Backspace
						 specialKeys.push(9); //Tab
						 
					 var keyCode = e.which ? e.which : e.keyCode
					 console.log(keyCode);
					 var ret = ((keyCode >= 48 && keyCode <= 57) ||  specialKeys.indexOf(keyCode) != -1);
					 return ret;
			    
			});
				$('.symval').keypress(function (e) {
					var specialKeys = new Array();
						 specialKeys.push(8); //Backspace
						 specialKeys.push(9); //Tab
						 var i = 65;
						 for(i=65;i<91;i++){
						 	specialKeys.push(i);
						 }
					 var keyCode = e.which ? e.which : e.keyCode
					 var ret = ((keyCode >= 48 && keyCode <= 57) || specialKeys.indexOf(keyCode) != -1);
					 return ret;
			    
			});

				$('#CouponValue').keypress(function (e) {
			    var specialKeys = new Array();
						 specialKeys.push(8); //Backspace
						 specialKeys.push(9); //Tab
					 var keyCode = e.which ? e.which : e.keyCode
					 var ret = ((keyCode >= 48 && keyCode <= 57) || specialKeys.indexOf(keyCode) != -1);
					 return ret;
			});


				$('#selectcontent').hide();
				
			});
     		$('#optionsRadios3').click(function()
     		{
				$('#textcontent').hide();
				$('#selectcontent').show();
				document.getElementById("Value").value = 1;
				$('#info').empty();
				$('#info').html('Coupon Valid based on the selected vehicle category');
				$('#info').show();
				$("#des").removeAttr("disabled");

			});
			$('#optionsRadios1').click(function()
     		{
     			document.getElementById("Value").value = "";
				$('#textcontent').show();
				$('#selectcontent').hide();
				$('#info').empty();
				$('#info').html('Coupon based on customers total number of rides ');
				$('#info').show();
				$("#des").removeAttr("disabled");
			});


			$('#optionsRadios2').click(function()
     		{
     			document.getElementById("Value").value = "";
				$('#textcontent').show();
				$('#selectcontent').hide();
				$('#info').empty();
				$('#info').html('Coupon based on customer total ride amount');
				$('#info').show();
				$("#des").removeAttr("disabled");
			});


			$('#optionsRadios4').click(function()
     		{
     			document.getElementById("Value").value = "";
     			document.getElementById("des").value = 1;
     			$("#des").attr('disabled', 'disabled');
				$('#textcontent').show();
				$('#selectcontent').hide();
				$('#info').empty();
				$('#info').html('User Count for Limiting the total number of coupon users');
				$('#info').show();
			});

			$('#optionsRadios5').click(function()
     		{
     			document.getElementById("Value").value = 1;
     			$("#des").removeAttr("disabled");
				$('#textcontent').hide();
				$('#selectcontent').hide();
				$('#info').empty();
				$('#info').html('Coupon Valid for all users');
				$('#info').show();
				$('#ctype').show();
				$('#cvalue').show();
			});

			$('#optionsRadios6').click(function()
     		{
     			document.getElementById("CouponValue").value = 1;
     			$('#cvalue').hide();
     			document.getElementById("des").value = 1;
     			document.getElementById("Value").value = 1;
				$('#textcontent').hide();
				$("#des").attr('disabled', 'disabled');
				$('#ctype').hide();
				$('#selectcontent').hide();
				$('#info').empty();
				$('#info').html('Free Ride');
				$('#info').show();
			});


		
		</script>	

@endsection