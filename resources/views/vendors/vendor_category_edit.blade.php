<?php if($mode=="category") {
    $vcat='vendor_category/category';
    $title="Vendor Category ";
    $homepageurl='vendor_category/category';
} else if($mode=="type") {
    $vcat='vendor_category/type';
    $title="Vendor Type ";
    $homepageurl='vendor_category/type';
}else if($mode=="service_type") {
    $vcat='vendor_category/service_type';
    $title="Service Type ";
    $homepageurl='vendor_category/service_type';
}else if($mode=="status") {
    $vcat='vendor_category/status';
    $title="Vendor Status ";
    $homepageurl='vendor_category/status';
}
?>

@extends('layouts.app', ['page' => __($title), 'pageSlug' =>$vcat])
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
                    <h5 class="title">{{ __('Edit '.$title) }}</h5>
                </div>
                <div class="col-8">
                    <nav aria-label="breadcrumb" role="navigation">
                        <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="home">Home</a></li>
                        <li class="breadcrumb-item "><a href="{{ url($homepageurl)  }}">{{ $title }}</a></li>
                        <li class="breadcrumb-item active" aria-current="page" id="bc_current">Edit</li>
                        </ol>
                    </nav>
                </div>
                <form method="post" action="{{ route('vendorcategory.update') }}" autocomplete="off">
                    <div class="card-body">
                    @csrf
                    @method('put')
                    @include('alerts.success')
                    <input type="hidden" id="hidden_mode" name="hidden_mode" value="{{ $mode }}" >
                    <input type="hidden" id="hidden_id" name="hidden_id" value="{{ $id }}" >
                    @if($mode=="status")
                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <label for="exampleFormControlInput1">{{ $title }} Name</label>
                                <input type="text" class="form-control{{ $errors->has('cat_name') ? ' is-invalid' : '' }}" style="color: white"  readonly="true" id="cat_name" name="cat_name" placeholder="{{ $title }} Name" value="{{ old('cat_name',$vendor[0]->name) }}">
                                @include('alerts.feedback', ['field' => 'cat_name'])
                            </div>
                            {{-- <div class="form-group col-md-6">
                                <label for="exampleFormControlInput1">OR</label>
                                <select class="form-control{{ $errors->has('status') ? ' is-invalid' : '' }} selectpicker " data-style="select-with-transition" readonly="true" title="Single Select" data-size="7" placeholder="{{ __('Status') }}" name="status" id="status" value="{{ old('status') }}">
                                    <option value="">Select Status</option>
                                    @foreach($category as $list)
                                            <option value="{{$list->id}}">{{$list->name}}</option>
                                        @endforeach
                                </select>
                                @include('alerts.feedback', ['field' => 'status'])
                            </div> --}}
                        </div>
                        <div class="form-group">
                            <label for="exampleFormControlInput1">Send Sms</label>
                            <select class="form-control selectpicker " data-style="select-with-transition" title="Single Select" data-size="7" placeholder="{{ __('Send Sms') }}" name="send_sms" id="send_sms">
                                        <option value="Y" @if($vendor[0]->send_sms =="Y") selected @endif>Yes</option>
                                        <option value="N" @if($vendor[0]->send_sms =="N") selected @endif>No</option>
                            </select>

                        </div>
                        <div class="form-group">
                            <label for="exampleFormControlInput1">Send email</label>
                            <select class="form-control selectpicker " data-style="select-with-transition" title="Single Select" data-size="7" placeholder="{{ __('Send Email') }}" name="send_email" id="send_email">
                                        <option value="Y" @if($vendor[0]->send_email =="Y") selected @endif>Yes</option>
                                        <option value="N" @if($vendor[0]->send_email =="N") selected @endif>No</option>
                            </select>

                        </div>
                        <div class="form-group">
                            <label for="exampleFormControlInput1">Display Order</label>
                            <input type="text" class="form-control{{ $errors->has('displayorder') ? ' is-invalid' : '' }}"  id="displayorder" name="displayorder" placeholder="{{ __('Display Order') }} " value="{{ old('displayorder',$vendor[0]->display_order) }}">
                            @include('alerts.feedback', ['field' => 'displayorder'])
                        </div>
                        <div class="form-group">
                            <label for="exampleFormControlInput1">Ending Status</label>
                            <select class="form-control selectpicker " data-style="select-with-transition" title="Single Select" data-size="7" placeholder="{{ __('Ending Status') }}" name="ending_status" id="ending_status">

                                        <option value="0" @if($vendor[0]->ending_status =="0") selected @endif>No</option>
                                        <option value="1" @if($vendor[0]->ending_status =="1") selected @endif>Yes</option>
                            </select>

                        </div>
                        <div class="form-group">
                            <label for="exampleFormControlInput1">Status</label>
                            <select class="form-control selectpicker " data-style="select-with-transition" title="Single Select" data-size="7" placeholder="{{ __('Status') }}" name="status" id="status">
                                        <option value="Y" @if($vendor[0]->active =="Y") selected @endif>Active</option>
                                        <option value="N" @if($vendor[0]->active =="N") selected @endif>Non Active</option>
                            </select>

                        </div>
                        @elseif($mode=="service_type")
                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <label for="exampleFormControlInput1">{{ $title }} Name</label>
                                <input type="text" class="form-control{{ $errors->has('cat_name') ? ' is-invalid' : '' }}" style="color: white"  readonly="true" id="cat_name" name="cat_name" placeholder="{{ $title }} Name" value="{{ old('cat_name',$vendor[0]->name) }}">
                                @include('alerts.feedback', ['field' => 'cat_name'])
                            </div>
                            {{-- <div class="form-group col-md-6">
                                <label for="exampleFormControlInput1">OR</label>
                                <select class="form-control{{ $errors->has('status') ? ' is-invalid' : '' }} selectpicker " data-style="select-with-transition" readonly="true" title="Single Select" data-size="7" placeholder="{{ __('Status') }}" name="status" id="status" value="{{ old('status') }}">
                                    <option value="">Select Status</option>
                                    @foreach($category as $list)
                                            <option value="{{$list->id}}">{{$list->name}}</option>
                                        @endforeach
                                </select>
                                @include('alerts.feedback', ['field' => 'status'])
                            </div> --}}
                        </div>
                        {{-- <div class="form-group">
                            <label for="exampleFormControlInput1">Service Category</label>
                            <select class="form-control{{ $errors->has('serv_cat') ? ' is-invalid' : '' }} selectpicker " data-style="select-with-transition" title="Single Select" data-size="7" placeholder="{{ __('Servie Category') }}" name="serv_cat" id="serv_cat" value="{{ old('serv_cat',$vendor[0]->service_category) }}">

                                @foreach($category as $list)
                                        <option value="{{$list->id}}" @if($vendor[0]->service_category==$list->id) selected @endif>{{$list->name}}</option>
                                    @endforeach
                            </select>
                            @include('alerts.feedback', ['field' => 'cat_status'])
                        </div> --}}
                                @if(Session::get('logged_user_type') =='1')
                                    <div class="form-group">
                                        <label>{{ __('Vendors') }}</label>
                                        <select class="form-control{{ $errors->has('vendor_name') ? ' is-invalid' : '' }} selectpicker " data-style="select-with-transition" title="Single Select" data-size="7" placeholder="{{ __('Vendors') }}" name="vendor_name" id="vendor_name" value="{{ old('vendor_name') }}">
                                            <option value="">Select Vendor</option>
                                            @foreach($vendor_list as $list)
                                                    <option value="{{$list->id}}" @if($vendor[0]->vendor_id==$list->id) selected @endif>{{$list->name}}</option>
                                                @endforeach
                                        </select>
                                        @include('alerts.feedback', ['field' => 'vendor_name'])
                                        </div>
                                @endif
                                <div class="form-group">
                                    <label for="exampleFormControlInput1">Status</label>
                                    <select class="form-control selectpicker " data-style="select-with-transition" title="Single Select" data-size="7" placeholder="{{ __('Status') }}" name="status" id="status">
                                                <option value="Y" @if($vendor[0]->status =="Y") selected @endif>Active</option>
                                                <option value="N" @if($vendor[0]->status =="N") selected @endif>Non Active</option>
                                    </select>

                                </div>

                    @else
                        <div class="form-group">
                            <label for="exampleFormControlInput1">{{ $title }} Name</label>
                            <input type="text" class="form-control{{ $errors->has('cat_name') ? ' is-invalid' : '' }}" style="color: white" readonly  id="cat_name" name="cat_name" placeholder="{{ $title }} Name" value="{{ old('cat_name',$vendor[0]->name) }}">
                            @include('alerts.feedback', ['field' => 'cat_name'])
                        </div>
                        <div class="form-group">
                            <label for="exampleFormControlInput1">Status</label>
                            <select class="form-control selectpicker " data-style="select-with-transition" title="Single Select" data-size="7" placeholder="{{ __('Status') }}" name="status" id="status">
                                        <option value="Y" @if($vendor[0]->status =="Y") selected @endif>Active</option>
                                        <option value="N" @if($vendor[0]->status =="N") selected @endif>Non Active</option>
                            </select>

                        </div>
                  @endif


                  <div class="form-group">
                     <div class="col-4 text-right">
                        <button type="submit" class="btn btn-fill btn-primary">{{ __('Update') }}</button>
                    {{-- <a class="btn btn-sm btn-primary submitpackage">Submit</a> --}}
                    </div>
                  </div>
                    </div>
                </form>
            </div>


        </div>

    </div>
@endsection
