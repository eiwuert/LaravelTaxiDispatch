@extends('layout.master');
@section('title')
Add Attached Vehicles - Go Cabs
@endsection
@section('content')
<!-- BEGIN RIGHTSIDE -->
<div class="rightside bg-grey-100">
    <!-- BEGIN PAGE HEADING -->
    <div class="page-head">
        <h1 class="page-title">Add Attached Vehicles</h1>
        <!-- BEGIN BREADCRUMB -->
        <!-- END BREADCRUMB -->
    </div>
    <!-- END PAGE HEADING -->
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12">
                <div class="panel">
                    <div class="panel-title bg-amber-200">
                        <div class="panel-head">Driver & Vehicle Information</div>
                    </div>
                    <div class="panel-body">
                        <form name="form" class="form-horizontal" method="post" action="add_attached_driver" role="form"
                              enctype="multipart/form-data">
                            <input type="hidden" name="_token" value="{{ csrf_token() }}">

                            <input type="hidden" name="id" id="id">


                            <div class="col-lg-6">
                                <h4>Driver Details:</h4>
                                <p class="text-light margin-bottom-30"></p>

                                <!-- <div class="form-group">
                                    <label for="" class="col-sm-4 control-label"></label>
                                    <div class="col-sm-8">
                                        <div class="radio radio-theme display-inline-block">
                                            <input name="usercheck" id="new" checked="checked" type="radio"
                                                   value='mavble'>
                                            <label for="new">New Driver</label>
                                            <input name="usercheck" id="old" type="radio" value="femalmne">
                                            <label for="old">Already Registered</label>
                                        </div>
                                    </div>
                                </div> -->

                                <!--<div class="form-group">
                                    <label for="" class="col-sm-4 control-label"> Company Type</label>
                                    <div class="col-sm-8">
                                        <div class="radio radio-theme display-inline-block">
                                            <input name="Franchise" checked="checked" id="franchiseyes"  type="radio" 
                                                   >
                                            <label for="franchiseyes">Franchise</label>
                                            <input name="Franchise" id="franchiseno" checked="checked" type="radio" >
                                            <label for="franchiseno">Go</label>
                                        </div>
                                    </div>
                                </div>-->


                                <div id="franchise">
                                    <div class="form-group">
                                        <label for="" class="col-sm-4 control-label">Franchise</label>
                                        <div class="col-sm-8">
                                        <select class="chosen-select-deselect form-control" name="franchise" id="franchise_select">
                                            
                                            
                                            <option value="">--Select Your Franchise--</option>
                                            
                                            @foreach ($franchise as $r)
                                            <option value="{{$r->id}}">{{$r->company_name}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    </div>
                                </div>


                                <div id="olddriverid">
                                    <div class="form-group">
                                        <label for="" class="col-sm-4 control-label">Old Driver ID</label>
                                        <div class="col-lg-8">
                                            <div class="col-sm-6 no-padding-left">
                                                <input type="text" name="olddriverid" class="form-control validate"
                                                       id="olddriveridv" placeholder="" style="float: left;" value="1"
                                                       type="text">
                                                <div id="dis"></div>
                                            </div>
                                            <div class="col-sm-1 no-padding-left">
                                                <a id="CheckUser"
                                                   class="btn btn-dark bg-red-600 color-white">CheckUser</a>

                                            </div>
                                        </div>
                                    </div>
                                </div>


                                <div class="form-group  {{ $errors->has('country')? 'has-error':'' }}">
                                    <label for="" class="col-sm-4 control-label">Vehicle Category</label>
                                    <div class="col-sm-8">
                                        <select class="form-control validate" name="ridetype" id="VehicleCategory">
                                            <option value="">--Select Vehicle Category--</option>
                                            @foreach ($ridetype as $r)
                                            <option value="{{$r->id}}">{{$r->ride_category}}</option>
                                            @endforeach
                                        </select>
                                        {!! $errors->first('country', '<span class="help-block">:message</span>') !!}
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="" class="col-sm-4 control-label">Driver First Name</label>
                                    <div class="col-sm-8">
                                        <input name="driver_first_name" maxlength="15" id="firstname" type="text" class="form-control validate">
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="" class="col-sm-4 control-label">Driver Last Name</label>
                                    <div class="col-sm-8">
                                        <input name="driver_last_name" maxlength="15" id="lastname" type="text" class="form-control validate">
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="" class="col-sm-4 control-label">Email</label>
                                    <div class="col-sm-8">
                                        <input name="email" type="email" maxlength="40" id="emailid" class="form-control">
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="" class="col-sm-4 control-label">Gender</label>
                                    <div class="col-sm-8">
                                        <div class="radio radio-theme display-inline-block">
                                            <input name="gender" value="male" id="optionsRadios1" checked="checked"
                                                   type="radio">
                                            <label for="optionsRadios1">Male</label>
                                            <input name="gender" value="female" id="optionsRadios2" type="radio">
                                            <label for="optionsRadios2">Female</label>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="" class="col-sm-4 control-label">Date of Birth</label>
                                    <div class="col-sm-8">
                                        <input name="dob" class="form-control datepicker1 validate" id="dateofbirth"
                                               placeholder="" required="" type="text">
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="" class="col-sm-4 control-label">Mobile Number</label>
                                    <div class="col-sm-8">
                                        <input type="text" name="mobile_number" id="mobile" maxlength="10" class="form-control validate">
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="" class="col-sm-4 control-label">Password</label>
                                    <div class="col-sm-8">
                                        <input type="text" maxlength="15" name="password" id="password" class="form-control validate">
                                    </div>
                                </div>

                               <!--  <div class="form-group">
                                    <label for="" class="col-sm-4 control-label">Confirm Password</label>
                                    <div class="col-sm-8">
                                        <input type="password" name="confirm_password" id="password1"
                                               class="form-control">
                                    </div>
                                </div> -->

                                <div class="form-group">
                                    <label for="" class="col-sm-4 control-label">Driver Photo</label>
                                    <div class="col-sm-8">
                                        <input type="file" name="profilepicture" id="profilepicture" onChange="validateImage('profilepicture')" class="form-control validate">
                                        <span>Only .jpg,.png are allowed</span>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="" class="col-sm-4 control-label">Driver License ID</label>
                                    <div class="col-sm-8">
                                        <input type="text" maxlength="20" name="licenseid" maxlength="20" id="license_id"
                                               class="form-control validate" placeholder="" required>
                                        <div id="licenseid"></div>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="" class="col-sm-4 control-label">Driver License Image</label>
                                    <div class="col-sm-8">
                                        <input type="file" name="driver_license_number" id="driver_license_number" class="form-control validate" onChange="validateImage('driver_license_number')">
                                        <span>Only .jpg,.png are allowed</span>
                                    </div>
                                </div>

                                <div class="form-group  {{ $errors->has('country')? 'has-error':'' }}">
                                    <label for="" class="col-sm-4 control-label">Country</label>
                                    <div class="col-sm-8">
                                        <select class="form-control validate" name="country" id="country">
                                            <option value="">--Select Country--</option>
                                            @foreach ($country_list as $country)
                                            <option value="{{$country->id}}" <?php if (old('country') == $country->id) {
                                                echo "selected=selected";
                                            } ?>>{{$country->name}}
                                            </option>
                                            @endforeach
                                        </select>
                                        {!! $errors->first('country', '<span class="help-block">:message</span>') !!}
                                    </div>
                                </div>

                                <div class="form-group  {{ $errors->has('state')? 'has-error':'' }}">
                                    <label for="" class="col-sm-4 control-label">State</label>
                                    <div class="col-sm-8">
                                        <select class="form-control validate" name="state" id="state">
                                            <option value="">--Select State--</option>
                                        </select>
                                        {!! $errors->first('state', '<span class="help-block">:message</span>') !!}
                                    </div>
                                </div>

                                <div class="form-group  {{ $errors->has('city')? 'has-error':'' }}">
                                    <label for="" class="col-sm-4 control-label">City</label>
                                    <div class="col-sm-8">
                                        <select class="form-control validate" name="city" id="city">
                                            <option value="">--Select City--</option>
                                        </select>
                                        {!! $errors->first('city', '<span class="help-block">:message</span>') !!}
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="" class="col-sm-4 control-label">Address</label>
                                    <div class="col-sm-8">
                                        <textarea rows="3" name="address" id="address" class="form-control validate"></textarea>
                                    </div>
                                </div>

                                


                            </div>

                            <div class="col-lg-6">
                                <h4>Vehicle Details:</h4>
                                <p class="text-light margin-bottom-30"></p>
                                <div class="form-group">
                                    <label for="" class="col-sm-4 control-label">Vehicle Number</label>
                                    <div class="col-sm-8">
                                        <input type="text" maxlength="20" name="taxi_number" id="taxi_number" class="form-control validate">
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="" class="col-sm-4 control-label">Vehicle Type</label>
                                    <div class="col-sm-8">

                                        <select name="taxi_type" id="VehicleType" class="form-control validate">
                                            <option disabled selected value> -- select an Type --</option>

                                            @foreach($cartype as $car_types)
                                            <option value="{{$car_types->id}}">{{$car_types->car_type}}
                                @if($car_types->car_board == 1) (W) @endif
                                @if($car_types->car_board == 2) (Y) @endif
                                            </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="" class="col-sm-4 control-label">Vehicle Brand</label>
                                    <div class="col-sm-8">

                                        <select name="taxi_brand" id="VehicleBrand" class="form-control validate">
                                            <option disabled selected value> -- select an Brand --</option>

                                            @foreach($carbrand as $car_brands)
                                            <option value="{{$car_brands->id}}">{{$car_brands->brand}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="" class="col-sm-4 control-label">Vehicle Model</label>
                                    <div class="col-sm-8">

                                        <select name="taxi_model" id="taxi-model" class="form-control validate">
                                            <option disabled selected value> -- select an Model --</option>

                                            
                                        </select>
                                    </div>
                                </div>
                                

                               <!--  <div class="form-group">
                                    <label for="" class="col-sm-4 control-label">Capacity</label>
                                    <div class="col-sm-8">
                                        <input type="text" name="capacity" id="capacity" class="form-control">
                                    </div>
                                </div> -->
                                
                                <div class="form-group">
                                    <label for="" class="col-sm-4 control-label">RC Number</label>
                                    <div class="col-sm-8">
                                        <input type="text" maxlength="20" name="rc_number" id="rc_number" class="form-control validate">
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="" class="col-sm-4 control-label">Vehicle Photo</label>
                                    <div class="col-sm-8">
                                        <input type="file" name="taxipicture" id="taxipicture" onChange="validateImage('taxipicture')" class="form-control validate">
                                        <span>Only .jpg,.png are allowed</span>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="" class="col-sm-4 control-label">RC Image</label>
                                    <div class="col-sm-8">
                                        <input type="file" name="rc_image" id="rc_image" onChange="validateImage('rc_image')" class="form-control validate">
                                        <span>Only .jpg,.png are allowed</span>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="" class="col-sm-4 control-label">Insurance Image</label>
                                    <div class="col-sm-8">
                                        <input type="file" name="insurance_image" id="insurance_image" class="form-control validate" onChange="validateImage('insurance_image')">
                                        <span>Only .jpg,.png are allowed</span>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="" class="col-sm-4 control-label">Insurance Expiry Date</label>
                                    <div class="col-sm-8">
                                        <input type="text" class="form-control datepicker validate" name="insurance_exp_date"
                                               id="expdate">
                                    </div>

                                </div>

                                <div class="form-group pull-right">
                                    <div class="col-sm-12 margin-top-10 ">
                                        <a href="manage_attached_drivers"
                                           class="btn btn-dark bg-black color-white">Back</a>
                                        <button type="reset" class="btn btn-dark bg-grey-400 color-black">Reset</button>
                                        <button id="button" type="submit" class="btn btn-dark bg-red-600 color-white">
                                            Add
                                        </button>

                                    </div>
                                </div>

                            </div>


                            <!-- <div class="col-md-12">
                                <div class="form-group pull-right">
                                    <div class="col-sm-12 margin-top-10 ">
                                        <a href="manage_attached_drivers"
                                           class="btn btn-dark bg-black color-white">Back</a>
                                        <button type="reset" class="btn btn-dark bg-grey-400 color-black">Reset</button>
                                        <button id="button" type="submit" class="btn btn-dark bg-red-600 color-white">
                                            Add
                                        </button>

                                    </div>
                                </div>
                            </div> -->
                        </form>
                    </div>
                </div>
            </div><!-- /.col -->
        </div><!-- /.row -->

        <!-- /.row -->
        <!-- /.row -->
        <!-- BEGIN FOOTER -->
        @include('includes.footer')
        <!-- END FOOTER -->
    </div><!-- /.container-fluid -->
</div><!-- /.rightside -->
<!-- Added for Client side Javascript Form Validation -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js">
</script>
<script>
    $(document).ready(function () {

            /* $("#franchise_select").removeClass("validate");
            $("#franchise").hide();

             $("#franchiseyes").click(function(){
                $("#franchise").show();
                $("#franchise_select").addClass("validate");
                
             });

             $("#franchiseno").click(function(){
               
                $("#franchise").hide();
                $("#franchise_select").removeClass("validate");
             }); */


        $('#mobile').keypress(function (e) {
            var regex = new RegExp("^[0-9]+$");
            var str = String.fromCharCode(!e.charCode ? e.which : e.charCode);
            if (regex.test(str)) {
                return true;
            }
            e.preventDefault();
            return false;
        });

        $("#olddriverid").hide();
        $("#new").click(function () {
            $("#olddriverid").hide();
        });

        $("#old").click(function () {
            document.getElementById("olddriveridv").value = "";

            $("#olddriverid").show();
        });

        $('#button').click(function (e) {
            var isvalid = true;
            $(".validate").each(function () {
                if ($.trim($(this).val()) == '') {
                    isValid = false;
                    $(this).css({
                        "border": "1px solid red",
                        "background": ""
                    });
                    if (isValid == false)
                        e.preventDefault();
                }
                else {
                    $(this).css({
                        "border": "2px solid green",
                        "background": ""
                    });
                    return true;
                }
            });
        });


        $("#CheckUser").click(function () {

            var id = $("#olddriveridv").val();

            function checku($id) {
                console.log(id);
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                    }
                })
                $.ajax({
                    type: 'POST',
                    url: 'checkattacheduser',
                    data: {data_id: id},
                    dataType: 'json',
                    success: function (data) {
                        console.log(data.Response);
                        console.log(data.Respons);
                        console.log(data.car_num);
                        document.getElementById("firstname").value = data.firstname;
                        document.getElementById("lastname").value = data.lastname;
                        document.getElementById("emailid").value = data.email;
                        document.getElementById("password").value = data.password;
                        document.getElementById("mobile").value = data.mobile;
                        document.getElementById("license_id").value = data.licenseid;

                        document.getElementById("dateofbirth").value = data.dob;
                        document.getElementById("ride_category").value = data.ride_category;
                        document.getElementById("address").value = data.address;
                        document.getElementById("id").value = data.id;

                        document.getElementById("taxi_number").value = data.taxi_number;
                        document.getElementById("taxi_type").value = data.taxi_type;
                        document.getElementById("taxi_model").value = data.taxi_model;
                        document.getElementById("taxi_brand").value = data.taxi_brand;
                        document.getElementById("capacity").value = data.capacity;
                        document.getElementById("rc_number").value = data.rc_number;
                        document.getElementById("expdate").value = data.expdate;


                    },
                    error: function (data) {
                        document.getElementById("dis").innerHTML = "<p><font color='red'>*Driver ID Invalid</font></p>";
                        console.log('Error:', data);
                    }
                });
            }

            checku(id);
        });


    });

</script>
<!-- End of Client side form Validation -->
@endsection