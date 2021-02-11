@extends('layouts.app', ['page' => __('Main Configurations'), 'pageSlug' => 'main_configuration'])


@section('content')
    <div class="row container">
        <div class="col-md-12">
            <div class="card">

                {{-- <div class="col-8">
                    <nav aria-label="breadcrumb" role="navigation">
                        <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="home">Home</a></li>
                        <li class="breadcrumb-item "><a href="../vendors">Vendors</a></li>
                        <li class="breadcrumb-item active" aria-current="page" id="bc_current">View</li>
                        </ol>
                    </nav>
                </div> --}}
                <div class="alert alert-success" style="display: none">
                    <button type="button" aria-hidden="true" class="close" data-dismiss="alert" aria-label="Close">
                        <i class="tim-icons icon-simple-remove"></i>
                    </button>
                    <span id="alertmessage">  Configuration successfully updated</span>
                </div>
                <div class="card-body">
                    <div class="card-header">
                        <h5 class="title" style="color: darkorchid;font-size: 155%">{{ __('Common/Admin Configurations') }}</h5>
                    </div>
                    <table class="table table-hover table-striped">
                        <thead>

                        </thead>
                        <tbody>
                            @foreach($configurations as $key=>$value)
                                <tr>
                                    <td>{{ $value->name }}</td>
                                    <td>
                                        @if($value->input_type == "checkbox")
                                            <?php $fieldname = strtolower(str_replace(" ", "_", $value->name)); ?>
                                            <input type="checkbox" data-id="{{ $value->id }}" data-field="{{ $fieldname }}" data-mode="common_config" name="status" class="js-switch" {{ Session::get($fieldname) == 'Y' ? 'checked' : '' }}>

                                        @elseif($value->input_type == "textbox")
                                            <?php $fieldname = strtolower(str_replace(" ", "_", $value->name)); ?>
                                            <input type="textbox" data-id="{{ $value->id }}" data-field="{{ $fieldname }}" data-mode="common_config" name="status" class="textbox" value="{{ Session::get($fieldname) }}">
                                        @endif
                                    </td>
                                    {{-- <td>{{ $user->created_at->diffForHumans() }}</td> --}}
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <div class="card-header">
                        <h5 class="title" style="color: darkorchid;font-size: 155%">{{ __('App Configurations') }}</h5>
                    </div>
                    <table class="table table-hover table-striped">
                        <thead>

                        </thead>
                        <tbody>
                            @foreach($appconfigurations as $key=>$value)
                                <tr>
                                    <td>{{ $value->name }}</td>
                                    <td>
                                        @if($value->input_type == "checkbox")
                                            <?php $fieldname = strtolower(str_replace(" ", "_", $value->name)); ?>
                                            <input type="checkbox" data-id="{{ $value->id }}" data-field="{{ $fieldname }}" data-mode="app_config" name="status" class="js-switch" {{ Session::get($fieldname) == 'Y' ? 'checked' : '' }}>

                                        @elseif($value->input_type == "textbox")
                                            <?php $fieldname = strtolower(str_replace(" ", "_", $value->name)); ?>
                                            <input type="textbox" data-id="{{ $value->id }}" data-field="{{ $fieldname }}" data-mode="app_config" name="status" class="textbox" value="{{ Session::get($fieldname) }}">
                                        @endif
                                    </td>
                                    {{-- <td>{{ $user->created_at->diffForHumans() }}</td> --}}
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                {{-- <div class="card-footer" style="display: none">
                    <button type="submit" class="btn btn-fill btn-primary">{{ __('Save') }}</button>
                </div> --}}



            </div>
        </div>
    </div>
    <script src="{{ asset('black') }}/js/core/jquery-3.4.1.min.js"></script>
    {{-- <link rel="stylesheet" href="{{ asset('black') }}/css/switchery.min.css"> --}}
    <script src="{{ asset('black') }}/js/plugins/switchery.min.js"></script>
    <link rel="stylesheet" href="{{ asset('black') }}/css/toastr.min.css">
    <script src="{{ asset('black') }}/js/plugins/toastr.min.js"></script>
        <style>
            select > option {
                color: black;
            }
    .switchery{background-color:#fff;border:1px solid #dfdfdf;border-radius:20px;cursor:pointer;display:inline-block;height:30px;position:relative;vertical-align:middle;width:50px;-moz-user-select:none;-khtml-user-select:none;-webkit-user-select:none;-ms-user-select:none;user-select:none;box-sizing:content-box;background-clip:content-box}
    .switchery>small{background:#fff;border-radius:100%;box-shadow:0 1px 3px rgba(0,0,0,0.4);height:30px;position:absolute;top:0;width:30px}
    .switchery-small{border-radius:20px;height:20px;width:33px}
    .switchery-small>small{height:20px;width:20px}
    .switchery-large{border-radius:40px;height:40px;width:66px}
    .switchery-large>small{height:40px;width:40px}

                </style>

<script>
 $(document).ready(function(){
    //$.noConflict();
    let elems = Array.prototype.slice.call(document.querySelectorAll('.js-switch'));
    elems.forEach(function(html) {
        let switchery = new Switchery(html,  { size: 'small' });
    });


        $('.js-switch').change(function () {
            let status = $(this).prop('checked') === true ? 'Y' : 'N';
            let field = $(this).data('field');
            let mode = $(this).data('mode');
            let id = $(this).data('id');
            $.ajax({
                type: "GET",
                dataType: "json",
                url: '{{ route('configuration.config_main_update') }}',
                data: {'status': status, 'field': field,'mode': mode,'from':'checkbox', 'id': id},
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
        $('.textbox').change(function () {
            var textvalue = $(this).val();
            let field = $(this).data('field');
            let id = $(this).data('id');
            let mode = $(this).data('mode');
            $.ajax({
                type: "GET",
                dataType: "json",
                url: '{{ route('configuration.config_main_update') }}',
                data: {'textvalue': textvalue, 'field': field, 'id': id, 'mode': mode,'from':'textbox'},
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

