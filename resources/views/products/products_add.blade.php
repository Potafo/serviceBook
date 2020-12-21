@extends('layouts.app', ['page' => __('Products'), 'pageSlug' => 'products'])
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
                    <h5 class="title">{{ __('Add Products') }}</h5>
                </div>
                <div class="col-12">
                    <nav aria-label="breadcrumb" role="navigation">
                        <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="home">Home</a></li>
                        <li class="breadcrumb-item "><a href="Products">Products</a></li>
                        <li class="breadcrumb-item active" aria-current="page" id="bc_current">Add</li>
                        </ol>
                    </nav>
                </div>
                <form method="post" action="{{ route('products.insert') }}" autocomplete="off" enctype="multipart/form-data">
                    <div class="card-body">
                    @csrf
                    @method('put')

                    @include('alerts.success')
                    <input type="hidden" name="user_id" id="user_id" value="{{  Session::get('logged_user_id') }}" >
                  <div class="form-group">
                    <label for="exampleFormControlInput1">Product Name</label>
                    <input type="text" class="form-control{{ $errors->has('productname') ? ' is-invalid' : '' }}"  id="productname" name="productname" placeholder="Product Name" value="{{ old('productname') }}">
                    @include('alerts.feedback', ['field' => 'productname'])
                  </div>
                    @if(Session::get('logged_user_type') =='1')
                        <div class="form-group">
                            <label>{{ __('Vendors') }}</label>
                            <select class="form-control{{ $errors->has('vendor_name') ? ' is-invalid' : '' }} selectpicker " data-style="select-with-transition" title="Single Select" data-size="7" placeholder="{{ __('Vendors') }}" name="vendor_name" id="vendor_name" value="{{ old('vendor_name') }}">
                                <option value="">Select Vendor</option>
                                @foreach($vendor_list as $list)
                                        <option value="{{$list->id}}">{{$list->name}}</option>
                                    @endforeach
                            </select>
                            @include('alerts.feedback', ['field' => 'vendor_name'])
                            </div>
                    @endif

                  <label>{{ __('Product Image') }}</label>
                  <input type="file" name="file" class="form-control{{ $errors->has('file') ? ' is-invalid' : '' }}" />
                  @include('alerts.feedback', ['field' => 'file'])

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
