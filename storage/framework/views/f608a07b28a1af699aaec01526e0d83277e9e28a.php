<div class="leftside">
			<div class="sidebar">
				<!-- BEGIN RPOFILE -->
				 
				<!-- END RPOFILE -->
				<!-- BEGIN NAV -->
				
					<ul class="nav-sidebar">
						<li  <?php echo e((Request::is('home') ? 'class=active' : '')); ?>>
                            <a href="<?php echo e(url("/")); ?>/home">
						
                                <i class="ion-home"></i> <span><?php echo e(trans('config.lblm_dashboard')); ?></span>
                            </a>
                        </li>
                        <!-- <li class="nav-dropdown 
                        <?php echo e((Request::is('taxi/add') ? 'open' : '')); ?>

                        <?php echo e((Request::is('taxi') ? 'open' : '')); ?>

                        <?php echo e((Request::is('brand') ? 'open' : '')); ?>

                                    <?php echo e((Request::is('model') ? 'open' : '')); ?>

                                    <?php echo e((Request::is('type') ? 'open' : '')); ?>

                        ">
                            <a href="<?php echo e(url("/")); ?>/add_taxi ">
                                <i class="fa fa-cab"></i> <span><?php echo e(trans('config.lblm_vehicle_setting')); ?></span>
                                <i class="ion-chevron-right pull-right"></i>
                            </a>
                           		 <ul>
                           		 		<!--  <?php if(Session::get('user_role')  ==1): ?>
                                	<li <?php echo e((Request::is('taxi/add') ? 'class=active' : '')); ?> ><a href="<?php echo e(url("/")); ?>/taxi/add"><?php echo e(trans('config.lblm_add_vehicle')); ?></a></li>
                                	<?php endif; ?> -->
                                    <!-- <li <?php echo e((Request::is('taxi') ? 'class=active' : '')); ?>><a href="<?php echo e(url("/")); ?>/taxi "> 
                            
                                    <?php if(Session::get('user_role')  ==1): ?><?php echo e(trans('config.lblm_manage_vehicle')); ?><?php else: ?><?php echo e(trans('config.lblfm_manage_vehicle')); ?> <?php endif; ?>
                                    </a></li>
                                </ul>
                                </li> --> 
                                     <?php if(Session::get('user_role')  ==1): ?>
                                    <li class="nav-dropdown
                                    <?php echo e((Request::is('brand*') ? 'open' : '')); ?>

                                    <?php echo e((Request::is('model*') ? 'open' : '')); ?>

                                    <?php echo e((Request::is('type*') ? 'open' : '')); ?>"><a href="manage_brand "><i class="fa fa-plus-circle"></i><?php echo e(trans('config.lblm_add_vehicle_details')); ?><i class="ion-chevron-right pull-right"></i></a>
                                    	
                                    	<ul>
                                        	<li <?php echo e((Request::is('brand*') ? 'class=active' : '')); ?>><a href="<?php echo e(url("/")); ?>/brand "><?php echo e(trans('config.lblm_manage_brand')); ?></a></li>
                                            <li <?php echo e((Request::is('model*') ? 'class=active' : '')); ?>><a href="<?php echo e(url("/")); ?>/model"><?php echo e(trans('config.lblm_manage_model')); ?></a></li>
                                            <li <?php echo e((Request::is('type*') ? 'class=active' : '')); ?>><a href="<?php echo e(url("/")); ?>/type ">Manage Vehicle Type</a></li>
                                        </ul>
                                    </li>
                                    <?php endif; ?>
                                 
                                    
                        </li>
                         <?php if(Session::get('user_role')  ==5): ?>
                        <li class="nav-dropdown 
                        <?php echo e((Request::is('add_driver*') ? 'open' : '')); ?>

                        <?php echo e((Request::is('manage_driver*') ? 'open' : '')); ?>

                        <?php echo e((Request::is('assign_taxi*') ? 'open' : '')); ?>

                        <?php echo e((Request::is('manage_assign_taxi*') ? 'open' : '')); ?>

                        ">
                            <a href="<?php echo e(url("/")); ?>/add_driver ">
                                <i class="fa fa-user"></i> <span><?php echo e(trans('config.lblm_driver_setting')); ?></span>
                                <i class="ion-chevron-right pull-right"></i>
                            </a>
                            <ul>
                                <!-- <li <?php echo e((Request::is('add_driver') ? 'class=active' : '')); ?> ><a href="<?php echo e(url("/")); ?>/add_driver "><?php echo e(trans('config.lblm_driver_add')); ?></a></li> -->
                                <li <?php echo e((Request::is('manage_driver*') ? 'class=active' : '')); ?>><a href="<?php echo e(url("/")); ?>/manage_driver "><?php echo e(trans('config.lblm_manage_driver')); ?></a></li>
                                <!-- <li <?php echo e((Request::is('assign_taxi') ? 'class=active' : '')); ?>><a href="<?php echo e(url("/")); ?>/assign_taxi "><?php echo e(trans('config.lblm_assign_vehicle')); ?></a></li> -->
                                <li <?php echo e((Request::is('manage_assign_taxi*') ? 'class=active' : '')); ?>><a href="<?php echo e(url("/")); ?>/manage_assign_taxi "><?php echo e(trans('config.lblm_manage_assign_vehicle')); ?></a></li>
                            </ul>
                        </li>
                        <?php endif; ?>
                        <li class="nav-dropdown
                        <?php echo e((Request::is('add_attached_drivers*') ? 'open' : '')); ?>

                        <?php echo e((Request::is('manage_attached_drivers*') ? 'open' : '')); ?>

                        <?php echo e((Request::is('edit_attached_driver*') ? 'open' : '')); ?>

                        <?php echo e((Request::is('view_attached_driver*') ? 'open' : '')); ?>

                        <?php echo e((Request::is('review*') ? 'open' : '')); ?>

                        ">

                            <a href="<?php echo e(url("/")); ?>/add_attached_drivers">
                                <i class="fa fa-plus-circle"></i> <span>  <?php if(Session::get('user_role')  ==1): ?><?php echo e(trans('config.lblm_attach_driver')); ?><?php else: ?><?php echo e(trans('config.lblfm_driver_vehicle')); ?> <?php endif; ?></span>
                                <i class="ion-chevron-right pull-right"></i>
                            </a>
                            <ul>
                                 <!-- <?php if(Session::get('user_role')  ==1): ?>
                                 <li <?php echo e((Request::is('add_attached_drivers') ? 'class=active' : '')); ?>><a href="<?php echo e(url("/")); ?>/add_attached_drivers"><?php echo e(trans('config.lblm_add_attach_driver')); ?></a></li>
                                <?php endif; ?> -->
                                <li <?php echo e((Request::is('manage_attached_drivers','add_attached_drivers','edit_attached_driver*','view_attached_driver*','review*') ? 'class=active' : '')); ?>><a href="<?php echo e(url("/")); ?>/manage_attached_drivers">   <?php if(Session::get('user_role')  ==1): ?><?php echo e(trans('config.lblm_manage_attach_driver')); ?><?php else: ?><?php echo e(trans('config.lblfm_manage_attach_driver')); ?> <?php endif; ?></a></li>
                            </ul>
                        </li>


								 <?php if(Session::get('user_role')  ==1): ?>
								      <li class="nav-dropdown 
                        <?php echo e((Request::is('add_fare*') ? 'open' : '')); ?>

                        <?php echo e((Request::is('view_fare*') ? 'open' : '')); ?>

                        <?php echo e((Request::is('edit_fare*') ? 'open' : '')); ?>

                        <?php echo e((Request::is('manage_fare*') ? 'open' : '')); ?>

                        <?php echo e((Request::is('add-tax*') ? 'open' : '')); ?>

                        <?php echo e((Request::is('edit-tax*') ? 'open' : '')); ?>

                        <?php echo e((Request::is('manage-tax*') ? 'open' : '')); ?>

                        ">
                            <a href="<?php echo e(url("/")); ?>/add_fare ">
                                <i class="fa fa-dollar"></i> <span><?php echo e(trans('config.lblm_fare_management')); ?></span>
                                <i class="ion-chevron-right pull-right"></i>
                            </a>
                            <ul>
                                <!-- <li <?php echo e((Request::is('add_fare') ? 'class=active' : '')); ?>><a href="<?php echo e(url("/")); ?>/add_fare "><?php echo e(trans('config.lblm_add_fare_details')); ?></a></li> -->
                                <li <?php echo e((Request::is('manage_fare*','add_fare*','view_fare*','edit_fare*') ? 'class=active' : '')); ?>><a href="<?php echo e(url("/")); ?>/manage_fare "><?php echo e(trans('config.lblm_manage_fare_details')); ?></a></li>
								   <!-- <li <?php echo e((Request::is('add-tax') ? 'class=active' : '')); ?>><a href="<?php echo e(url("/")); ?>/add-tax "><?php echo e(trans('config.lblm_add_tax')); ?></a></li> -->
                                <li <?php echo e((Request::is('add-tax*','edit-tax*') ? 'class=active' : '')); ?> <?php echo e((Request::is('manage-tax') ? 'class=active' : '')); ?>><a href="<?php echo e(url("/")); ?>/manage-tax "><?php echo e(trans('config.lblm_manage_tax')); ?></a></li>
                            </ul>
                        </li>
                    
                        <li class="nav-dropdown
                        <?php echo e((Request::is('add_offers*') ? 'open' : '')); ?>

                        <?php echo e((Request::is('manage_offers*') ? 'open' : '')); ?>

                        <?php echo e((Request::is('view_offers*') ? 'open' : '')); ?>

                        ">

                            <a href="<?php echo e(url("/")); ?>/add_offers">
                                <i class="fa fa-plus-circle"></i> <span><?php echo e(trans('config.lbl_offer_manage')); ?></span>
                                <i class="ion-chevron-right pull-right"></i>
                            </a>
                            <ul>
                                <!-- <li <?php echo e((Request::is('add_offers') ? 'class=active' : '')); ?>><a href="<?php echo e(url("/")); ?>/add_offers"><?php echo e(trans('config.lbladd_offer')); ?></a></li> -->
                                <li <?php echo e((Request::is('manage_offers*','add_offers*','view_offers*') ? 'class=active' : '')); ?>><a href="<?php echo e(url("/")); ?>/manage_offers"><?php echo e(trans('config.lblmanage_offer')); ?></a></li>
                            </ul>
                        </li>

                      <!--  <li class="nav-dropdown">
                            <a href="<?php echo e(url("/")); ?>/add_dispatcher ">
                                <i class="ion-male"></i> <span>Dispatcher Details</span>
                                <i class="ion-chevron-right pull-right"></i>
                            </a>
                            <ul>
                                <li><a href="<?php echo e(url("/")); ?>/add_dispatcher ">Add Dispatcher</a></li>
                                <li><a href="<?php echo e(url("/")); ?>/manage_dispatcher ">Manage Dispatcher</a></li>
                            </ul>
                        </li> -->
                        
                        <!--<li class="nav-dropdown 
                        <?php echo e((Request::is('add_franchise*') ? 'open' : '')); ?>

                        <?php echo e((Request::is('manage_franchise*') ? 'open' : '')); ?>

                        <?php echo e((Request::is('view_franchise*') ? 'open' : '')); ?>

                        <?php echo e((Request::is('edit_franchise*') ? 'open' : '')); ?>

                        ">
                            <a href="<?php echo e(url("/")); ?>/add_franchise ">
                                <i class="fa fa-bank"></i> <span>Franchise Management</span>
                                <i class="ion-chevron-right pull-right"></i>
                            </a>
                            <ul>
                                <!-- <li <?php echo e((Request::is('add_franchise') ? 'class=active' : '')); ?> ><a href="<?php echo e(url("/")); ?>/add_franchise ">Add Franchise</a></li> -->
                               <!-- <li <?php echo e((Request::is('manage_franchise*','add_franchise*','view_franchise*','edit_franchise*') ? 'class=active' : '')); ?>><a href="<?php echo e(url("/")); ?>/manage_franchise ">Manage Franchise</a></li>
                            </ul>
                        </li> -->


                        <li class="nav-dropdown <?php echo e((Request::is('manage_customers*') ? 'open' : '')); ?>">
                            <a href="manage_customers ">
                                <i class="fa fa-users"></i> <span><?php echo e(trans('config.lblm_customer_details')); ?></span>
                                <i class="ion-chevron-right pull-right"></i>
                            </a>
                            <ul>
                                <li <?php echo e((Request::is('manage_customers*') ? 'class=active' : '')); ?>><a href="<?php echo e(url("/")); ?>/manage_customers "><?php echo e(trans('config.lblm_manage_customer')); ?></a></li>
                            </ul>
                        </li>
                        <?php endif; ?>

                        <li class="nav-dropdown
                        <?php echo e((Request::is('total_rides*') ? 'open' : '')); ?>

                        <?php echo e((Request::is('success_rides*') ? 'open' : '')); ?>

                        <?php echo e((Request::is('cancel_rides*') ? 'open' : '')); ?>

                        <?php echo e((Request::is('reject_rides*') ? 'open' : '')); ?>

                        <?php echo e((Request::is('drivers_share*') ? 'open' : '')); ?>

                        ">
                            <a href="<?php echo e(url("/")); ?>/total_rides ">
                                <i class="ion-document-text"></i> <span><?php echo e(trans('config.lblm_reports')); ?></span>
                                <i class="ion-chevron-right pull-right"></i>
                            </a>
                            <ul>
                                <li <?php echo e((Request::is('total_rides*') ? 'class=active' : '')); ?>><a href="<?php echo e(url("/")); ?>/total_rides "><?php echo e(trans('config.lblm_totla_ride')); ?></a></li>
                                <li <?php echo e((Request::is('success_rides*') ? 'class=active' : '')); ?>><a href="<?php echo e(url("/")); ?>/success_rides "><?php echo e(trans('config.lblm_successful_ride')); ?></a></li>
                                <li <?php echo e((Request::is('cancel_rides*') ? 'class=active' : '')); ?>><a href="<?php echo e(url("/")); ?>/cancel_rides "><?php echo e(trans('config.lblm_cancel_ride')); ?></a></li>
                                <li <?php echo e((Request::is('reject_rides*') ? 'class=active' : '')); ?>><a href="<?php echo e(url("/")); ?>/reject_rides "><?php echo e(trans('config.lblm_reject_ride')); ?></a></li>
                                     <li <?php echo e((Request::is('drivers_share*') ? 'class=active' : '')); ?>><a href="<?php echo e(url("/")); ?>/drivers_share"><?php echo e(trans('config.lblm_share_details_report')); ?></a></li>
                            </ul>
                        </li>
                        
                        <?php if(Session::get('user_role')  == 1): ?>
                        
                        <li class="nav-dropdown <?php echo e((Request::is('manage_rating*') ? 'open' : '')); ?>

						<?php echo e((Request::is('rating*') ? 'open' : '')); ?>">
                            <a href="<?php echo e(url("/")); ?>/setting ">
                                <i class="fa fa-users"></i> <span>Settings</span>
                                <i class="ion-chevron-right pull-right"></i>
                            </a>
                            <ul>
                                <li <?php echo e((Request::is('manage_rating*','rating*') ? 'class=active' : '')); ?>><a href="<?php echo e(url("/")); ?>/manage_rating"><?php echo e(trans('config.lblm_manage_rating')); ?></a></li>
                            </ul>
                        </li>
                        
                        
                     <?php endif; ?>
                    	
                      
                          <!--   <ul>
                                <li><a href="add-companies ">Add Companies</a></li>
                                <li><a href="manage-companies ">Manage Companies</a></li>
                                <li class="nav-dropdown"><a href="terms ">Manage Support<i class="ion-chevron-right pull-right"></i></a>
                                	<ul>
                                    	<li><a href="terms ">Terms & Conditions</a></li>
                                        <li><a href="privacy ">Privacy Policy</a></li>
                                        <li><a href="contact ">Contact Info</a></li>
                                    </ul>
                                </li>
                            </ul> 
                        </li>  -->                 
                    </ul>
					<!-- END NAV -->
					
					<!-- BEGIN WIDGET -->
					
					<!-- END WIDGET -->
			</div><!-- /.sidebar -->
        </div>
