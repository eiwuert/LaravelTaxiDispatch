@extends('layout.master');

@section('title')

Reject Rides - Go Cabs

@endsection

@section('content')
<style>
.panel > .panel-body {
     overflow: scroll !important;
}
.dialogBorder{
    left: 100px !important;
}
</style>
 <div class="rightside bg-grey-100">
			<!-- BEGIN PAGE HEADING -->
            <div class="page-head">
				<h1 class="page-title">Rejected Ride Details</h1>
				<!-- BEGIN BREADCRUMB -->
				
				<!-- END BREADCRUMB -->
			</div>
			<!-- END PAGE HEADING -->

            <div class="container-fluid">
				<div class="row">
					<div class="col-lg-12">
                           <div class="panel">
                            <div class="panel-title bg-amber-200">
							<div class="panel-head">Report Information</div>
							</div>
                            <div class="panel-body">
       	
<iframe id = "myIframe"  onload = "setIframeHeight(this)"  height="100%" width="130%" src="http://{{$_SERVER['SERVER_NAME']}}:8080/goapp_viewer/frameset?__report={{getcwd()}}/report/rejected_rides.rptdesign"></iframe> 

						
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
