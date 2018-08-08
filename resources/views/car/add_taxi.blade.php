@extends('layout.master');

@section('title')

    AddVehicle - Wrydes

@endsection

@section('content')
    <div class="rightside bg-grey-100">
        <!-- BEGIN PAGE HEADING -->
        <div class="page-head">
            <h1 class="page-title">{{ trans('config.lblv_addtile') }}</h1>
            <!-- BEGIN BREADCRUMB -->

            <!-- END BREADCRUMB -->
        </div>
        <!-- END PAGE HEADING -->

        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-12">
                    <div class="panel">
                        <div class="panel-title bg-grey-300">
                            <div class="panel-head">{{ trans('config.lblv_heading') }}</div>
                        </div>
                        <div class="panel-body">
                            <form class="form-horizontal" role="form" method="POST" enctype="multipart/form-data">
                                {{ csrf_field() }}
                                @if (session('error_status'))
                                    <div class="alert alert-danger">
                                        <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                                        {{ session('error_status') }}
                                    </div>
                                @endif

                                <!-- franchise check -->

                                <div class="form-group">
                                    <label for="" class="col-sm-2 control-label">Company Type</label>
                                    <div class="col-sm-4">
                                        <div class="radio radio-theme display-inline-block">
                                            <input name="Franchise" id="franchiseyes"  type="radio" >
                                            <label for="franchiseyes">Franchise</label>
                                            <input name="Franchise" id="franchiseno" checked="checked" type="radio" >
                                            <label for="franchiseno">Go</label>
                                        </div>
                                    </div>
                                </div>


                                <div id="franchise">
                                    <div class="form-group">
                                        <label for="" class="col-sm-2 control-label">Franchise</label>
                                        <div class="col-sm-4">
                                        <select class="form-control " name="franchise" id="">
                                            <option value="">--Select Your Franchise--</option>
                                            @foreach ($franchise as $r)
                                            <option value="{{$r->id}}">{{$r->company_name}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    </div>
                                </div>

                                <!-- franchise check end -->
                                {{--<div class="form-group {{ $errors->has('ride_category')? 'has-error':'' }}">--}}
                                    {{--<label for=""--}}
                                           {{--class="col-sm-2 control-label">{{trans('config.lbl_vehicle_category') }}</label>--}}
                                    {{--<div class="col-sm-4">--}}
                                        {{--<select class="form-control" name="ride_category" >--}}
                                            {{--@foreach ($ride_category as $cat)--}}

                                            {{--@endforeach--}}
                                        {{--</select>--}}
                                    {{--</div>--}}
                                {{--</div>--}}

                                <div class="form-group">
                                    <label for="" class="col-sm-2 control-label">Select Vehicle Category</label>
                                    <div class="col-sm-4">
                                        <select class="form-control " name="VehicleCategory" id="VehicleCategory">
                                            <option value="">--Select Type--</option>
                                            @foreach ($ride_category as $ride)
                                                <option value="{{$ride->id}}" >{{$ride->ride_category}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="" class="col-sm-2 control-label">Select Vehicle Type</label>
                                    <div class="col-sm-4">
                                        <select class="form-control " name="VehicleType" id="VehicleType">
                                            <option value="">--Select Car--</option>

                                        </select>
                                    </div>
                                </div>


                               <div class="form-group  {{ $errors->has('taxi_no')? 'has-error':'' }}">
                                    <label for=""
                                           class="col-sm-2 control-label">{{ trans('config.lblv_vehicle') }}</label>
                                    <div class="col-sm-4">
                                        <input type="text" class="form-control" id="VehicleNo" value="{{old('taxi_no')}}"
                                               placeholder="" name="taxi_no"  required>
                                        {!! $errors->first('taxi_no', '<span class="help-block">:message</span>') !!}
                                    </div>
                                </div>

                                <div class="form-group  {{ $errors->has('VehicleImage')? 'has-error':'' }}">
                                    <label for=""
                                           class="col-sm-2 control-label">Vehicle Image</label>
                                    <div class="col-sm-4">
                                        <input type="file" class="form-control" id="VehicleImage" value=""
                                               placeholder="" onChange="validateImage('VehicleImage')" name="VehicleImage" required>
                                        {!! $errors->first('VehicleImage', '<span class="help-block">:message</span>') !!}
                                    </div>
                                </div>

                              
                                <div class="form-group  {{ $errors->has('taxi_brand')? 'has-error':'' }}">
                                    <label for=""
                                           class="col-sm-2 control-label">{{ trans('config.lbl_vehicle_brnad') }}</label>
                                    <div class="col-sm-4">
                                        <select class="form-control " name="taxi_brand" id="VehicleBrand">
                                            <option value="">--Select Brand--</option>
                                            @foreach ($carbrand as $brand)
                                                <option value="{{$brand->id}}" <?php if (old('taxi_brand') == $brand->id) {
                                                    echo "selected=selected";
                                                } ?>>{{$brand->brand}}</option>
                                            @endforeach
                                        </select>
                                        {!! $errors->first('taxi_brand', '<span class="help-block">:message</span>') !!}
                                    </div>
                                </div>
                                <div class="form-group  {{ $errors->has('taxi_model')? 'has-error':'' }}">
                                    <label for=""
                                           class="col-sm-2 control-label">{{ trans('config.lbl_vehicle_model') }}</label>
                                    <div class="col-sm-4">
                                        <input type="hidden" name="t_type" id="t_type" value="{{old('taxi_model')}}"/>
                                        <select class="form-control" id="taxi-model" name="taxi_model">
                                            <option value="">--Select Model--</option>
                                            
                                        </select>
                                        {!! $errors->first('taxi_model', '<span class="help-block">:message</span>') !!}
                                    </div>
                                </div>

                                <!--<div class="form-group  {{ $errors->has('taxi_capacity')? 'has-error':'' }}">
                                    <label for=""
                                           class="col-sm-2 control-label">{{ trans('config.lblv_capacity') }}</label>
                                    <div class="col-sm-4">
                                        <input type="number" class="form-control" value="{{old('taxi_capacity')}}" id=""
                                               placeholder="" name="taxi_capacity" required max="99">
                                        {!! $errors->first('taxi_capacity', '<span class="help-block">:message</span>') !!}
                                    </div>
                                </div>-->

                                <div class="form-group  {{ $errors->has('country')? 'has-error':'' }}">
                                    <label for=""
                                           class="col-sm-2 control-label">{{ trans('config.lbl_country') }}</label>
                                    <div class="col-sm-4">
                                        <select class="form-control " name="country" id="country">
                                            <option value="">--Select Country--</option>
                                            @foreach ($country_list as $country)
                                                <option value="{{$country->id}}" <?php if (old('country') == $country->id) {
                                                    echo "selected=selected";
                                                } ?>>{{$country->name}}</option>
                                            @endforeach
                                        </select>
                                        {!! $errors->first('country', '<span class="help-block">:message</span>') !!}
                                    </div>
                                </div>

                                <div class="form-group  {{ $errors->has('state')? 'has-error':'' }}">
                                    <label for="" class="col-sm-2 control-label">{{ trans('config.lbl_state') }}</label>
                                    <div class="col-sm-4">
                                        <select class="form-control " name="state" id="state">
                                            <option value="">--Select State--</option>
                                        </select>
                                        {!! $errors->first('state', '<span class="help-block">:message</span>') !!}
                                    </div>
                                </div>

                                <div class="form-group  {{ $errors->has('city')? 'has-error':'' }}">
                                    <label for="" class="col-sm-2 control-label">{{ trans('config.lbl_city') }}</label>
                                    <div class="col-sm-4">
                                        <select class="form-control" name="city" id="city">
                                            <option value="">--Select City--</option>
                                        </select>
                                        {!! $errors->first('city', '<span class="help-block">:message</span>') !!}
                                    </div>
                                </div>

                                <div class="form-group  {{ $errors->has('rc_book_image')? 'has-error':'' }}">
                                    <label for=""
                                           class="col-sm-2 control-label">{{ trans('config.lblv_rc_image') }}</label>
                                    <div class="col-sm-4">
                                        <input type="file" class="form-control" id="rc_book_image"
                                               onChange="validateImage('rc_book_image')" name="rc_book_image">
                                        <span>{{ trans('config.lbl_valid_img_format') }}</span>
                                        {!! $errors->first('rc_book_image', '<span class="help-block">:message</span>') !!}
                                    </div>
                                </div>

                                <div class="form-group  {{ $errors->has('rc_number')? 'has-error':'' }}">
                                    <label for=""
                                           class="col-sm-2 control-label">{{ trans('config.lblv_rc_number') }}</label>
                                    <div class="col-sm-4">
                                        <input type="text" class="form-control alphanumeric" maxlength="20"
                                               value="{{old('rc_number')}}" id="" name="rc_number" placeholder=""
                                               required>
                                        {!! $errors->first('rc_number', '<span class="help-block">:message</span>') !!}
                                    </div>
                                </div>

                                <div class="form-group  {{ $errors->has('insurance_image')? 'has-error':'' }}">
                                    <label for=""
                                           class="col-sm-2 control-label">{{ trans('config.lblv_insurance_img') }}</label>
                                    <div class="col-sm-4">
                                        <input type="file" class="form-control" id="insurance_image"
                                               onChange="validateImage('insurance_image')" name="insurance_image">
                                        <span>{{ trans('config.lbl_valid_img_format') }}</span>
                                        {!! $errors->first('insurance_image', '<span class="help-block">:message</span>') !!}

                                    </div>
                                </div>

                                <div class="form-group  {{ $errors->has('insurance_expiry_date')? 'has-error':'' }}">
                                    <label for=""
                                           class="col-sm-2 control-label">{{ trans('config.lblv_insurance_expiry') }}</label>
                                    <div class="col-sm-4">
                                        <input class="form-control datepicker" value="{{old('insurance_expiry_date')}}"
                                               name="insurance_expiry_date" id="datepicker" placeholder="" required=""
                                               type="text">
                                        {!! $errors->first('insurance_expiry_date', '<span class="help-block">:message</span>') !!}
                                    </div>
                                </div>

                                <div class="form-group">
                                    <div class="col-sm-offset-2 col-sm-4 margin-top-10">
                                        <button type="button" class="btn btn-dark bg-black color-white"
                                                onclick="window.location.href='{{ url('/taxi') }}';">{{trans('config.lbl_back') }}</button>
                                        <button type="submit"
                                                class="btn btn-dark bg-yellow-600 color-white">{{trans('config.lbl_submit') }}</button>
                                        <button type="reset"
                                                class="btn btn-dark bg-grey-400 color-black">{{trans('config.lbl_reset') }}</button>
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
    <script>
        /*	jQuery(function() {
         jQuery("#taxi-brand").customselect();
         jQuery("#taxi-type").customselect();
         jQuery("#taxi-model").customselect();
         jQuery("#country").customselect();
         jQuery("#state").customselect();
         jQuery("#city").customselect();
         });*/
         $(document).ready(function(){
             $('#VehicleNo').keypress(function (e) {
            var regex = new RegExp("^[a-zA-Z0-9]+$");
            var str = String.fromCharCode(!e.charCode ? e.which : e.charCode);
            if (regex.test(str)) {
                return true;
            }

            e.preventDefault();
            return false;
        });

             $("#franchise").hide();

             $("#franchiseyes").click(function(){
                $("#franchise").show();
             });

             $("#franchiseno").click(function(){
                $("#franchise").hide();
             });
         });

    </script>

@endsection