@extends('layout.master');

@section('title')

AddDriver - Wrydes

@endsection

@section('content')
<div class="rightside bg-grey-100">
    <!-- BEGIN PAGE HEADING -->
    <div class="page-head">
        <h1 class="page-title">{{trans('config.lblm_driver_add')}}</h1>
        <!-- BEGIN BREADCRUMB -->

        <!-- END BREADCRUMB -->
    </div>
    <!-- END PAGE HEADING -->

    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12">
                <div class="panel">
                    <div class="panel-title bg-amber-200">
                        <div class="panel-head">{{trans('config.lbl_driverinfo')}}</div>
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

                        @if (Session::has('driver_added'))
                        <div class="alert alert-success"><strong>{{ Session::get('driver_added') }}</strong></div>
                        @endif

                        @if (Session::has('email_present'))
                        <div class="alert alert-danger"><strong>{{ Session::get('email_present') }}</strong></div>
                        @endif


                        <form class="form-horizontal" action="add_driver" method="post" role="form"
                              enctype="multipart/form-data">

                            <input type="hidden" name="_token" value="{{ csrf_token() }}">
                            <input type="hidden" name="id" id="id">
                            <div class="form-group">
                                <label for="" class="col-sm-2 control-label"></label>
                                <div class="col-sm-4">
                                    <div class="radio radio-theme display-inline-block">
                                        <input name="usercheck" id="new" checked="checked" type="radio" value='mavble'>
                                        <label for="new">{{trans('config.lbl_newdriver')}}</label>
                                        <input name="usercheck" id="old" type="radio" value="femalmne">
                                        <label for="old">{{trans('config.lbl_already')}}</label>
                                    </div>
                                </div>
                            </div>

                            <div id="olddriverid">
                                <div class="form-group">
                                    <label for="" class="col-sm-2 control-label">{{trans('config.lbl_driverold')}}</label>
                                    <div class="col-lg-8">
                                        <div class="col-sm-6 no-padding-left">
                                            <input type="text" name="olddriverid" class="form-control" id="olddriveridv"
                                                   placeholder="" style="float: left;" value="1" type="text">
                                            <div id="dis"></div>
                                        </div>
                                        <div class="col-sm-1 no-padding-left">
                                            <a id="CheckUser" class="btn btn-dark bg-red-600 color-white">{{trans('config.lbl_checkuser')}}</a>

                                        </div>
                                    </div>
                                </div>
                            </div>


                            <div class="form-group  {{ $errors->has('country')? 'has-error':'' }}">
                                <label for="" class="col-sm-2 control-label">{{trans('config.lbl_vehicle_category')}}</label>
                                <div class="col-sm-4">
                                    <select class="form-control " name="ridetype" id="ride_category">
                                        <option value="">--Select --</option>
                                        @foreach ($ridetype as $r)
                                        <option value="{{$r->id}}">{{$r->ride_category}}</option>
                                        @endforeach
                                    </select>
                                    {!! $errors->first('country', '<span class="help-block">:message</span>') !!}
                                </div>
                            </div>


                            <div class="form-group">
                                <label for="" class="col-sm-2 control-label">{{trans('config.lbl_fname')}}</label>
                                <div class="col-sm-4">
                                    <input type="text" name="firstname" class="form-control textonly" id="firstname"
                                           placeholder="" required>
                                    {!! $errors->first('firstname', '<span class="help-block">:message</span>') !!}
                                    <div id="fname"></div>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="" class="col-sm-2 control-label">{{trans('config.lbl_lname')}}</label>
                                <div class="col-sm-4">
                                    <input type="text" name="lastname" class="form-control textonly" id="lastname"
                                           placeholder=""
                                           required>
                                    {!! $errors->first('ride_name', '<span class="help-block">:message</span>') !!}
                                    <div id="lname"></div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="" class="col-sm-2 control-label">{{trans('config.lbl_email')}}</label>
                                <div class="col-sm-4">
                                    <input type="email" name="email" class="form-control" id="emailid" placeholder=""
                                           required>
                                    {!! $errors->first('ride_name', '<span class="help-block">:message</span>') !!}
                                    <div id="email"></div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="" class="col-sm-2 control-label">{{trans('config.lbl_password')}}</label>
                                <div class="col-sm-4">
                                    <input type="text" name="password" class="form-control" id="password"
                                           placeholder="" required>
                                    {!! $errors->first('ride_name', '<span class="help-block">:message</span>') !!}
                                    <div id="pass"></div>
                                </div>
                            </div>
                            <!-- <div class="form-group">
                                <label for="" class="col-sm-2 control-label">Confirm Password</label>
                                <div class="col-sm-4">
                                    <div id="pass1"></div>
                                    <input type="text" name="password1" id="password1" class="form-control" id=""
                                           placeholder="" required>
                                    {!! $errors->first('ride_name', '<span class="help-block">:message</span>') !!}
                                </div>
                            </div> -->
                            <div class="form-group">
                                <label for="" class="col-sm-2 control-label">{{trans('config.lbl_gender')}}</label>
                                <div class="col-sm-4">
                                    <div class="radio radio-theme display-inline-block">
                                        <input name="gender" id="optionsRadios1" checked="checked" type="radio"
                                               value='male'>
                                        <label for="optionsRadios1">Male</label>
                                        <input name="gender" id="optionsRadios2" type="radio" value="female">
                                        <label for="optionsRadios2">Female</label>
                                    </div>
                                    <div id="gender"></div>
                                </div>

                            </div>

                            <div class="form-group">
                                <label for="" class="col-sm-2 control-label">{{trans('config.lbl_dob')}}</label>
                                <div class="col-sm-4">
                                    <input type="text" name="dob" class="form-control datepicker1 " id="datepicker"
                                           placeholder="" required>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="" class="col-sm-2 control-label">{{trans('config.lbl_driverphoto')}}</label>
                                <div class="col-sm-4">
                                    <input type="file" onChange="validateImage('photo')" name="profilepicture" id="photo" class="form-control" required>
                                    <span>Only .jpg,.png are allowed</span>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="" class="col-sm-2 control-label">{{trans('config.lbl_licenseid')}}</label>
                                <div class="col-sm-4">
                                    <input type="text" name="licenseid" maxlength="20" id="license_id"
                                           class="form-control" placeholder="" required>
                                    <div id="licenseid"></div>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="" class="col-sm-2 control-label">{{trans('config.lbl_license')}}</label>
                                <div class="col-sm-4">
                                    <input type="file" onChange="validateImage('license')" name="license" id="license" class="form-control" required>
                                    <span>Only .jpg,.png are allowed</span>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="" class="col-sm-2 control-label">{{trans('config.lbl_mobile')}}</label>
                                <div class="col-sm-4">
                                    <input type="text" maxlength="10" name="mobile" class="form-control" id="mobile_number" placeholder=""
                                           required>
                                    
                                </div>
                            </div>

                            <div class="form-group  {{ $errors->has('country')? 'has-error':'' }}">
                                <label for="" class="col-sm-2 control-label">{{trans('config.lbl_country')}}</label>
                                <div class="col-sm-4">
                                    <select class="form-control " name="country" id="country">
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
                                <label for="" class="col-sm-2 control-label">{{trans('config.lbl_state')}}</label>
                                <div class="col-sm-4">
                                    <select class="form-control " name="state" id="state">
                                        <option value="">--Select State--</option>
                                    </select>
                                    {!! $errors->first('state', '<span class="help-block">:message</span>') !!}
                                </div>
                            </div>

                            <div class="form-group  {{ $errors->has('city')? 'has-error':'' }}">
                                <label for="" class="col-sm-2 control-label">{{trans('config.lbl_city')}}</label>
                                <div class="col-sm-4">
                                    <select class="form-control" name="city" id="city">
                                        <option value="">--Select City--</option>
                                    </select>
                                    {!! $errors->first('city', '<span class="help-block">:message</span>') !!}
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="" class="col-sm-2 control-label">{{trans('config.lbl_address')}}</label>
                                <div class="col-sm-4">
                                    <textarea rows="3" name="address" id="address" class="form-control"></textarea>
                                    <div id="address"></div>
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="col-sm-offset-2 col-sm-4 margin-top-10">
                                    <a href="manage_driver" class="btn btn-dark bg-black color-white">{{trans('config.lbl_back')}}</a>
                                    <button id="button1" type="reset" class="btn btn-dark bg-white-600 color-red">
                                        {{trans('config.lbl_reset')}}
                                    </button>

                                    <input id="button" type="submit" class="btn btn-dark bg-red-600 color-white"
                                           value="Save">
                                </div>
                            </div>
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
</div>


<!-- Added for Client side Javascript Form Validation -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
<script>

    function displayunicode(e) {
        var unicode = e.keyCode ? e.keyCode : e.charCode
        if (unicode >= 48 && unicode <= 57) {
            $("#firstname").css({
                "border": "1px solid red",
                "background": ""
            });
        }
    }

    function displayunicodel(e1) {
        var unicode = e1.keyCode ? e1.keyCode : e1.charCode
        if (unicode >= 48 && unicode <= 57) {
            alert('number');
        }
        else {
            alert('nonnumber');
        }
    }

    $(document).ready(function () {

       /*  $('#mobile_number').keypress(function(e){
            console.log("inside");
       var r = $('#mobile_number').val().length;
       alert(r);
        }); */

        $('.textonly').keypress(function (e) {
            var regex = new RegExp("^[a-zA-Z]+$");
            var str = String.fromCharCode(!e.charCode ? e.which : e.charCode);
            if (regex.test(str)) {
                return true;
            }
            e.preventDefault();
            return false;
        });
        $('#mobile_number').keypress(function (e) {
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
            document.getElementById("olddriveridv").value = "1";
            $("#olddriverid").hide();
        });

        $("#old").click(function () {
            document.getElementById("olddriveridv").value = "";

            $("#olddriverid").show();
        });

        $('#button').click(function (e) {

            var isvalid = true;
            $(".form-control").each(function () {
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

            var e = $("#firstname").val();
            var lname = $("#lastname").val();
            var unicode = e.keyCode ? e.keyCode : e.charCode
            if (unicode >= 48 && unicode <= 57) {
                $("#firstname").css({
                    "border": "1px solid red",
                    "background": ""
                });
            }


            var m = $("#mobile").val();
            if (m.length < 9 || m.length > 12) {
                $("#mobile").focus();
                document.getElementById("mobile").value = '';
                $("#mobile").css({
                    "border": "1px solid red",
                    "background": ""
                });
                return false;
            }
           /*  if (m.length > 12) {
                $("#mobile").focus();
                document.getElementById("mobile").value = '';
                $("#mobile").css({
                    "border": "1px solid red",
                    "background": ""
                });
                return false;
            } */

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
                    url: 'checkuser',
                    data: {data_id: id},
                    dataType: 'json',
                    success: function (data) {
                        console.log(data.Response);
                        console.log(data.state);
                        document.getElementById("firstname").value = data.firstname;
                        document.getElementById("lastname").value = data.lastname;
                        document.getElementById("emailid").value = data.email;
                        document.getElementById("password").value = data.password;
                        
                        $('#license_id').val(data.licenseid);
                        console.log(data.licenseid);
                        document.getElementById("ride_category").value = data.ride_category;
                        document.getElementById("address").value = data.address;
                        document.getElementById("city").value = data.city;
                        document.getElementById("id").value = data.id;

                        document.getElementById("license").removeAttribute("required");
                        document.getElementById("photo").removeAttribute("required");
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
@endsection