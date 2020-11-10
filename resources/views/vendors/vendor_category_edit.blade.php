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
                  <div class="form-group">
                    <label for="exampleFormControlInput1">{{ $title }} Name</label>
                    <input type="text" class="form-control{{ $errors->has('cat_name') ? ' is-invalid' : '' }}"  id="cat_name" name="cat_name" placeholder="{{ $title }} Name" value="{{ old('cat_name',$vendor[0]->name) }}">
                    @include('alerts.feedback', ['field' => 'cat_name'])
                  </div>
                  <div class="form-group">
                    <label for="exampleFormControlInput1">Status</label>
                    <select class="form-control selectpicker " data-style="select-with-transition" title="Single Select" data-size="7" placeholder="{{ __('Status') }}" name="status" id="status">
                                <option value="Y" @if($vendor[0]->status =="Y") selected @endif>Active</option>
                                <option value="N" @if($vendor[0]->status =="N") selected @endif>Non Active</option>
                    </select>

                    </div>
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
