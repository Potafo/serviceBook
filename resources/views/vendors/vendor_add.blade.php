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
                <form method="post" action="{{ route('vendors.insert') }}" autocomplete="off" enctype="multipart/form-data">
                    <div class="card-body">
                            @csrf
                            @method('put')

                            @include('alerts.success')

                            <div class="form-group{{ $errors->has('name') ? ' has-danger' : '' }}">
                                <label>{{ __('Name') }}<span style="color: red"> *</span></label>
                                <input type="text" name="name" class="form-control{{ $errors->has('name') ? ' is-invalid' : '' }}" placeholder="{{ __('Name') }}" value="{{ old('name') }}">
                                @include('alerts.feedback', ['field' => 'name'])
                            </div>
                            <div class="form-group{{ $errors->has('shortkey') ? ' has-danger' : '' }}">
                                <label>{{ __('Short Key') }}<span style="color: red"> *</span></label>
                                <input type="text" name="shortkey" class="form-control{{ $errors->has('shortkey') ? ' is-invalid' : '' }}" placeholder="{{ __('Short key') }}" value="{{ old('shortkey',mt_rand(100000,999999)) }}">
                                @include('alerts.feedback', ['field' => 'shortkey'])
                            </div>
                            <div class="form-group{{ $errors->has('address') ? ' has-danger' : '' }}">
                                <label>{{ __('Address') }}</label>
                                <input type="text" name="address" class="form-control{{ $errors->has('address') ? ' is-invalid' : '' }}" placeholder="{{ __('Address') }}" value="">
                                @include('alerts.feedback', ['field' => 'address'])
                            </div>
                            <div class="form-group{{ $errors->has('latitude') ? ' has-danger' : '' }}">
                                <label>{{ __('Latitude') }}<span style="color: red"> *</span></label>
                                <input type="text" name="latitude" class="form-control{{ $errors->has('latitude') ? ' is-invalid' : '' }}" placeholder="{{ __('Latitude') }}" value="{{ old('latitude') }}">
                                @include('alerts.feedback', ['field' => 'latitude'])
                            </div>
                            <div class="form-group{{ $errors->has('longitude') ? ' has-danger' : '' }}">
                                <label>{{ __('Longitude') }}<span style="color: red"> *</span></label>
                                <input type="text" name="longitude" class="form-control{{ $errors->has('longitude') ? ' is-invalid' : '' }}" placeholder="{{ __('Longitude') }}" value="{{ old('longitude') }}">
                                @include('alerts.feedback', ['field' => 'longitude'])
                            </div>
                            <div class="form-group{{ $errors->has('maplink') ? ' has-danger' : '' }}">
                                <label>{{ __('Map Link') }}</label>
                                <input type="text" name="maplink" class="form-control{{ $errors->has('maplink') ? ' is-invalid' : '' }}" placeholder="{{ __('Map Link') }}" value="">
                                @include('alerts.feedback', ['field' => 'maplink'])
                            </div>
                            <div class="form-group{{ $errors->has('embed') ? ' has-danger' : '' }}">
                                <label>{{ __('Embed') }}</label>
                                <input type="text" name="embed" class="form-control{{ $errors->has('embed') ? ' is-invalid' : '' }}" placeholder="{{ __('Embed') }}" value="">
                                @include('alerts.feedback', ['field' => 'embed'])
                            </div>
                            <div class="form-group{{ $errors->has('description') ? ' has-danger' : '' }}">
                                <label>{{ __('Description') }}</label>
                                <input type="text" name="description" class="form-control{{ $errors->has('description') ? ' is-invalid' : '' }}" placeholder="{{ __('Description') }}" value="">
                                @include('alerts.feedback', ['field' => 'description'])
                            </div>
                            <div class="form-group{{ $errors->has('website') ? ' has-danger' : '' }}">
                                <label>{{ __('Website') }}</label>
                                <input type="text" name="website" class="form-control{{ $errors->has('website') ? ' is-invalid' : '' }}" placeholder="{{ __('Website') }}" value="">
                                @include('alerts.feedback', ['field' => 'website'])
                            </div>
                            <div class="form-group{{ $errors->has('email') ? ' has-danger' : '' }}">
                                <label>{{ __('Email address') }}<span style="color: red"> *</span></label>
                                <input type="email" name="email" class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}" placeholder="{{ __('Email address') }}" value="{{ old('email') }}">
                                @include('alerts.feedback', ['field' => 'email'])
                            </div>
                            <div class="form-group{{ $errors->has('mobile') ? ' has-danger' : '' }}">
                                <label>{{ __('Mobile') }}<span style="color: red"> *</span></label>
                                <input type="text" name="mobile" class="form-control{{ $errors->has('mobile') ? ' is-invalid' : '' }}" placeholder="{{ __('Mobile') }}" value="{{ old('mobile') }}">
                                @include('alerts.feedback', ['field' => 'mobile'])
                            </div>

                            <div class="form-group">
                                <label>{{ __('Refferal') }}</label>
                                <select class="form-control selectpicker " data-style="select-with-transition" title="Single Select" data-size="7" placeholder="{{ __('Refferal') }}" name="refferal" id="refferal">
                                    <option value="0">No Refferal</option>
                                    @foreach($salesexec as $list)
                                            <option value="{{$list->id}}">{{$list->name}}</option>
                                        @endforeach
                                </select>
                                @include('alerts.feedback', ['field' => 'mobile'])
                            </div>
                            <input type="hidden" name="packid" id="packid" value="1">
                            <div class="form-group">
                                <label>{{ __('Vendor Category') }}</label>
                                <select class="form-control selectpicker " data-style="select-with-transition" title="Single Select" data-size="7" placeholder="{{ __('Category') }}" name="category" id="category">
                                        @foreach($vendorcategory as $list)
                                            <option value="{{$list->id}}">{{$list->name}}</option>
                                        @endforeach
                                </select>
                                @include('alerts.feedback', ['field' => 'mobile'])
                            </div>
                            <div class="form-group">
                                <label>{{ __('Vendor Type') }}</label>
                                <select class="form-control selectpicker " data-style="select-with-transition" title="Single Select" data-size="7" placeholder="{{ __('Type') }}" name="type" id="type">
                                        @foreach($vendortype as $list)
                                            <option value="{{$list->id}}">{{$list->name}}</option>
                                        @endforeach
                                </select>
                                @include('alerts.feedback', ['field' => 'mobile'])
                            </div>
                            <div class="form-group">
                                <label>{{ __('Digital Profile Status') }}</label>
                                <select class="form-control selectpicker " data-style="select-with-transition" title="Single Select" data-size="7" placeholder="{{ __('Digital Profile Status') }}" name="dps" id="dps">
                                            <option value="Active">Active</option>
                                            <option value="Non Active">Non Active</option>
                                </select>
                                @include('alerts.feedback', ['field' => 'mobile'])
                            </div>

                            <label>{{ __('Logo') }}</label>
                            <input type="file" name="file" class="form-control{{ $errors->has('file') ? ' is-invalid' : '' }}" />
                            @include('alerts.feedback', ['field' => 'file'])



                            {{-- <div class="form-group{{ $errors->has('file') ? ' has-danger' : '' }}">
                                <label class="form-control{{ $errors->has('file') ? ' is-invalid' : '' }} custom-file-label" for="file">Logo upload</label>
                                <input  type="file" class="form-control-file" id="file" value="{{old('file')}}" name="file" >

                                 @include('alerts.feedback', ['field' => 'file'])
                            </div>

                            <div class="form-group row">
                                <label for="profile_image" class="col-md-4 col-form-label text-md-right">Profile Image</label>
                                <div class="col-md-6">
                                    <input id="profile_image" type="file" class="form-control" name="profile_image">
                                    @if (auth()->user()->image)
                                        <code>{{ auth()->user()->image }}</code>
                                    @endif
                                </div>
                            </div>  --}}
                            {{-- <div class="form-group">
                                <label>{{ __('File Upload') }}</label>
                                <input type="file" name="file" class="form-control{{ $errors->has('file') ? ' is-invalid' : '' }}">
                                <label class="form-control custom-file-label" for="chooseFile">Select file</label>
                                <button  class="btn btn-success" style="display: none">Upload</button>
                                @include('alerts.feedback', ['field' => 'file'])
                            </div> --}}



                    </div>
                    <div class="card-footer" style="margin-top: 50px;">
                        <button type="submit" class="btn btn-fill btn-primary">{{ __('Save') }}</button>
                    </div>
                </form>
            </div>


        </div>

    </div>
@endsection
