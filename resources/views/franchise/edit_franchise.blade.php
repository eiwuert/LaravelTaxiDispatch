@extends('layout.master');

@section('title')

Edit Company - Go Cabs

@endsection

@section('content')
<div class="rightside bg-grey-100">

    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12">
                <div class="panel">
                    <div class="panel-title bg-amber-200">
                        <div class="panel-head">Edit Company</div>
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

                        <form class="form-horizontal" action="" method="post" role="form"
                              enctype="multipart/form-data">

                            <input type="hidden" name="_token" value="{{ csrf_token() }}">
                            <input type="hidden" name="id" value="{{$data->id}}">
                            <div class="form-group">
                                <label for="" class="col-sm-2 control-label"></label>
                                <div class="col-sm-4">
                                    <label for="" class="control-label">Personal Information:</label>
                                </div>
                            </div>


                            <div class="form-group">
                                <label for="" class="col-sm-2 control-label">{{trans('config.lbl_fname')}}</label>
                                <div class="col-sm-4">
                                    <input type="text" name="firstname" class="form-control textonly symval" id="firstname"
                                           placeholder="" value="{{$data->first_name}}" required>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="" class="col-sm-2 control-label">{{trans('config.lbl_lname')}}</label>
                                <div class="col-sm-4">
                                    <input type="text" value="{{$data->last_name}}" name="lastname" class="form-control textonly symval" id="lastname"
                                           placeholder=""
                                           required>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="" class="col-sm-2 control-label">{{trans('config.lbl_email')}}</label>
                                <div class="col-sm-4">
                                    <input type="email" value="{{$data->email}}" name="email" class="form-control symval" id="emailid" placeholder=""
                                           required>
                                </div>
                            </div>

                        
                            <div class="form-group">
                                <label for="" class="col-sm-2 control-label">{{trans('config.lbl_password')}}</label>
                                <div class="col-sm-4">
                                    <input type="text" value="" name="password" class="form-control" id="password"
                                           placeholder="" >
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="" class="col-sm-2 control-label">Service Tax Image</label>
                                <div class="col-sm-4">
                                   <img height="200" width="300" src="{{url("/")}}/public{{$data->service_tax_image}}">
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="" class="col-sm-2 control-label">Service Tax Image</label>
                                <div class="col-sm-4">
                                    <input type="file" name="service_tax_image" onchange="validateImage('service_tax_image')" class="form-control" id="service_tax_image"
                                           placeholder="" >
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="" class="col-sm-2 control-label">Service Tax number</label>
                                <div class="col-sm-4">
                                    <input type="text" name="service_tax_number" class="form-control alphanumeric symval" id="service_tax_number"
                                           placeholder="" value="{{$data->service_tax_number}}" required>
                                </div>
                            </div>

                            <!-- <div class="form-group">
                                <label for="" class="col-sm-2 control-label">Confirm Password</label>
                                <div class="col-sm-4">
                                    <div id="pass1"></div>
                                    <input type="text" value="" name="password1" id="password1" class="form-control" id=""
                                           placeholder="" required>

                                </div>
                            </div> -->

                            <div class="form-group">
                                <label for="" class="col-sm-2 control-label">{{trans('config.lbl_mobile')}}</label>
                                <div class="col-sm-4">
                                    <input type="text" value="{{$data->mobile}}" maxlength="10" name="mobile" class="form-control symval"
                                           id="mobile_number" placeholder=""
                                           required>

                                </div>
                            </div>

                            <div class="form-group">
                                <label for="" class="col-sm-2 control-label">{{trans('config.lbl_address')}}</label>
                                <div class="col-sm-4">
                                    <textarea rows="3" value="{{$data->address}}" name="address" id="address" class="form-control symval">{{$data->address}}</textarea>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="" class="col-sm-2 control-label"></label>
                                <div class="col-sm-4">
                                    <label for="" class="control-label">Company Information:-</label>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="" class="col-sm-2 control-label">Company Name:</label>
                                <div class="col-sm-4">
                                    <input type="text" value="{{$data->company_name}}" name="companyname" class="form-control symval" id="password"
                                           placeholder="" required>
                                </div>
                            </div>

                            <!-- Landline number -->
                            <div class="form-group">
                                <label for="" class="col-sm-2 control-label">Landline Number</label>
                                <div class="col-sm-4">
                                    <input type="text" maxlength="15" name="landlinenumber" class="form-control" id="landlinenumber"
                                           placeholder="" @if($data->landline != "") value="{{$data->landline}} @endif">
                                </div>
                            </div>
                            <!-- End of landline number -->

                            <div class="form-group">
                                <label for="" class="col-sm-2 control-label">{{trans('config.lbl_country')}}</label>
                                <div class="col-sm-4">
                                    <select class="form-control symval" name="country" id="country">
                                        <option value="">--Select Country--</option>
                                        @foreach ($country_list as $country)
                                        <option value="{{$country->id}}" 
                                        <?php if ($data->country == $country->id) {
                                            echo "selected=selected";
                                        } ?>>{{$country->name}}
                                        </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="form-group  {{ $errors->has('state')? 'has-error':'' }}">
                                <label for="" class="col-sm-2 control-label">{{trans('config.lbl_state')}}</label>
                                <div class="col-sm-4">
                                    <select class="form-control symval" name="state" id="state">
                                        <option value="">--Select State--</option>
                                        <input type="hidden" value="<?php echo $data->state; ?>" id="temp_state"/>
                                    </select>
                                </div>
                            </div>

                            <div class="form-group  {{ $errors->has('city')? 'has-error':'' }}">
                                <label for="" class="col-sm-2 control-label">District</label>
                                <div class="col-sm-4">
                                    <select class="form-control symval" name="city" id="city">
                                        <option value="">--Select District--</option>
                                        <input type="hidden" value="<?php echo $data->city; ?>" id="temp_city"/>
                                    </select>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="" class="col-sm-2 control-label">Company Address</label>
                                <div class="col-sm-4">
                                    <textarea rows="3" name="companyaddress" value="{{$data->company_address}}" id="companyaddress"
                                              class="form-control symval">{{$data->company_address}}</textarea>
                                </div>
                            </div>


                            <div class="form-group">
                                <div class="col-sm-offset-2 col-sm-4 margin-top-10">
                                    <a href="{{url('/')}}/manage_franchise" class="btn btn-dark bg-black color-white">{{trans('config.lbl_back')}}</a>
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
            </div>
        </div>

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

         $('#landlinenumber').keypress(function (e) {
            var regex = new RegExp("^[0-9]+$");
            var str = String.fromCharCode(!e.charCode ? e.which : e.charCode);
            if (regex.test(str)) {
                return true;
            }
            e.preventDefault();
            return false;
        });
         
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


        $('#button').click(function (e) {

            var isvalid = true;
            $(".symval").each(function () {
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


    });


</script>
@endsection
