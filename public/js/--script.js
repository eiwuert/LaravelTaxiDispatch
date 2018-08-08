 var block_table = $('#multicheck_block').DataTable({
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

// Non active taxi list 
   $('#non-active-taxi').on('submit', function(e){
		e.preventDefault();
      var form = this;
	  var curdata=[];
      var rows_selected = inactive_table.column(0).checkboxes.selected();
		   $.each(rows_selected, function(index, rowId){
				var n = rowId.lastIndexOf("=");
			var n=rowId.substr(n)
			var cur_val = n.replace('"', '').replace('"', '').replace('=', '').replace('>', '');
			 curdata.push(cur_val);
				
			});    	
				var url=window.location.protocol + "//" + window.location.host + "/wrydes/taxi/change_bulk_status";
		   
			
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
				var n = rowId.lastIndexOf("=");
				var n=rowId.substr(n)
				var cur_val = n.replace('"', '').replace('"', '').replace('=', '').replace('>', '');
				curdata.push(cur_val);
				
			});    	
			var url=window.location.protocol + "//" + window.location.host + "/wrydes/taxi/change_bulk_status";
		   if(curdata.length >0 ){
			  bulk_update_status(curdata,url,"0"," Activate the taxi");
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
				var n = rowId.lastIndexOf("=");
			var n=rowId.substr(n)
			var cur_val = n.replace('"', '').replace('"', '').replace('=', '').replace('>', '');
			 curdata.push(cur_val);
				
			});    	
			var url=window.location.protocol + "//" + window.location.host + "/wrydes/taxi/change_bulk_status";
		   
			
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
				var n = rowId.lastIndexOf("=");
			var n=rowId.substr(n)
			var cur_val = n.replace('"', '').replace('"', '').replace('=', '').replace('>', '');
			 curdata.push(cur_val);
				
			});    	
			var url=window.location.protocol + "//" + window.location.host + "/wrydes/fare/change_bulk_status";
		   
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
				var n = rowId.lastIndexOf("=");
			var n=rowId.substr(n)
			var cur_val = n.replace('"', '').replace('"', '').replace('=', '').replace('>', '');
			 curdata.push(cur_val);
				
			});    	
			var url=window.location.protocol + "//" + window.location.host + "/wrydes/fare/change_tax_status";
		   
		
			if(curdata.length >0 ){
			  bulk_update_status(curdata,url,cur_status,'Tax details');
			
			}else{
			   bootbox.alert('<div class="ajax_status"><i class="fa fa-check-circle-o success" aria-hidden="true"></i> No Tax Selected. </div>')
			}
		
    });
	
	



	$('#activate-customer').on('submit', function(e){
		e.preventDefault();
      var form = this;
	  var curdata=[];
	  var cur_status=$("#ch_status").val();
	
      var rows_selected = active_table.column(0).checkboxes.selected();
		
		   $.each(rows_selected, function(index, rowId){
				var n = rowId.lastIndexOf("=");
			var n=rowId.substr(n)
			var cur_val = n.replace('"', '').replace('"', '').replace('=', '').replace('>', '');
			 curdata.push(cur_val);
				
			});    	
			var url="bulkactivatecustomer";
		   
		
			if(curdata.length >0 ){
			  //activate(curdata,url,'Tax details');
			console.log('inside ');
			}else{
			   bootbox.alert('<div class="ajax_status"><i class="fa fa-check-circle-o success" aria-hidden="true"></i> No Tax Selected. </div>')
			}
		
    });




    $('#block-customer').on('submit', function(e){
		e.preventDefault();
      var form = this;
	  var curdata=[];
	  var cur_status=$("#ch_status").val();
	
      var rows_selected = active_table.column(0).checkboxes.selected();
		
		   $.each(rows_selected, function(index, rowId){
				var n = rowId.lastIndexOf("=");
			var n=rowId.substr(n)
			var cur_val = n.replace('"', '').replace('"', '').replace('=', '').replace('>', '');
			 curdata.push(cur_val);
				
			});    	
			var url="bulkblockcustomer";
		   
		
			if(curdata.length >0 ){
			  //deactivate(curdata,url,'Tax details');
			console.log('inside');
			}else{
			   bootbox.alert('<div class="ajax_status"><i class="fa fa-check-circle-o success" aria-hidden="true"></i> No Tax Selected. </div>')
			}
		
    });
