
@extends('layout.master');

@section('title')

Driver Rating Details - GoApp

@endsection

@section('content')
<div class="rightside bg-grey-100">
			<!-- BEGIN PAGE HEADING -->
            <div class="page-head">
				<h1 class="page-title">Driver Review Details</h1>
				<!-- BEGIN BREADCRUMB -->
				<a href="{{url("/")}}/manage_attached_drivers" class="btn btn-dark bg-black color-white pull-right">Back</a>
				<!-- END BREADCRUMB -->
			</div>
			<!-- END PAGE HEADING -->

            <div class="container-fluid">

            	   <!-- START OF FILTER-->
	<form name="searchfare" action="" method="post">
	<div class="row">
        <div class="col-lg-12">
				
			<div class="form-horizontal" >
				 {{ csrf_field() }}

				 @php
				 $fdate = "";
				 $tdate = "";
				 if(isset($date)){
				 $fdate = date('d-m-Y',strtotime($date[0]));
				 if(count($date) > 1){
				 $tdate = date('d-m-Y',strtotime($date[1]));
				 }
				 }
				 
				 @endphp
				 <input type="hidden" name="id" value="{{$id}}">
				<div class="form-group margin-bottom-20 col-md-4 margin-right-10">
				   From Date :	<input type="text" class="form-control datepicker1 " name="fromdate" 
				   @if($fdate != "") value="{{$fdate}}" @endif>
				</div>
                                    
                <div class="form-group margin-bottom-20 col-md-4 margin-right-10">
					To Date :<input type="text" class="form-control datepicker1 " name="todate" @if($tdate != "") value="{{$tdate}}" @endif >
				</div>
                       
                <div class="form-group margin-bottom-20 col-md-4 margin-top-15">
          			<input type="submit" class="btn btn-dark bg-red-600 color-white pull-left" id="button_submit" value="Search" />
          		</div> 

				</div>
			</div>
			</div>
	</form>
	<!-- END OF FILTER-->

				<div class="row">
                        <div class="col-lg-12">
							<div class="panel no-border">
                            	<div class="panel-title bg-amber-200">
								<div class="panel-head">Driver Details</div>
								</div>
                                <div class="panel-body no-padding-top bg-white">
									<p class="text-light margin-bottom-30"></p>
										
										@php
										if(count($rating) == 0){
										 echo 'No Data Available';
										}
										@endphp

										@foreach($rating as $rat)
										<table class="table table-bordered " style="max-width: 50%;">
										
										<tbody>
											<tr> 
												
												<td class="vertical-middle" style="width: 23%;">Customer Name</td>
												<td class="vertical-middle">
												@foreach($customer as $ct)
												@if($rat->customer_id == $ct->id)
												{{$ct->name}} 
												@endif
												@endforeach</td>
												
											</tr>

											<tr> 
												
												<td class="vertical-middle" style="width: 23%;">Customer Email</td>
												<td class="vertical-middle">
												@foreach($customer as $ct)
												@if($rat->customer_id == $ct->id)
												{{$ct->email}}
												@endif
												@endforeach</td>
												
											</tr>

											<tr> 
												
												<td class="vertical-middle" style="width: 23%;">Rating</td>
												<td class="vertical-middle">
												    <span class="stars">
												    	<span style="width: {{$rat->rating*16}}px;"></span>
												    </span>

												    				
												</td>

											</tr>

											<tr> 
												
												<td class="vertical-middle" style="width: 23%;">Comments</td>
												<td class="vertical-middle">{{$rat->comments}}</td>
												
											</tr>

											<tr> 
												
												<td class="vertical-middle" style="width: 23%;">Alert Count</td>
												<td class="vertical-middle">
												@php
												$t = 1;
												foreach($alert as $alerts){
												$t = 0;
													if($alerts->ride_id == $rat->ride_id){
													echo $alerts->total;
													$t =1;
													}
												 }
												if($t == 0){
												echo '0';
												}
												@endphp</td>
												
											</tr>

											<tr>
												<td class="vertical-middle" style="width: 23%;">Reason</td>
											    <td class="vertical-middle">
											    	@php
												    $str = $rat->reason_id;
												    $ar = array();
												    $ar = explode(",",$str);
												    
												    $count = count($ar);
												    $res = array();
												    for($i=0;$i<$count;$i++){
												    	foreach($reason as $re){
												    	if($re->id == $ar[$i]){
												    		$res[] = $re->reason;
												    	}
												      }
												    }
												    echo implode(",",$res);
												    @endphp
											    </td>
											 </tr>

											<tr>
												<td class="vertical-middle" style="width: 23%;">Date</td>
											    <td class="vertical-middle">
											    @php echo date('d-m-Y',strtotime($rat->created_at)); @endphp
											    </td>
											</tr>
											</tbody>
											</table>
										@endforeach
											
									
                            </div>
                        </div><!-- /.col -->
                    </div><!-- /. row -->
		    </div><!-- /.row -->
				
				<!-- /.row -->

				<!-- /.row -->
				
				<!-- BEGIN FOOTER -->
				@include('includes.footer')
				<!-- END FOOTER -->
            </div><!-- /.container-fluid -->
        </div>

        <script type="text/javascript">
        	$( document ).ready(function() {
    console.log( "ready!" );
    $('span.stars').stars();
    $.fn.stars = function() {
			return $(this).each(function() {
				$(this).html($('<span />').width(Math.max(0, (Math.min(5, parseFloat($(this).html())))) * 16));
			});
		}
});
        </script>
 @endsection