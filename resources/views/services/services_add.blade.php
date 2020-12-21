@extends('layouts.app', ['page' => __('Services'), 'pageSlug' => 'services'])
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
                    <h5 class="title">{{ __('Add Services') }}</h5>
                </div>
                <div class="col-12">
                    <nav aria-label="breadcrumb" role="navigation">
                        <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="home">Home</a></li>
                        <li class="breadcrumb-item "><a href="Services">services</a></li>
                        <li class="breadcrumb-item active" aria-current="page" id="bc_current">Add</li>
                        </ol>
                    </nav>
                </div>
                <form method="post" action="" id="servicecreate" autocomplete="off">
                    <div class="card-body">
                    @csrf
                    {{-- @method('put') --}}

                    @include('alerts.success')
                    <div class="alert alert-success" style="display: none">
                        <button type="button" aria-hidden="true" class="close" data-dismiss="alert" aria-label="Close">
                          <i class="tim-icons icon-simple-remove"></i>
                        </button>
                        <span>
                          <b> Services Successfully Added </b></span>
                      </div>
                      <input type="hidden" name="vendor_id" id="vendor_id" value="{{  Session::get('logged_vendor_id') }}" >
                    <input type="hidden" name="user_id" id="user_id" value="{{  Session::get('logged_user_id') }}" >
                    <input type="hidden" name="serv_type" id="serv_type" >
                    <div class="form-group">
                        <label>{{ __('Service Type') }}</label>
                        <select class="form-control{{ $errors->has('servicetype_list') ? ' is-invalid' : '' }} selectpicker " data-style="select-with-transition" title="Single Select" data-size="7" placeholder="{{ __('Service Type') }}" name="servicetype_list" id="servicetype_list" value="{{ old('servicetype_list') }}">
                            <option value="">Select Service Type</option>
                            @foreach($servicelist_vendor as $list)
                                                <option value="{{$list->id}}" tableconnected="{{ $list->table_connected }}" @if(old('servicetype_list') == $list->id) selected @endif >{{$list->name}}</option>
                                            @endforeach
                        </select>
                        @include('alerts.feedback', ['field' => 'servicetype_list'])
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

                    <div class="form-group" id="product_div" @if($errors->has('product_list')) style="display: block" @else style="display: none" @endif>
                        <label>{{ __('Products') }}</label>
                        <select class="form-control{{ $errors->has('product_list') ? ' is-invalid' : '' }} selectpicker " data-style="select-with-transition" title="Single Select" data-size="7" placeholder="{{ __('Products') }}" name="product_list" id="product_list" value="{{ old('product_list') }}">
                            <option value="">Select Products</option>
                            @foreach($products as $list)
                                                <option value="{{$list->id}}">{{$list->name}}</option>
                                            @endforeach
                        </select>
                        @include('alerts.feedback', ['field' => 'product_list'])
                    </div>
                    <div class="form-group">
                        <label for="exampleFormControlInput1">Services Name</label>
                        <input type="text" class="form-control{{ $errors->has('servicename') ? ' is-invalid' : '' }}"  id="servicename" name="servicename" placeholder="Service Name" value="{{ old('servicename') }}">
                        @include('alerts.feedback', ['field' => 'servicename'])
                      </div>
                      <div class="form-group">
                        <label for="exampleFormControlInput1">Services Price</label>
                        <input type="text" class="form-control{{ $errors->has('serviceprice') ? ' is-invalid' : '' }}"  id="serviceprice" name="serviceprice" placeholder="Service Price" value="{{ old('serviceprice') }}">
                        @include('alerts.feedback', ['field' => 'serviceprice'])
                      </div>
                      @if(Session::get('tax_enabled')=='Y')
                      <div class="form-group">
                        <label for="exampleFormControlInput1">SGST</label>
                        <input type="text" class="form-control{{ $errors->has('servicesgst') ? ' is-invalid' : '' }}"  id="servicesgst" name="servicesgst" placeholder="Service SGST" value="{{ old('servicesgst') }}">
                        @include('alerts.feedback', ['field' => 'servicesgst'])
                      </div>
                      <div class="form-group">
                        <label for="exampleFormControlInput1">CGST</label>
                        <input type="text" class="form-control{{ $errors->has('servicecgst') ? ' is-invalid' : '' }}"  id="servicecgst" name="servicecgst" placeholder="Service CGST" value="{{ old('servicecgst') }}">
                        @include('alerts.feedback', ['field' => 'servicecgst'])
                      </div>
                      @endif
                      <div class="form-group">
                        <label for="exampleFormControlInput1">Services Offer Price</label>
                        <input type="text" class="form-control{{ $errors->has('serviceoffer') ? ' is-invalid' : '' }}"  id="serviceoffer" name="serviceoffer" placeholder="Service Offer Price" value="{{ old('serviceoffer') }}">
                        @include('alerts.feedback', ['field' => 'serviceoffer'])
                      </div>
                  <div class="form-group">
                     <div class="col-4 text-right">
                        <button type="button" class="btn btn-fill btn-primary" id="submitForm">{{ __('Save') }}</button>
                    {{-- <a class="btn btn-sm btn-primary submitpackage">Submit</a>
                    <button type="button" id="submitForm" class="btn btn-primary" style="display: none">Save </button> --}}
                    </div>
                  </div>
                    </div>
                </form>
            </div>


        </div>

    </div>

@endsection
<script src="{{ asset('black') }}/js/core/jquery-3.4.1.min.js"></script>
<script language="JavaScript" type="text/javascript">
    $(document).ready(function() {

       $('select[name="vendor_name"]').on('change', function() {
           var vendor_id = $(this).val();
               if(vendor_id) {

                   var data={"vendor_id":vendor_id};
                   $.ajax({
                       method: "post",
                       url : "api/product_list",
                       data : data,
                       cache : false,
                       crossDomain : true,
                       async : false,
                       dataType :'text',
                       success : function(data)
                       {
                       $('select[name="product_list"]').empty();
                           $('#product_list').html(data);


                           }
                       });
               }else{
                   $('select[name="product_list"]').empty();
                   }
        });



              $('select[name="servicetype_list"]').on('change', function() {
                var element = $(this).find('option:selected');
                var service_type = element.attr("tableconnected");

                $('#serv_type').val(service_type);
                //var service_type = $(this).attr('tableconnected');
                $('#vendor_div').css('display','block');
               if(service_type=='products') {
                   $('#product_div').css('display','block');

                   var vendor_id = $('#vendor_id').val();
                    if(vendor_id) {

                        var data={"vendor_id":vendor_id};
                        $.ajax({
                            method: "post",
                            url : "api/product_list",
                            data : data,
                            cache : false,
                            crossDomain : true,
                            async : false,
                            dataType :'text',
                            success : function(data)
                            {
                            $('select[name="product_list"]').empty();
                                $('#product_list').html(data);


                                }
                            });
                    }else{
                        $('select[name="product_list"]').empty();
                        }


               }else{
                  $('#product_div').css('display','none');
                  //$('#vendor_div').css('display','none');
                   $('select[name="product_list"]').empty();
                   }
              });




            $(document).on('click', '#submitForm', function(){
                var registerForm = $("#servicecreate");
                var formData = registerForm.serialize();
                //var ref=$('#jobcardnumber_ref').val();
                $.ajax({
                    url: 'services_insert',
                    method:'post',
                    data:formData,
                    success:function(data) {
                        //var json_x= JSON.parse(data);
                        //alert(data.errors);
                        $.each(data.errors,function(field,errors){
                                 $(document).find('[name='+field+']').addClass( ' is-invalid' );
                                });
                        if(data.errors) {
                            var element = $(this).find('option:selected');
                            var service_type = element.attr("tableconnected");
                            if(service_type == 'products')
                            {
                                $('#product_div').css('display','block');
                            }else{
                                $('#product_div').css('display','none');
                            }
                            // if(data.errors.product_list){
                            //     $( '#product_list' ).addClass( ' is-invalid' );
                            // }else{
                            //     $( '#product_list' ).removeClass( ' is-invalid' );
                            // }

                            // if(data.errors.generalservice || data.errors.productservice){
                            //     $( '.alert-danger' ).css('display','block');
                            // }else{
                            //     $( '.alert-danger' ).css('display','none');
                            // }

                        }else{
                            $( '.alert-success' ).show().delay(1000).hide('slow');
                            window.location="services";

                        }
                        // else{
                        //     window.location="services";
                        //     //$( '.alert-success' ).show().delay(1000).hide('slow');
                        //     //$('#productsInsert').modal('hide');
                        // //load_service_list(ref);
                        // }


                    },
                // error:function (response){
                //     $.each(response.responseJSON.errors,function(field_name,error){
                //         $(document).find('[name='+field_name+']').after('<span class="text-strong textdanger">' +error+ '</span>')
                //     })
                // }
                });

            });


           });
   </script>

