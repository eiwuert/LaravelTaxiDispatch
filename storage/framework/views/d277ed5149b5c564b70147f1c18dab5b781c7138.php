<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no"/>
	<meta content="" name="description"/>
	<meta content="" name="author"/>
	 <meta name="_token" content="<?php echo csrf_token(); ?>" />
	<title><?php echo $__env->yieldContent('title'); ?></title>
	
	<!-- BEGIN CORE FRAMEWORK -->
	<link href="<?php echo e(URL::asset("public/assets/plugins/bootstrap/css/bootstrap.min.css")); ?>" rel="stylesheet" />
	<link href="<?php echo e(URL::asset("public/assets/plugins/ionicons/css/ionicons.min.css")); ?>" rel="stylesheet" />
<link href="<?php echo e(URL::asset("public/assets/plugins/font-awesome/css/font-awesome.min.css")); ?>" rel="stylesheet" />
	<!-- END CORE FRAMEWORK -->
	
	<!-- BEGIN PLUGIN STYLES -->
	<link href="<?php echo e(URL::asset("public/assets/plugins/animate/animate.css")); ?>" rel="stylesheet" />
	<link href="<?php echo e(URL::asset("public/assets/plugins/bootstrap-slider/css/slider.css")); ?>" rel="stylesheet" />
	<link href="<?php echo e(URL::asset("public/assets/plugins/datatables/dataTables.bootstrap.css")); ?>" rel="stylesheet" />
	<!-- END PLUGIN STYLES -->
	
	<!-- BEGIN THEME STYLES -->
	<link href="<?php echo e(URL::asset("public/assets/css/material.css")); ?>" rel="stylesheet" />
	<link href="<?php echo e(URL::asset("public/assets/css/style.css")); ?>" rel="stylesheet" />
	<link href="<?php echo e(URL::asset("public/assets/css/plugins.css")); ?>" rel="stylesheet" />
	<link href="<?php echo e(URL::asset("public/assets/css/helpers.css")); ?>" rel="stylesheet" />
	<link href="<?php echo e(URL::asset("public/assets/css/responsive.css")); ?>" rel="stylesheet" />
	<!-- <link href="<?php echo e(URL::asset("public/assets/css/jquery.rating.css")); ?>" rel="stylesheet" /> -->
	
	<!-- END THEME STYLES -->
    <!-- library for auto complete-->
	<link  href="<?php echo e(URL::asset("public/assets/css/bootstrap-chosen.css")); ?>" rel="stylesheet" />
	
	<link rel="shortcut icon" href="<?php echo e(URL::asset("public/assets/img/favicon.ico")); ?>" type="image/x-icon" />
   <!--  <link href="<?php echo e(URL::asset("public/favicon.ico")); ?>" type="image/x-icon" rel="icon"> -->
	
	<script src="<?php echo e(URL::asset("public/assets/plugins/jquery-1.12.4.min.js")); ?>" type="text/javascript"></script>

    <!-- Time Picker Css-->
		
		<link rel="stylesheet" media="all" type="text/css" href="<?php echo e(URL::asset("public/css/jquery-ui.css")); ?>" />
		<link rel="stylesheet" media="all" type="text/css" href="<?php echo e(URL::asset("public/css/jquery-ui-timepicker-addon.css")); ?>" />	
		<!-- Custom Css-->
		<link href="<?php echo e(URL::asset("public/css/custom.css")); ?>" rel="stylesheet" />
		<style type="text/css">
		span.stars, span.stars span {
			display: block;
			background: url(http://52.35.102.74/goapp/public/assets/img/stars.png) 0 -16px repeat-x;
			width: 80px;
			height: 16px;
		}
	
		span.stars span {
			background-position: 0 0;
		}
	</style>
</head>
<!-- END HEAD -->
<!-- BEGIN BODY -->
<body class="fixed-leftside fixed-header">
	<!-- BEGIN HEADER -->
	<?php echo $__env->make('includes.header', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
	
	<!-- END HEADER -->
		 
	<div class="wrapper">
		<!-- BEGIN LEFTSIDE -->
        <?php echo $__env->make('includes.left_menu', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
        
		<!-- END LEFTSIDE -->

		<!-- BEGIN RIGHTSIDE -->
		
		<?php if(Session::has('success_message')): ?>
	<div class="alert alert-success" style=" z-index: 9999999; position: absolute; right: 70px; top: 1px;">
	<?php echo e(Session::get('success_message')); ?>

	</div>
		<?php endif; ?>

		<?php if(Session::has('fail_message')): ?>
	<div class="alert alert-danger" style=" z-index: 9999999; position: absolute; right: 70px; top: 1px;">
	<?php echo e(Session::get('fail_message')); ?>

	</div>
		<?php endif; ?>

		<?php if(Session::has('message')): ?>
	<div class="alert alert-danger" style=" z-index: 9999999; position: absolute; right: 70px; top: 1px;">
	<?php echo e(Session::get('message')); ?>

	</div>
		<?php endif; ?>
		
		<?php echo $__env->yieldContent('content'); ?>
	<!--	<?php if(count($errors) > 0): ?>
		<div class="alert alert-danger">
			<ul>
				<?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getFirstLoop(); ?>
					<li><?php echo e($error); ?></li>
				<?php endforeach; $__env->popLoop(); $loop = $__env->getFirstLoop(); ?>
			</ul>
		</div>
	<?php endif; ?> -->
        
	<!-- END CONTENT -->
		</div>
		<!-- /.wrapper -->
	<!-- BEGIN JAVASCRIPTS -->
	
	<!-- BEGIN CORE PLUGINS -->

	<script src="<?php echo e(URL::asset("public/assets/plugins/bootstrap/js/bootstrap.min.js")); ?>" type="text/javascript"></script>
	<script src="<?php echo e(URL::asset("public/assets/plugins/bootstrap/js/holder.js")); ?>"></script>

	<script src="<?php echo e(URL::asset("public/assets/plugins/pace/pace.min.js")); ?>" type="text/javascript"></script>
	<script src="<?php echo e(URL::asset("public/assets/plugins/slimScroll/jquery.slimscroll.min.js")); ?>" type="text/javascript"></script>
	<script src="<?php echo e(URL::asset("public/assets/js/core.js")); ?>" type="text/javascript"></script>
	<!-- <script src="<?php echo e(URL::asset("public/assets/js/jquery.rating.js")); ?>" type="text/javascript"></script> -->
	<!-- END CORE PLUGINS -->
    
    <!-- flot chart -->
	<script src="<?php echo e(URL::asset("public/assets/plugins/flot/jquery.flot.min.js")); ?>" type="text/javascript"></script>
	<script src="<?php echo e(URL::asset("public/assets/plugins/flot/jquery.flot.grow.js")); ?>" type="text/javascript"></script>

	<script src="<?php echo e(URL::asset("public/assets/plugins/flot/jquery.flot.resize.min.js")); ?>" type="text/javascript"></script>
	
    <!-- datatables -->
	<script src="<?php echo e(URL::asset("public/assets/plugins/datatables/jquery.dataTables.js")); ?>" type="text/javascript"></script>
	<script src="<?php echo e(URL::asset("public/js/datatables.min.js")); ?>" type="text/javascript"></script>
	<script src="<?php echo e(URL::asset("public/assets/plugins/datatables/dataTables.bootstrap.js")); ?>" type="text/javascript"></script>
	
	<!-- counter 
	<script src="<?php //echo env('jquery.countTo'); ?>" type="text/javascript"></script>-->
	
	<!-- maniac -->
	<script src="<?php echo e(URL::asset("public/assets/js/maniac.js")); ?>" type="text/javascript"></script>
	<!-- <script src="<?php echo e(URL::asset("public/assets/js/jquery.formError.js")); ?>" type="text/javascript"></script> -->
	<!-- DataTable multiple checkbox-->
	<script type="text/javascript" src="<?php echo e(URL::asset("public/js/dataTables.checkboxes.min.js")); ?>" type="text/javascript"></script>
		

	<!--ajax functionality js-->
	 <script src="<?php echo e(URL::asset("public/js/bootbox.min.js")); ?>" type="text/javascript"></script>
	<script src="<?php echo e(URL::asset("public/js/ajaxscript.js")); ?>" type="text/javascript"></script>
		<script src="<?php echo e(URL::asset("public/js/script.js")); ?>" type="text/javascript"></script>
		
	<!-- Date Time Picker JS-->
	<script type="text/javascript" src="<?php echo e(URL::asset("public/js/jquery-ui.min.js")); ?>" type="text/javascript"></script>
	<script type="text/javascript" src="<?php echo e(URL::asset("public/js/jquery-ui-timepicker-addon.js")); ?>" type="text/javascript"></script>
	
	<script src="<?php echo e(URL::asset("public/assets/js/chosen.js")); ?>" type="text/javascript"></script>
 

	<script>
      $(function() {
        $('.chosen-select').chosen();
        $('.chosen-select-deselect').chosen({ allow_single_deselect: true });
	  });
    </script>

	<!-- dashboard -->
	<script type="text/javascript">
		maniac.loadchart();
		maniac.loadcounter();
		maniac.loadprogress();
		maniac.loaddatatables();
	</script> 
    
	</script> 
    
    <script type="text/javascript">
    	$(document).ready(function() {
    		// this div hide for edit vehicle hide Model disabled
    		$('#ofModel').hide();
				$('table.display').DataTable({
			        "iDisplayLength": 25
			       });

					$('[data-toggle="tooltip"]').tooltip();   
				});

    	
		
       

				//Time Picker implementation
			$('.timepicker').timepicker({
				timeFormat: "hh:mm tt"
			});

			//Date Picker implementation
			$('.datepicker').datepicker({
						DatFormat: "MM/DD/YY",
						changeMonth: true,
						changeYear: true,
						 minDate: 0 
			});
			
			$(".datepicker").attr( 'readOnly' , 'true' );

			$('.datepicker1').datepicker({
				DatFormat: "MM/DD/YY",
				changeMonth: true,
				yearRange: "-100:+0",
				changeYear: true
			});
			$(".datepicker1").attr( 'readOnly' , 'true' );

    </script>
    
    <!--FROM DATE AND TO DATE--->
    
    	<script>
	$(document).ready(function(){
    $(".from_date").datepicker({
        numberOfMonths: 1,
						changeYear: true,
						maxDate:0,
						dateFormat: "dd-mm-yy",
						changeMonth: true,
						yearRange: "2000:+0",
        onSelect: function(selected) {
          $(".to_date").datepicker("option","minDate", selected)
        }
    });
    $(".to_date").datepicker({ 
					numberOfMonths: 1,
					changeYear: true,
					maxDate:0,
					dateFormat: "dd-mm-yy",
					changeMonth: true,
					yearRange: "2000:+0",
        onSelect: function(selected) {
           $(".from_date").datepicker("option","maxDate", selected)
        }
    });  
});
</script>

    <!-- <script src="<?php echo e(URL::asset("public/js/farejs.js")); ?>" type="text/javascript"></script> -->
	<!-- END JAVASCRIPTS -->
</body>
<!-- END BODY -->
</html>
