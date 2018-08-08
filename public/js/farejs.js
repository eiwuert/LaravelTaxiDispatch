jQuery(document).ready(function(){

			var VehicleCategory = $("#VehicleCategory").val();
			var VehicleType = $("#VehicleType").val();
			var FareType = $("#FareType").val();
			var start_time_fare = $("#start_time_fare").val();
			var end_time_fare = $("#end_time_fare").val();

			$.ajaxSetup({
						headers: {
							'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
						}
					})
				   $.ajax({
						type: 'POST',
						url: 'getmanagefare',
						data:  {VehicleCategory: VehicleCategory,VehicleType: VehicleType,FareType: FareType,start_time_fare: start_time_fare,end_time_fare: end_time_fare},
						success: function (data) {
							console.log(data);
							
							$("#multicheck_active").empty();
							$("#multicheck_active").append(data);
						},
						error: function (data) {
							
							console.log('Error:', data);
						}
					});

			$("#button_submit").click(function(){
				var VehicleCategory = $("#VehicleCategory").val();
			var VehicleType = $("#VehicleType").val();
			var FareType = $("#FareType").val();
			var start_time_fare = $("#start_time_fare").val();
			var end_time_fare = $("#end_time_fare").val();
			
			$.ajaxSetup({
						headers: {
							'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
						}
					})
				   $.ajax({
						type: 'POST',
						url: 'getmanagefare',
						data:  {VehicleCategory: VehicleCategory,VehicleType: VehicleType,FareType: FareType,start_time_fare: start_time_fare,end_time_fare: end_time_fare},
						success: function (data) {
							
							$("#multicheck_active").empty();
							$("#multicheck_active").append(data);
						},
						error: function (data) {
							$("#MainContent").html(data.responseText);
							console.log('Error:', data);
						}
					});
			});

			jQuery(".base_fare_rm").empty("");
			$('.timepicker').timepicker({
			timeFormat: "hh:mm tt"
				});

		});