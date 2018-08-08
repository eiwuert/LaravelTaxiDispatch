;

<?php $__env->startSection('title'); ?>

Total Transaction - Go Cabs

<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<style>
.panel > .panel-body {
     overflow: scroll !important;
}</style>
 <div class="rightside bg-grey-100">
			<!-- BEGIN PAGE HEADING -->
            <div class="page-head">
				<h1 class="page-title">Total Transaction Details</h1>
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
       	
<iframe id = "myIframe"  onload = "setIframeHeight(this)"  height="100%" width="130%" src="http://<?php echo e($_SERVER['SERVER_NAME']); ?>:8080/goapp_viewer/frameset?__report=<?php echo e(getcwd()); ?>/report/total_rides.rptdesign"></iframe> 

						
					</div><!-- /.col -->
				</div><!-- /.row -->
				
				<!-- /.row -->
				
				<!-- /.row -->
				
				<!-- BEGIN FOOTER -->
				
        			<?php echo $__env->make('includes.footer', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
				<!-- END FOOTER -->
            </div><!-- /.container-fluid -->
        </div>

    
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layout.master', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>