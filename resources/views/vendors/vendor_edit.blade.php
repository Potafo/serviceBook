@extends('layouts.app', ['page' => __('User Profile'), 'pageSlug' => 'vendors'])
{{-- <script src="{{ asset('black') }}/js/core/jquery.min.js"></script> --}}
<script src="{{ asset('black') }}/js/core/jquery-3.4.1.min.js"></script>
<style>
select > option {
    color: black;
}
    </style>
@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="title">{{ __('Vendor Details') }}</h5>
                </div>
                <form method="post" action="{{ route('vendors.update') }}" autocomplete="off"  enctype="multipart/form-data">
                    <div class="card-body">
                            @csrf
                            @method('put')

                            @include('alerts.success')
                            <input type="hidden" name="vendorid" id="vendorid" value="{{ $vendor[0]->vid }}">
                            <div class="form-row">
                                <div class="form-group{{ $errors->has('name') ? ' has-danger' : '' }} col-md-6">
                                    <label>{{ __('Name') }}</label>
                                    <input type="text" name="name" id="name" class="form-control{{ $errors->has('name') ? ' is-invalid' : '' }}" placeholder="{{ __('Name') }}" value="{{ old('name',$vendor[0]->vname) }}">
                                    @include('alerts.feedback', ['field' => 'name'])
                                </div>
                                <div class="form-group{{ $errors->has('mobile') ? ' has-danger' : '' }} col-md-6">
                                    <label>{{ __('Mobile') }}</label>
                                    <input type="text" name="mobile" class="form-control{{ $errors->has('mobile') ? ' is-invalid' : '' }}" placeholder="{{ __('Mobile') }}" value="{{ old('mobile',$vendor[0]->contact_number) }}">
                                    @include('alerts.feedback', ['field' => 'mobile'])
                                </div>
                            </div>
                            <div class="form-row">
                                <?php
                                $shotkey= ($vendor[0]->shortkey ==null) ? (mt_rand(100000,999999)) : ($vendor[0]->shortkey);
                                ?>
                                <div class="form-group{{ $errors->has('shortkey') ? ' has-danger' : '' }} col-md-6">
                                    <label>{{ __('Short Key') }}<span style="color: red"> *</span></label>
                                    <input type="text" name="shortkey" id="shortkey" class="form-control{{ $errors->has('shortkey') ? ' is-invalid' : '' }}" placeholder="{{ __('Short key') }}" value="{{ old('shortkey',$shotkey) }}">
                                    @include('alerts.feedback', ['field' => 'shortkey'])
                                </div>

                                <div class="form-group{{ $errors->has('shortcode') ? ' has-danger' : '' }} col-md-6">
                                    <label>{{ __('Short Code') }}<span style="color: red"> *</span></label>
                                    <input type="text" name="shortcode" id="shortcode" class="form-control{{ $errors->has('shortcode') ? ' is-invalid' : '' }}" placeholder="{{ __('Short Code - Max 3') }}" maxlength="3" value="{{ old('shortcode',$vendor[0]->short_code) }}">
                                    @include('alerts.feedback', ['field' => 'shortcode'])
                                </div>

                            </div>
                            <div class="form-row">
                                <div class="form-group{{ $errors->has('address') ? ' has-danger' : '' }} col-md-6">
                                    <label>{{ __('Address') }}</label>
                                    <input type="text" name="address" class="form-control{{ $errors->has('address') ? ' is-invalid' : '' }}" placeholder="{{ __('Address') }}" value="{{ old('address',$vendor[0]->address) }}">
                                    @include('alerts.feedback', ['field' => 'address'])
                                </div>
                                <div class="form-group{{ $errors->has('email') ? ' has-danger' : '' }} col-md-6">
                                    <label>{{ __('Email address') }}</label>
                                    <input type="email" name="email" class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}" placeholder="{{ __('Email address') }}" value="{{ old('email',$vendor[0]->mail_id) }}">
                                    @include('alerts.feedback', ['field' => 'email'])
                                </div>

                            </div>
                            <div class="form-row">
                                <div class="form-group{{ $errors->has('latitude') ? ' has-danger' : '' }} col-md-6">
                                    <label>{{ __('Latitude') }}</label>
                                    <input type="text" name="latitude" class="form-control{{ $errors->has('latitude') ? ' is-invalid' : '' }}" placeholder="{{ __('Latitude') }}" value="{{ old('latitude',$vendor[0]->location_lat) }}">
                                    @include('alerts.feedback', ['field' => 'latitude'])
                                </div>
                                <div class="form-group{{ $errors->has('longitude') ? ' has-danger' : '' }} col-md-6" >
                                    <label>{{ __('Longitude') }}</label>
                                    <input type="text" name="longitude" class="form-control{{ $errors->has('longitude') ? ' is-invalid' : '' }}" placeholder="{{ __('Longitude') }}" value="{{ old('longitude',$vendor[0]->location_long) }}">
                                    @include('alerts.feedback', ['field' => 'longitude'])
                                </div>

                            </div>
                            <div class="form-row">
                                <div class="form-group{{ $errors->has('maplink') ? ' has-danger' : '' }} col-md-6">
                                    <label>{{ __('Map Link') }}</label>
                                    <input type="text" name="maplink" class="form-control{{ $errors->has('maplink') ? ' is-invalid' : '' }}" placeholder="{{ __('Map Link') }}" value="{{ old('maplink',$vendor[0]->location_maplink) }}">
                                    @include('alerts.feedback', ['field' => 'maplink'])
                                </div>
                                <div class="form-group{{ $errors->has('embed') ? ' has-danger' : '' }} col-md-6">
                                    <label>{{ __('Embed') }}</label>
                                    <input type="text" name="embed" class="form-control{{ $errors->has('embed') ? ' is-invalid' : '' }}" placeholder="{{ __('Longitude') }}" value="{{ old('embed',$vendor[0]->location_embed) }}">
                                    @include('alerts.feedback', ['field' => 'embed'])
                                </div>

                            </div>
                            <div class="form-row">
                                <div class="form-group{{ $errors->has('webname') ? ' has-danger' : '' }} col-md-6">
                                    <label>{{ __('Web Name') }}<span style="color: red"> *</span></label>
                                    <input type="text" name="webname" id="webname" class="form-control{{ $errors->has('webname') ? ' is-invalid' : '' }}" placeholder="{{ __('Web Name') }}" value="{{ old('webname',$vendor[0]->web_name) }}">
                                    @include('alerts.feedback', ['field' => 'webname'])
                                    <span id="suggestions">  <!--Web name Suggestions -->

                                    </span>
                                </div>
                                <div class="form-group{{ $errors->has('website') ? ' has-danger' : '' }} col-md-6">
                                    <label>{{ __('Website') }}</label>
                                    <input type="text" name="website" class="form-control{{ $errors->has('website') ? ' is-invalid' : '' }}" placeholder="{{ __('Website') }}" value="{{ old('website',$vendor[0]->website) }}">
                                    @include('alerts.feedback', ['field' => 'website'])
                                </div>

                            </div>
                            <div class="form-row">
                                <div class="form-group{{ $errors->has('description') ? ' has-danger' : '' }} col-md-6">
                                    <label>{{ __('Description') }}</label>
                                    <input type="text" name="description" class="form-control{{ $errors->has('description') ? ' is-invalid' : '' }}" placeholder="{{ __('Description') }}" value="{{ old('description',$vendor[0]->description) }}">
                                    @include('alerts.feedback', ['field' => 'description'])
                                </div>
                                <div class="form-group col-md-6">
                                    <label>{{ __('Refferal') }}</label>
                                    <select class="form-control selectpicker " data-style="select-with-transition" title="Single Select" data-size="7" placeholder="{{ __('Refferal') }}" name="refferal" id="refferal">
                                        <option value="0">No Refferal</option>
                                        @foreach($salesexec as $list)
                                                <option value="{{$list->id}}" @if($vendor[0]->refferal_by == $list->id) selected @endif>{{$list->name}}</option>
                                            @endforeach
                                    </select>
                                    @include('alerts.feedback', ['field' => 'mobile'])
                                </div>
                            </div>
                            <input type="hidden" name="packid" id="packid" value="{{ Session::get('default_package') }}">
                            <div class="form-row">
                                <div class="form-group col-md-6">
                                    <label>{{ __('Vendor Category') }}</label>
                                    <select class="form-control selectpicker " data-style="select-with-transition" title="Single Select" data-size="7" placeholder="{{ __('Category') }}" name="category" id="category">
                                            @foreach($vendorcategory as $list)
                                                <option value="{{$list->id}}" @if($vendor[0]->category == $list->id) selected @endif>{{$list->name}}</option>
                                            @endforeach
                                    </select>
                                    @include('alerts.feedback', ['field' => 'mobile'])
                                </div>
                                <div class="form-group col-md-6">
                                    <label>{{ __('Vendor Type') }}</label>
                                    <select class="form-control selectpicker " data-style="select-with-transition" title="Single Select" data-size="7" placeholder="{{ __('Type') }}" name="type" id="type">
                                            @foreach($vendortype as $list)
                                                <option value="{{$list->id}}" @if($vendor[0]->type == $list->id) selected @endif>{{$list->name}}</option>
                                            @endforeach
                                    </select>
                                    @include('alerts.feedback', ['field' => 'mobile'])
                                </div>
                            </div>
                            {{-- <div class="form-group">
                                <label>{{ __('Digital Profile Status') }}</label>
                                <select class="form-control selectpicker " data-style="select-with-transition" title="Single Select" data-size="7" placeholder="{{ __('Digital Profile Status') }}" name="dps" id="dps">
                                            <option value="Active" @if($vendor[0]->digital_profile_status =="Active") selected @endif>Active</option>
                                            <option value="Non Active" @if($vendor[0]->digital_profile_status =="Non Active") selected @endif>Non Active</option>
                                </select>
                                @include('alerts.feedback', ['field' => 'mobile'])
                            </div>
                            <div class="form-group">
                                <label>{{ __('Tax') }}</label>
                                <select class="form-control selectpicker " data-style="select-with-transition" title="Single Select" data-size="7" placeholder="{{ __('Tax - (CGST - SGST)') }}" name="tax" id="tax">
                                            <option value="Y" @if($vendor[0]->tax_enabled =="Y") selected @endif>Active</option>
                                            <option value="N" @if($vendor[0]->tax_enabled =="N") selected @endif>Non Active</option>
                                </select>
                                @include('alerts.feedback', ['field' => 'tax'])
                            </div> --}}

                            <label>{{ __('Logo') }}</label>
                            <?php
                            $url =Storage::url('app/public/'.$vendor[0]->image);
                            ?>
                            <div  style="width:30%; height:30%" >
                                <img src='{{ url($url) }}'/>
                            </div>
                            <input type="file" name="file" class="form-control{{ $errors->has('file') ? ' is-invalid' : '' }}" />
                            @include('alerts.feedback', ['field' => 'file'])

                    </div>
                    <div class="card-footer">
                        <button type="submit" class="btn btn-fill btn-primary">{{ __('Save') }}</button>
                    </div>
                </form>
            </div>


        </div>

    </div>
@endsection
<script language="JavaScript" type="text/javascript">
    function loadsuggestion(webname)
           {

               $('#webname').val(webname);
           }
       $(document).ready(function() {
         // $('#product_list').multiselect();
           $('#name').on('change', function() {
               var name = $(this).val();
               if(name) {
                   var data={"name":name};
                   $.ajax({
                          method: "post",
                          url : "../api/shorcode_generate",
                          data : data,
                          cache : false,
                          crossDomain : true,
                          async : false,
                          dataType :'text',
                          success : function(result)
                          {
                               var json_x= JSON.parse(result);
                               $('#shortcode').val(json_x.shortcode);
                               $('#webname').val(json_x.webname);
                          }
                          });
               }
           });


           $('#webname').on('change', function() { // act upon keyup events every 250 milliseconds when user is typing
           $('#suggestions').html('');

           var input = $(this).val();
           if(input) {
                   var data={"name":input};
                   $.ajax({
                          method: "post",
                          url : "../api/webname_generate",
                          data : data,
                          cache : false,
                          crossDomain : true,
                          async : false,
                          dataType :'text',
                          success : function(result)
                          {
                           document.getElementById('suggestions').innerHTML = result;
                           document.getElementById('suggestions').style.overflow='auto';

                          }
                          });
               }
            });

           //  $('.webnamesuggestions').on('click', function() {
           //      alert($(this).attr('myval'));
           //     $('#webname').val();
           // });


       });

      </script>
