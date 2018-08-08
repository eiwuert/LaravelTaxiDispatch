<script>
      var map;
      function initMap() {
        map = new google.maps.Map(document.getElementById('map'), {
          zoom: 16,
		  <?php if(count($vehicles_status)){	
		  foreach($vehicles_status as $val){	?>
          center: new google.maps.LatLng({{$val->lat}}, {{$val->lng}}),
		  <?php  break; } ?>   
		<?php }else{ ?>
		  center:new google.maps.LatLng(11.0168,76.9558),
		<?php } ?>
          mapTypeId: 'roadmap'
        });

        var iconBase = "{{URL::to('/')}}/public/css/vehicle_icon/";
        var icons = {
          rides: {
            icon: iconBase + 'ongoingride.png'
          },
          free: {
            icon: iconBase + 'freeride.png'
          },
           auto: {
            icon: iconBase + 'auto.png'
          }
          
        };

        function addMarker(feature) {
          var marker = new google.maps.Marker({
            position: feature.position,
            icon: icons[feature.type].icon,
            map: map,
            title:feature.title
          });
        }
		
        var features = [
				<?php	$i=1;
				foreach($vehicles_status as $val){	?>
          {
          
            position: new google.maps.LatLng({{$val->lat}}, {{$val->lng}}),
              	
            position: new google.maps.LatLng({{$val->lat}}, {{$val->lng}}),
            @if($val->ride_category== 1)
            type: "<?php echo $val->ride_status;?>",
            @else
             type: "auto",
            @endif
            title: "<?php echo $val->driver_id;?>",
            
          
          } <?php if(count($vehicles_status) > $i++) {echo ",";} ?>
		  <?php }	?> 
		  ];

        for (var i = 0, feature; feature = features[i]; i++) {
      
          addMarker(feature);
        }
      }
    </script>
  
    <script src="{{ URL::asset("public/js/amcharts.js") }}" type="text/javascript"></script>
    <script src="{{ URL::asset("public/js/serial.js") }}" type="text/javascript"></script>
    <script src="{{ URL::asset("public/js/light.js") }}"></script>
    <script src="https://www.amcharts.com/lib/3/pie.js"></script>
     <script>
//      var chart = AmCharts.makeChart("driver_profit", {
//   "type": "serial",
//      "theme": "light",
//   "categoryField": "year",
//   "rotate": true,
//   "startDuration": 1,
//   "categoryAxis": {
//     "gridPosition": "start",
//     "position": "left"
//   },
//   "trendLines": [],
//   "graphs": [
//     {
//       "balloonText": "Income:[[value]]",
//       "fillAlphas": 0.8,
//       "id": "AmGraph-1",
//       "lineAlpha": 0.2,
//       "title": "Income",
//       "type": "column",
//       "valueField": "income"
//     },
//     {
//       "balloonText": "Expenses:[[value]]",
//       "fillAlphas": 0.8,
//       "id": "AmGraph-2",
//       "lineAlpha": 0.2,
//       "title": "Expenses",
//       "type": "column",
//       "valueField": "expenses"
//     },
//     {
//       "balloonText": "Expensest:[[value]]",
//       "fillAlphas": 0.8,
//       "id": "AmGraph-24",
//       "lineAlpha": 0.2,
//       "title": "Expensest",
//       "type": "column",
//       "valueField": "expensest"
//     }
//   ],
//   "guides": [],
//   "valueAxes": [
//     {
//       "id": "ValueAxis-1",
//       "position": "top",
//       "axisAlpha": 0
//     }
//   ],
//   "allLabels": [],
//   "balloon": {},
//   "titles": [],
//   "dataProvider": [
//     {
//       "year": 2005,
//       "income": 23.5,
//       "expenses": 18.1,
//       "expensest": 34.1
//     },
//     {
//       "year": 2006,
//       "income": 26.2,
//       "expenses": 22.8
//     },
//     {
//       "year": 2007,
//       "income": 30.1,
//       "expenses": 23.9
//     },
//     {
//       "year": 2008,
//       "income": 29.5,
//       "expenses": 25.1
//     },
//     {
//       "year": 2009,
//       "income": 24.6,
//       "expenses": 25
//     },
//     {
//       "year": 2009,
//       "income": 24.6,
//       "expenses": 25
//     },
//     {
//       "year": 2009,
//       "income": 24.6,
//       "expenses": 25
//     },
//     {
//       "year": 2009,
//       "income": 24.6,
//       "expenses": 25
//     },
//     {
//       "year": 2009,
//       "income": 24.6,
//       "expenses": 25
//     },
//     {
//       "year": 2009,
//       "income": 24.6,
//       "expenses": 25
//     },
//     {
//       "year": 2009,
//       "income": 24.6,
//       "expenses": 2576786
//     },
//     {
//       "year": 2009,
//       "income": 24.6,
//       "expenses": 25
//     }
//   ],
//     "export": {
//       "enabled": true
//      }

// });
     AmCharts.makeChart("driver_profit",
				{
					"type": "serial",
					"categoryField": "category",
					"autoMarginOffset": 40,
					"marginRight": 70,
					"marginTop": 70,
					"startDuration": 1,
					"color": "#72ACD9",
					"fontSize": 13,
					"theme": "light",
					"categoryAxis": {
						"gridPosition": "start",
						"labelRotation": 90,
					},
					"trendLines": [],
					"graphs": [
						{
							"balloonText": "[[title]] of <b>[[category]]:Rs [[value]]</b>",
							"fillAlphas": 0.9,
							"id": "AmGraph-1",
							"title": "Total Share Amount",
							"type": "column",
							"valueField": "column-1"
						},
						{
							"balloonText": "[[title]] of <b>[[category]]: Rs [[value]]</b>",
							"fillAlphas": 0.9,
							"id": "AmGraph-2",
							"title": "Company Profit",
							"type": "column",
							"valueField": "column-2"
						}
					],
					"guides": [],
					"valueAxes": [
						{
							"id": "ValueAxis-1",
							"title": "Share Amount (Rs)",
							 "bold": true, 
							 
						}
					],
					 "chartScrollbar": {
						"enabled": true
					},
				"valueScrollbar": {
					"autoGridCount": false,
					"color": "#000000",
				//	"scrollbarHeight": 50,
				//	"gridCount":50,
				//	"labelFrequency":50,
				},
 
					"allLabels": [],
					"balloon": {},
					"titles": [],
					"dataProvider": [
					<?php $i=0; ?>
					@if(count($individual_driver_share) > 0)
					  @foreach($individual_driver_share as $individual_share)
			  			{
							"category": "{{$individual_share->driver_name}}",
							"column-1": {{$individual_share->share_amount}},
							"column-2": {{$individual_company_share[$i++]->share_amount}}
						},
						@endforeach	
					@else
						{
							"category": "",
							"column-1": 0,
							"column-2": 0
						}
					@endif
					]
				}
			);
			
			/*********** LAST THREE MONTH RIDE DETAILS ******/

 AmCharts.makeChart( "vehiecle_ride_details", {
  "type": "serial",
  "theme": "none",
  "autoMarginOffset": 40,
	"marginRight": 70,
	"marginTop": 70,
  "dataDateFormat": "YYYY-MM-DD",
  "valueAxes": [ {
    "id": "v1",
    "axisAlpha": 0,
    "position": "left",
    "ignoreAxisWidth": false,
    "title": "Total Number of Rides"
  } ],
  "balloon": {
    "borderThickness": 1,
    "shadowAlpha": 0
  },
  "graphs": [ {
    "id": "g1",
    "balloon": {
      "drop": true,
      "adjustBorderColor": false,
      "color": "#ffffff",
      "type": "smoothedLine"
    },
    "fillAlphas": 0.2,
    "bullet": "round",
    "bulletBorderAlpha": 1,
    "bulletColor": "#FFFFFF",
    "bulletSize": 5,
    "hideBulletsCount": 50,
    "lineThickness": 2,
    "title": "red line",
    "useLineColorForBulletBorder": true,
    "valueField": "value",
    "balloonText": "<span style='font-size:18px;'>[[value]]</span>"
  } ],
  "chartCursor": {
    "valueLineEnabled": true,
    "valueLineBalloonEnabled": true,
    "cursorAlpha": 0,
    "zoomable": false,
    "valueZoomable": true,
    "valueLineAlpha": 0.5
  },
  "chartScrollbar": {
						"enabled": true
					},
  "valueScrollbar": {
    "autoGridCount": true,
    "color": "#000000",
    "scrollbarHeight": 50
  },
  "categoryField": "date",
  "categoryAxis": {
    "parseDates": true,
    "dashLength": 1,
    "minorGridEnabled": true
  },
  "export": {
    "enabled": true
  },
  "titles": [
		{
			"id": "successful_rides_chart",
			"size": 15,
			"text": "Total Successful Rides"
		}
	],
  "dataProvider": [ 
						@if(count($latest_ride_details) > 0)
					  @foreach($latest_ride_details as $ride_details)
			  			{
						"date": "{{$ride_details->date_of_ride}}",
   					 "value": {{$ride_details->ride_count}}
						},
					@endforeach	
					@else
							{
						"date": "<?php echo date('Y-m-d'); ?>",
   					 "value": 0
						},
						@endif
	
		]
} );

	/************	VECHILE TYPE AND COUNT ************/
	
 AmCharts.makeChart( "vehicle_type_list", {
  "type": "pie",
  "theme": "light",
 labelsEnabled: false,
  autoMargins: false,
  marginTop: 0,
  marginBottom: 0,
  marginLeft: 0,
  marginRight: 0,
  pullOutRadius: 0,

  "dataProvider": [  
   		 @foreach($vehicletype_details as $vehicletype_cnt)
		  			{
					"vehicle_type": "{{$vehicletype_cnt->vehicle_type}}",
 					 "value": '{{$vehicletype_cnt->vehicle_cnt}}'
					},
				@endforeach	
					  ],
  "valueField": "value",
  "titleField": "vehicle_type",
  "outlineAlpha": 0.4,
  "depth3D": 15,
  "balloonText": "[[title]]<br><span style='font-size:14px'><b>[[value]]</b> ([[percents]]%)</span>",
  "angle": 30,
  "export": {
    "enabled": true
  }
} );
	/************	VECHILE TYPE SHARE ************/
	
 AmCharts.makeChart( "vehicle_type_share", {
  "type": "pie",
  "theme": "light",
 labelsEnabled: false,
  autoMargins: false,
  marginTop: 0,
  marginBottom: 0,
  marginLeft: 0,
  marginRight: 0,
  pullOutRadius: 0,
  
  "dataProvider": [ {
    "ride_status": "Completed Ride",
    "value": {{$completed_ride[0]->ride_cnt}},
    "color": "#f65a07"
  }, {
    "ride_status": "Rejected By Customer",
    "value": {{$rejectby_customer[0]->ride_cnt}},
     "color": "#f1915e"
  },{
    "ride_status": "Rejected By Driver",
    "value": {{$rejectby_driver[0]->ride_cnt}},
     "color": "#f16820"
  },{
    "ride_status": "Auto denied",
    "value": {{$auto_denied[0]->ride_cnt}},
     "color": "#af4209"
  }],
  "valueField": "value",
  "titleField": "ride_status",
  "colorField": "color",
  "labelColorField": "color",
  "outlineAlpha": 0.4,
  "depth3D": 15,
  "balloonText": "[[title]]<br><span style='font-size:14px'><b>[[value]]</b> ([[percents]]%)</span>",
  "angle": 30,
  "export": {
    "enabled": true
  }
} );
        </script>
        
        
        
    <script async defer
    src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAmH9TdMle9B0kjwx3BERETPVvDsvZYJ-s&callback=initMap">
    </script>

