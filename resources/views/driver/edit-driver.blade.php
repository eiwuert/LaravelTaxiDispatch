@extends('layout.master');

@section('title')

EditDriver - Wrydes

@endsection

@section('content')


<div class="rightside bg-grey-100">
    <!-- BEGIN PAGE HEADING -->
    <div class="page-head">
        <h1 class="page-title">Edit Driver</h1>
        <!-- BEGIN BREADCRUMB -->

        <!-- END BREADCRUMB -->
    </div>
    <!-- END PAGE HEADING -->

    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12">
                <div class="panel">
                    <div class="panel-title bg-amber-200">
                        <div class="panel-head">Driver Information</div>
                    </div>
                    <div class="panel-body">

                        <!-- For display server side validation errors -->
                        @if (count($errors) > 0)
                        <div class="alert alert-danger">
                            <ul>
                                @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                        @endif


                        @foreach($data as $d)
     <form class="form-horizontal" action="" method="post" role="form" enctype="multipart/form-data">

                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                        <input type="hidden" name="id" value="{{ $d->id }}">


                        <div class="form-group  ">
                                <label for="" class="col-sm-2 control-label">Vehicle Category</label>
                                <div class="col-sm-4">
                                    <select class="form-control " @if($d->profile_status ==1) disabled='disabled' @endif name="ridetype" id="ride_category">
                                        @foreach($ride_category as $rd)
                                <option value="{{$rd->id}}" @if($rd->id ==$d->ride_category) selected="selected"  @endif>{{$rd->ride_category}}</option>
                                @endforeach
                                                                            </select>                               
                                </div>
                            </div>


                        <div class="form-group val">
                            <label for="" class="col-sm-2 control-label">First Name</label>
                            <div class="col-sm-4">
                                <input type="text" name="firstname" class="form-control vald" id="firstname"
                                       value="{{ $d->firstname }}"
                                       placeholder="" required>
                            </div>
                        </div>

                        <div class="form-group val">
                            <label for="" class="col-sm-2 control-label">Last Name</label>
                            <div class="col-sm-4">
                                <input type="text" value="{{ $d->lastname }}" name="lastname" class="form-control vald"
                                       id="lastname" placeholder="" required>

                                <div id="lname"></div>
                            </div>
                        </div>

                        <div class="form-group val">
                            <label for="" class="col-sm-2 control-label">Email</label>
                            <div class="col-sm-4">
                                <input type="email" name="email" id="email" value="{{ $d->email }}" class="form-control vald"
                                        placeholder="" required>
                            </div>
                        </div>

    @if($d->isfranchise ==1)
    @php $r = 1; @endphp
        @foreach($franchise as $fra)
            @if($fra->id == $d->franchise_id) 
                @php
                $is = $fra->company_name
                @endphp
            @endif
        @endforeach
    @else 
        @php
        $is = 'Go';
        $r = 2;
        @endphp
    @endif
                        
                        <div class="form-group ">
                                        <label for="franchise" class="col-sm-2 control-label">Franchise</label>
                                        <div class="col-sm-4">
                                        <div class="radio radio-theme display-inline-block">
                                        <input name="Franchise" id="franchiseyes" @if($r ==1)checked="checked"@endif type="radio" value="1" >
                                            <label for="franchiseyes">Franchise</label>
                                            <input name="Franchise" id="franchiseno" @if($r ==2)checked="checked"@endif type="radio" value="0" >
                                            <label for="franchiseno">Go</label>
                                        
                                         </div>
                                        </div></div>

                        <div id="franchise">
                                    <div class="form-group">
                                        <label for="" class="col-sm-2 control-label">Franchise</label>
                                        <div class="col-sm-4">
                                        <select class="form-control " name="franchise" id="">
                                            <option value="0">--Select Your Franchise--</option>
                                            @foreach ($franchise as $r)
                                            <option value="{{$r->id}}" @if($d->franchise_id == $r->id) selected="selected" @endif>{{$r->company_name}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    </div>
                                </div>

                        <div class="form-group val">
                            <label for="" class="col-sm-2 control-label">Password</label>
                            <div class="col-sm-4">
                                <input type="text" name="password" value="{{ $password }}" class="form-control vald"
                                       id="password" placeholder="" required>

                                <div id="pass"></div>
                            </div>
                        </div>

                        <div class="form-group val">
                            <label for="" class="col-sm-2 control-label">Confirm Password</label>
                            <div class="col-sm-4">
                                <div id="pass1"></div>
                                <input type="text" value="{{ $password }}" name="password1" id="password1"
                                       class="form-control vald"  placeholder="" required>

                            </div>
                        </div>
                        <div class="form-group val">
                            <label for="" class="col-sm-2 control-label">Gender</label>
                            <div class="col-sm-4">
                                <div class="radio radio-theme display-inline-block">
                                    <input name="gender" value="male" id="radio1" checked="checked" type="radio">
                                    <label for="optionsRadios1">Male</label>
                                    <input name="gender" value="female" id="radio2" type="radio">
                                    <label for="optionsRadios2">Female</label>
                                </div>
                                <div id="gender"></div>
                            </div>
                        </div>

                        <div class="form-group">
                                <label for="" class="col-sm-2 control-label">Driver photo</label>
                                <div class="col-sm-4">
                                    <input type="file" name="profilepicture" id="photo" onChange="validateImage('photo')" class="form-control" >
                                    <span>Only .jpg,.png are allowed</span>
                                </div>
                            </div>

                        <div class="form-group">
                            <label for="" class="col-sm-2 control-label"></label>
                            <div class="col-sm-4">
                                <img height="200" width="300" src="{{url("/")}}/public{{ $d->profile_photo }}">
                            </div>
                        </div>

                        <div class="form-group val">
                            <label for="" class="col-sm-2 control-label">Date of Birth</label>
                            <div class="col-sm-4">
                                <input type="text" name="dob" value="{{ $d->dob }}" class="form-control datepicker vald"
                                       id="dateofbirth" placeholder="" required>
                                <div id="dob"></div>
                            </div>
                        </div>


                        <div class="form-group val">
                            <label for="" class="col-sm-2 control-label">Driver License ID</label>
                            <div class="col-sm-4">
                                <input type="text" name="licenseid" value="{{ $d->licenseid }}" id="license_id"
                                       class="form-control vald" placeholder="" required>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="" class="col-sm-2 control-label"></label>
                            <div class="col-sm-4">
                                <img height="200" width="300" src="{{url("/")}}/public{{ $d->license }}">
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="" class="col-sm-2 control-label">License</label>
                            <div class="col-sm-4">
                                <input type="file" name="license" id="license" onChange="validateImage('license')" class="form-control">
                            </div>
                        </div>

                        <div class="form-group val">
                            <label for="" class="col-sm-2 control-label">Mobile Number</label>
                            <div class="col-sm-4">
                                <input type="text" maxlength="10" name="mobile" value="{{ $d->mobile }}" id="mobileno"
                                       class="form-control vald" id="" placeholder="" required>
                                <div id="mobile"></div>
                            </div>
                        </div>

                        <div class="form-group val {{ $errors->has('country')? 'has-error':'' }}">
                            <label for="" class="col-sm-2 control-label">Country</label>
                            <div class="col-sm-4">
                                <select class="form-control vald" name="country" id="country">
                                    <option value="">--Select Country--</option>
                                    @foreach ($countrylist as $country)
                                    <option value="{{$country->id}}"
                                        <?php if ($country->id == $d->country) {
                                            echo "selected=selected";
                                        } ?>
                                    >{{$country->name}}
                                    </option>
                                    @endforeach
                                </select>
                                {!! $errors->first('country', '<span class="help-block">:message</span>') !!}
                            </div>
                        </div>

                        <div class="form-group val {{ $errors->has('state')? 'has-error':'' }}">
                            <label for="" class="col-sm-2 control-label">State</label>
                            <div class="col-sm-4">
                                <select class="form-control vald" name="state" id="state">
                                    <option value="">--Select State--</option>
                                    <input type="hidden" value="<?php echo $d->state; ?>" id="temp_state"/>
                                </select>
                                {!! $errors->first('state', '<span class="help-block">:message</span>') !!}
                            </div>
                        </div>

                        <div class="form-group val {{ $errors->has('city')? 'has-error':'' }}">
                            <label for="" class="col-sm-2 control-label">City</label>
                            <div class="col-sm-4">
                                <select class="form-control vald" name="city" id="city">
                                    <option value="">--Select City--</option>
                                    <input type="hidden" value="<?php echo $d->city; ?>" id="temp_city"/>
                                </select>
                                {!! $errors->first('city', '<span class="help-block">:message</span>') !!}
                            </div>
                        </div>


                        <div class="form-group val">
                            <label for="" class="col-sm-2 control-label">Address</label>
                            <div class="col-sm-4">
									  <textarea rows="3" id="address" name="address" class="form-control vald">
									  	{{ $d->address }}
									  </textarea>
                                <div ></div>
                            </div>
                        </div>

                        <div class="form-group val">
                            <div class="col-sm-offset-2 col-sm-4 margin-top-10">
                                <a href="{{url("/")}}/manage_driver" class="btn btn-dark bg-black color-white">Back</a>
                                <button id="button1" type="reset" class="btn btn-dark bg-white-600 color-red">Reset
                                </button>

                                <button id="button" type="submit" class="btn btn-dark bg-red-600 color-white">Update
                                </button>
                            </div>
                        </div>
                        </form>
                        @endforeach
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
</div>

<!-- Added for Client side Javascript Form Validation -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
<script>
    $(document).ready(function () {

       
        var franchise = $("input[name='Franchise']:checked").val();

        if(franchise == 1){
            $("#franchise").show();
        }
        else{
            $("#franchise").hide();
        }

        $("#franchiseyes").click(function(){
            $("#franchise").show();
        });
        $("#franchiseno").click(function(){
            $("#franchise").hide();
        });
        $('#mobileno').keypress(function (e) {
            var regex = new RegExp("^[0-9]+$");
            var str = String.fromCharCode(!e.charCode ? e.which : e.charCode);
            if (regex.test(str)) {
                return true;
            }
            e.preventDefault();
            return false;
        });

        $('#button').click(function (e) {
            var isvalid = true;
            var firstname = $("#firstname");
            var lastname = $("#lastname");
            var email = $("#email");
            var password = $("#password");
            var password1 = $("#password1");
            var dateofbirth = $("#dateofbirth");
            var license_id = $("#license_id");
            var mobileno = $("#mobileno");
            var country = $("#country");
            var state = $("#state");
            var city = $("#city");
            var address = $("#address");

            if(firstname.val() == '')
            {
            	$("#firstname").css({
                        "border": "1px solid red",
                        "background": ""
                    });
            	$("#firstname").focus();
            	return false;
            }
            else
            {
            	$("#firstname").css({
                        "border": "2px solid green",
                        "background": ""
                    });
            }
            if(lastname.val() == '')
            {
            	$("#lastname").css({
                        "border": "1px solid red",
                        "background": ""
                    });
            	$("#lastname").focus();
            	return false;
            }
            else
            {
            	$("#lastname").css({
                        "border": "2px solid green",
                        "background": ""
                    });
            }
            if(email.val() == '')
            {
            	$("#email").css({
                        "border": "1px solid red",
                        "background": ""
                    });
            	$("#email").focus();
            	return false;
            }
            else
            {
            	$("#email").css({
                        "border": "2px solid green",
                        "background": ""
                    });
            }
            if(password.val() == '')
            {
            	$("#password").css({
                        "border": "1px solid red",
                        "background": ""
                    });
            	$("#password").focus();
            	return false;
            }
            else
            {
            	$("#password").css({
                        "border": "2px solid green",
                        "background": ""
                    });
            }
            if(password1.val() == '')
            {
            	$("#password1").css({
                        "border": "1px solid red",
                        "background": ""
                    });
            	$("#password1").focus();
            	return false;
            }
            else
            {
            	$("#password1").css({
                        "border": "2px solid green",
                        "background": ""
                    });
            }
            if(dateofbirth.val() == '')
            {
            	$("#dateofbirth").css({
                        "border": "1px solid red",
                        "background": ""
                    });
            	$("#dateofbirth").focus();
            	return false;
            }
            else
            {
            	$("#dateofbirth").css({
                        "border": "2px solid green",
                        "background": ""
                    });
            }
            if(license_id.val() == '')
            {
            	$("#license_id").css({
                        "border": "1px solid red",
                        "background": ""
                    });
            	$("#license_id").focus();
            	return false;
            }
            else
            {
            	$("#license_id").css({
                        "border": "2px solid green",
                        "background": ""
                    });
            }
            if(mobileno.val() == '')
            {
            	$("#mobileno").css({
                        "border": "1px solid red",
                        "background": ""
                    });
            	$("#mobileno").focus();
            	return false;
            }
            else
            {
            	$("#mobileno").css({
                        "border": "2px solid green",
                        "background": ""
                    });
            }
            if(country.val() == '')
            {
            	$("#country").css({
                        "border": "1px solid red",
                        "background": ""
                    });
            	$("#country").focus();
            	return false;
            }
            else
            {
            	$("#country").css({
                        "border": "2px solid green",
                        "background": ""
                    });
            }
            if(state.val() == '')
            {
            	$("#state").css({
                        "border": "1px solid red",
                        "background": ""
                    });
            	$("#state").focus();
            	return false;
            }
            else
            {
            	$("#state").css({
                        "border": "2px solid green",
                        "background": ""
                    });
            }
            if(city.val() == '')
            {
            	$("#city").css({
                        "border": "1px solid red",
                        "background": ""
                    });
            	$("#city").focus();
            	return false;
            }
            else
            {
            	$("#city").css({
                        "border": "2px solid green",
                        "background": ""
                    });
            }
            if(address.val() == '')
            {
            	$("#address").css({
                        "border": "1px solid red",
                        "background": ""
                    });
            	$("#address").focus();
            	return false;
            }
            else
            {
            	$("#address").css({
                        "border": "2px solid green",
                        "background": ""
                    });
            }
            

  /*
           $('input[type="textarea"]').each(function () {

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
                }
            });*/

        });
    });


</script>
@endsection