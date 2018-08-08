@extends('layout.master');

@section('title')

Manage Driver - Wrydes

@endsection

@section('content')
<div class="rightside bg-grey-100">
    <!-- BEGIN PAGE HEADING -->
    <div class="page-head">
        <h1 class="page-title">Manage Driver</h1>
        <!-- BEGIN BREADCRUMB -->
        <a href="add_driver" class="btn btn-dark bg-red-600 color-white pull-right">Add Driver</a>
        <!-- END BREADCRUMB -->
    </div>
    <!-- START OF FILTER-->
    <div class="f_filter container-fluid">
        <div class="pull-right col-lg-3 no-padding">
            <form method="GET" action="" name="filter">
                {{ csrf_field() }}
                <div class="input-group">
                    <select class=" form-control" name="ride_category">
                        <option value="">--Vehicle Category--</option>
                        <option value="0">All Category</option>
                        @foreach ($ride_category as $cat)
                        <option value="{{$cat->id}}" {{ session(
                        'cf_driver') == $cat->id ? "selected=selected":''}} >{{$cat->ride_category}}</option>
                        @endforeach
                    </select>
                    <span class="input-group-btn">
						<input type="submit" class="btn btn-dark bg-red-600 color-white pull-right" value="Search"/>
				   </span>
                </div>
            </form>
        </div>
        <div class="clearfix"></div>
    </div>
    <!-- END OF FILTER-->
    <!-- END PAGE HEADING -->
    @if (Session::has('driver_added'))
    <div class="alert alert-success"><strong>{{ Session::get('driver_added') }}</strong></div>
    @endif
    @if (Session::has('driver_updated'))
    <div class="alert alert-success"><strong>{{ Session::get('driver_updated') }}</strong></div>
    @endif
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12">
                <div class="panel no-border">
                    <div class="panel-title bg-amber-200">
                        <div class="panel-head">Added Driver Information</div>
                    </div>
                    <div class="panel-body no-padding-top bg-white">
                        <br>
                        <ul class="nav nav-tabs tab-grey bg-grey-100">
                            <li class="active"><a href="#fontawesome" id="fontawesome-tab" data-toggle="tab"
                                                  aria-controls="fontawesome" aria-expanded="true">Un Assigned
                                    Drivers</a></li>
                            <li><a href="#ionicons" id="ionicons-tab" data-toggle="tab" aria-controls="ionicons">Assigned
                                    Drivers</a></li>
                            <li><a href="#ionicons1" id="ionicons-tab1" data-toggle="tab" aria-controls="ionicons1">Blocked
                                    Drivers</a></li>

                            <li>
                                <div id="status"></div>
                            </li>
                        </ul>

                        <div class="tab-content">
                            <div id="fontawesome" aria-labelledBy="fontawesome-tab"
                                 class="panel-body padding-md table-responsive tab-pane in active">
                                <p class="text-light margin-bottom-30"></p>

                                <table class="table table-bordered display" id="multicheck_inactive">
                                    <thead>
                                    <tr>
                                        <th class="vertical-middle">Select</th>
                                        <th class="vertical-middle">Name</th>
                                        <th class="vertical-middle">Email</th>
                                        
                                        <th class="vertical-middle">Vehicle Category</th>
                                        <th class="vertical-middle">Phone</th>
                                        <th class="vertical-middle">Driver ID</th>
                                        <th class="vertical-middle">Franchise Name</th>
                                        <th class="vertical-middle">Action</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($nonactive_driver as $nd)
                                    <tr>
                                        <td class="vertical-middle">
                                           <input type="checkbox" class="checkf" name="checkbox[]" value="{{$nd->id}}">
                                        </td>
                                        <td class="vertical-middle">{{$nd->firstname}} {{$nd->lastname}}</td>
                                        <td class="vertical-middle">{{$nd->email}}</td>
                                        
                                        <td class="vertical-middle">{{$nd->ride_category}}</td>
                                        <td class="vertical-middle">{{$nd->mobile}}</td>
                                        <td class="vertical-middle">{{$nd->driver_id}}</td>
                                        <td class="vertical-middle">
                                            @if($nd->isfranchise ==1)
                                           @foreach($franchise as $fra)
                                           @if($fra->id == $nd->franchise_id) {{$fra->company_name}} @endif
                                           @endforeach
                                           @else 
                                           Go
                                            @endif
                                        </td>

                                        <td class="vertical-middle">
                                            <a data-toggle="tooltip" title="View" href="view_driver/{{$nd->id}}"><i
                                                        class="fa fa-eye fa-2x"></i></a>&nbsp;
                                            <a data-toggle="tooltip" title="Edit" href="edit_driver/{{$nd->id}}"><i
                                                        class="fa fa-edit fa-2x"></i></a>&nbsp;

                                            <a href="#" data-toggle="tooltip" title="Block"
                                               onclick="deactivate({{ $nd->id}},'BlockDriver','Driver');"
                                               data-original-title="Edit">
                                                <i class="fa fa-close fa-2x"></i>
                                            </a>

                                            &nbsp;<a href="#" data-toggle="tooltip" title="Delete"
                                                     onclick="delete1({{ $nd->id}},'DeleteDriver','Driver');"><i
                                                        class="fa fa-trash fa-2x"></i>
                                            </a>

                                            <a href="review/{{ $nd->id}}" data-toggle="tooltip" title="Review Details"
                                               data-original-title="Edit">
                                                <i class="fa fa-star fa-2x"></i>
                                            </a>
                                        </td>
                                    </tr>
                                    @endforeach
                                    </tbody>
                                </table>

                                <div class="form-group ">
                                    <label for="" class="col-sm-12 control-label no-padding">Change Status</label>
                                    <div class="col-sm-2 no-padding">
                                      <select class="form-control">
                                        <option>Blocked</option>
                                      </select>
                                    </div>
                                    <div class="col-sm-2 ">
                                   <button id="btn_block" class="btn btn-danger" type="submit">Change</button>
                                  </div>
                                  </div>


                            </div>

                            <!-- Second Tab Starts ---------->

                            <div id="ionicons" aria-labelledBy="ionicons-tab"
                                 class="panel-body padding-md table-responsive tab-pane">
                                <p class="text-light margin-bottom-30"></p>
                                <table class="table table-bordered display" id="multicheck_active">
                                    <thead>
                                    <tr>
                                        <th class="vertical-middle">Select</th>
                                        <th class="vertical-middle">Name</th>
                                        <th class="vertical-middle">Email</th>
                                        <th class="vertical-middle">Vehicle Number</th>
                                        <th class="vertical-middle">Vehicle Category</th>
                                        <th class="vertical-middle">Phone</th>
                                        <th class="vertical-middle">Driver ID</th>
                                        <th class="vertical-middle">Franchise Name</th>
                                        <th class="vertical-middle">Action</th>
                                    </tr>
                                    </thead>

                                    <tbody>
                                    @foreach($active_driver as $ad)
                                    <tr>
                                        <td class="vertical-middle">
                                            <input type="checkbox" class="checkf" name="checkbox[]" value="{{$ad->id}}">
                                        </td>
                                        <td class="vertical-middle">{{$ad->firstname}} {{$ad->lastname}}</td>
                                        <td class="vertical-middle">{{$ad->email}}</td>
                                        <td class="vertical-middle">{{$ad->car_no}} {{$ad->car_type}}
                                            @if($ad->car_board == 1) (W) @endif
                                            @if($ad->car_board == 2) (Y) @endif
                                        </td>
                                        <td class="vertical-middle">{{$ad->ride_category}}</td>                                      
                                        <td class="vertical-middle">{{$ad->mobile}}</td>
                                        <td class="vertical-middle">{{$ad->driver_id}}</td>
                                        <td class="vertical-middle">
                                            @if($ad->isfranchise ==1)
                                           @foreach($franchise as $fra)
                                           @if($fra->id == $ad->franchise_id) {{$fra->company_name}} @endif
                                           @endforeach
                                            @else 
                                            Go
                                            @endif
                                        </td>
                                        <td class="vertical-middle">
                                            <a data-toggle="tooltip" title="View" href="view_driver/{{$ad->id}}"><i
                                                        class="fa fa-eye fa-2x"></i></a>&nbsp;
                                            <a data-toggle="tooltip" title="Edit" href="edit_driver/{{$ad->id}}"><i
                                                        class="fa fa-edit fa-2x"></i></a>&nbsp;
                                            <a data-toggle="tooltip" title="Block" href="#"
                                               onclick="deactivate({{ $ad->id}},'AssignedBlockDriver','Driver');"><i
                                                        class="fa fa-close fa-2x"></i>
                                            </a>
                                            <a href="review/{{ $ad->id}}" data-toggle="tooltip" title="Review Details"
                                               data-original-title="Edit">
                                                <i class="fa fa-star fa-2x"></i>
                                            </a>
                                        </td>
                                    </tr>
                                    @endforeach
                                    </tbody>
                                </table>

                                <div class="form-group ">
                                    <label for="" class="col-sm-12 control-label no-padding">Change Status</label>
                                    <div class="col-sm-2 no-padding">
                                      <select class="form-control">
                                        <option>Blocked</option>
                                      </select>
                                    </div>
                                    <div class="col-sm-2 ">
                                   <button id="btn_block1" class="btn btn-danger" type="submit">Change</button>
                                  </div>
                                  </div>

                            </div>


                            <!-- End of Second Tab -------->


                            <div id="ionicons1" aria-labelledBy="ionicons-tab1"
                                 class="panel-body padding-md table-responsive tab-pane">
                                <p class="text-light margin-bottom-30"></p>
                                <table class="table table-bordered display" id="multicheck_block">
                                    <thead>
                                    <tr>
                                        <th class="vertical-middle">Select</th>
                                        <th class="vertical-middle">Name</th>
                                        <th class="vertical-middle">Email</th>
                                        
                                        <th class="vertical-middle">Vehicle Category</th>
                                        <th class="vertical-middle">Phone</th>
                                        <th class="vertical-middle">Driver ID</th>
                                        <th class="vertical-middle">Franchise Name</th>
                                        <th class="vertical-middle">Action</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($blocked_driver as $bd)
                                    <tr>
                                        <td class="vertical-middle">
                                            <input type="checkbox" class="checkf" name="checkbox[]" value="{{$bd->id}}">
                                        </td>
                                        <td class="vertical-middle">{{$bd->firstname}} {{$bd->lastname}}</td>
                                        <td class="vertical-middle">{{$bd->email}}</td>
                                        
                                        <td class="vertical-middle">{{$bd->ride_category}}</td>
                                        <td class="vertical-middle">{{$bd->mobile}}</td>
                                        <td class="vertical-middle">{{$bd->driver_id}}</td>
                                        <td class="vertical-middle">
                                            @if($bd->isfranchise ==1)
                                           @foreach($franchise as $fra)
                                           @if($fra->id == $bd->franchise_id) {{$fra->company_name}} @endif

                                           @endforeach
                                           @else 
                                           Go
                                            @endif
                                        </td>
                                        <td class="vertical-middle">
                                            <a data-toggle="tooltip" title="View" href="view_driver/{{$bd->id}}"><i
                                                        class="fa fa-eye fa-2x"></i></a>&nbsp;
                                            <a data-toggle="tooltip" title="Edit" href="edit_driver/{{$bd->id}}"><i
                                                        class="fa fa-edit fa-2x"></i></a>&nbsp;
                                            <a href="#" data-toggle="tooltip" title="Activate"
                                               onclick="activate({{ $bd->id}},'ActivateDriver','Driver');"><i
                                                        class="fa fa-check fa-2x"></i>
                                                &nbsp;<a href="#" data-toggle="tooltip" title="Delete"
                                                         onclick="delete1({{ $bd->id}},'DeleteDriver','Driver');"><i
                                                            class="fa fa-trash fa-2x"></i>
                                                </a>
                                                <a href="review/{{ $bd->id}}" data-toggle="tooltip"
                                                   title="Review Details" data-original-title="Edit">
                                                    <i class="fa fa-star fa-2x"></i>
                                                </a>
                                        </td>
                                    </tr>
                                    @endforeach
                                    </tbody>
                                </table>

                                <div class="form-group ">
                                    <label for="" class="col-sm-12 control-label no-padding">Change Status</label>
                                    <div class="col-sm-2 no-padding">
                                      <select class="form-control" disabled="disabled">
                                        <option>Activate</option>
                                      </select>
                                    </div>
                                    <div class="col-sm-2 ">
                                   <button id="btn_activate" class="btn btn-danger" type="submit">Change</button>
                                  </div>
                                  </div>

                            </div>


                            <!-- End of Third Tab -->

                        </div>
                    </div>
                </div><!-- /.col -->
            </div><!-- /. row -->
        </div><!-- /.row -->

        <script>
            $(document).ready(function () {
                $('[data-toggle="tooltip"]').tooltip();

                $('#btn_activate').click(function(){  
           
           
           $("input:checked").each(function (e) {
        var form = this;
      var curdata=[];
      var cur_status=$("#ch_status").val();
   
      var rows_selected = block_table.column(0).checkboxes.selected();

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
                          url:'ActivateDriver',  
                          method:'POST',  
                          data:{data_id:curdata[i]},  
                          success:function(data)  
                          {  
                              
                          }  
                     });  
                 }
                 
                }    

    }); 

         bootbox.alert('<div class="ajax_status"><i class="fa fa-check-circle-o success" aria-hidden="true"></i>'+
               'selected drivers activated successfully' +' </div>');
                          document.cookie = "tabstatus=2";
                          setTimeout(function(){ window.location.reload(); }, 3000);
      });  




            $('#btn_block').click(function(){  
           
           
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
                          url:'BlockDriver',  
                          method:'POST',  
                          data:{data_id:curdata[i]},  
                          success:function(data)  
                          {  
                            
                          }  
                     });  
                 }
                 
                }     
    });

           bootbox.alert('<div class="ajax_status"><i class="fa fa-check-circle-o success" aria-hidden="true"></i>'+
                 'selected customers blocked successfully'+' </div>');
                           document.cookie = "tabstatus=2";
                            setTimeout(function(){ window.location.reload(); }, 3000);
      });




            $('#btn_block1').click(function(){  
           
           
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
                          url:'AssignedBlockDriver',  
                          method:'POST',  
                          data:{data_id:curdata[i]},  
                          success:function(data)  
                          {  
                            
                          }  
                     });  
                 }
                 
                }     
    });

          bootbox.alert('<div class="ajax_status"><i class="fa fa-check-circle-o success" aria-hidden="true"></i>'+
                 'Status updated successfully'+' </div>');
                           document.cookie = "tabstatus=2";
                           setTimeout(function(){ window.location.reload(); }, 3000);
      });






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