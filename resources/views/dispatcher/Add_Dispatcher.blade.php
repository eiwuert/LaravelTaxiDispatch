@extends('layout.master');

@section('title')

AddDispatcher - Wrydes

@endsection

@section('content')
<div class="rightside bg-grey-100">
			<!-- BEGIN PAGE HEADING -->
            <div class="page-head">
				<h1 class="page-title">Add Dispatcher</h1>
				<!-- BEGIN BREADCRUMB -->
				
				<!-- END BREADCRUMB -->
			</div>
			<!-- END PAGE HEADING -->

            <div class="container-fluid">
				<div class="row">
					<div class="col-lg-12">
                           <div class="panel">
                            <div class="panel-title bg-amber-200">
								<div class="panel-head">Dispatcher Information</div>
							</div>
                            <div class="panel-body">
								<form class="form-horizontal" role="form">
								  <div class="form-group">
									<label for="" class="col-sm-2 control-label">First Name</label>
									<div class="col-sm-4">
									  <input type="text" class="form-control" id="" placeholder="" required>
									</div>
								  </div>
                                  <div class="form-group">
									<label for="" class="col-sm-2 control-label">Last Name</label>
									<div class="col-sm-4">
									  <input type="text" class="form-control" id="" placeholder="" required>
									</div>
								  </div>
                                  <div class="form-group">
									<label for="" class="col-sm-2 control-label">Email</label>
									<div class="col-sm-4">
									  <input type="email" class="form-control" id="" placeholder="" required>
									</div>
								  </div>
                                  <div class="form-group">
									<label for="" class="col-sm-2 control-label">Username</label>
									<div class="col-sm-4">
									  <input type="text" class="form-control" id="" placeholder="" required>
									</div>
								  </div>
                                  <div class="form-group">
									<label for="" class="col-sm-2 control-label">Password</label>
									<div class="col-sm-4">
									  <input type="password" class="form-control" id="" placeholder="" required>
									</div>
								  </div>
                                  <div class="form-group">
									<label for="" class="col-sm-2 control-label">Confirm Password</label>
									<div class="col-sm-4">
									  <input type="password" class="form-control" id="" placeholder="" required>
									</div>
								  </div>
                                  <div class="form-group">
									<label for="" class="col-sm-2 control-label">Mobile No.</label>
									<div class="col-sm-4">
									  <input type="tel" class="form-control" id="" placeholder="" required>
									</div>
								  </div>
                                  <div class="form-group">
									<label for="" class="col-sm-2 control-label">Address</label>
									<div class="col-sm-4">
									 <textarea rows="2" class="form-control"></textarea>
									</div>
								  </div>
                                  
                                  <div class="form-group">
									<label for="" class="col-sm-2 control-label">Country</label>
									<div class="col-sm-4">
									  <select class="form-control">
                                      <option>India</option>
                                      <option>USA</option>
                                      <option>Canada</option>
                                      <option>Australia</option>
                                      </select>
									</div>
								  </div>
                                  
                                  <div class="form-group">
									<label for="" class="col-sm-2 control-label">State</label>
									<div class="col-sm-4">
									  <select class="form-control">
                                      <option>TamilNadu</option>
                                      <option>Kerala</option>
                                      <option>Maharastra</option>
                                      <option>Andra Pradesh</option>
                                      </select>
									</div>
								  </div>
                                  
                                  <div class="form-group">
									<label for="" class="col-sm-2 control-label">City</label>
									<div class="col-sm-4">
									  <select class="form-control">
                                      <option>Coimbatore</option>
                                      <option>Madurai</option>
                                      <option>Chennai</option>
                                      <option>Trichy</option>
                                      </select>
									</div>
								  </div>
                                  
                                  
                                  <div class="form-group">
									<div class="col-sm-offset-2 col-sm-4 margin-top-10">
									  <button type="submit" class="btn btn-dark bg-grey-400 color-white">Back</button>
                                      <button type="submit" class="btn btn-dark bg-red-600 color-white">Add</button>
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
				<footer class="bg-white">
					<div class="pull-left">
						<span class="pull-left margin-right-15">&copy; 2016 Wrydes. All Rights Reserved.</span>
					</div>
				</footer>
				<!-- END FOOTER -->
            </div><!-- /.container-fluid -->
        </div>
@endsection