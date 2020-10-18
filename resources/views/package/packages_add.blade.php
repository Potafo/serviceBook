@extends('layouts.app', ['page' => __('Packages'), 'pageSlug' => 'packages'])
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
                    <h5 class="title">{{ __('Add Package') }}</h5>
                </div>
                <div class="col-8">
                    <nav aria-label="breadcrumb" role="navigation">
                        <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="home">Home</a></li>
                        <li class="breadcrumb-item "><a href="packages">Packages</a></li>
                        <li class="breadcrumb-item active" aria-current="page" id="bc_current">Add</li>
                        </ol>
                    </nav>
                </div>
                <form method="post" action="{{ route('package.insert') }}" autocomplete="off">
                    <div class="card-body">
                    @csrf
                    @method('put')

                    @include('alerts.success')
                  <div class="form-group">
                    <label for="exampleFormControlInput1">Package Type</label>
                    <input type="text" class="form-control{{ $errors->has('package_type') ? ' is-invalid' : '' }}"  id="package_type" name="package_type" placeholder="Package Type/Name" value="{{ old('package_type') }}">
                    @include('alerts.feedback', ['field' => 'package_type'])
                  </div>
                  <div class="form-group">
                    <label for="exampleFormControlInput1">Days</label>
                    <input type="text" class="form-control{{ $errors->has('package_days') ? ' is-invalid' : '' }}" id="package_days" name="package_days" placeholder="Days" value="{{ old('package_days') }}">
                    @include('alerts.feedback', ['field' => 'package_days'])
                  </div>
                  <div class="form-group">
                    <label for="exampleFormControlInput1">Amount</label>
                    <input type="text" class="form-control{{ $errors->has('package_amount') ? ' is-invalid' : '' }}" id="package_amount" name="package_amount" placeholder="Amount" value="{{ old('package_amount') }}">
                    @include('alerts.feedback', ['field' => 'package_amount'])
                  </div>
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
