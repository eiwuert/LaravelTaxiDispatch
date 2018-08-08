@extends('layout.master');

@section('title')

Manage Dispatcher - Wrydes

@endsection

@section('content')
<div class="rightside bg-grey-100">
			<!-- BEGIN PAGE HEADING -->
            <div class="page-head">
				<h1 class="page-title">Manage Dispatcher</h1>
				<!-- BEGIN BREADCRUMB -->
				<a href="add_dispatcher"><button class="btn btn-dark bg-red-600 color-white pull-right">Add Dispatcher</button></a>
				<!-- END BREADCRUMB -->
			</div>
			<!-- END PAGE HEADING -->

            <div class="container-fluid">
				<div class="row">
                        <div class="col-lg-12">
							<div class="panel no-border">
                            	<div class="panel-title bg-amber-200">
								<div class="panel-head">Fare List</div>
								</div>
                                <div class="panel-body no-padding-top bg-white">
									<p class="text-light margin-bottom-30"></p>
											<table class="table table-bordered display">
										<thead>
											<tr>
												<th class="vertical-middle">Select</th>
												<th class="vertical-middle">Dispatcher ID</th>
												<th class="vertical-middle">Name</th>
                                                <th class="vertical-middle">Mobile number</th>
                                                <th class="vertical-middle">Username</th>
                                                <th class="vertical-middle">Password</th>
                                                <th class="vertical-middle">City</th>
                                                <th class="vertical-middle">Action</th>
											</tr>
										</thead>
										<tbody>
											<tr> 
												<td class="vertical-middle"><div class="checkbox checkbox-theme no-margin"><input id="checkbox13" type="checkbox"><label for="checkbox13" class="no-padding"></label></div></td>
												<td class="vertical-middle">AD147201</td>
												<td class="vertical-middle">Mark</td>
												<td class="vertical-middle">87012 54740</td>
                                                <td class="vertical-middle">mark123</td>
                                                <td class="vertical-middle">mark123456</td>
                                                <td class="vertical-middle">Coimbatore</td>
                                                <td class="vertical-middle"><a href=""><i class="fa fa-edit fa-2x"></i></a>&nbsp;
                                                <a href=""><i class="fa fa-eye fa-2x"></i></a>&nbsp;
                                                <a href=""><i class="fa fa-trash fa-2x"></i></a>
                                                </td>
											</tr>
											<tr> 
												<td class="vertical-middle"><div class="checkbox checkbox-theme no-margin"><input id="checkbox13" type="checkbox"><label for="checkbox13" class="no-padding"></label></div></td>
												<td class="vertical-middle">AD147201</td>
												<td class="vertical-middle">Mark</td>
												<td class="vertical-middle">87012 54740</td>
                                                <td class="vertical-middle">mark123</td>
                                                <td class="vertical-middle">mark123456</td>
                                                <td class="vertical-middle">Coimbatore</td>
                                                <td class="vertical-middle"><a href=""><i class="fa fa-edit fa-2x"></i></a>&nbsp;
                                                <a href=""><i class="fa fa-eye fa-2x"></i></a>&nbsp;
                                                <a href=""><i class="fa fa-trash fa-2x"></i></a>
                                                </td>
											</tr>
											<tr> 
												<td class="vertical-middle"><div class="checkbox checkbox-theme no-margin"><input id="checkbox13" type="checkbox"><label for="checkbox13" class="no-padding"></label></div></td>
												<td class="vertical-middle">AD147201</td>
												<td class="vertical-middle">Mark</td>
												<td class="vertical-middle">87012 54740</td>
                                                <td class="vertical-middle">mark123</td>
                                                <td class="vertical-middle">mark123456</td>
                                                <td class="vertical-middle">Coimbatore</td>
                                                <td class="vertical-middle"><a href=""><i class="fa fa-edit fa-2x"></i></a>&nbsp;
                                                <a href=""><i class="fa fa-eye fa-2x"></i></a>&nbsp;
                                                <a href=""><i class="fa fa-trash fa-2x"></i></a>
                                                </td>
											</tr>
										</tbody>
									</table>
                            </div>
                        </div><!-- /.col -->
                    </div><!-- /. row -->
		    </div><!-- /.row -->
				
				<!-- /.row -->
				
				<!-- /.row -->
				
				<!-- BEGIN FOOTER -->
				<footer class="bg-white">
					<div class="pull-left">
						<span class="pull-left margin-right-15">&copy; 2016 WrydesDispatch. All Rights Reserved.</span>
					</div>
				</footer>
				<!-- END FOOTER -->
            </div><!-- /.container-fluid -->
        </div>
@endsection