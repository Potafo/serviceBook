@extends('layouts.app', ['page' => __('Job Card'), 'pageSlug' => 'jobcard'])

    {{-- <link rel="stylesheet" href="{{ asset('black') }}/css/bootstrap-4.5.2.min.css" type="text/css"/> --}}
    <script src="{{ asset('black') }}/js/core/jquery-2.1.3.min.js"></script>
    {{-- <script src="//ajax.googleacom/ajax/libs/jquery/2.0.3/jquery.min.js"></script>
    <script type="text/javascript" src="{{ asset('black') }}/js/core/bootstrap.bundle-4.5.2.min.js"></script>
     <link rel="stylesheet" href="{{ asset('black') }}/css/bootstrap.min.css" type="text/css"/>
    <link rel="stylesheet" href="{{asset('black') }}/css/bootstrap-multiselect.css" type="text/css"/>
    <script type="text/javascript" src="{{ asset('black') }}/js/core/bootstrap-multiselect.js"></script>--}}


@section('content')
    <div class="row">
        <div class="col-md-12">
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
                    {{-- @method('put') --}}
                    <div class="alert alert-success" style="display: none">
                        <button type="button" aria-hidden="true" class="close" data-dismiss="alert" aria-label="Close">
                          <i class="tim-icons icon-simple-remove"></i>
                        </button>
                        <span>
                          <b> Product Successfully Added </b></span>
                      </div>
                    @include('alerts.success')
                    <input type="hidden" name="user_id" id="user_id" value="{{  Session::get('logged_user_id') }}" >
                    <input type="hidden" name="jobcardrefnumber" id="jobcardrefnumber" value="{{  Session::get('jobcard_reference') }}" >
                  {{-- <div class="form-group">
                    <label for="exampleFormControlInput1">JobCard Number</label>
                    <input type="text" class="form-control{{ $errors->has('jobcardnumber') ? ' is-invalid' : '' }}"  id="jobcardnumber" name="jobcardnumber" placeholder="Job Card Number" value="{{ old('jobcardnumber','JCN'.mt_rand(1000000,99999999)) }}">
                    @include('alerts.feedback', ['field' => 'jobcardnumber'])
                  </div> --}}
                  <div class="form-group">
                    <label for="exampleFormControlInput1">Name</label>
                    <input type="text" class="form-control{{ $errors->has('jobcard_name') ? ' is-invalid' : '' }}" id="jobcard_name" name="jobcard_name" placeholder="Name" value="{{ old('jobcard_name') }}">
                    @include('alerts.feedback', ['field' => 'jobcard_name'])
                  </div>
                  <div class="form-group">
                    <label for="exampleFormControlInput1">Mobile</label>
                    <input type="text" class="form-control{{ $errors->has('jobcard_mobile') ? ' is-invalid' : '' }}" id="jobcard_mobile" name="jobcard_mobile" placeholder="Mobile" value="{{ old('jobcard_mobile') }}">
                    @include('alerts.feedback', ['field' => 'jobcard_mobile'])
                  </div>
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
                <div class="form-group">
                    <label>{{ __('Products') }}</label>
                    {{-- <div class="col-4 text-right"> --}}
                        <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#exampleModal" data-jobcardref ="{{   Session::get('jobcard_reference') }}" >
                            Add Products
                          </button>
                        {{-- </div> --}}
                 </div>

                 <div class="form-group">
                    <div class="col-4 text-right">
                       <button type="submit" class="btn btn-fill btn-primary">{{ __('Submit') }}</button>
                   {{-- <a class="btn btn-sm btn-primary submitpackage">Submit</a> --}}
                   </div>
                 </div>
                    </div>
                </form>


                <table class="table tablesorter " id="">
                    <thead class=" text-primary">
                      <tr>
                        <th>
                          Slno
                        </th>
                        <th>
                          JobCard Number
                        </th>

                        <th>
                            Product
                          </th>
                          <th>
                            Service
                          </th>
                        {{-- <th >
                            Action
                          </th> --}}
                      </tr>
                    </thead>
                    <tbody id="service_full_list">
                        {{-- @if(count($servicelist)>0)
                            @foreach($servicelist as $key=>$value)
                                <tr>
                                    <td>
                                        {{ $servicelist->firstItem() + $key }}
                                    </td>
                                    <td>
                                        {{ $value->jobcard_number }}
                                    </td>

                                    <td>
                                        {{ $value->product_id }}
                                    </td>
                                    <td>
                                        {{ $value->generalservice }}
                                    </td>


                                </tr>
                        @endforeach

                     @endif --}}
                    </tbody>
                  </table>
                  <div class="card-footer py-4 loadpagination">
                    @if(count($servicelist)>0)
                    {{ $servicelist->render() }}
                    @endif
                </div>



            </div>


        </div>

    </div>


    <div class="modal fade " id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
          <div class="modal-content">
            <div class="modal-header">
              <h1 class="modal-title" id="exampleModalLabel" style="    text-align: center !important;color: purple;margin-left: 28%;
          ">Add Product</h1>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>





      {{-- action="{{ route('jobcard.service_insert') }}" --}}
            <form method="post" action="" autocomplete="off" id="jobcardcreate">
                {{-- <div class="card-body"> --}}
                @csrf
                {{-- @method('put') --}}
                <div class="modal-body">
                    <div class="alert alert-danger" style="display: none">
                        <button type="button" aria-hidden="true" class="close" data-dismiss="alert" aria-label="Close">
                          <i class="tim-icons icon-simple-remove"></i>
                        </button>
                        <span>
                          <b> Warning - </b> Select any service</span>
                      </div>
                    <input type="hidden" name="jobcardnumber_ref" id="jobcardnumber_ref"  >
                    <div class="form-group" id="product_list_div">
                        <label>{{ __('Products') }}</label>
                        {{-- {{Form::select('product_list', $products,'', ['class' => 'form-control'])}} --}}
                        <select  class="  productstyle form-control"  title="Single Select" data-size="7" placeholder="{{ __('Products') }}" name="product_list" id="product_list"  style="color: black;"  value="{{ old('product_list') }}">
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
                        @include('alerts.feedback', ['field' => 'generalservice'])
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
                        @include('alerts.feedback', ['field' => 'productservice'])
                    </div>
                    {{-- <div class="form-group">
                        <div class="col-4 text-right">
                            <button type="submit" class="btn btn-fill btn-primary">{{ __('Save') }}</button>

                        </div>
                    </div> --}}
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="button" id="submitForm" class="btn btn-primary">Save </button>
                  </div>
                {{-- </div> --}}
            </form>

          </div>
        </div>
      </div>




@endsection

<script language="JavaScript" type="text/javascript">
 $(document).ready(function() {
     var ref=$('#jobcardrefnumber').val();//alert(ref);
    load_service_list(ref);
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

    $('#exampleModal').on('show.bs.modal',function(event){

var button =$(event.relatedTarget)
var id = button.data('jobcardref'); //alert(id);
var modal=$(this)
modal.find('.modal-body #jobcardnumber_ref').val(id);

});
    // $(document).on('click', '#exampleModal', function(event) {
    //         event.preventDefault();
    //         let href = $(this).attr('data-jobcardref');alert(href);
    //         // $.ajax({
    //         //     url: href,
    //         //     beforeSend: function() {
    //         //         $('#loader').show();
    //         //     },
    //         //     // return the result
    //         //     success: function(result) {
    //         //         $('#mediumModal').modal("show");
    //         //         $('#mediumBody').html(result).show();
    //         //     },
    //         //     complete: function() {
    //         //         $('#loader').hide();
    //         //     },
    //         //     error: function(jqXHR, testStatus, error) {
    //         //         console.log(error);
    //         //         alert("Page " + href + " cannot open. Error:" + error);
    //         //         $('#loader').hide();
    //         //     },
    //         //     timeout: 8000
    //         // })
    //     });

        });
</script>

<script type="text/javascript">
    $(document).on('click', '#submitForm', function(){
        var registerForm = $("#jobcardcreate");
    var formData = registerForm.serialize();
    var ref=$('#jobcardnumber_ref').val();

        $.ajax({
            url: 'jobcard_serviceinsert' ,
            method:'post',
            data:formData,
            success:function(data) {//alert(data);
               // console.log(data);
                if(data.errors) {
                    if(data.errors.product_list){
                        $( '#product_list' ).addClass( ' is-invalid' );
                    }else{
                        $( '#product_list' ).removeClass( ' is-invalid' );
                    }
                    if(data.errors.generalservice || data.errors.productservice){
                        $( '.alert-danger' ).css('display','block');
                    }else{
                        $( '.alert-danger' ).css('display','none');
                    }

                }else{
                    $('#exampleModal').modal('hide');
                   // location.reload();
                   load_service_list(ref);
                }


            },
        });

    });
    function load_service_list(ref){
            var data={'ref':ref};
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $('#service_full_list').html('');
            $.ajax({
                url: 'jobcard_servicelist',
                method:'post',
                data:data,
                cache : false,
                crossDomain : true,
                async : false,
                dataType :'text',
                success:function(data) {
                    var json_x= JSON.parse(data);
                    $( '.alert-success' ).css('display','block');
                    $('#service_full_list').append(json_x.append);
                    $('.loadpagination').html(json_x.links);
                },
            });

        }
</script>
<script>
    if ( window.history.replaceState ) {
      window.history.replaceState( null, null, window.location.href );
    }
    </script>
