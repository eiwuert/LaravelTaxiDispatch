;

<?php $__env->startSection('title'); ?>

Manage Fare - Go Cabs

<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>


    <div class="rightside bg-grey-100">
			<!-- BEGIN PAGE HEADING -->
            <div class="page-head">
				<h1 class="page-title">Manage Fare Details</h1>
				<!-- BEGIN BREADCRUMB -->
				<a href="add_fare"><button class="btn btn-dark bg-red-600 color-white pull-right">Add Fare Details</button></a>
				<!-- END BREADCRUMB -->
			</div>
			<!-- END PAGE HEADING -->

            <div class="container-fluid">

            <!-- START OF FILTER-->
	<form name="searchfare" action="" method="get">
	<div class="row">
        <div class="col-lg-12">
				
			<div class="form-horizontal" >
				 <?php echo e(csrf_field()); ?>


								<div class="form-group margin-bottom-20 col-md-4 margin-right-10">
									 	<select class=" form-control" name="franchise_id" id="franchise_id" >
											<option value="">--Select Franchise--</option>
						
									</select>
								</div>

                <div class="form-group margin-bottom-20 col-md-4 margin-right-10">
          				<input type="submit" class="btn btn-dark bg-red-600 color-white pull-right" id="button_submit" value="Search" />
          		</div> 

				</div>
			</div>
			</div>
	</form>
	<!-- END OF FILTER-->


	
				<!-- BEGIN FOOTER -->
				<footer class="bg-white">
					<div class="pull-left">
						<span class="pull-left margin-right-15">&copy; 2016 WrydesDispatch. All Rights Reserved.</span>
					</div>
				</footer>
				<!-- END FOOTER -->
            </div><!-- /.container-fluid -->
        </div>
        
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layout.master', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>