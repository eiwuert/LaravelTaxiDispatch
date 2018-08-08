@extends('layout.master');

@section('title')

Manage Offer - Go Cabs

@endsection

@section('content')
<div class="rightside bg-grey-100">
    <!-- BEGIN PAGE HEADING -->
    <div class="page-head">
        <h1 class="page-title">{{ trans('config.lblmanage_offer') }}</h1>
        <!-- BEGIN BREADCRUMB -->
        <a href="{{url("/")}}/add_offers" class="btn btn-dark bg-red-600 color-white pull-right">{{ trans('config.lbladd_offer') }}</a>
        <!-- END BREADCRUMB -->
    </div>
    <!-- START OF FILTER-->
    <!-- <div class="f_filter container-fluid">
        <div class="pull-right col-lg-3 no-padding">
            <form method="GET" action="" name="filter">
                {{ csrf_field() }}
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
                        <div class="panel-head">Offers Information</div>
                    </div>
                    <div class="panel-body no-padding-top bg-white">
                        <br>
                        <ul class="nav nav-tabs tab-grey bg-grey-100">
                            <li class="active"><a href="#fontawesome" id="fontawesome-tab" data-toggle="tab"
                                                  aria-controls="fontawesome" aria-expanded="true">Active Offers</a></li>
                            <li><a href="#ionicons" id="ionicons-tab" data-toggle="tab" aria-controls="ionicons">Expired Offers</a></li>
                            
                                <div id="status"></div>
                            </li>
                        </ul>

                        <div class="tab-content">
                            <div id="fontawesome" aria-labelledBy="fontawesome-tab"
                                 class="panel-body padding-md table-responsive tab-pane in active">
                                <p class="text-light margin-bottom-30"></p>

                                <table class="table table-bordered display" id="dataTables-example">
                                    <thead>
                                    <tr>
                                        <!--<th class="vertical-middle">Select</th>-->
                                        <th class="vertical-middle">Coupon Code</th>
                                        <th class="vertical-middle">Coupon Type</th>
                                        <th class="vertical-middle">Coupon Category</th>

                                        <th class="vertical-middle">Coupon Description</th>
                                        <th class="vertical-middle">Offer From</th>
                                        <th class="vertical-middle">Expiry Date</th>
                                        <th class="vertical-middle">Usage Count</th>
                                        <th class="vertical-middle">Action</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($activeoffers as $nd)
                                    <tr>
                                        <!--<td class="vertical-middle">
                                            <div class="checkbox checkbox-theme no-margin"><input id="checkbox13"
                                                                                                  type="checkbox"><label
                                                        for="checkbox13" class="no-padding"></label></div>-->
                                        </td>
                                        <td class="vertical-middle">{{$nd->coupon_code}} </td>
                                        <td class="vertical-middle">
                                        @if($nd->coupon_type == 2)
                                        Offer
                                        @endif
                                        @if($nd->coupon_type == 1)
                                        Cash Back
                                        @endif
                                        @if($nd->coupon_type == 3)
                                        Free Ride
                                        @endif
                                        </td>
                                        <td class="vertical-middle">
                                            {{ $nd->coupon_basedon == 1 ? "Ride Count":""}}
                                            {{ $nd->coupon_basedon == 2 ? "Ride Value":""}}
                                            {{ $nd->coupon_basedon == 3 ? "Vehicle Type":""}}
                                            {{ $nd->coupon_basedon == 4 ? "User Count":""}}
                                            {{ $nd->coupon_basedon == 5 ? "All User":""}}
                                            {{ $nd->coupon_basedon == 6 ? "Free Ride":""}}
                                        </td>
                                        <td class="vertical-middle">{{$nd->coupon_desc}}</td>
                                        <td class="vertical-middle">
                                        <?php 
                                        $fromdate = date("d-m-Y", strtotime($nd->valid_from)); 
                                        $todate = date("d-m-Y", strtotime($nd->valid_to)); ?>
                                        {{$fromdate}}</td>
                                        <td class="vertical-middle">{{$todate}}</td>
                                        <td class="vertical-middle">{{$nd->usage_count}}</td>
                                        <td class="vertical-middle">
                                            <a  data-toggle="tooltip" title="View" href="view_offers/{{$nd->id}}"><i
                                                        class="fa fa-eye fa-2x"></i></a>&nbsp;
                                           

                                            <a href="#" data-toggle="tooltip" title="Expire"
                                               onclick="deactivate({{ $nd->id}},'expireoffer','Offer');"
                                               data-original-title="Edit">
                                                <i class="fa fa-close fa-2x"></i>
                                            </a>

                                            <!--&nbsp;<a href="#" data-toggle="tooltip" title="Delete"
                                                     onclick="delete1({{ $nd->id}},'deleteoffer','Offer');"><i
                                                        class="fa fa-trash fa-2x"></i>
                                            </a>-->

                                           
                                        </td>
                                    </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>

                            <!-- Second Tab Starts ---------->

                            <div id="ionicons" aria-labelledBy="ionicons-tab"
                                 class="panel-body padding-md table-responsive tab-pane">
                                <p class="text-light margin-bottom-30"></p>
                                <table class="table table-bordered display" >
                                    <thead>
                                    <tr>
                                        <!--<th class="vertical-middle">Select</th>-->
                                        <th class="vertical-middle">Coupon Code</th>
                                        <th class="vertical-middle">Coupon Type</th>
                                        <th class="vertical-middle">Coupon Description</th>
                                        <th class="vertical-middle">Expiry Date</th>
                                        <th class="vertical-middle">Usage Count</th>
                                        <th class="vertical-middle">Action</th>
                                    </tr>
                                    </thead>

                            <tbody>
                                    @foreach($expiredoffers as $nd)
                                    <tr>
                                         <!--<td class="vertical-middle">
                                            <div class="checkbox checkbox-theme no-margin"><input id="checkbox13"
                                                                                                  type="checkbox"><label
                                                        for="checkbox13" class="no-padding"></label></div>-->
                                        </td>
                                        <td class="vertical-middle">{{$nd->coupon_code}} </td>
                                        <td class="vertical-middle">
                                        @if($nd->coupon_type == 2)
                                        Offer
                                        @endif
                                        @if($nd->coupon_type == 1)
                                        Cash Back
                                        @endif
                                        </td>
                                        <td class="vertical-middle">{{$nd->coupon_desc}}</td>
                                        <td class="vertical-middle">{{$nd->valid_to}}</td>
                                        <td class="vertical-middle">{{$nd->usage_count}}</td>
                                        <td class="vertical-middle">
                                            <a  data-toggle="tooltip" title="View" href="view_offers/{{$nd->id}}"><i
                                                        class="fa fa-eye fa-2x"></i></a>&nbsp;
                                           

                                           <!-- <a href="#" data-toggle="tooltip" title="activateoffer"
                                               onclick="activate({{ $nd->id}},'activateoffer','Offer');"
                                               data-original-title="Edit">
                                                <i class="fa fa-close fa-2x"></i>
                                            </a>-->

                                            &nbsp;<a href="#" data-toggle="tooltip" title="Delete"
                                                     onclick="delete1({{ $nd->id}},'deleteoffer','Offer');"><i
                                                        class="fa fa-trash fa-2x"></i>
                                            </a>
                                        </td>
                                    </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>


                            <!-- End of Second Tab -------->

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
        <!-- /.row -->

        <!-- /.row -->


        <!-- BEGIN FOOTER -->
        @include('includes.footer')
        <!-- END FOOTER -->
    </div><!-- /.container-fluid -->
</div>

@endsection
