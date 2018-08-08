//only allow alphanumeric
$('.alphanumeric').keypress(function (e) {
    var specialKeys = new Array();
						 specialKeys.push(8); //Backspace
						 specialKeys.push(9); //Tab
						 var i = 65;
						 for(i=65;i<91;i++){
						 	specialKeys.push(i);
						 }
						 var j = 97;
						 for(j=97;j<123;j++){
						 	specialKeys.push(j);
						 }
					 var keyCode = e.which ? e.which : e.keyCode
					 var ret = ((keyCode >= 48 && keyCode <= 57) || specialKeys.indexOf(keyCode) != -1 || (keyCode >= 65 && keyCode <= 90));
					 return ret;
});

//only number allow 
$('.numeric').keypress(function (e) {
					var specialKeys = new Array();
						 specialKeys.push(8); //Backspace
						 specialKeys.push(9); //Tab
						 
					 var keyCode = e.which ? e.which : e.keyCode
					 console.log(keyCode);
					 var ret = ((keyCode >= 48 && keyCode <= 57) ||  specialKeys.indexOf(keyCode) != -1);
					 return ret;
			    
			});

// image type and size validation
function validateImage(id) {
    var formData = new FormData();
	
    var file = document.getElementById(id).files[0];
    var img = new Image();

        img.src = window.URL.createObjectURL(file);

        img.onload = function() {
            var width = img.naturalWidth,
                height = img.naturalHeight;
            console.log('height is'+height);
            console.log('width is'+width);
        };
    formData.append("Filedata", file);
    var t = file.type.split('/').pop().toLowerCase();
    if (t != "jpeg" && t != "jpg" && t != "png" && t != "bmp" && t != "gif") {
		  bootbox.alert('<div class="ajax_status"><i class="fa fa-check-circle-o success" aria-hidden="true"></i> Please select a valid image file</div>')
     
        document.getElementById(id).value = '';
        return false;
    }
    if (file.size > 3000000) {
        bootbox.alert('<div class="ajax_status"><i class="fa fa-check-circle-o success" aria-hidden="true"></i> Max Upload size is 3MB only</div>')
       document.getElementById(id).value = '';
        return false;
    }
    return true;
}

function validateImagewithdimension(id) {
    var formData = new FormData();
	
    var file = document.getElementById(id).files[0];
    var img = new Image();

        img.src = window.URL.createObjectURL(file);

        img.onload = function() {
            var width = img.naturalWidth,
                height = img.naturalHeight;
            console.log('height is'+height);
            console.log('width is'+width);
            if(height > 50 || width > 100){
            	bootbox.alert('<div class="ajax_status"><i class="fa fa-check-circle-o success" aria-hidden="true"></i> Image Dimension must be 100x50</div>')
     
		        document.getElementById(id).value = '';
		        return false;
            }
        };
    formData.append("Filedata", file);
    var t = file.type.split('/').pop().toLowerCase();
    if (t != "jpeg" && t != "jpg" && t != "png" && t != "bmp" && t != "gif") {
		  bootbox.alert('<div class="ajax_status"><i class="fa fa-check-circle-o success" aria-hidden="true"></i> Please select a valid image file</div>')
     
        document.getElementById(id).value = '';
        return false;
    }
    if (file.size > 3000000) {
        bootbox.alert('<div class="ajax_status"><i class="fa fa-check-circle-o success" aria-hidden="true"></i> Max Upload size is 3MB only</div>')
       document.getElementById(id).value = '';
        return false;
    }
    return true;
}
 
 var block_table = $('#multicheck_block').DataTable({
  	"iDisplayLength": 25,
	  'columnDefs': [
		 {
			'targets': 0,
			'checkboxes': {
			   'selectRow': true
			}
		 }
	  ],
	  'select': {
		 'style': 'multi'
	  },
	  'order': [[1, 'asc']]
   });
 var inactive_table = $('#multicheck_inactive').DataTable({
 	"iDisplayLength": 25,
	  'columnDefs': [
		 {
			'targets': 0,
			'checkboxes': {
			   'selectRow': true
			}
		 }
	  ],
	  'select': {
		 'style': 'multi'
	  },
	  'order': [[1, 'asc']]
   });	   
 var active_table = $('#multicheck_active').DataTable({
 	"iDisplayLength": 25,
	  'columnDefs': [
		 {
			'targets': 0,
			'checkboxes': {
			   'selectRow': true
			}
		 }
	  ],
	  'select': {
		 'style': 'multi'
	  },
	  'order': [[1, 'desc']]
   });		   

// Non active taxi list 
   $('#non-active-taxi').on('submit', function(e){
		e.preventDefault();
      var form = this;
	  var curdata=[];
      var rows_selected = inactive_table.column(0).checkboxes.selected();
		   $.each(rows_selected, function(index, rowId){
				curdata.push($(rowId).val());
			});    	
				var url=window.location.protocol + "//" + window.location.host + "/goapp/taxi/change_bulk_status";
		   
			
			if(curdata.length >0 ){
			  bulk_update_status(curdata,url,"-1"," block the taxi");
			}else{
			   bootbox.alert('<div class="ajax_status"><i class="fa fa-check-circle-o success" aria-hidden="true"></i> No Taxi Selected. </div>')
			}
	});
// blocked active taxi list	
	$('#blocked-taxi').on('submit', function(e){
		e.preventDefault();
      var form = this;
	  var curdata=[];
      var rows_selected = block_table.column(0).checkboxes.selected();
		   $.each(rows_selected, function(index, rowId){
				curdata.push($(rowId).val());
				
			});    	
			var url=window.location.protocol + "//" + window.location.host + "/goapp/taxi/change_bulk_status";
		   if(curdata.length >0 ){
			  bulk_update_status(curdata,url,"0"," activate the taxi");
		   }else{
			   bootbox.alert('<div class="ajax_status"><i class="fa fa-check-circle-o success" aria-hidden="true"></i> No Taxi Selected. </div>')
		   }
	});
// Active taxi list	
	$('#active-taxi').on('submit', function(e){
		e.preventDefault();
      var form = this;
	  var curdata=[];
      var rows_selected = active_table.column(0).checkboxes.selected();
		
		   $.each(rows_selected, function(index, rowId){
				curdata.push($(rowId).val());
				
			});    	
			var url=window.location.protocol + "//" + window.location.host + "/goapp/taxi/change_bulk_status";
		   
			
			if(curdata.length >0 ){
			  bulk_update_status(curdata,url,"-1","block the taxi");
			}else{
			   bootbox.alert('<div class="ajax_status"><i class="fa fa-check-circle-o success" aria-hidden="true"></i> No Taxi Selected. </div>')
			}
		
    });
	// Active taxi list	
	$('#change-status').on('submit', function(e){
		e.preventDefault();
      var form = this;
	  var curdata=[];
	  var cur_status=$("#ch_status").val();
	
      var rows_selected = active_table.column(0).checkboxes.selected();
		
		   $.each(rows_selected, function(index, rowId){
				if($(rowId).val()!= undefined){
					curdata.push($(rowId).val());
				}
			});   
			
			var url=window.location.protocol + "//" + window.location.host + "/goapp/fare/change_bulk_status";
		   
			if(curdata.length >0 ){
			 bulk_update_status(curdata,url,cur_status,'Fare details');
			
			}else{
			   bootbox.alert('<div class="ajax_status"><i class="fa fa-check-circle-o success" aria-hidden="true"></i> No List Selected. </div>')
			}
		
    });
	
	// Active tax list	
	$('#change-tax-status').on('submit', function(e){
		e.preventDefault();
      var form = this;
	  var curdata=[];
	  var cur_status=$("#ch_status").val();
	
      var rows_selected = active_table.column(0).checkboxes.selected();
		
		   $.each(rows_selected, function(index, rowId){
			  curdata.push($(rowId).val());
				
			});    	
			var url=window.location.protocol + "//" + window.location.host + "/goapp/fare/change_tax_status";
		   
		
			if(curdata.length >0 ){
				if(cur_status==1) var key=" activate Tax details";
				else var key = " inactivate Tax details";
			  bulk_update_status(curdata,url,cur_status,key);
			
			}else{
			   bootbox.alert('<div class="ajax_status"><i class="fa fa-check-circle-o success" aria-hidden="true"></i> No Tax Selected. </div>')
			}
		
    });
	
	
	//Update the brand status
	$('#change-brand-status').on('submit', function(e){
		e.preventDefault();
		var form = this;
		var curdata=[];
		var cur_status=$("#ch_status").val();
		var rows_selected = active_table.column(0).checkboxes.selected();
		   $.each(rows_selected, function(index, rowId){
				curdata.push($(rowId).val());
			});    
			var url=window.location.protocol + "//" + window.location.host + "/goapp/brand/change_status";
			if(curdata.length >0 ){
			  bulk_update_status(curdata,url,cur_status,'car brand details');
			}else{
			   bootbox.alert('<div class="ajax_status"><i class="fa fa-check-circle-o success" aria-hidden="true"></i> No Car Brand Selected. </div>')
			}
    });
	
	
		
	//Update the Model status
	$('#change-model-status').on('submit', function(e){
		e.preventDefault();
		var form = this;
		var curdata=[];
		var cur_status=$("#ch_status").val();
		var rows_selected = active_table.column(0).checkboxes.selected();
		   $.each(rows_selected, function(index, rowId){
				curdata.push($(rowId).val());
			});    
			var url=window.location.protocol + "//" + window.location.host + "/goapp/model/change_status";
			if(curdata.length >0 ){
			  bulk_update_status(curdata,url,cur_status,'car model details');
			}else{
			   bootbox.alert('<div class="ajax_status"><i class="fa fa-check-circle-o success" aria-hidden="true"></i> No Car Model Selected. </div>')
			}
    });
//Update the Car Type status
	$('#change-type-status').on('submit', function(e){
		e.preventDefault();
		var form = this;
		var curdata=[];
		var cur_status=$("#ch_status").val();
		var rows_selected = active_table.column(0).checkboxes.selected();
		   $.each(rows_selected, function(index, rowId){
				curdata.push($(rowId).val());
			});    
			var url=window.location.protocol + "//" + window.location.host + "/goapp/type/change_status";
			if(curdata.length >0 ){
			  bulk_update_status(curdata,url,cur_status,'car type details');
			}else{
			   bootbox.alert('<div class="ajax_status"><i class="fa fa-check-circle-o success" aria-hidden="true"></i> No Car Type Selected. </div>')
			}
    });

	//Update the Car color status
	$('#ride_categoy').change(function(){ 
		var cat=$("#ride_categoy").val();
		if(cat==1){
			$("#vehical_color").show();
		}else{
			$("#vehical_color").hide();
			$("#optionsRadios1").val('0');
			$("#optionsRadios2").val('0');
		}
    });

