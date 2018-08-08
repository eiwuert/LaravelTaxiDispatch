/*

	Active the data status - Generic function
	id- Curresponding id -- exam if user master then user id should pass
	url- Curresponding Application URL
	variable_name - DIsplay the message in while confirmation box alert
	METHOD post method Implemented  

*/ 

function deletefare(id){
	 bootbox.confirm({
			message: "Do you want to Delete the fare ?",
			buttons: {
				confirm: {
					label: 'Yes',
					className: 'btn-success'
				},
				cancel: {
					label: 'No',
					className: 'btn-danger'
				}
			},
			callback: function (result) {
				if(result==true){
					$.ajaxSetup({
						headers: {
							'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
						}
					})
				   $.ajax({
						type: 'POST',
						url: 'deletefare',
						data:  {id: id},
						dataType: 'json',
						success: function (data) {
							//Bootstrap  success message 
							bootbox.alert('<div class="ajax_status"><i class="fa fa-check-circle-o success" aria-hidden="true"></i>'+ data.Response +'  </div>');
							document.cookie = "tabstatus=2";
							setTimeout(function(){ window.location.reload(); }, 3000);
						},
						error: function (data) {
							console.log('Error:', data);
						}
					});
				}
			}	
		});
	}

	function activate(id,url,variable_name){
	 bootbox.confirm({
			message: "Do you want to Activate the "+variable_name +" ?",
			buttons: {
				confirm: {
					label: 'Yes',
					className: 'btn-success'
				},
				cancel: {
					label: 'No',
					className: 'btn-danger'
				}
			},
			callback: function (result) {
				if(result==true){
					$.ajaxSetup({
						headers: {
							'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
						}
					})
				   $.ajax({
						type: 'POST',
						url: url,
						data:  {data_id: id},
						dataType: 'json',
						success: function (data) {
							//Bootstrap  success message 
							bootbox.alert('<div class="ajax_status"><i class="fa fa-check-circle-o success" aria-hidden="true"></i>'+ data.Response +'  </div>');
							document.cookie = "tabstatus=2";
							setTimeout(function(){ window.location.reload(); }, 3000);
						},
						error: function (data) {
							console.log('Error:', data);
						}
					});
				}
			}	
		});
	}
 
/*

	Deactive the data status - Generic function
	id- Curresponding id -- exam if user master then user id should pass
	url- Curresponding Application URL
	variable_name - DIsplay the message in while confirmation box alert
	METHOD post method Implemented  

*/ 

function deactivate(id,url,variable_name){
	 bootbox.confirm({
			message: "Do you want to block this "+variable_name +" ?",
			buttons: {
				confirm: {
					label: 'Yes',
					className: 'btn-success'
				},
				cancel: {
					label: 'No',
					className: 'btn-danger'
				}
			},
			callback: function (result) {
				if(result==true){
					$.ajaxSetup({
						headers: {
							'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
						}
					})
					$.ajax({
						type: 'POST',
						url: url,
						data:  {data_id: id,},
						dataType: 'json',
						success: function (data) { 
							if(data.Status = 2){
								bootbox.alert('<div class="ajax_status"><i class="fa fa-check-circle-o success" aria-hidden="true"></i>'+ data.Response +' </div>');
								document.cookie = "tabstatus=2";
								setTimeout(function(){ window.location.reload(); }, 3000);
							}else{
								if(data.Status == 1)
								{
									bootbox.alert('<div class="ajax_status"><i class="fa fa-check-circle-o success" aria-hidden="true"></i>'+ data.Response +'  </div>');
								}
								else
								{
									bootbox.alert('<div class="ajax_status"><i class="fa fa-check-circle-o success" aria-hidden="true"></i>'+ variable_name +' blocked successfully . </div>');
								document.cookie = "tabstatus=3";
								setTimeout(function(){ window.location.reload(); }, 3000);
								}
							}
							
						  
						},
						error: function (data) {
							console.log('Error:', data);
						}
					});
				}
			}	
		});
	}
	
	/*

	Block the data status - Generic function
	id- Curresponding id -- exam if user master then user id should pass
	url- Curresponding Application URL
	variable_name - DIsplay the message in while confirmation box alert
	METHOD post method Implemented  

*/ 

function block_list(id,url,variable_name,tabstatus){

	 bootbox.confirm({
			message: "Do you want to Block the selected "+variable_name + " ?",
			buttons: {
				confirm: {
					label: 'Yes',
					className: 'btn-success'
				},
				cancel: {
					label: 'No',
					className: 'btn-danger'
				}
			},
			callback: function (result) {
				if(result==true){
					$.ajaxSetup({
						headers: {
							'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
						}
					})
					$.ajax({
						type: 'POST',
						url:url,
						data:  {data_id: id},
						dataType: 'json',
						success: function (data) {
						
							bootbox.alert('<div class="ajax_status"><i class="fa fa-check-circle-o success" aria-hidden="true"></i>'+ data.Response +' . </div>');
							setTimeout(function(){ window.location.reload(); }, 3000);
							if(data.Status ==2){
								document.cookie = "tabstatus=2";
							}else{
								document.cookie = "tabstatus="+tabstatus;
							}
							
							//setTimeout(function(){ window.location.reload(); }, 3000);
						
						  
						},
						error: function (data) {
							console.log('Error:', data);
						}
					});
				}
			}	
		});
	}
	
	
	/*

	Update the status multiple record
	id- Curresponding id -- exam if user master then user id should pass
	url- Curresponding Application URL
	variable_name - DIsplay the message in while confirmation box alert
	METHOD post method Implemented  

*/ 
	
	function bulk_update_status(id,url,curstatus,variable_name){
	
	 bootbox.confirm({
			message: "Do you want to"+variable_name + " ?",
			buttons: {
				confirm: {
					label: 'Yes',
					className: 'btn-success'
				},
				cancel: {
					label: 'No',
					className: 'btn-danger'
				}
			},
			callback: function (result) {
				if(result==true){
					$.ajaxSetup({
						headers: {
							'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
						}
					})
				   $.ajax({
						type: 'POST',
						url: url,
						data:  {curdata: id,curstatus:curstatus},
						dataType: 'json',
						success: function (data) {
							bootbox.alert('<div class="ajax_status"><i class="fa fa-check-circle-o success" aria-hidden="true"></i> status updated successfully . </div>')
							setTimeout(function(){ window.location.reload(); }, 3000);
							if(curstatus==1){
								document.cookie = "tabstatus=1";
							}else if(curstatus = -1){
								document.cookie = "tabstatus=2";
							}else{
								document.cookie = "tabstatus=3";
							}
							//setTimeout(function(){ window.location.reload(); }, 3000);
						},
						error: function (data) {
							console.log('Error:', data);
						}
					});
				}
			}	
		});
	}
 
//******** GET THE STATE & CITY LIST ,WHILE LOADING AND ONCHANGE***********//

    var country =$("#country").val();
     var temp_state =$("#temp_state").val();
     if(country!=""){
	
        getstatelist(country);
       
     }
    $("#country").change(function(){ 
        var country=$("#country").val();
        if(country!="") {
             getstatelist(country);
        }
    });
    


    //get state list

    $("#state").change(function(){
        var state=$("#state").val();
        if(state!="") {
                getcitylist(state); 
        }
    });
    
    function getstatelist(country){ 
		var url=window.location.protocol + "//" + window.location.host + "/goapp/state/getstatelist";
            var temp_state =$("#temp_state").val();
			var temp_city =$("#temp_city").val(); 
              $.ajax({
        	type: 'GET',
        	url: url,
        	data:  {data_id: country},
        	dataType: 'json',
        	success: function (data) { 
        	    $('#state').empty();
				 $('#city').empty();
				 $('#state').append('<option value="" >--Select State-- </option>');
                $.each(data, function(i, item) {
                    if(temp_state==item.id) {
                         $('#state').append('<option value="'+item.id+'" selected=selected>'+item.name+' </option>');
                     }else{
                         $('#state').append('<option value="'+item.id+'">'+item.name+' </option>');
                     }
                   // console.log(item.name);
               });
                if(temp_city!=""){
                     getcitylist(temp_state);
                }
            },
        	error: function (data) { 
        		console.log('Error:', data);
        	}
         
        });
    }
    function getcitylist(stateid){
		var url=window.location.protocol + "//" + window.location.host + "/goapp/city/getcitylist";
          var temp_city =$("#temp_city").val();
                $.ajax({
        	type: 'GET',
        	url: url,
        	data:  {data_id: stateid},
        	dataType: 'json',
        	success: function (data) {
        	    $('#city').empty();
				  $('#city').append('<option value="" >--Select City-- </option>');
                $.each(data, function(i, item) {
                    if(temp_city==item.id) {
                         $('#city').append('<option value="'+item.id+'" selected=selected>'+item.city+' </option>');
                     }else{
                         $('#city').append('<option value="'+item.id+'">'+item.city+' </option>');
                     }
                });
               },
        	error: function (data) {
        		console.log('Error:', data);
        	}
         
        });
    }
   

   // Delete 
function delete1(id,url,variable_name){
	 bootbox.confirm({
			message: "Do you want to delete the "+variable_name + " ?",
			buttons: {
				confirm: {
					label: 'Yes',
					className: 'btn-success'
				},
				cancel: {
					label: 'No',
					className: 'btn-danger'
				}
			},
			callback: function (result) {
				if(result==true){
					$.ajaxSetup({
						headers: {
							'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
						}
					})
				   $.ajax({
						type: 'POST',
						url: url,
						data:  {data_id: id},
						dataType: 'json',
						success: function (data) { 
							//Bootstrap  success message 

							bootbox.alert('<div class="ajax_status"><i class="fa fa-check-circle-o success" aria-hidden="true"></i>'+ data.Response +' </div>');
							if(url !=='rating/delete'){
								document.cookie = "tabstatus=3";
							}
							setTimeout(function(){ window.location.reload(); }, 3000);
						},
						error: function (data) {
							console.log('Error:', data);
						}
					});
				}
			}	
		});
	}
	
	
//******** GET VEHICLE MODEL AND TYPE BASED ON VEHICLE CATEGORY *******//
 
 function getmodel_type_list(vehicle_type){
		var url=window.location.protocol + "//" + window.location.host + "/goapp/taxi/getmodel_type_list";
          var t_model =$("#t_model").val();
		  var t_type =$("#t_type").val();
		  var color='';
		  $.ajaxSetup({ headers: { 'csrftoken' : '{{ csrf_token() }}' } });
                $.ajax({
        	type: 'GET',
        	url: url,
        	data:  {category_id: vehicle_type},
        	dataType: 'json',
			
        	success: function (data) {
        	    $('#taxi-type').empty();
				$('#taxi-model').empty();
				//Type displaying
				$('#taxi-type').append('<option value="" >--Select Type-- </option>');
                $.each(data.type_list, function(i, item) {
					if(item.car_board == 1) { color = '(W)'; } else if(item.car_board == 2) { color ='(Y)'; }
					if(t_model==item.id) {
                         $('#taxi-type').append('<option value="'+item.id+'" selected=selected>'+item.car_type+' '+color+' </option>');
                     }else{
                         $('#taxi-type').append('<option value="'+item.id+'">'+item.car_type+' '+color+' </option>');
                     }
                });
				//Model displaying
				$('#taxi-model').append('<option value="" >--Select Model-- </option>');
                $.each(data.model_list, function(i, item) {
					if(t_type==item.id) {
                         $('#taxi-model').append('<option value="'+item.id+'" selected=selected>'+item.model+' </option>');
                     }else{
                         $('#taxi-model').append('<option value="'+item.id+'">'+item.model+' </option>');
                     }
                });
               },
        	error: function (data) {
        		console.log('Error:', data);
        	}
         
        });
    }
   
   
//******** GET VEHICLE CAR AND DRIVER BASED ON VEHICLE CATEGORY *******//
 
 $("#VCategory").change(function(){
        var state=$("#VCategory").val();
        if(state!="") {
                getvehicle_driver_list(state); 
        }
    });

// Ride category change function



 function getvehicle_driver_list(vehicle_type){

		var url=window.location.protocol + "//" + window.location.host + "/goapp/assign_taxi/getvehicle_driver_list";
          var t_driver =$("#t_driver").val();
		  var t_type =$("#t_vehicle").val();
		  
		   var assign_taxi_id =$("#assign_taxi_id").val();
		  $.ajaxSetup({ headers: { 'csrftoken' : '{{ csrf_token() }}' } });
                $.ajax({
        	type: 'GET',
        	url: url,
        	data:  {category_id: vehicle_type,assign_taxi_id:assign_taxi_id},
        	dataType: 'json',
			
        	success: function (data) {
    
				$("#CarNumberDyn").empty();
				//Type displaying
				
				$('#CarNumberDyn').append('<option value="" >Select Vehical No</option>');
		
				
					$.each(data.vehicle_list, function(i, item) {
						if(t_driver==item.id) {
							 $("#CarNumberDyn").append('<option value="'+item.id+'" selected=selected>'+item.car_no+' </option>');
						 }else{
						 	console.log('car id'+item.id);
							 $("#CarNumberDyn").append('<option value="'+item.id+'">'+item.car_no+' </option>');
						 }
					});
					  $("#CarNumberDyn").trigger("chosen:updated");
				jQuery('#CarNumberDyn').trigger('render');
				$("#DriverNameDyn").empty();
				//Model displaying
				$('#DriverNameDyn').append('<option value="" >Select Driver Id</option>');
				
                $.each(data.driver_list, function(i, item) {
					if(t_driver==item.id) {
                         $("#DriverNameDyn").append('<option value="'+item.id+'" selected=selected>'+item.driver_name+' </option>');
                     }else{
                         $("#DriverNameDyn").append('<option value="'+item.id+'">'+item.driver_name+' </option>');
                     }
                });
         $("#DriverNameDyn").trigger("chosen:updated");
               },
        	error: function (data) {
        		console.log('Error:', data);
        	}
         // $('#CarNumberDyn').trigger('render');
						// $('#CarNumberDyn').trigger('render');
        });
       
        
    
        
    }
   
//******** GET VEHICLE TYPES  BASED ON VEHICLE CATEGORY *******//


 function gettype_basedonfare(vehicle_type){
		var url=window.location.protocol + "//" + window.location.host + "/goapp/fare/gettype_basedonfare";
         var t_type =$("#t_vehicle").val();
		 var color = '';
		$.ajaxSetup({ headers: { 'csrftoken' : '{{ csrf_token() }}' } });
        $.ajax({
        	type: 'GET',
        	url: url,
        	data:  {category_id: vehicle_type,},
        	dataType: 'json',
	    	success: function (data) {
				fare_status_based_vehicle(vehicle_type);
				$('#taxt_type').empty();
				//Type displaying
				$('#taxt_type').append('<option value="" >--Select-- </option>');
					$.each(data.type_list, function(i, item) {
						if(item.car_board == 1) { color = '(W)'; } else if(item.car_board == 2) { color ='(Y)'; }
						if(t_type==item.id) {
							 $('#taxt_type').append('<option value="'+item.id+'" selected=selected>'+item.car_type +' '+color+' </option>');
						 }else{
							 $('#taxt_type').append('<option value="'+item.id+'">'+item.car_type+' '+color+' </option>');
						 }
					});
			   },
        	error: function (data) {
        		console.log('Error:', data);
        	}
         
        });
    }
	
	function fare_status_based_vehicle(category_id){
		$('#fare_type').empty();
		$('#fare_type').append('<option value="" >--Select--</option>');
		$('#fare_type').append('<option value="1">Base fare</option>');
		if(category_id == 1 || category_id == 2){
		
			// $('#fare_type').append('<option value="2">Morning time</option>');
			// $('#fare_type').append('<option value="3">Night time</option>');
			$('#fare_type').append('<option value="4">Peak time</option>');
			$('#fare_type').append('<option value="5">Special time</option>');
		}
	}

    // Added for offers page dynamic loading of vehicle Type from Vehicle category
	$("#VehicleCategory").change(function(){
        var VehicleCategory=$("#VehicleCategory").val();
        if(VehicleCategory!="") {
             getvehicletype(VehicleCategory);
        }
    });

	function getvehicletype(VehicleCategory){
		var url=window.location.protocol + "//" + window.location.host + "/goapp/getvehicletype";
		var color='';
          	$.ajaxSetup({
						headers: {
							'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
						}
					})
            $.ajax({
        	type: 'POST',
        	url: url, 
        	data:  {data_id: VehicleCategory},
        	dataType: 'json',
        	success: function (data) {
        		console.log(data);
        	    $('#VehicleType').empty();
				  $('#VehicleType').append('<option value="" >--Select VehicleType-- </option>');
                $.each(data, function(i, item) {
						if(item.car_board == 1) { color = '(W)'; } else if(item.car_board == 2) { color ='(Y)'; }
                         $('#VehicleType').append('<option value="'+item.id+'">'+item.car_type+' '+color+ ' </option>');
                     
                });
               },
        	error: function (data) {
        		console.log('Error:', data);
        	}
         
        });
    }

// GET Model based on Brand

$("#VehicleBrand").change(function(event){
		event.preventDefault();
		$('#onModel').hide();
		$('#ofModel').show();
        var VehicleBrand=$("#VehicleBrand").val();
        if(VehicleBrand!="") {
                get_model(VehicleBrand); 
        }
    });



function get_model(vehicle_type){
		var url=window.location.protocol + "//" + window.location.host + "/goapp/model/get_model";
          var t_model =$("#t_model").val();
		  var t_type =$("#t_type").val();
		  $.ajaxSetup({ headers: { 'csrftoken' : '{{ csrf_token() }}' } });
                $.ajax({
        	type: 'GET',
        	url: url,
        	data:  {category_id: vehicle_type},
        	dataType: 'json',
			
        	success: function (data) {
        	    
				$('#taxi-model').empty();
				
			
				//Model displaying
				$('#taxi-model').append('<option value="" >--Select Model-- </option>');
                $.each(data.model_list, function(i, item) {
					if(t_type==item.id) {
                         $('#taxi-model').append('<option value="'+item.id+'" selected=selected>'+item.model+' </option>');
                     }else{
                         $('#taxi-model').append('<option value="'+item.id+'">'+item.model+' </option>');
                     }
                });
               },
        	error: function (data) {
        		console.log('Error:', data);
        	}
         
        });
    }


//******** GET VEHICLE AND DRIVER  BASED ON FRANCHISE *******//


$("#franchise").change(function(){
        var franchise=$("#franchise").val();
        if(franchise!="") {
                getvehicledriveroffranchise(franchise); 
               //  alert(franchise);
        }
    });

 function getvehicledriveroffranchise(franchiseid){

		var url=window.location.protocol + "//" + window.location.host + "/goapp/getvehicledriver/"+franchiseid;
          
          var t_driver =$("#t_driver").val();
		  var t_type =$("#t_vehicle").val();
		  
		   var assign_taxi_id =$("#assign_taxi_id").val();
		  
		  $.ajaxSetup({ headers: { 'csrftoken' : '{{ csrf_token() }}' } });
                $.ajax({
        	type: 'GET',
        	url: url,
        	dataType: 'json',
			
        	success: function (data) {
    
				$("#vehicle").empty();
				//Type displaying
				
				$('#vehicle').append('<option value="" >Select Vehicle No</option>');
		
				
					$.each(data.vehicle_list, function(i, item) {
						if(t_driver==item.id) {
							 $("#vehicle").append('<option value="'+item.id+'" selected=selected>'+item.car_no+'--'+item.car_type+'</option>');
						 }else{
						 	console.log('car id'+item.id);
							 $("#vehicle").append('<option value="'+item.id+'">'+item.car_no+'--'+item.car_type+' </option>');
						 }
					});
					  $("#vehicle").trigger("chosen:updated");
				jQuery('#vehicle').trigger('render');
				$("#driver").empty();
				//Model displaying
				$('#driver').append('<option value="" >Select Driver Id</option>');
				
                $.each(data.driver_list, function(i, item) {
					if(t_driver==item.id) {
                         $("#driver").append('<option value="'+item.id+'" selected=selected>'+item.driver_id+'--'+item.firstname+item.lastname+' </option>');
                     }else{
                         $("#driver").append('<option value="'+item.id+'">'+item.driver_id+'--'+item.firstname+item.lastname+' </option>');
                     }
                });
         $("#driver").trigger("chosen:updated");
               },
        	error: function (data) {
        		console.log('Error:', data);
        	}
         // $('#CarNumberDyn').trigger('render');
						// $('#CarNumberDyn').trigger('render');
        });
        
    }