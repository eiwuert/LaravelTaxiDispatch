@extends('layout.master');

@section('title')

Manage Customers - Go Cabs
@endsection

@section('content')
<div class="rightside bg-grey-100">
    <!-- BEGIN PAGE HEADING -->
    <div class="page-head">
        <h1 class="page-title">Manage Customers</h1>
        <!-- BEGIN BREADCRUMB -->
        <!-- END BREADCRUMB -->
    </div>
    <!-- END PAGE HEADING -->

    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12">
                <div class="panel no-border">
                    <div class="panel-title bg-amber-200">
                        <div class="panel-head">Customer List</div>
                    </div>
                    <div class="panel-body no-padding-top bg-white">
                        <br>
                        <ul class="nav nav-tabs tab-grey bg-grey-100">
                            <li class="active">
                                <a href="#fontawesome" id="fontawesome-tab" data-toggle="tab"
                                   aria-controls="fontawesome" aria-expanded="true">Active Customers</a>
                            </li>
                            <li>
                                <a href="#ionicons" id="ionicons-tab" data-toggle="tab" aria-controls="ionicons">Blocked Customers</a>
                            </li>

                            <li>
                                <div id="status"></div>
                            </li>
                        </ul>
                        <p class="text-light margin-bottom-30"></p>
                        <div class="tab-content">
                            <div id="fontawesome" aria-labelledBy="fontawesome-tab"
                                 class="panel-body padding-md table-responsive tab-pane in active">
                                 
                                <table class="table table-bordered display" id="mvvulticheck_active">
                                    <thead>
                                    <tr>
                                        <!-- <th class="vertical-middle">Select</th> -->
                                        <th class="vertical-middle">Name</th>
                                        <th class="vertical-middle">Mobile No.</th>
                                        <th class="vertical-middle">Email</th>

                                        <th class="vertical-middle">Action</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($adata as $d)
                                    <tr>
                                        <!-- <td class="vertical-middle">
                                            
                                            <input type="checkbox" class="checkf" name="checkbox[]" value="{{$d->id}}">
                                            
                                        </td> -->
                                        <td class="vertical-middle">{{$d->name}}</td>
                                        <td class="vertical-middle">{{$d->mobile}}</td>
                                        <td class="vertical-middle">{{$d->email}}</td>
                                        <td class="vertical-middle ">
                                            <a  onclick="deactivate({{ $d->id}},'blockcustomer','Customer');" title="Block" class="" ><i class="fa fa-close fa-2x"></i></a>
                                        </td>
                                    </tr>
                                    @endforeach

                                    </tbody>
                                </table>
                                <!-- <div class="form-group ">
                                    <label for="change" class="col-sm-1 control-label no-padding">Change</label>
                                    <div class="col-sm-2 no-padding">
                                      <select class="form-control">
                                        <option>Blocked</option>
                                      </select>
                                    </div>
                                    <div class="col-sm-2 ">
                                   <button id="btn_block" class="btn btn-danger" @if(count($adata)==0) {{'disabled=disabled'}} @endif >Change</button>
                                  </div>
                                  </div> -->
                                    
                            </div>




                            <div id="ionicons" aria-labelledBy="ionicons-tab"
                                 class="panel-body padding-md table-responsive tab-pane">
                                
                                <table class="table table-bordered display" id="gmulticheck_inactive" >
                                    <thead>
                                    <tr>
                                        <!-- <th class="vertical-middle">Select</th> -->
                                        <th class="vertical-middle">Name</th>
                                        <th class="vertical-middle">Mobile No.</th>
                                        <th class="vertical-middle">Email</th>

                                        <th class="vertical-middle">Action</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($bdata as $d)
                                    <tr>
                                        <!-- <td class="vertical-middle">
                                            
                                            <input type="checkbox" class="checkf" name="checkbox[]" value="{{$d->id}}">
                                            
                                        </td> -->
                                        <td class="vertical-middle">{{$d->name}}</td>
                                        <td class="vertical-middle">{{$d->mobile}}</td>
                                        <td class="vertical-middle">{{$d->email}}</td>
                                        <td class="vertical-middle ">
                                            <a  onclick="activate({{ $d->id}},'activatecustomer','Customer');" title="Activate"  class="" ><i class="fa fa-check fa-2x"></i></a>
                                        </td>
                                    </tr>
                                    @endforeach

                                    </tbody>
                                    
                                    
                                </table>

                                <p class="text-light margin-bottom-30"></p>
                                    
                                    
                                    <!-- <div class="form-group ">
                                    <label for="change" class="col-sm-1 control-label no-padding">Change</label>
                                    <div class="col-sm-2 no-padding">
                                      <select class="form-control">
                                        <option>Activate</option>
                                      </select>
                                    </div>
                                    <div class="col-sm-2 ">
                                    
                                    <button id="btn_delete" class="btn btn-danger" @if(count($bdata)==0) {{'disabled=disabled'}} @endif >Change</button>
                                    
                                  </div>
                                  </div> -->
 

                                
                            </div>
                        </div>
                    </div>
                </div><!-- /.col -->
            </div><!-- /. row -->
        </div><!-- /.row -->

        <!-- /.row -->

        <!-- /.row -->

        <!-- BEGIN FOOTER -->
        @include('includes.footer')

       <script>  
$(document).ready(function(){  
	$('#btn_delete').click(function(){ 

        bootbox.confirm({
            message: "Do you want to Block the customer ?",
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

	   var a=0;
		$("input:checked").each(function (e) {
			var form = this;
			var curdata=[];
			var cur_status=$("#ch_status").val();
			var rows_selected = inactive_table.column(0).checkboxes.selected();
			$.each(rows_selected, function(index, rowId){
                var n = rowId.lastIndexOf("=");
				var n=rowId.substr(n)
				var cur_val = n.replace('"', '').replace('"', '').replace('=', '').replace('>', '');
				 curdata.push(cur_val);
					
			});
            console.log(curdata.length);  
            console.log(curdata[0]);  
             
            var i = 0;
            console.log(curdata[i]); 
            if(curdata.length === 0) //tell you if the array is empty  
                {  
                     bootbox.alert("Please Select atleast one checkbox");  
                }  
                else  
                {  
                    $.ajaxSetup({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                        }
                    })
                    for(i=0;i<curdata.length;i++){
                     $.ajax({  
                          url:'bulkactivatecustomer',  
                          method:'POST',  
                          data:{id:curdata[i]},  
                          success:function(data)  
                          {  
                              
                          }  
                     });  
                 }
                 
                }
			a=1;				
		});
		if(a==0){
			bootbox.alert("Please Select atleast one customer");
		}else{
			bootbox.alert('<div class="ajax_status"><i class="fa fa-check-circle-o success" aria-hidden="true"></i>'+
						'selected customers activated successfully' +' </div>');
			document.cookie = "tabstatus=2";
			setTimeout(function(){ window.location.reload(); }, 3000);
		}

    }
    }   
        });
	});  




	$('#btn_block').click(function(){ 
           
           bootbox.confirm({
            message: "Do you want to Block the customer ?",
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

           var a=0;
	   $("input:checked").each(function (e) { 
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
            console.log(curdata.length);  
            console.log(curdata[0]);  
             
            var i = 0;
            console.log(curdata[i]); 
            if(curdata.length === 0) //tell you if the array is empty  
                {  
                     bootbox.alert("Please Select atleast one checkbox");  
                }  
                else  
                {  
                    $.ajaxSetup({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                        }
                    })
                    for(i=0;i<curdata.length;i++){
                     $.ajax({  
                          url:'bulkblockcustomer',  
                          method:'POST',  
                          data:{id:curdata[i]},  
                          success:function(data)  
                          {  
                            
                          }  
                     });  
                 }
                 
                }  
			a=1;				
		});
		if(a == 0){
		  bootbox.alert("Please Select atleast one customer");
		}else{
				   bootbox.alert('<div class="ajax_status"><i class="fa fa-check-circle-o success" aria-hidden="true"></i>'+
						 'selected customers blocked successfully'+' </div>');
									document.cookie = "tabstatus=2";
									setTimeout(function(){ window.location.reload(); }, 3000);
		}    
               }
    }   
        }); 
	        });
    
 });  
 </script>
        <!-- END FOOTER -->
    </div><!-- /.container-fluid -->
</div>
@endsection

