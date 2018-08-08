;

<?php $__env->startSection('title'); ?>

Manage Franchise - Go Cabs

<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<div class="rightside bg-grey-100">
    <!-- BEGIN PAGE HEADING -->
    <div class="page-head">
        <h1 class="page-title">Manage Franchise</h1>
        <!-- BEGIN BREADCRUMB -->
        <a href="<?php echo e(url("/")); ?>/add_franchise" class="btn btn-dark bg-red-600 color-white pull-right">Add Franchise</a>
        <!-- END BREADCRUMB -->
    </div>
    <!-- START OF FILTER-->
    <!-- <div class="f_filter container-fluid">
        <div class="pull-right col-lg-3 no-padding">
            <form method="GET" action="" name="filter">
                <?php echo e(csrf_field()); ?>

                <div class="input-group">
                    <select class=" form-control" name="ride_category">
                        <option value="">--Select Vehicle Type--</option>
                       
                    </select>
                    <span class="input-group-btn">
						<input type="submit" class="btn btn-dark bg-red-600 color-white pull-right" value="Search"/>
				   </span>
                </div>
            </form>
        </div>
        <div class="clearfix"></div>
    </div>
    END OF FILTER-->
    <!-- END PAGE HEADING -->
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12">
                <div class="panel no-border">
                    <div class="panel-title bg-amber-200">
                        <div class="panel-head">Franchise Information</div>
                    </div>
                    <div class="panel-body no-padding-top bg-white">
                        <br>
                        <ul class="nav nav-tabs tab-grey bg-grey-100">
                            <li class="active"><a href="#fontawesome" id="fontawesome-tab" data-toggle="tab"
                                                  aria-controls="fontawesome" aria-expanded="true">Active Franchise</a></li>
                            <li><a href="#ionicons" id="ionicons-tab" data-toggle="tab" aria-controls="ionicons">Blocked Franchise</a></li>
                            
                                <div id="status"></div>
                            </li>
                        </ul>

                        <div class="tab-content">
                            <div id="fontawesome" aria-labelledBy="fontawesome-tab"
                                 class="panel-body padding-md table-responsive tab-pane in active">
                                <p class="text-light margin-bottom-30"></p>

                                <table class="table table-bordered display" >
                                    <thead>
                                    <tr>
                                       <!--  <th class="vertical-middle">Select</th> -->
                                        <th class="vertical-middle">Name</th>
                                        <th class="vertical-middle">Email</th>
                                        <th class="vertical-middle">Mobile number</th>
                                        <th class="vertical-middle">Company Name</th>
                                         <th class="vertical-middle">Country</th>
                                   
                                        <th class="vertical-middle">State</th>
                                             <th class="vertical-middle">City</th>
                                        <th class="vertical-middle">Action</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <?php $__currentLoopData = $activefranchise; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $ad): $__env->incrementLoopIndices(); $loop = $__env->getFirstLoop(); ?>
                                    <tr>
                                        <!-- <td class="vertical-middle">
                                        <input id="checkbox13" type="checkbox">
                                        </td> -->
                                        <td class="vertical-middle"> <?php echo e($ad->first_name); ?> <?php echo e($ad->lastname); ?> </td>
                                        <td class="vertical-middle"> <?php echo e($ad->email); ?> </td>
                                        <td class="vertical-middle"> <?php echo e($ad->mobile); ?> </td>
                                        <td class="vertical-middle"> <?php echo e($ad->company_name); ?> </td>
                                        
                                         <td class="vertical-middle"> <?php echo e($ad->country_name->name); ?> </td>
                                        <td class="vertical-middle"> <?php echo e($ad->state_name->name); ?> </td>
                                        <td class="vertical-middle"> <?php echo e($ad->city_name->name); ?> </td>
                                        <td class="vertical-middle">
                                        <a href="view_franchise/<?php echo e($ad->id); ?>" data-toggle="tooltip" title="View"><i class="fa fa-eye fa-2x" ></i></a>

                                      <a href="edit_franchise/<?php echo e($ad->id); ?>" data-toggle="tooltip" title="Edit"><i class="fa fa-edit fa-2x"></i></a>
                                            <a href="#" data-toggle="tooltip" title="Block"
                                               onclick="deactivate(<?php echo e($ad->id); ?>,'blockfranchise','Franchise');"
                                               data-original-title="Edit">
                                                <i class="fa fa-close fa-2x"></i>
                                            </a>                  
                                        </td>
                                    </tr>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getFirstLoop(); ?>
                                    </tbody>
                                </table>
                            </div>

                            <!-- Second Tab Starts -->

                            <div id="ionicons" aria-labelledBy="ionicons-tab"
                                 class="panel-body padding-md table-responsive tab-pane">
                                <p class="text-light margin-bottom-30"></p>
                                <table class="table table-bordered display" id="">
                                    <thead>
                                    <tr>
                                        <!-- <th class="vertical-middle">Select</th> -->
                                        <th class="vertical-middle">Name</th>
                                        <th class="vertical-middle">Email</th>
                                        <th class="vertical-middle">Mobile number</th>
                                        <th class="vertical-middle">Company Name</th>
                                          <th class="vertical-middle">Country</th>
                                           <th class="vertical-middle">State</th>
                                        <th class="vertical-middle">City</th>
                                       
                                        <th class="vertical-middle">Action</th>
                                    </tr>
                                    </thead>

                            <tbody>
                                    <?php $__currentLoopData = $blockedfranchise; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $bd): $__env->incrementLoopIndices(); $loop = $__env->getFirstLoop(); ?>
                                    <tr>
                                        <!-- <td class="vertical-middle">
                                         <input id="checkbox13" type="checkbox">
                                        </td> -->
                                        <td class="vertical-middle"> <?php echo e($bd->first_name); ?> <?php echo e($bd->lastname); ?> </td>
                                        <td class="vertical-middle"> <?php echo e($bd->email); ?> </td>
                                        <td class="vertical-middle"> <?php echo e($bd->mobile); ?> </td>
                                        <td class="vertical-middle"> <?php echo e($bd->company_name); ?> </td>
                                          
                                         <td class="vertical-middle"> <?php echo e($bd->country_name->name); ?> </td>
                                        <td class="vertical-middle"> <?php echo e($bd->state_name->name); ?> </td>
                                        <td class="vertical-middle"> <?php echo e($bd->city_name->name); ?> </td>
                                        <td class="vertical-middle">
                                        <a href="#" data-toggle="tooltip" title="Activate"
                                               onclick="activate(<?php echo e($bd->id); ?>,'activatefranchise','Franchise');"
                                               data-original-title="Edit">
                                                <i class="fa fa-check fa-2x"></i>
                                            </a> &nbsp;
                                        <!-- <a href="#" data-toggle="tooltip" title="Delete"
                                                     onclick="delete1(<?php echo e($bd->id); ?>,'deletefranchise','Franchise');"><i
                                                        class="fa fa-trash fa-2x"></i> -->
                                            </a>
                                        </td>
                                    </tr>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getFirstLoop(); ?>
                                    </tbody>
                                </table>
                            </div>


                            <!-- End of Second Tab -->

                        </div>
                    </div>
                </div><!-- /.col -->
            </div><!-- /. row -->
        </div><!-- /.row -->

        <script>
            $(document).ready(function () {
                $('[data-toggle="tooltip"]').tooltip(); 
            });
        </script>

        <!-- BEGIN FOOTER -->
        <?php echo $__env->make('includes.footer', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
        <!-- END FOOTER -->
    </div>
</div>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layout.master', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>