<div class="leftside">
			<div class="sidebar">
				<!-- BEGIN RPOFILE -->
				 
				<!-- END RPOFILE -->
				<!-- BEGIN NAV -->
				
					<ul class="nav-sidebar">
						<li  {{{ (Request::is('home') ? 'class=active' : '') }}}>
                            <a href="{{url("/")}}/home">
						
                                <i class="ion-home"></i> <span>{{ trans('config.lblm_dashboard') }}</span>
                            </a>
                        </li>
                        <li class="nav-dropdown 
                        {{{ (Request::is('taxi/add') ? 'open' : '') }}}
                        {{{ (Request::is('taxi') ? 'open' : '') }}}
                        {{{ (Request::is('brand') ? 'open' : '') }}}
                                    {{{ (Request::is('model') ? 'open' : '') }}}
                                    {{{ (Request::is('type') ? 'open' : '') }}}
                        ">
                            <a href="{{url("/")}}/add_taxi ">
                                <i class="fa fa-cab"></i> <span>{{ trans('config.lblm_vehicle_setting') }}</span>
                                <i class="ion-chevron-right pull-right"></i>
                            </a>
                           		 <ul>
                           		 		 @if(Session::get('user_role')  ==1)
                                	<li {{{ (Request::is('taxi/add') ? 'class=active' : '') }}} ><a href="{{url("/")}}/taxi/add">{{ trans('config.lblm_add_vehicle') }}</a></li>
                                	@endif
                                    <li {{{ (Request::is('taxi') ? 'class=active' : '') }}}><a href="{{url("/")}}/taxi ">{{ trans('config.lblm_manage_vehicle') }}</a></li>
                                     @if(Session::get('user_role')  ==1)
                                    <li class="nav-dropdown
                                    {{{ (Request::is('brand') ? 'open' : '') }}}
                                    {{{ (Request::is('model') ? 'open' : '') }}}
                                    {{{ (Request::is('type') ? 'open' : '') }}}"><a href="manage_brand ">{{ trans('config.lblm_add_vehicle_details') }}<i class="ion-chevron-right pull-right"></i></a>
                                    	
                                    	<ul>
                                        	<li {{{ (Request::is('brand') ? 'class=active' : '') }}}><a href="{{url("/")}}/brand ">{{ trans('config.lblm_manage_brand') }}</a></li>
                                            <li {{{ (Request::is('model') ? 'class=active' : '') }}}><a href="{{url("/")}}/model">{{ trans('config.lblm_manage_model') }}</a></li>
                                            <li {{{ (Request::is('type') ? 'class=active' : '') }}}><a href="{{url("/")}}/type ">Manage Vehicle Type</a></li>
                                        </ul>
                                    </li>
                                    @endif
                                 </ul>
                                    
                        </li>
                         @if(Session::get('user_role')  ==1)
                        <li class="nav-dropdown 
                        {{{ (Request::is('add_driver') ? 'open' : '') }}}
                        {{{ (Request::is('manage_driver') ? 'open' : '') }}}
                        {{{ (Request::is('assign_taxi') ? 'open' : '') }}}
                        {{{ (Request::is('manage_assign_taxi') ? 'open' : '') }}}
                        ">
                            <a href="{{url("/")}}/add_driver ">
                                <i class="fa fa-user"></i> <span>{{ trans('config.lblm_driver_setting') }}</span>
                                <i class="ion-chevron-right pull-right"></i>
                            </a>
                            <ul>
                                <li {{{ (Request::is('add_driver') ? 'class=active' : '') }}} ><a href="{{url("/")}}/add_driver ">{{ trans('config.lblm_driver_add') }}</a></li>
                                <li {{{ (Request::is('manage_driver') ? 'class=active' : '') }}}><a href="{{url("/")}}/manage_driver ">{{ trans('config.lblm_manage_driver') }}</a></li>
                                <li {{{ (Request::is('assign_taxi') ? 'class=active' : '') }}}><a href="{{url("/")}}/assign_taxi ">{{ trans('config.lblm_assign_vehicle') }}</a></li>
                                <li {{{ (Request::is('manage_assign_taxi') ? 'class=active' : '') }}}><a href="{{url("/")}}/manage_assign_taxi ">{{ trans('config.lblm_manage_assign_vehicle') }}</a></li>
                            </ul>
                        </li>
                        @endif
                        <li class="nav-dropdown
                        {{{ (Request::is('add_attached_drivers') ? 'open' : '') }}}
                        {{{ (Request::is('manage_attached_drivers') ? 'open' : '') }}}
                        ">

                            <a href="{{url("/")}}/add_attached_drivers">
                                <i class="fa fa-plus-circle"></i> <span>{{ trans('config.lblm_attach_driver') }}</span>
                                <i class="ion-chevron-right pull-right"></i>
                            </a>
                            <ul>
                                 @if(Session::get('user_role')  ==1)
                                 <li {{{ (Request::is('add_attached_drivers') ? 'class=active' : '') }}}><a href="{{url("/")}}/add_attached_drivers">{{ trans('config.lblm_add_attach_driver') }}</a></li>
                                @endif
                                <li {{{ (Request::is('manage_attached_drivers') ? 'class=active' : '') }}}><a href="{{url("/")}}/manage_attached_drivers">{{ trans('config.lblm_manage_attach_driver') }}</a></li>
                            </ul>
                        </li>


								 @if(Session::get('user_role')  ==1)
								      <li class="nav-dropdown 
                        {{{ (Request::is('add_fare') ? 'open' : '') }}}
                        {{{ (Request::is('manage_fare') ? 'open' : '') }}}
                        {{{ (Request::is('add-tax') ? 'open' : '') }}}
                        {{{ (Request::is('manage-tax') ? 'open' : '') }}}
                        ">
                            <a href="{{url("/")}}/add_fare ">
                                <i class="fa fa-dollar"></i> <span>{{ trans('config.lblm_fare_management') }}</span>
                                <i class="ion-chevron-right pull-right"></i>
                            </a>
                            <ul>
                                <li {{{ (Request::is('add_fare') ? 'class=active' : '') }}}><a href="{{url("/")}}/add_fare ">{{ trans('config.lblm_add_fare_details') }}</a></li>
                                <li {{{ (Request::is('manage_fare') ? 'class=active' : '') }}}><a href="{{url("/")}}/manage_fare ">{{ trans('config.lblm_manage_fare_details') }}</a></li>
								   <li {{{ (Request::is('add-tax') ? 'class=active' : '') }}}><a href="{{url("/")}}/add-tax ">{{ trans('config.lblm_add_tax') }}</a></li>
                                <li {{{ (Request::is('manage-tax') ? 'class=active' : '') }}}><a href="{{url("/")}}/manage-tax ">{{ trans('config.lblm_manage_tax') }}</a></li>
                            </ul>
                        </li>
                    
                        <li class="nav-dropdown
                        {{{ (Request::is('add_offers') ? 'open' : '') }}}
                        {{{ (Request::is('manage_offers') ? 'open' : '') }}}
                        ">

                            <a href="{{url("/")}}/add_offers">
                                <i class="fa fa-plus-circle"></i> <span>{{ trans('config.lbl_offer_manage') }}</span>
                                <i class="ion-chevron-right pull-right"></i>
                            </a>
                            <ul>
                                <li {{{ (Request::is('add_offers') ? 'class=active' : '') }}}><a href="{{url("/")}}/add_offers">{{ trans('config.lbladd_offer') }}</a></li>
                                <li {{{ (Request::is('manage_offers') ? 'class=active' : '') }}}><a href="{{url("/")}}/manage_offers">{{ trans('config.lblmanage_offer') }}</a></li>
                            </ul>
                        </li>

                      <!--  <li class="nav-dropdown">
                            <a href="{{url("/")}}/add_dispatcher ">
                                <i class="ion-male"></i> <span>Dispatcher Details</span>
                                <i class="ion-chevron-right pull-right"></i>
                            </a>
                            <ul>
                                <li><a href="{{url("/")}}/add_dispatcher ">Add Dispatcher</a></li>
                                <li><a href="{{url("/")}}/manage_dispatcher ">Manage Dispatcher</a></li>
                            </ul>
                        </li> -->
                        
                        <li class="nav-dropdown 
                        {{{ (Request::is('add_franchise') ? 'open' : '') }}}
                        {{{ (Request::is('manage_franchise') ? 'open' : '') }}}
                        ">
                            <a href="{{url("/")}}/add_franchise ">
                                <i class="fa fa-bank"></i> <span>Franchise Management</span>
                                <i class="ion-chevron-right pull-right"></i>
                            </a>
                            <ul>
                                <li {{{ (Request::is('add_franchise') ? 'class=active' : '') }}} ><a href="{{url("/")}}/add_franchise ">Add Franchise</a></li>
                                <li {{{ (Request::is('manage_franchise') ? 'class=active' : '') }}}><a href="{{url("/")}}/manage_franchise ">Manage Franchise</a></li>
                            </ul>
                        </li>


                        <li class="nav-dropdown {{{ (Request::is('manage_customers') ? 'open' : '') }}}">
                            <a href="manage_customers ">
                                <i class="fa fa-users"></i> <span>{{ trans('config.lblm_customer_details') }}</span>
                                <i class="ion-chevron-right pull-right"></i>
                            </a>
                            <ul>
                                <li {{{ (Request::is('manage_customers') ? 'class=active' : '') }}}><a href="{{url("/")}}/manage_customers ">{{ trans('config.lblm_manage_customer') }}</a></li>
                            </ul>
                        </li>
                        
                        <li class="nav-dropdown
                        {{{ (Request::is('total_trans') ? 'open' : '') }}}
                        {{{ (Request::is('successful_rides') ? 'open' : '') }}}
                        {{{ (Request::is('cancel_rides') ? 'open' : '') }}}
                        {{{ (Request::is('reject_rides') ? 'open' : '') }}}
                        ">
                            <a href="{{url("/")}}/total_trans ">
                                <i class="ion-document-text"></i> <span>{{ trans('config.lblm_reports') }}</span>
                                <i class="ion-chevron-right pull-right"></i>
                            </a>
                            <ul>
                                <li {{{ (Request::is('total_trans') ? 'class=active' : '') }}}><a href="{{url("/")}}/total_trans ">{{ trans('config.lblm_totla_ride') }}</a></li>
                                <li {{{ (Request::is('successful_rides') ? 'class=active' : '') }}}><a href="{{url("/")}}/successful_rides ">{{ trans('config.lblm_successful_ride') }}</a></li>
                                <li {{{ (Request::is('cancel_rides') ? 'class=active' : '') }}}><a href="{{url("/")}}/cancel_rides ">{{ trans('config.lblm_cancel_ride') }}</a></li>
                                <li {{{ (Request::is('reject_rides') ? 'class=active' : '') }}}><a href="{{url("/")}}/reject_rides ">{{ trans('config.lblm_reject_ride') }}</a></li>
                            </ul>
                        </li>
                     @endif
                      <!-- <li >
                            <a href="{{url("/")}}/setting">
                                <i class="fa fa-gear"></i> <span>Settings</span>
                            </a>                       
                        </li>   -->
                      
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
