@extends('layout.master');

@section('title')

Edit Tax - Go Cabs

@endsection

@section('content')
 <div class="rightside bg-grey-100">
			<!-- BEGIN PAGE HEADING -->
            <div class="page-head">
				<h1 class="page-title">Edit Tax Details</h1>
				<!-- BEGIN BREADCRUMB -->
				
				<!-- END BREADCRUMB -->
			</div>
			<!-- END PAGE HEADING -->
		
            <div class="container-fluid">
				<div class="row">
					<div class="col-lg-12">
                           <div class="panel">
                            <div class="panel-title bg-amber-200">
								<div class="panel-head">Tax Information</div>
							</div>
							<div class="panel-body">
							
						
								<form class="form-horizontal" role="form" method="post">
								     {{ csrf_field() }}
									 <input type="hidden" name="tax_id" value="{{$taxdetails->id}}" />
								<div class="form-group {{ $errors->has('tax_name')? 'has-error':'' }}">
									<label for="" class="col-sm-2 control-label">Tax Name</label>
									<div class="col-sm-4">
									  <input type="text" maxlength="30" class="form-control" id="tax_name" value ="{{old('tax_name',$taxdetails->tax_name)}}" name="tax_name" placeholder="" required > 
									{!! $errors->first('tax_name', '<span class="help-block">:message</span>') !!}
									</div>
									
									
								</div>	
                                 <div class="form-group {{ $errors->has('tax_percentage')? 'has-error':'' }}">
									<label for="" class="col-sm-2 control-label">Tax (%)</label>
									<div class="col-sm-4">
									  <input type="text" class="form-control" id="tax_percentage" value ="{{old('tax_percentage',$taxdetails->percentage)}}" maxlength="3" name="tax_percentage" placeholder="" required > 
									{!! $errors->first('tax_percentage', '<span class="help-block">:message</span>') !!}
									</div>
									<div class="col-sm-2"><label>%</label></div> 	
									
								</div>	 
                                    <div class="form-group  {{ $errors->has('country')? 'has-error':'' }}">
									<label for="" class="col-sm-2 control-label">Country</label>
									<div class="col-sm-4">
									  <select class="form-control" name="country" id="country">
                                        @foreach ($country_list as $country)
									  <option    value="{{$country->id}}" <?php if(old('country',$taxdetails->country) ==$country->id) { echo "selected=selected"; } ?>>{{$country->name}}</option>
									    @endforeach
                                      </select>
                                      	{!! $errors->first('country', '<span class="help-block">:message</span>') !!}
                                     </div>
								  </div>
                                  
                                  <div class="form-group  {{ $errors->has('state')? 'has-error':'' }}">
									<label for="" class="col-sm-2 control-label">State</label>
									<div class="col-sm-4">
                                    <input type="hidden" value="{{old('state',$taxdetails->state)}}" id="temp_state"/>
									  <select class="form-control "  name="state" id="state">
                                     
                                      </select>
                                      	{!! $errors->first('state', '<span class="help-block">:message</span>') !!}
									</div>
								  </div>
                                
                                  	
								  @if (session('error_status'))
										<div class="alert alert-danger">
											<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
											{{ session('error_status') }}
										</div>
									@endif
									
								  <div class="form-group">
									<div class="col-sm-offset-2 col-sm-4 margin-top-10">
									<button type="button" class="btn btn-dark bg-black color-white" onclick="window.location.href='{{ url('/manage-tax') }}';">Back</button>
									  <button type="submit" class="btn btn-dark bg-red-600 color-white">Update</button>
									  	<button type="reset" class="btn btn-dark bg-grey-400 color-black">Reset</button>
									</div>
								  </div>
								</form>
								
                            </div>
						</div>
					</div><!-- /.col -->
				</div><!-- /.row -->
				
				<!-- /.row -->
				
				<!-- /.row -->
		

				<!-- ============= example -->

			<!-- date AND Time picker implementation -->

		<script type="text/javascript">
			

$('.number').keypress(function(event) {
  if ((event.which != 46 || $(this).val().indexOf('.') != -1) && (event.which < 48 || event.which > 57)) {
    event.preventDefault();
  }
});
</script>
			
					<!-- BEGIN FOOTER -->
				@include('includes.footer')
				<!-- END FOOTER -->
            </div><!-- /.container-fluid -->
        </div>
@endsection