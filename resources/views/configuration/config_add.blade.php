@extends('layouts.app', ['page' => __('Add Configuration'), 'pageSlug' => 'config_add'])
<script src="{{ asset('black') }}/js/core/jquery-3.4.1.min.js"></script>
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
                    <h5 class="title">{{ __('Add Configurations') }}</h5>
                </div>

                <form method="post" action="{{ route('configuration.insert') }}" autocomplete="off">
                    <div class="card-body">
                    @csrf
                    @method('put')

                    @include('alerts.success')
                  <div class="form-group">
                    <label for="exampleFormControlInput1">Configuration Name</label>
                    <input type="text" class="form-control{{ $errors->has('config_name') ? ' is-invalid' : '' }}"  id="config_name" name="config_name" placeholder="Configuration Name" value="{{ old('config_name') }}">
                    @include('alerts.feedback', ['field' => 'config_name'])
                  </div>
                  {{-- <div class="form-group">
                    <label for="exampleFormControlInput1">Value</label>
                    <input type="text" class="form-control{{ $errors->has('config_value') ? ' is-invalid' : '' }}" id="config_value" name="config_value" placeholder="Configuration value" value="{{ old('config_value') }}">
                    @include('alerts.feedback', ['field' => 'config_value'])
                  </div> --}}
                  <div class="form-group">
                    <label for="exampleFormControlInput1">Type</label>
                    <select class="form-control{{ $errors->has('config_type') ? ' is-invalid' : '' }} selectpicker " data-style="select-with-transition" title="Single Select" data-size="7" placeholder="{{ __('COnfiguration Type') }}" name="config_type" id="config_type" value="{{ old('config_type') }}">
                        <option value="">Select Configuration Type</option>
                            @foreach($configtype as $list)
                                <option value="{{$list->id}}">{{$list->name}}</option>
                            @endforeach
                    </select>
                    @include('alerts.feedback', ['field' => 'config_type'])
                  </div>
                  <div class="form-group">
                    <label for="exampleFormControlInput1">Status</label>
                    <select class="form-control selectpicker " data-style="select-with-transition" title="Single Select" data-size="7" placeholder="{{ __('Configuration Status') }}" name="config_status" id="config_status">
                        <option value="N" >Non Active</option>
                        <option value="Y" >Active</option>
                    </select>
                </div>
                  <div class="form-group">
                    <label for="exampleFormControlInput1">View</label>
                    <select class="form-control selectpicker " data-style="select-with-transition" title="Single Select" data-size="7" placeholder="{{ __('Configuration View') }}" name="config_view" id="config_view">
                        <option value="N" >No</option>
                        <option value="Y" >Yes</option>
                    </select>
                    @include('alerts.feedback', ['field' => 'config_view'])
                  </div>
                  <div class="form-group">
                    <label for="exampleFormControlInput1">Input Type</label>
                    <select class="form-control selectpicker " data-style="select-with-transition" title="Single Select" data-size="7" placeholder="{{ __('Input Type') }}" name="config_input" id="config_input">

                        <option value="checkbox" >CheckBox</option>
                        <option value="textbox" >TextBox</option>
                    </select>
                    @include('alerts.feedback', ['field' => 'config_view'])
                  </div>
                  <div class="form-group">
                     <div class="col-4 text-right">
                        <button type="submit" class="btn btn-fill btn-primary">{{ __('Save') }}</button>
                    </div>
                  </div>
                    </div>
                </form>
            </div>


        </div>

    </div>
@endsection
