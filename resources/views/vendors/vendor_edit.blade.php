@extends('layouts.app', ['page' => __('User Profile'), 'pageSlug' => 'vendors'])
<style>
select > option {
    color: black;
}
    </style>
@section('content')
    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h5 class="title">{{ __('Details') }}</h5>
                </div>
                <form method="post" action="{{ route('vendors.update') }}" autocomplete="off"  enctype="multipart/form-data">
                    <div class="card-body">
                            @csrf
                            @method('put')

                            @include('alerts.success')
                            <input type="hidden" name="vendorid" id="vendorid" value="{{ $vendor[0]->vid }}">
                            <div class="form-group{{ $errors->has('name') ? ' has-danger' : '' }}">
                                <label>{{ __('Name') }}</label>
                                <input type="text" name="name" class="form-control{{ $errors->has('name') ? ' is-invalid' : '' }}" placeholder="{{ __('Name') }}" value="{{ old('name',$vendor[0]->vname) }}">
                                @include('alerts.feedback', ['field' => 'name'])
                            </div>
                            <div class="form-group{{ $errors->has('shortkey') ? ' has-danger' : '' }}">
                                <label>{{ __('Short Key') }}<span style="color: red"> *</span></label>
                                <input type="text" name="shortkey" class="form-control{{ $errors->has('shortkey') ? ' is-invalid' : '' }}" placeholder="{{ __('Short key') }}" value="{{ old('shortkey',$vendor[0]->shortkey) }}">
                                @include('alerts.feedback', ['field' => 'shortkey'])
                            </div>
                            <div class="form-group{{ $errors->has('shortcode') ? ' has-danger' : '' }}">
                                <label>{{ __('Short Code') }}<span style="color: red"> *</span></label>
                                <input type="text" name="shortcode" id="shortcode" class="form-control{{ $errors->has('shortcode') ? ' is-invalid' : '' }}" placeholder="{{ __('Short Code - Max 3') }}" maxlength="3" value="{{ old('shortcode',$vendor[0]->short_code) }}">
                                @include('alerts.feedback', ['field' => 'shortcode'])
                            </div>
                            <div class="form-group{{ $errors->has('webname') ? ' has-danger' : '' }}">
                                <label>{{ __('Web Name') }}<span style="color: red"> *</span></label>
                                <input type="text" name="webname" id="webname" class="form-control{{ $errors->has('webname') ? ' is-invalid' : '' }}" placeholder="{{ __('Web Name') }}" value="{{ old('webname',$vendor[0]->web_name) }}">
                                @include('alerts.feedback', ['field' => 'webname'])
                                <span id="suggestions"> Web name Suggestions

                                </span>
                            </div>
                            <div class="form-group{{ $errors->has('address') ? ' has-danger' : '' }}">
                                <label>{{ __('Address') }}</label>
                                <input type="text" name="address" class="form-control{{ $errors->has('address') ? ' is-invalid' : '' }}" placeholder="{{ __('Address') }}" value="{{ old('address',$vendor[0]->address) }}">
                                @include('alerts.feedback', ['field' => 'address'])
                            </div>
                            <div class="form-group{{ $errors->has('latitude') ? ' has-danger' : '' }}">
                                <label>{{ __('Latitude') }}</label>
                                <input type="text" name="latitude" class="form-control{{ $errors->has('latitude') ? ' is-invalid' : '' }}" placeholder="{{ __('Latitude') }}" value="{{ old('latitude',$vendor[0]->location_lat) }}">
                                @include('alerts.feedback', ['field' => 'latitude'])
                            </div>
                            <div class="form-group{{ $errors->has('longitude') ? ' has-danger' : '' }}">
                                <label>{{ __('Longitude') }}</label>
                                <input type="text" name="longitude" class="form-control{{ $errors->has('longitude') ? ' is-invalid' : '' }}" placeholder="{{ __('Longitude') }}" value="{{ old('longitude',$vendor[0]->location_long) }}">
                                @include('alerts.feedback', ['field' => 'longitude'])
                            </div>
                            <div class="form-group{{ $errors->has('maplink') ? ' has-danger' : '' }}">
                                <label>{{ __('Map Link') }}</label>
                                <input type="text" name="maplink" class="form-control{{ $errors->has('maplink') ? ' is-invalid' : '' }}" placeholder="{{ __('Map Link') }}" value="{{ old('maplink',$vendor[0]->location_maplink) }}">
                                @include('alerts.feedback', ['field' => 'maplink'])
                            </div>
                            <div class="form-group{{ $errors->has('embed') ? ' has-danger' : '' }}">
                                <label>{{ __('Embed') }}</label>
                                <input type="text" name="embed" class="form-control{{ $errors->has('embed') ? ' is-invalid' : '' }}" placeholder="{{ __('Longitude') }}" value="{{ old('embed',$vendor[0]->location_embed) }}">
                                @include('alerts.feedback', ['field' => 'embed'])
                            </div>
                            <div class="form-group{{ $errors->has('description') ? ' has-danger' : '' }}">
                                <label>{{ __('Description') }}</label>
                                <input type="text" name="description" class="form-control{{ $errors->has('description') ? ' is-invalid' : '' }}" placeholder="{{ __('Description') }}" value="{{ old('description',$vendor[0]->description) }}">
                                @include('alerts.feedback', ['field' => 'description'])
                            </div>
                            <div class="form-group{{ $errors->has('website') ? ' has-danger' : '' }}">
                                <label>{{ __('Website') }}</label>
                                <input type="text" name="website" class="form-control{{ $errors->has('website') ? ' is-invalid' : '' }}" placeholder="{{ __('Website') }}" value="{{ old('website',$vendor[0]->website) }}">
                                @include('alerts.feedback', ['field' => 'website'])
                            </div>
                            <div class="form-group{{ $errors->has('email') ? ' has-danger' : '' }}">
                                <label>{{ __('Email address') }}</label>
                                <input type="email" name="email" class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}" placeholder="{{ __('Email address') }}" value="{{ old('email',$vendor[0]->mail_id) }}">
                                @include('alerts.feedback', ['field' => 'email'])
                            </div>
                            <div class="form-group{{ $errors->has('mobile') ? ' has-danger' : '' }}">
                                <label>{{ __('Mobile') }}</label>
                                <input type="text" name="mobile" class="form-control{{ $errors->has('mobile') ? ' is-invalid' : '' }}" placeholder="{{ __('Mobile') }}" value="{{ old('mobile',$vendor[0]->contact_number) }}">
                                @include('alerts.feedback', ['field' => 'mobile'])
                            </div>
                            <div class="form-group">
                                <label>{{ __('Refferal') }}</label>
                                <select class="form-control selectpicker " data-style="select-with-transition" title="Single Select" data-size="7" placeholder="{{ __('Refferal') }}" name="refferal" id="refferal">
                                    <option value="0">No Refferal</option>
                                    @foreach($salesexec as $list)
                                            <option value="{{$list->id}}" @if($vendor[0]->refferal_by == $list->id) selected @endif>{{$list->name}}</option>
                                        @endforeach
                                </select>
                                @include('alerts.feedback', ['field' => 'mobile'])
                            </div>
                            <input type="hidden" name="packid" id="packid" value="1">
                            <div class="form-group">
                                <label>{{ __('Vendor Category') }}</label>
                                <select class="form-control selectpicker " data-style="select-with-transition" title="Single Select" data-size="7" placeholder="{{ __('Category') }}" name="category" id="category">
                                        @foreach($vendorcategory as $list)
                                            <option value="{{$list->id}}" @if($vendor[0]->category == $list->id) selected @endif>{{$list->name}}</option>
                                        @endforeach
                                </select>
                                @include('alerts.feedback', ['field' => 'mobile'])
                            </div>
                            <div class="form-group">
                                <label>{{ __('Vendor Type') }}</label>
                                <select class="form-control selectpicker " data-style="select-with-transition" title="Single Select" data-size="7" placeholder="{{ __('Type') }}" name="type" id="type">
                                        @foreach($vendortype as $list)
                                            <option value="{{$list->id}}" @if($vendor[0]->type == $list->id) selected @endif>{{$list->name}}</option>
                                        @endforeach
                                </select>
                                @include('alerts.feedback', ['field' => 'mobile'])
                            </div>
                            <div class="form-group">
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
                            </div>

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
