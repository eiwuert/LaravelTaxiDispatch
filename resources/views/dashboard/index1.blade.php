<div class="rightside bg-grey-100">
			<!-- BEGIN PAGE HEADING -->
            
<div class="page-head bg-grey-100">
				<h1 class="page-title">{{ Config::get('common.dashboard') }}<small>Welcome to WrydesDispatch administration</small></h1>
                <div class="btn bg-grey-600 padding-10-20 no-border color-white pull-right border-radius-5 hidden-xs no-shadow margin-left-10">Total Company Balance<i class="fa fa-dollar margin-left-10 "></i> <span><strong>{{$total_company_share}}</strong></span></div>
                <div class="btn bg-yellow-600 padding-10-20 no-border pull-right border-radius-5 hidden-xs no-shadow margin-left-10">Total Drivers Share Amount<i class="fa fa-dollar margin-left-10 "></i> <span><strong>{{$total_driver_share}}</strong></span></div>
			</div>
            
            
			<!-- END PAGE HEADING -->

            <div class="container-fluid">
				<div class="row">
					<div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
						<div class="panel bg-grey-500">
							<div class="panel-body padding-15-20">
								<div class="clearfix">
									<div class="pull-left">
										<div class="color-white font-size-26 font-roboto font-weight-600" data-toggle="counter" data-start="0" data-from="0" data-to="143" data-speed="500" data-refresh-interval="10">{{count($total_drivers)}}</div>
										<div class="display-block color-white font-weight-600"><i class="ion-person-add"></i> TOTAL DRIVERS</div>
									</div>
									<div class="pull-right">
										<i class="font-size-36 color-white ion-person-add"></i>
									</div>
								</div>
								<div class="progress progress-animation progress-xs margin-top-25 margin-bottom-5">
									<div class="progress-bar bg-grey-600" role="progressbar" aria-valuenow="72" aria-valuemin="0" aria-valuemax="100" style="width: 0%;">
									</div>
								</div>
								<div class="font-size-11 clearfix color-white font-weight-600">
									<div class="pull-left">PROGRESS</div>
									<div class="pull-right">72%</div>
								</div>
							</div>
						</div><!-- /.panel -->
					</div><!-- /.col -->
								
					<div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
						<div class="panel bg-deep-orange-400">
							<div class="panel-body padding-15-20">
								<div class="clearfix">
									<div class="pull-left">
										<div class="color-white font-size-26 font-roboto font-weight-600" data-toggle="counter" data-start="0" data-from="0" data-to="10" data-speed="500" data-refresh-interval="10">{{count($total_cars)}} </div>
										<div class="display-block color-red-50 font-weight-600"><i class="fa fa-cab"></i> TOTAL CARS</div>
									</div>
									<div class="pull-right">
										<i class="font-size-36 color-red-100 fa fa-cab"></i>
									</div>
								</div>
								<div class="progress progress-animation progress-xs margin-top-25 margin-bottom-5">
									<div class="progress-bar bg-red-100" role="progressbar" aria-valuenow="80" aria-valuemin="0" aria-valuemax="100" style="width: 0%;">
									</div>
								</div>
								<div class="font-size-11 clearfix color-red-50 font-weight-600">
									<div class="pull-left">PROGRESS</div>
									<div class="pull-right">80%</div>
								</div>
							</div>
						</div><!-- /.panel -->
					</div><!-- /.col -->
								
					<div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
						<div class="panel bg-blue-400">
							<div class="panel-body padding-15-20">
								<div class="clearfix">
									<div class="pull-left">
										<div class="color-white font-size-26 font-roboto font-weight-600" data-toggle="counter" data-start="0" data-from="0" data-to="43" data-speed="500" data-refresh-interval="10">{{count($today_wryde)}}</div>
										<div class="display-block color-blue-50 font-weight-600"><i class="fa fa-users"></i> TODAY'S TOTAL RIDE</div>
									</div>
									<div class="pull-right">
										<i class="font-size-36 color-blue-100 fa fa-users"></i>
									</div>
								</div>
								<div class="progress progress-animation progress-xs margin-top-25 margin-bottom-5">
									<div class="progress-bar bg-blue-100" role="progressbar" aria-valuenow="45" aria-valuemin="0" aria-valuemax="100" style="width: 0%;">
									</div>
								</div>
								<div class="font-size-11 clearfix color-blue-50 font-weight-600">
									<div class="pull-left">PROGRESS</div>
									<div class="pull-right">45%</div>
								</div>
							</div>
						</div><!-- /.panel -->
					</div><!-- /.col -->
								
					
				</div><!-- /.row -->

                <!-- /.row -->
					
				<div class="row">
                        <div class="col-lg-12">
							<div class="panel no-border ">
                                <div class="panel-body no-padding-top bg-white">
									<h3 class="color-grey-700">Rides on track</h3>
										<!-- START OF FILTER-->
									<div class="dashboard-f-filter">
										<div class="pull-right col-lg-6 no-padding">
											<form method="get" action="" name="filter">
											 {{ csrf_field() }}
											 <table class="table-responsive dashboard-table-filter" style="padding: 4px;">
												 <tr>
													<td width='25%' style="position: relative; top: 5px;"> <label>Search Vehicle ID</label></td>
													<td  width='60%'> <select class="form-control" name="vehicle-number" >
															<option value="">ALL</option>
															 @foreach ($active_vehicles as $vehicle)
															 		<option value="{{$vehicle->vehicle_id}}" {{ session('d_vehiclenum') == $vehicle->vehicle_id ? "selected=selected":''}} >{{$vehicle->car_no}}({{$vehicle->car_board}})</option>
															   @endforeach
															
														</select>
												</td>
													<td  width='20%'><input type="submit" class="btn btn-dark bg-red-600 color-white pull-right" value="Search" /></td>
												 </tr>
											 </table>
											</form>
										</div>
										<div class="clearfix"></div>
									</div>
								<!-- END OF FILTER-->
									<div id="map" class="height-460 margin-bottom-30"></div>
									
									<p class="text-light margin-bottom-30">Current rides.</p>
					
									<p class="text-light margin-bottom-30">List of current rides.</p>
									
									<div id="daily_vehicle_ride"></div>      
									  </div>
                        </div><!-- /.col -->
                    </div><!-- /. row -->
		    </div>
            
            <div class="row">
                        <div class="col-lg-12">
							<div class="panel no-border ">
                                <div class="panel-body no-padding-top bg-white">
									<h3 class="color-grey-700">Today’s Available Drivers and Cars</h3>
									<p class="text-light margin-bottom-30">List of cars for waiting to ride.</p>
								
                            </div>
                        </div><!-- /.col -->
                    </div><!-- /. row -->
		    </div>
            <div class="row">
                        <div class="col-lg-12">
							<div class="panel no-border ">
                                <div class="panel-body no-padding-top bg-white">
									<!--<h3 class="color-grey-700">Individual Drivers Total Share and Company Profit</h3>-->
								<div class="col-lg-12 ">	
									<h4 class="color-grey-700">Individual Drivers Total Share Amount</h4>		
									<div id="driver_profit" style="height:400px;"></div>
								</div>
							
								
                            </div>
                        </div><!-- /.col -->
                    </div>
                    <!-- /. row -->
				
            </div>
            
            <div class="row">
                        <div class="col-lg-12">
							<div class="panel no-border ">
                                <div class="panel-body no-padding-top bg-white">
												<h3 class="color-grey-700">Vehicle Types With Share Amount</h3>
														<div id="cartype_profit" style="min-width: 400px; height: 400px; margin: 0 auto"></div>
                            </div>
                        </div><!-- /.col -->
                    </div>

                    
                    <!-- /. row -->
				<!-- BEGIN FOOTER -->
				@include('includes.footer')
				<!-- END FOOTER -->
            </div>
                <!-- /.row -->
<!-- --------DASHBOARD MAP CONFIGURATION ------------->	
<!-- dashboard -->

	<script>
      var map;
      function initMap() {
        map = new google.maps.Map(document.getElementById('map'), {
          zoom: 16,
		  <?php	foreach($vehicles_status as $val){	?>
          center: new google.maps.LatLng({{$val->lat}}, {{$val->lng}}),
		  <?php  break; } ?>   
		
          mapTypeId: 'roadmap'
        });

        var iconBase = 'http://armorappz.com/wrydesadmin/assets/img/';
        var icons = {
          rides: {
            icon: iconBase + 'ongoingride.png'
          },
          free: {
            icon: iconBase + 'freeride.png'
          }
        };

        function addMarker(feature) {
          var marker = new google.maps.Marker({
            position: feature.position,
            icon: icons[feature.type].icon,
            map: map
          });
        }
		
        var features = [
		<?php
		$i=1;
		foreach($vehicles_status as $val){
		?>
          {
            position: new google.maps.LatLng({{$val->lat}}, {{$val->lng}}),
            type: "<?php echo $val->ride_status;?>"
          } <?php if(count($vehicles_status) > $i++) {echo ",";} ?>
		  <?php }
			?>        
          
        ];

        for (var i = 0, feature; feature = features[i]; i++) {
          addMarker(feature);
        }
      }
    </script>
    

<script src="https://code.highcharts.com/highcharts.js"></script>
<script src="https://code.highcharts.com/modules/exporting.js"></script>

<script>

$(function () {
    Highcharts.chart('driver_profit', {
        chart: {
            type: 'column',
             spacingBottom: 15,
				    spacingTop: 10,
				    spacingLeft: 10,
				    spacingRight: 10,
				    backgroundColor: "#fff",
				    borderColor: "#335cad",

				    // Explicitly tell the width and height of a chart
				    width: null,
				    height: null
            
        },
        title: {
            text: 'Individual Drivers& Company Total Share Amount'
        },
        subtitle: {
            text: ''
        },
        xAxis: {
            categories: [
               	@foreach($individual_driver_share as $individual_share)
			  							'{{$individual_share->driver_name}}',
								@endforeach	
                'Nov',
                'Dec'
            ],
            crosshair: true
        },
        yAxis: {
            min: 0,
            title: {
                text: 'Total Share Amount'
            }
        },
        tooltip: {
            headerFormat: '<span style="font-size:8px">{point.key}</span><table>',
            pointFormat: '<tr><td style="color:{series.color};padding:0">{series.name}: </td>' +
                '<td style="padding:0"><b>{point.y:.1f}</b></td></tr>',
            footerFormat: '</table>',
            shared: true,
            useHTML: true
        },
        plotOptions: {
            column: {
                pointPadding: 0.2,
                borderWidth: 0
            }
        },
        series: [{
            name: 'Individual Drivers Total Share Amount',
            data: [
             @foreach($individual_driver_share as $individual_share)
			  			{{$individual_share->share_amount}},
						@endforeach	
						]

        }, {
            name: 'Individual Drivers Company Profit',
             data: [
             @foreach($individual_company_share as $individual_share)
			  			{{$individual_share->share_amount}},
						@endforeach	
						]

    

        }]
    });
});


/********** Car Model List ************/

$(function () {
    Highcharts.chart('cartype_profit', {
        chart: {
            type: 'column'
        },
        title: {
            text: 'Vehicle Type With Share Amount For Last One Month'
        },
        xAxis: {
            categories: [
                'Seattle HQ',
                'San Francisco',
                'Tokyo',
                 'jappan'
            ]
        },
        yAxis: [{
            min: 0,
            title: {
                text: 'Vehile Type & Amount Gained'
            }
        }, {
            title: {
                text: 'Profit (millions)'
            },
            opposite: true
        }],
        legend: {
            shadow: false
        },
        tooltip: {
            shared: true
        },
        plotOptions: {
            column: {
                grouping: false,
                shadow: false,
                borderWidth: 0
            }
        },
        series: [{
            name: 'Vehicle Type',
            color: 'rgba(165,170,217,1)',
            data: [150, 73,15,22],
            pointPadding: 0.3,
            pointPlacement: -0.2
        }, {
            name: 'Share Amount',
            color: 'rgba(126,86,134,.9)',
            data: [140, 90,55,99],
            pointPadding: 0.4,
            pointPlacement: -0.2
        },]
    });
});

/**************VEHICLE LAST 3 Month Driving Count List ************/

$(function () {
    $.getJSON('https://www.highcharts.com/samples/data/jsonp.php?filename=usdeur.json&callback=?', function (data) {

        Highcharts.chart('daily_vehicle_ride', {
            chart: {
                zoomType: 'x'
            },
            title: {
                text: 'Total Rides Last One Month'
            },
            subtitle: {
                text: document.ontouchstart === undefined ?
                        'Click and drag in the plot area to zoom in' : 'Pinch the chart to zoom in'
            },
            xAxis: {
                type: 'datetime'
            },
            yAxis: {
                title: {
                    text: 'Exchange rate'
                }
            },
            legend: {
                enabled: false
            },
            plotOptions: {
                area: {
                    fillColor: {
                        linearGradient: {
                            x1: 0,
                            y1: 0,
                            x2: 0,
                            y2: 1
                        },
                        stops: [
                            [0, Highcharts.getOptions().colors[0]],
                            [1, Highcharts.Color(Highcharts.getOptions().colors[0]).setOpacity(0).get('rgba')]
                        ]
                    },
                    marker: {
                        radius: 2
                    },
                    lineWidth: 1,
                    states: {
                        hover: {
                            lineWidth: 1
                        }
                    },
                    threshold: null
                }
            },

            series: [{
                type: 'area',
                name: 'USD to EUR',
                data: data
            }]
        });
    });
});
</script>

			
        </script>
    <script async defer
    src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAmH9TdMle9B0kjwx3BERETPVvDsvZYJ-s&callback=initMap">
    </script>

<!-----------END OF MAP CONFIGURATION------------------>			
				<!-- END FOOTER -->
            </div><!-- /.container-fluid -->
        </div><!-- /.rightside -->
    </div><!-- /.wrapper -->
