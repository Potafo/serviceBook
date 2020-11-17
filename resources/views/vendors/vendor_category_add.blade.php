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
                    <h5 class="title">{{ __('Add '.$title) }}</h5>
                </div>
                <div class="col-8">
                    <nav aria-label="breadcrumb" role="navigation">
                        <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="home">Home</a></li>
                        <li class="breadcrumb-item "><a href="{{ url($homepageurl)  }}">{{ $title }}</a></li>
                        <li class="breadcrumb-item active" aria-current="page" id="bc_current">Add</li>
                        </ol>
                    </nav>
                </div>
                <form method="post" action="{{ route('vendorcategory.insert') }}" autocomplete="off">
                    <div class="card-body">
                    @csrf
                    @method('put')
                    @include('alerts.success')
                    <input type="hidden" id="hidden_mode" name="hidden_mode" value="{{ $mode }}" >
                  <div class="form-group">
                    <label for="exampleFormControlInput1">{{ $title }} Name</label>
                    <input type="text" class="form-control{{ $errors->has('cat_name') ? ' is-invalid' : '' }}"  id="cat_name" name="cat_name" placeholder="{{ $title }} Name" value="{{ old('cat_name') }}">
                    @include('alerts.feedback', ['field' => 'cat_name'])
                  </div>
                    @if($mode=="status")
                        <div class="form-group">
                            <label for="exampleFormControlInput1">Notification</label>
                            <select class="form-control selectpicker " data-style="select-with-transition" title="Single Select" data-size="7" placeholder="{{ __('Notification') }}" name="notification" id="notification" value="{{ old('notification') }}">
                                <option value="N" >No</option>
                                <option value="Y" >Yes</option>

                            </select>

                            </div>
                            <div class="form-group">
                                <label for="exampleFormControlInput1"> Display Order</label>
                                <input type="text" class="form-control{{ $errors->has('displayorder') ? ' is-invalid' : '' }}"  id="displayorder" name="displayorder" placeholder="{{ __('Display Order') }} " value="{{ old('displayorder') }}">
                                @include('alerts.feedback', ['field' => 'displayorder'])
                            </div>
                    @endif
                  <div class="form-group">
                     <div class="col-4 text-right">
                        <button type="submit" class="btn btn-fill btn-primary">{{ __('Save') }}</button>
                    {{-- <a class="btn btn-sm btn-primary submitpackage">Submit</a> --}}
                    </div>
                  </div>
                    </div>
                </form>
            </div>


        </div>

    </div>
@endsection
