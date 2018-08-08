<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no"/>
	<meta content="" name="description"/>
	<meta content="" name="author"/>
	 <meta name="_token" content="{!! csrf_token() !!}" />
	<title>@yield('title')</title>
	
	<!-- BEGIN CORE FRAMEWORK -->
	<link href="{{ URL::asset("public/assets/plugins/bootstrap/css/bootstrap.min.css") }}" rel="stylesheet" />
	<link href="{{ URL::asset("public/assets/plugins/ionicons/css/ionicons.min.css") }}" rel="stylesheet" />
<link href="{{ URL::asset("public/assets/plugins/font-awesome/css/font-awesome.min.css") }}" rel="stylesheet" />
	<!-- END CORE FRAMEWORK -->
	
	<!-- BEGIN PLUGIN STYLES -->
	<link href="{{ URL::asset("public/assets/plugins/animate/animate.css") }}" rel="stylesheet" />
	<link href="{{ URL::asset("public/assets/plugins/bootstrap-slider/css/slider.css") }}" rel="stylesheet" />
	<link href="{{ URL::asset("public/assets/plugins/datatables/dataTables.bootstrap.css") }}" rel="stylesheet" />
	<!-- END PLUGIN STYLES -->
	
	<!-- BEGIN THEME STYLES -->
	<link href="{{ URL::asset("public/assets/css/material.css") }}" rel="stylesheet" />
	<link href="{{ URL::asset("public/assets/css/style.css") }}" rel="stylesheet" />
	<link href="{{ URL::asset("public/assets/css/plugins.css") }}" rel="stylesheet" />
	<link href="{{ URL::asset("public/assets/css/helpers.css") }}" rel="stylesheet" />
	<link href="{{ URL::asset("public/assets/css/responsive.css") }}" rel="stylesheet" />
	
	<!-- END THEME STYLES -->
    <!-- library for auto complete-->
	<link href="{{ URL::asset("public/css/jquery-customselect.css") }}" rel="stylesheet" />
	
    <link href="{{ URL::asset("public/assets/img/favicon.ico") }}" type="image/x-icon" rel="icon">
	
	<script src="{{ URL::asset("public/assets/plugins/jquery-1.11.1.min.js") }}" type="text/javascript"></script>
		<script src="{{asset('public/js/jquery-customselect.js')}}"></script>
    <!-- Time Picker js-->
		<script type="text/javascript" src="http://code.jquery.com/ui/1.11.0/jquery-ui.min.js"></script>
		<script type="text/javascript" src="http://localhost/date_pic/dist/jquery-ui-timepicker-addon.js"></script>
		<!-- Custom Css-->
		<link href="{{ URL::asset("public/css/custom.css") }}" rel="stylesheet" />
	
</head>
<!-- END HEAD -->
<!-- BEGIN BODY -->
<body class="fixed-leftside fixed-header">
	<!-- BEGIN HEADER -->
	@include('includes.header')
	
	<!-- END HEADER -->
		 
	<div class="wrapper">
		<!-- BEGIN LEFTSIDE -->
        @include('includes.left_menu')
        
		<!-- END LEFTSIDE -->

		<!-- BEGIN RIGHTSIDE -->
					
		@if (Session::has('message'))
		   <div class="alert alert-success">{{ Session::get('message') }}</div>
		@endif
		
		@yield('content')
	<!--	@if (count($errors) > 0)
		<div class="alert alert-danger">
			<ul>
				@foreach ($errors->all() as $error)
					<li>{{ $error }}</li>
				@endforeach
			</ul>
		</div>
	@endif -->
        
	<!-- END CONTENT -->
		</div>
		<!-- /.wrapper -->
	<!-- BEGIN JAVASCRIPTS -->
	
	<!-- BEGIN CORE PLUGINS -->

	<script src="{{ URL::asset("public/assets/plugins/bootstrap/js/bootstrap.min.js") }}" type="text/javascript"></script>
	<script src="{{ URL::asset("public/assets/plugins/bootstrap/js/holder.js") }}</script>

	<script src="{{ URL::asset("public/assets/plugins/pace/pace.min.js") }}" type="text/javascript"></script>
	<script src="{{ URL::asset("public/assets/plugins/slimScroll/jquery.slimscroll.min.js") }}" type="text/javascript"></script>
	<script src="{{ URL::asset("public/assets/js/core.js") }}" type="text/javascript"></script>
	<!-- END CORE PLUGINS -->
	
    <!-- datatables -->
	<script src="{{ URL::asset("public/assets/plugins/datatables/jquery.dataTables.js") }}" type="text/javascript"></script>
	<script src="{{ URL::asset("public/assets/plugins/datatables/dataTables.bootstrap.js") }}" type="text/javascript"></script>
	
	<!-- counter 
	<script src="<?php //echo env('jquery.countTo'); ?>" type="text/javascript"></script>-->
	
	<!-- maniac -->
	<script src="{{ URL::asset("public/assets/js/maniac.js") }}" type="text/javascript"></script>
	<script src="{{ URL::asset("public/assets/js/jquery.formError.js") }}" type="text/javascript"></script>
	 <!--ajax functionality js-->
	<script src="{{URL::asset("public/js/bootbox.min.js")}}"></script>	
	<script src="{{URL::asset("public/js/ajaxscript.js")}}"></script>

	
	
	

	<!-- dashboard -->
	<script type="text/javascript">
		maniac.loadcounter();
		maniac.loadprogress();
		maniac.loaddatatables();
	</script> 
    
    <script type="text/javascript">
    	$(document).ready(function() {
    $('table.display').DataTable();
} );


    </script>

	<!-- END JAVASCRIPTS -->
</body>
<!-- END BODY -->
</html>