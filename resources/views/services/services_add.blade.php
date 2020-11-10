@extends('layouts.app', ['page' => __('Services'), 'pageSlug' => 'services'])
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
                    <h5 class="title">{{ __('Add Services') }}</h5>
                </div>
                <div class="col-8">
                    <nav aria-label="breadcrumb" role="navigation">
                        <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="home">Home</a></li>
                        <li class="breadcrumb-item "><a href="Services">services</a></li>
                        <li class="breadcrumb-item active" aria-current="page" id="bc_current">Add</li>
                        </ol>
                    </nav>
                </div>
                <form method="post" action="{{ route('services.insert') }}" autocomplete="off">
                    <div class="card-body">
                    @csrf
                    @method('put')

                    @include('alerts.success')
                    <input type="hidden" name="user_id" id="user_id" value="{{  Session::get('logged_user_id') }}" >
                    <div class="form-group">
                        <label>{{ __('Service Type') }}</label>
                        <select class="form-control{{ $errors->has('servicetype_list') ? ' is-invalid' : '' }} selectpicker " data-style="select-with-transition" title="Single Select" data-size="7" placeholder="{{ __('Service Type') }}" name="servicetype_list" id="servicetype_list" value="{{ old('servicetype_list') }}">
                            <option value="">Select Service Type</option>
                            @foreach($servicetype as $list)
                                                <option value="{{$list->id}}">{{$list->name}}</option>
                                            @endforeach
                        </select>
                        @include('alerts.feedback', ['field' => 'product_list'])
                    </div>
                    @if(Session::get('logged_user_type') =='1')
                        <div class="form-group" id="vendor_div" style="display: none">
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

                    {{-- <div class="form-group" id="product_div" style="display: none">
                        <label>{{ __('Products') }}</label>
                        <select class="form-control{{ $errors->has('product_list') ? ' is-invalid' : '' }} selectpicker " data-style="select-with-transition" title="Single Select" data-size="7" placeholder="{{ __('Products') }}" name="product_list" id="product_list" value="{{ old('product_list') }}">
                            <option value="">Select Products</option>
                            @foreach($products as $list)
                                                <option value="{{$list->id}}">{{$list->name}}</option>
                                            @endforeach
                        </select>
                        @include('alerts.feedback', ['field' => 'product_list'])
                    </div> --}}
                    <div class="form-group">
                        <label for="exampleFormControlInput1">Services Name</label>
                        <input type="text" class="form-control{{ $errors->has('servicename') ? ' is-invalid' : '' }}"  id="servicename" name="servicename" placeholder="Service Name" value="{{ old('servicename') }}">
                        @include('alerts.feedback', ['field' => 'servicename'])
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
<script src="{{ asset('black') }}/js/jquery.min.js"></script>
<script language="JavaScript" type="text/javascript">
 $(document).ready(function() {

    // $('select[name="vendor_name"]').on('change', function() {
    //     var vendor_id = $(this).val();
    //         if(vendor_id) {

    //             var data={"vendor_id":vendor_id};
    //             $.ajax({
    //                 method: "post",
    //                 url : "api/product_list",
    //                 data : data,
    //                 cache : false,
    //                 crossDomain : true,
    //                 async : false,
    //                 dataType :'text',
    //                 success : function(data)
    //                 {
    //                 $('select[name="product_list"]').empty();
    //                     $('#product_list').html(data);


    //                     }
    //                 });
    //         }else{
    //             $('select[name="product_list"]').empty();
    //             }
    //        });



    //        $('select[name="servicetype_list"]').on('change', function() {
    //     var service_type = $(this).val();
    //     $('#vendor_div').css('display','block');
    //         if(service_type=='1') {
    //             $('#product_div').css('display','block');

    //             var data={"vendor_id":vendor_id};
    //             $.ajax({
    //                 method: "post",
    //                 url : "api/product_list",
    //                 data : data,
    //                 cache : false,
    //                 crossDomain : true,
    //                 async : false,
    //                 dataType :'text',
    //                 success : function(data)
    //                 {
    //                 $('select[name="product_list"]').empty();
    //                     $('#product_list').html(data);
    //                     // $.each(data, function(key, value) {
    //                     //     $('select[name="product_list"]').append('<option value="'+ value +'">'+ value +'</option>');
    //                     // });

    //                     }
    //                 });
    //         }else{
    //            $('#product_div').css('display','none');
    //            //$('#vendor_div').css('display','none');
    //             $('select[name="product_list"]').empty();
    //             }
    //        });

        });
</script>
