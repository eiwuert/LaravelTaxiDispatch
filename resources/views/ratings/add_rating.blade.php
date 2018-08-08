@extends('layout.master');

@section('title')

AddRating - GO Cab

@endsection

@section('content')
<div class="rightside bg-grey-100">
			<!-- BEGIN PAGE HEADING -->
            <div class="page-head">
				<h1 class="page-title">{{ trans('config.lblr_add_rating') }}</h1>
				<!-- BEGIN BREADCRUMB -->
				
				<!-- END BREADCRUMB -->
			</div>
			<!-- END PAGE HEADING -->

            <div class="container-fluid">
				<div class="row">
					<div class="col-lg-12">
                           <div class="panel">
                            <div class="panel-title bg-amber-200">
								<div class="panel-head">{{ trans('config.lblr_rating_info') }}</div>
							</div>
                            <div class="panel-body">
								<form class="form-horizontal" action="" role="form" method="post">
									 {{ csrf_field() }}
								  <div class="form-group {{ $errors->has('rating_id')? 'has-error':'' }}">
									<label for="" class="col-sm-2 control-label">{{ trans('config.lblr_rating_id') }}</label>
									<div class="col-sm-4">
											<select class="form-control" id="rating_id" name="rating_id">
												<option value="1" <?php if(old('rating_id')==1){ echo "selected=selected";} ?>>1</option>
												<option value="2" <?php if(old('rating_id')==3){ echo "selected=selected";} ?>>2</option>
												<option value="3" <?php if(old('rating_id')==3){ echo "selected=selected";} ?>>3</option>
												<option value="4" <?php if(old('rating_id')==4){ echo "selected=selected";} ?>>4</option>
												<option value="5" <?php if(old('rating_id')==5){ echo "selected=selected";} ?>>5</option>
											</select>
									 
									  {!! $errors->first('rating_id', '<span class="help-block">:message</span>') !!}
									</div>
								  </div>
								  
								   <div class="form-group {{ $errors->has('rating_reason')? 'has-error':'' }}">
									<label for="" class="col-sm-2 control-label">{{ trans('config.lblr_rating_reason') }}</label>
									<div class="col-sm-4">
									  <input type="text" maxlength="40" class="form-control" id="rating_reason" name="rating_reason" value ="{{old('rating_reason')}}" placeholder="" >
									  {!! $errors->first('rating_reason', '<span class="help-block">:message</span>') !!}
									</div>
								  </div>
								  
								  
								  <div class="form-group">
									<div class="col-sm-offset-2 col-sm-4 margin-top-10">
									
										<button type="button" class="btn btn-dark bg-black color-white" onclick="window.location.href='{{ url('/manage_rating') }}';">Back</button>
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
