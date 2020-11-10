@extends('layouts.app', ['page' => __('Job Card'), 'pageSlug' => 'jobcard'])
<style>
select > option {
    color: black;
}
    </style>
    {{-- <link rel="stylesheet" href="{{ asset('black') }}/css/bootstrap-4.5.2.min.css" type="text/css"/> --}}
    <script src="{{ asset('black') }}/js/core/jquery-2.1.3.min.js"></script>
    {{-- <script src="//ajax.googleacom/ajax/libs/jquery/2.0.3/jquery.min.js"></script>
    <script type="text/javascript" src="{{ asset('black') }}/js/core/bootstrap.bundle-4.5.2.min.js"></script>
     <link rel="stylesheet" href="{{ asset('black') }}/css/bootstrap.min.css" type="text/css"/>
    <link rel="stylesheet" href="{{asset('black') }}/css/bootstrap-multiselect.css" type="text/css"/>
    <script type="text/javascript" src="{{ asset('black') }}/js/core/bootstrap-multiselect.js"></script>--}}


@section('content')
    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h5 class="title">{{ __('Add Jobcard') }}</h5>
                </div>
                <div class="col-8">
                    <nav aria-label="breadcrumb" role="navigation">
                        <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="home">Home</a></li>
                        <li class="breadcrumb-item "><a href="jobcard">Job Card</a></li>
                        <li class="breadcrumb-item active" aria-current="page" id="bc_current">Add</li>
                        </ol>
                    </nav>
                </div>
                <form method="post" action="{{ route('jobcard.insert') }}" autocomplete="off">
                    <div class="card-body">
                    @csrf
                    @method('put')

                    @include('alerts.success')
                    <input type="hidden" name="user_id" id="user_id" value="{{  Session::get('logged_user_id') }}" >
                  <div class="form-group">
                    <label for="exampleFormControlInput1">JobCard Number</label>
                    <input type="text" class="form-control{{ $errors->has('jobcardnumber') ? ' is-invalid' : '' }}"  id="jobcardnumber" name="jobcardnumber" placeholder="Job Card Number" value="{{ old('jobcardnumber','JCN'.mt_rand(1000000,99999999)) }}">
                    @include('alerts.feedback', ['field' => 'jobcardnumber'])
                  </div>
                  {{-- <div class="form-group">
                    <label for="exampleFormControlInput1">Name</label>
                    <input type="text" class="form-control{{ $errors->has('jobcard_name') ? ' is-invalid' : '' }}" id="jobcard_name" name="jobcard_name" placeholder="Name" value="{{ old('jobcard_name') }}">
                    @include('alerts.feedback', ['field' => 'jobcard_name'])
                  </div>
                  <div class="form-group">
                    <label for="exampleFormControlInput1">Mobile</label>
                    <input type="text" class="form-control{{ $errors->has('jobcard_mobile') ? ' is-invalid' : '' }}" id="jobcard_mobile" name="jobcard_mobile" placeholder="Mobile" value="{{ old('jobcard_mobile') }}">
                    @include('alerts.feedback', ['field' => 'jobcard_mobile'])
                  </div> --}}
                  {{-- <div class="form-group">
                    <label>{{ __('Service Type') }}</label>
                    <select class="form-control{{ $errors->has('servicetype_list') ? ' is-invalid' : '' }} selectpicker " data-style="select-with-transition" title="Single Select" data-size="7" placeholder="{{ __('Service Type') }}" name="servicetype_list" id="servicetype_list" value="{{ old('servicetype_list') }}">
                        <option value="">Select Service Type</option>
                        @foreach($servicetype as $list)
                            <option value="{{$list->id}}">{{$list->name}}</option>
                        @endforeach
                    </select>
                    @include('alerts.feedback', ['field' => 'product_list'])
                </div> --}}
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

                <div class="form-group" id="product_list_div">
                    <label>{{ __('Products') }}</label>

                    <select  class="form-control{{ $errors->has('product_list') ? ' is-invalid' : '' }} selectpicker " data-style="select-with-transition" title="Single Select" data-size="7" placeholder="{{ __('Products') }}" name="product_list" id="product_list"    value="{{ old('product_list') }}">
                        <option value="">Select Products</option>
                        @foreach($products as $list)
                                            <option value="{{$list->id}}">{{$list->name}}</option>
                                        @endforeach
                    </select>

                    @include('alerts.feedback', ['field' => 'product_list'])
                </div>
                <div class="form-group" id="service_list_div">
                    <label>{{ __('General Service ') }}</label><br>
                    <table >
                    <?php $i=0; ?>
                    @foreach($general_service as $list)
                    <?php $i++; ?>

                        @if($i==1 )
                        <tr>
                            @endif
                            <td width="40%">
                                <div class="form-check form-check-inline">
                                    <label class="form-check-label">
                                        <input class="form-check-input" type="checkbox" name="generalservice[]" id="inlineCheckbox_gs{{$list->id}}" value="{{$list->id}}"> {{$list->name}}
                                        <span class="form-check-sign"></span>
                                    </label>
                                </div>
                            </td>
                            @if($i%3 == 0)
                        </tr> <tr>
                            @endif

                      @endforeach
                    </table>
                    @include('alerts.feedback', ['field' => 'product_list'])
                </div>
                <br>
                <div class="form-group" id="service_list_div">
                    <label>{{ __('Product Service ') }}</label><br>
                    <table >
                    <?php $i=0; ?>
                    @foreach($product_service as $list)
                    <?php $i++; ?>

                        @if($i==1 )
                        <tr>
                            @endif
                            <td width="40%">
                                <div class="form-check form-check-inline">
                                    <label class="form-check-label">
                                        <input class="form-check-input" type="checkbox"  name="productservice[]" id="inlineCheckbox_ps{{$list->id}}" value="{{$list->id}}"> {{$list->name}}
                                        <span class="form-check-sign"></span>
                                    </label>
                                </div>
                            </td>
                            @if($i%3 == 0)
                        </tr> <tr>
                            @endif

                      @endforeach
                    </table>
                    @include('alerts.feedback', ['field' => 'product_list'])
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

<script language="JavaScript" type="text/javascript">
 $(document).ready(function() {
   // $('#product_list').multiselect();
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

        });
</script>

