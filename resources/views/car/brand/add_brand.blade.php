@extends('layout.master');

@section('title')

AddBrand - Go Cabs

@endsection

@section('content')
<div class="rightside bg-grey-100">
			<!-- BEGIN PAGE HEADING -->
            <div class="page-head">
				<h1 class="page-title">Add Brand</h1>
				<!-- BEGIN BREADCRUMB -->
				
				<!-- END BREADCRUMB -->
			</div>
			<!-- END PAGE HEADING -->

            <div class="container-fluid">
				<div class="row">
					<div class="col-lg-12">
                           <div class="panel">
                            <div class="panel-title bg-amber-200">
								<div class="panel-head">Brand Type</div>
							</div>
                            <div class="panel-body">
								<form class="form-horizontal" action="" role="form" method="post">
									 {{ csrf_field() }}
								  <div class="form-group {{ $errors->has('brand_name')? 'has-error':'' }}">
									<label for="" class="col-sm-2 control-label">Brand Name</label>
									<div class="col-sm-4">
									  <input type="text" class="form-control" id="brand" name="brand_name" value ="{{old('brand_name')}}" maxlength="20" placeholder="" >
									  {!! $errors->first('brand_name', '<span class="help-block">:message</span>') !!}
									</div>
								  </div>
								  
								  
								  <div class="form-group">
									<div class="col-sm-offset-2 col-sm-4 margin-top-10">
									
										<button type="button" class="btn btn-dark bg-black color-white" onclick="window.location.href='{{ url('/brand') }}';">Back</button>
										  <button type="submit" class="btn btn-dark bg-red-600 color-white">Add</button>
										  <button type="reset" class="btn btn-dark bg-grey-400 color-black">Reset</button>
									  </div>
								  </div>
								  @if (session('error_status'))
										<div class="alert alert-danger">
											<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
											{{ session('error_status') }}
										</div>
									@endif
								</form>
								<div id="status"></div>
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