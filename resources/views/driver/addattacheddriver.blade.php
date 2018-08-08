<!-- BEGIN RIGHTSIDE -->

<div class="rightside bg-grey-100">

    <!-- BEGIN PAGE HEADING -->

    <div class="page-head">

        <h1 class="page-title">Add Attached Drivers</h1>

        <!-- BEGIN BREADCRUMB -->


        <!-- END BREADCRUMB -->

    </div>

    <!-- END PAGE HEADING -->


    @if (Session::has('attached_driver_added'))
    <div class="alert alert-success"><strong>{{ Session::get('attached_driver_added') }}</strong></div>
    @endif
    <div class="container-fluid">

        <div class="row">

            <div class="col-lg-12">

                <div class="panel">

                    <div class="panel-title bg-amber-200">

                        <div class="panel-head">Driver & Taxi Information</div>

                    </div>

                    <div class="panel-body">

                        <form name="form" class="form-horizontal" method="post" action="add_attached_driver" role="form"
                              enctype="multipart/form-data">

                            <input type="hidden" name="_token" value="{{ csrf_token() }}">

                            <div class="col-lg-6">

                                <h4>Driver Details:</h4>

                                <p class="text-light margin-bottom-30"></p>

                                <div class="form-group">

                                    <label for="" class="col-sm-4 control-label">Driver First Name</label>

                                    <div class="col-sm-8">

                                        <input name="driver_first_name" type="text" class="form-control">

                                    </div>

                                </div>


                                <div class="form-group">

                                    <label for="" class="col-sm-4 control-label">Driver Last Name</label>

                                    <div class="col-sm-8">

                                        <input name="driver_last_name" type="text" class="form-control">

                                    </div>

                                </div>


                                <div class="form-group">

                                    <label for="" class="col-sm-4 control-label">Email</label>

                                    <div class="col-sm-8">

                                        <input name="email" type="email" class="form-control">

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

                                        <input name="dob" class="form-control datepicker" id="datepicker" placeholder=""
                                               required="" type="text">

                                    </div>

                                </div>


                                <div class="form-group">

                                    <label for="" class="col-sm-4 control-label">Mobile Number</label>

                                    <div class="col-sm-8">

                                        <input type="tel" name="mobile_number" class="form-control">

                                    </div>

                                </div>


                                <div class="form-group">

                                    <label for="" class="col-sm-4 control-label">Password</label>

                                    <div class="col-sm-8">

                                        <input type="password" name="password" class="form-control">

                                    </div>

                                </div>


                                <div class="form-group">

                                    <label for="" class="col-sm-4 control-label">Confirm Password</label>

                                    <div class="col-sm-8">

                                        <input type="password" name="confirm_password" class="form-control">

                                    </div>

                                </div>


                                <div class="form-group">
                                    <label for="" class="col-sm-4 control-label">Country</label>
                                    <div class="col-sm-8">
                                        <select name="country" class="form-control" onchange="showState1(this.value)"
                                                required>
                                            <option disabled selected value> -- select an Country --</option>
                                            <?php use App\country; $d = new country();
									  $ds = $d->all();
                                            foreach($ds as $sd){
                                            echo '
                                            <option value="'.$sd->id.'">'.$sd->name.'</option>
                                            ';
                                            } ?>
                                        </select>
                                    </div>
                                </div>
                                <div id="state"></div>
                                <div id="city"></div>


                                <div class="form-group">

                                    <label for="" class="col-sm-4 control-label">Driver License Number</label>

                                    <div class="col-sm-8">

                                        <input type="file" name="driver_license_number" class="form-control">

                                    </div>

                                </div>


                            </div>


                            <div class="col-lg-6">

                                <h4>Taxi Details:</h4>

                                <p class="text-light margin-bottom-30"></p>

                                <div class="form-group">

                                    <label for="" class="col-sm-4 control-label">Taxi Number</label>

                                    <div class="col-sm-8">

                                        <input type="text" name="taxi_number" class="form-control">

                                    </div>

                                </div>


                                <div class="form-group">

                                    <label for="" class="col-sm-4 control-label">Taxi Brand</label>

                                    <div class="col-sm-8">

                                        <select onchange="showmodel(this.value)" name="taxi_brand" class="form-control">
                                            <option disabled selected value> -- select an option --</option>

                                            @foreach($ds as $ad)
                                            <option value="{{$ad->id}}">{{$ad->brand}}</option>
                                            @endforeach
                                        </select>

                                    </div>

                                </div>
                                <div id="model"></div>
                                <div id="car_type"></div>


                                <div class="form-group">

                                    <label for="" class="col-sm-4 control-label">Capacity</label>

                                    <div class="col-sm-8">

                                        <input type="text" name="capacity" class="form-control">

                                    </div>

                                </div>


                                <div class="form-group">

                                    <label for="" class="col-sm-4 control-label">RC Number</label>

                                    <div class="col-sm-8">

                                        <input type="text" name="rc_number" class="form-control">

                                    </div>

                                </div>


                                <div class="form-group">

                                    <label for="" class="col-sm-4 control-label">RC Image</label>

                                    <div class="col-sm-8">

                                        <input type="file" name="rc_image" class="form-control">

                                    </div>

                                </div>

                                <div class="form-group">

                                    <label for="" class="col-sm-4 control-label">Insurance Image</label>

                                    <div class="col-sm-8">

                                        <input type="file" name="insurance_image" class="form-control">

                                    </div>

                                </div>

                                <div class="form-group">

                                    <label for="" class="col-sm-4 control-label">Insurance Expiry Date</label>

                                    <div class="col-sm-8">

                                        <input type="text" class="form-control datepicker" name="insurance_exp_date"
                                               id="datepicker1" class="form-control">

                                    </div>

                                </div>

                            </div>


                            <div class="col-md-12">

                                <div class="form-group pull-right">

                                    <div class="col-sm-12 margin-top-10 ">

                                        <button type="reset" class="btn btn-dark bg-grey-400 color-black">Reset</button>

                                        <button id="button" type="submit" class="btn btn-dark bg-red-600 color-white">
                                            Add
                                        </button>

                                    </div>

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

</div><!-- /.rightside -->


<!-- Added for Client side Javascript Form Validation -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
<script>
    $(document).ready(function () {

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
                }
            });

        });
    });


</script>

<script>
    function showmodel(str) {
        if (str == "") {
            document.getElementById("model").innerHTML = "";
            return;
        } else {
            if (window.XMLHttpRequest) {
                // code for IE7+, Firefox, Chrome, Opera, Safari
                xmlhttp = new XMLHttpRequest();
            } else {
                // code for IE6, IE5
                xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
            }
            xmlhttp.onreadystatechange = function () {
                if (this.readyState == 4 && this.status == 200) {
                    document.getElementById("model").innerHTML = this.responseText;
                }
            };
            xmlhttp.open("GET", "getmodel/" + str, true);
            xmlhttp.send();
        }
    }
</script>

<script>
    function showcartype(str) {
        if (str == "") {
            document.getElementById("car_type").innerHTML = "";
            return;
        } else {
            if (window.XMLHttpRequest) {
                // code for IE7+, Firefox, Chrome, Opera, Safari
                xmlhttp = new XMLHttpRequest();
            } else {
                // code for IE6, IE5
                xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
            }
            xmlhttp.onreadystatechange = function () {
                if (this.readyState == 4 && this.status == 200) {
                    document.getElementById("car_type").innerHTML = this.responseText;
                }
            };
            xmlhttp.open("GET", "getcartype/" + str, true);
            xmlhttp.send();
        }
    }
</script>
<script>
    function showState(str) {
        if (str == "") {
            document.getElementById("txtHint").innerHTML = "";
            return;
        } else {
            if (window.XMLHttpRequest) {
                // code for IE7+, Firefox, Chrome, Opera, Safari
                xmlhttp = new XMLHttpRequest();
            } else {
                // code for IE6, IE5
                xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
            }
            xmlhttp.onreadystatechange = function () {
                if (this.readyState == 4 && this.status == 200) {
                    document.getElementById("txtHint").innerHTML = this.responseText;
                }
            };
            xmlhttp.open("GET", "getstate2/" + str, true);
            xmlhttp.send();
        }
    }
</script>

<script>
    function showCity(str) {
        if (str == "") {
            document.getElementById("txtHint1").innerHTML = "";
            return;
        } else {
            if (window.XMLHttpRequest) {
                // code for IE7+, Firefox, Chrome, Opera, Safari
                xmlhttp = new XMLHttpRequest();
            } else {
                // code for IE6, IE5
                xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
            }
            xmlhttp.onreadystatechange = function () {
                if (this.readyState == 4 && this.status == 200) {
                    document.getElementById("txtHint1").innerHTML = this.responseText;
                }
            };
            xmlhttp.open("GET", "getcity2/" + str, true);
            xmlhttp.send();
        }
    }
</script>

<script>
    function showState1(str) {
        if (str == "") {
            document.getElementById("state").innerHTML = "";
            return;
        } else {
            if (window.XMLHttpRequest) {
                // code for IE7+, Firefox, Chrome, Opera, Safari
                xmlhttp = new XMLHttpRequest();
            } else {
                // code for IE6, IE5
                xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
            }
            xmlhttp.onreadystatechange = function () {
                if (this.readyState == 4 && this.status == 200) {
                    document.getElementById("state").innerHTML = this.responseText;
                }
            };
            xmlhttp.open("GET", "getstate1/" + str, true);
            xmlhttp.send();
        }
    }
</script>

<script>
    function showCity1(str) {
        if (str == "") {
            document.getElementById("city").innerHTML = "";
            return;
        } else {
            if (window.XMLHttpRequest) {
                // code for IE7+, Firefox, Chrome, Opera, Safari
                xmlhttp = new XMLHttpRequest();
            } else {
                // code for IE6, IE5
                xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
            }
            xmlhttp.onreadystatechange = function () {
                if (this.readyState == 4 && this.status == 200) {
                    document.getElementById("city").innerHTML = this.responseText;
                }
            };
            xmlhttp.open("GET", "getcity1/" + str, true);
            xmlhttp.send();
        }
    }
</script>