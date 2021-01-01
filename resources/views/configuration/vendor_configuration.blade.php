@extends('layouts.app', ['page' => __('User Profile'), 'pageSlug' => 'vendors'])
<script src="{{ asset('black') }}/js/core/jquery-3.4.1.min.js"></script>

<style>
    select > option {
        color: black;
    }



        </style>

@section('content')

<meta name="csrf-token" content="{{ csrf_token() }}">
    <div class="row container">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h5 class="title">{{ __('Configuration') }}</h5>
                </div>
                <div class="col-8">
                    <nav aria-label="breadcrumb" role="navigation">
                        <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="home">Home</a></li>
                        <li class="breadcrumb-item "><a href="../vendors">Vendors</a></li>
                        <li class="breadcrumb-item active" aria-current="page" id="bc_current">Configuration View</li>
                        </ol>
                    </nav>
                </div>
                <div class="alert alert-success" style="display: none">
                    <button type="button" aria-hidden="true" class="close" data-dismiss="alert" aria-label="Close">
                        <i class="tim-icons icon-simple-remove"></i>
                    </button>
                    <span id="alertmessage"> Vendor Configuration successfully updated</span>
                </div>
                <input type="hidden" name="vendor_name_config" id="vendor_name_config" value="{{ $id }}" >
                <div class="card-body">
                    {{-- <div class="form-row">
                        <div class="form-group  col-md-6">
                            <label>{{ __('Vendors') }}</label>
                                        <select class="form-control{{ $errors->has('vendor_name_config') ? ' is-invalid' : '' }} selectpicker " data-style="select-with-transition" style="color: white;"   title="Single Select" data-size="7" placeholder="{{ __('Vendors') }}"  name="vendor_name_config" id="vendor_name_config" >
                                            <option value="">Select Vendor</option>
                                            @foreach($vendor_list as $list)
                                                    <option value="{{$list->id}}" @if($list->id==$id) checked @endif>{{$list->name}}</option>
                                                @endforeach
                                        </select>
                                        @include('alerts.feedback', ['field' => 'vendor_name_config'])
                        </div>
                    </div> --}}
                    <div id="list_config" style="display: block">
                        @foreach($configurations as $key=>$value)
                        <div class="form-row">
                            <div class="form-group  col-md-6">
                                <h4>{{ ucwords($value->config_name) }}</h4>
                                    @if($value->input_type == "checkbox")
                                        <?php $fieldname = strtolower(str_replace(" ", "_", $value->config_name)); ?>
                                        <input type="checkbox" data-id="{{ $value->id }}" data-field="{{ $fieldname }}" name="status" class="form-control js-switch" {{ $data[$fieldname] == 'Y' ? 'checked' : '' }}>

                                    @elseif($value->input_type == "textbox")

                                    @endif
                            </div>
                        </div><br/>
                        @endforeach
                    </div>

                </div>

            </div>
        </div>
    </div>

    <script src="{{ asset('black') }}/js/plugins/switchery.min.js"></script>
    <link rel="stylesheet" href="{{ asset('black') }}/css/toastr.min.css">
    <script src="{{ asset('black') }}/js/plugins/toastr.min.js"></script>
<script>

 $(document).ready(function(){

        $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
        });
    //     setTimeout(function() {
    //     $("#vendor_name_config").trigger('change');
    // },10);

        //$('select[name="vendor_name_config"]').on('change', function() {
               // var vendor_id = $(this).val();
             //  var vendor_id = $('#vendor_id').val();//alert(vendor_id);
                // $('#list_config').html('');


                //         var data={"vendor_id":vendor_id};
                //         $.ajax({
                //             method: "post",
                //             url : 'vendor_config_list',
                //             data : data,
                //             cache : false,
                //             crossDomain : true,
                //             async : false,
                //             dataType: "text",
                //             success : function(datas)
                //             {
                //             //$('select[name="product_list"]').empty();
                //                 $('#list_config').css('display','block');
                //                 $('#list_config').html(datas);


                //                 }
                //             });

           // });


    });


</script>
<style>

    .switchery{background-color:#fff;border:1px solid #dfdfdf;border-radius:20px;cursor:pointer;display:inline-block;height:30px;position:relative;vertical-align:middle;width:50px;-moz-user-select:none;-khtml-user-select:none;-webkit-user-select:none;-ms-user-select:none;user-select:none;box-sizing:content-box;background-clip:content-box}
    .switchery>small{background:#fff;border-radius:100%;box-shadow:0 1px 3px rgba(0,0,0,0.4);height:30px;position:absolute;top:0;width:30px}
    .switchery-small{border-radius:20px;height:20px;width:33px}
    .switchery-small>small{height:20px;width:20px}
    .switchery-large{border-radius:40px;height:40px;width:66px}
    .switchery-large>small{height:40px;width:40px}

            </style>
    <script>

        $(document).ready(function(){

           let elems = Array.prototype.slice.call(document.querySelectorAll('.js-switch'));
           elems.forEach(function(html) {
               let switchery = new Switchery(html,  { size: 'small' });
           });

          //$.noConflict();



               $('.js-switch').change(function () {
                   //e.preventDefault();
                   //alert("sdsf");
                   let status = $(this).prop('checked') === true ? 'Y' : 'N';
                   let field = $(this).data('field');
                   let vendor = $('#vendor_name_config').val();
                   $.ajax({
                       type: "GET",
                       dataType: "json",
                       url: '{{ route('configuration.config_update') }}',
                       data: {'status': status, 'field': field,'vendor':vendor},
                       success: function (data) {
                           $('#alertmessage').html(data.message);
                           $('.alert-success').css('display','block');
                           $(".alert-success").fadeOut(3000);
                           //showNotification('top','right');
                           //toastr.options.closeButton = true;
                           //toastr.options.closeMethod = 'fadeOut';
                          // toastr.options.closeDuration = 100;
                           //toastr.success(data.message);
                       }
                   });
               });



           });


       </script>
    @endsection

