<?php session_start(); ?>
<!DOCTYPE html>
<html lang="en">
<!-- BEGIN HEAD -->

<!-- Mirrored from yakuzi.eu/maniac/login.html by HTTrack Website Copier/3.x [XR&CO'2014], Tue, 04 Oct 2016 07:04:43 GMT -->
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no"/>
	<meta content="" name="description"/>
	<meta content="" name="author"/>
	
	<title>Reset Password</title>
	
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
	<!-- END THEME STYLES -->
    
    <link href="<?php echo e(URL::asset("public/assets/img/favicon.ico")); ?>" type="image/x-icon" rel="icon">
    
</head>

<!-- END HEAD -->
<!-- BEGIN BODY -->
<body class="auth-page height-auto" style="background:url(<?php echo e(URL::asset('public/assets/img/bg.jpg')); ?>) no-repeat center; background-size:cover;	" >
	<!-- BEGIN CONTENT -->
	<div class="wrapper animated fadeInDown">
		<div class="panel overflow-hidden">
			<div class="bg-white padding-top-25 no-margin-bottom font-size-20 color-white text-center text-uppercase">
				<img src="<?php echo e(URL::asset("public/assets/img/logo-new.png")); ?>">
			</div>
			<form name="myForm" id="checkform" method="post" action="" onsubmit="return validateForm()">
				<div class="box-body padding-md">	
					
					<input type="hidden" name="id" value="<?php echo e($id); ?>">
					<input type="hidden" name="token" value="<?php echo e($token); ?>">
					<div class="form-group">
						<input type="password" name="password" id="password" class="form-control input-lg" placeholder="Password"   />
					</div>
					
					<div class="form-group">
						<input type="password" name="confirmpassword" id="confirmpassword" class="form-control input-lg" placeholder="Confirm Password" onfocus="myFunction()" onkeyup="myFunction1()"/>
					</div>        
					
					<!--<div class="form-group margin-top-20">
						<input type="checkbox" class="js-switch" id="checkbox" checked name="remember" /><label for="checkbox" class="font-size-12 normal margin-left-10">Remember Me</label>
					</div> -->      
					 <?php echo e(csrf_field()); ?>

					<button type="submit" class="btn btn-dark bg-orange-900 padding-10 btn-block color-white"><i class="ion-log-in"></i> Reset Password</button>  
				</div>
			</form>
			<div id="al" ></div>
		<?php if(Session::has('message')): ?>
		   <div class="alert alert-danger"><?php echo e(Session::get('message')); ?></div>
		<?php endif; ?>
		<?php if(Session::has('message_success')): ?>
		   <div class="alert alert-success"><?php echo e(Session::get('message_success')); ?></div>
		<?php endif; ?>
			<div class="panel-footer padding-md no-margin no-border bg-grey-500 text-center color-white">&copy; 2016 GO. All Rights Reserved.</div>
		</div>
	</div>
	<!-- END CONTENT -->
		
<!-- BEGIN CORE PLUGINS -->
	<script src="<?php echo e(URL::asset("public/assets/plugins/jquery-1.11.1.min.js")); ?>" type="text/javascript"></script>
	<script src="<?php echo e(URL::asset("public/assets/plugins/bootstrap/js/bootstrap.min.js")); ?>" type="text/javascript"></script>
	<script src="<?php echo e(URL::asset("public/assets/plugins/bootstrap/js/holder.js")); ?>"></script>
	<script src="<?php echo e(URL::asset("public/assets/plugins/pace/pace.min.js")); ?>" type="text/javascript"></script>
	<script src="<?php echo e(URL::asset("public/assets/plugins/slimScroll/jquery.slimscroll.min.js")); ?>" type="text/javascript"></script>
	<script src="<?php echo e(URL::asset("public/assets/js/core.js")); ?>" type="text/javascript"></script>
	<!-- END CORE PLUGINS -->
	
    <!-- datatables -->
	<script src="<?php echo e(URL::asset("public/assets/plugins/datatables/jquery.dataTables.js")); ?>" type="text/javascript"></script>
	<script src="<?php echo e(URL::asset("public/assets/plugins/datatables/dataTables.bootstrap.js")); ?>" type="text/javascript"></script>
	
	<!-- counter -->
	
	<!-- maniac -->
	<script src="<?php echo e(URL::asset("public/assets/js/maniac.js")); ?>" type="text/javascript"></script>
	
	<script type="text/javascript">
		maniac.loadvalidator();
		maniac.loadswitchery();
	</script>
	<script type="text/javascript">

	function validateForm() {
		console.log('test');
    var x = document.forms["myForm"]["password"].value;
    var y = document.forms["myForm"]["confirmpassword"].value;
    if (x == "") {
        document.getElementById("password").style.borderColor = "red";
         document.getElementById("password").focus();
        return false;
    	}
    if (y == "") {
    	document.getElementById("password").style.borderColor = "";
        document.getElementById("confirmpassword").style.borderColor = "red";
        return false;
    	}
    if(x != y)
    {
    	document.getElementById('al').innerHTML += '<p class="alert alert-danger">Passwords not Match</p>';
    	return false;
    }
	}

	function myFunction() {
		console.log('focused');
    document.getElementById("password").style.borderColor = "";
}
function myFunction1() {
    console.log('focused');
    document.getElementById("confirmpassword").style.borderColor = "";
}
	</script>
	<!-- END JAVASCRIPTS -->
</body>
<!-- END BODY -->

</html>