@extends('layouts.app', ['page' => __('Job Card'), 'pageSlug' => 'jobcard'])
{{-- <script src="{{ asset('black') }}/js/core/jquery.min.js"></script> --}}

<style>
    #servicelist  tbody>tr:hover td{background: #fff  !important;}
.ui-menu-item-wrapper{color: black;}

</style>
@section('content')
<meta name="csrf-token" content="{{ csrf_token() }}">
{{-- <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-3-typeahead/4.0.1/bootstrap3-typeahead.min.js"></script> --}}
{{-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.1.3/css/bootstrap.min.css" />--}}

    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="title">{{ __('Add Jobcard') }}</h5>
                </div>
                <div class="col-12">
                    <nav aria-label="breadcrumb" role="navigation">
                        <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="home">Home</a></li>
                        <li class="breadcrumb-item "><a href="{{ url('jobcard') }}">Job Card</a></li>
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
                          <b> JobCard Successfully Added </b></span>
                      </div>
                    @include('alerts.success')
                    <input type="hidden" name="user_id" id="user_id" value="{{  Session::get('logged_user_id') }}" >
                    <input type="hidden" name="jobcardrefnumber" id="jobcardrefnumber" value="{{  Session::get('jobcard_reference') }}" >
                    <input type="hidden" name="customerid_update" id="customerid_update" >
                    <?php
                    $customername='';
                    $customermobile='';
                    $customeremail='';
                    if(count($customer)>0){
                        $customername=$customer[0]->name;
                        $customermobile=$customer[0]->contact_number;
                        $customeremail=$customer[0]->email;
                    }
                    ?>
                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label for="exampleFormControlInput1">Name</label>
                            <input type="text" class="typeahead form-control{{ $errors->has('jobcard_name') ? ' is-invalid' : '' }}" id="jobcard_name" name="jobcard_name" placeholder="Name" value="{{ old('jobcard_name',$customername) }}">
                            @include('alerts.feedback', ['field' => 'jobcard_name'])
                        </div>
                        <div class="form-group col-md-6">
                            <label for="exampleFormControlInput1">Mobile</label>
                            <input type="text" class="typeahead form-control{{ $errors->has('jobcard_mobile') ? ' is-invalid' : '' }}" id="jobcard_mobile" name="jobcard_mobile" placeholder="Mobile" value="{{ old('jobcard_mobile',$customermobile) }}">
                            @include('alerts.feedback', ['field' => 'jobcard_mobile'])
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label for="exampleFormControlInput1">Email</label>
                            <input type="text" class="typeahead form-control{{ $errors->has('jobcard_email') ? ' is-invalid' : '' }}" id="jobcard_email" name="jobcard_email" placeholder="Email" value="{{ old('jobcard_email',$customeremail) }}">
                            @include('alerts.feedback', ['field' => 'jobcard_email'])
                        </div>

                    </div>
                    <div class="form-row">
                        @if(Session::get('logged_user_type') =='1')
                        <div class="form-group col-md-6">
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
                    </div>
                    <div class="form-group">
                        {{-- <label>{{ __('Products') }}</label> --}}
                            <button type="button" class="btn btn-primary" data-type='add' data-toggle="modal" data-target="#productsInsert" data-jobcardref ="{{   Session::get('jobcard_reference') }}" >
                                <i class="tim-icons icon-simple-add"></i>  Add Products
                            </button>
                    </div>

                    <div class="form-group">
                            <div class="col-8 text-right">
                            <button type="submit" class="btn btn-fill btn-primary">{{ __('Submit Job Card') }}</button>
                        </div>
                        </div>
                    </div>
                </form>
                <div class="card-body">

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
                        <th >
                            Action
                          </th>
                      </tr>
                    </thead>
                    <tbody id="service_full_list">
                    </tbody>
                  </table>
                </div>
                <div class="card-footer py-4 loadpagination">
                </div>
            </div>
        </div>
    </div>


    <div class="modal fade " id="productsInsert" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
          <div class="modal-content">
            <div class="modal-header">
              <h1 class="modal-title" id="exampleModalLabel" style="    text-align: center !important;color: purple;margin-left: 28%;
          "><span id="type_title"> </span> Product</h1>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <form method="post" action="" autocomplete="off" id="jobcardcreate">
                @csrf
                <div class="modal-body">
                    <div class="alert alert-danger" style="display: none">
                        <button type="button" aria-hidden="true" class="close" data-dismiss="alert" aria-label="Close">
                          <i class="tim-icons icon-simple-remove"></i>
                        </button>
                        <span>
                          <b> Warning - </b> Select any service</span>
                      </div>
                    <input type="hidden" name="jobcardnumber_ref" id="jobcardnumber_ref"  >
                    <input type="hidden" name="jobcardnumber_update" id="jobcardnumber_update"  >
                    <input type="hidden" name="jobcardid_update" id="jobcardid_update"  >
                    <input type="hidden" name="jobcard_service" id="jobcard_service"  value="{{ Session::get('Products') }}" >
                    <div class="form-group" id="product_list_div">
                        <label>{{ __('Products') }}</label>
                        <select  class="  productstyle form-control"  title="Single Select" data-size="7" placeholder="{{ __('Products') }}" name="product_list" id="product_list"  style="color: black;"  value="{{ old('product_list') }}">
                            <option value="">Select Products</option>
                            @foreach($products as $list)
                                <option value="{{$list->id}}">{{$list->name}}</option>
                            @endforeach
                        </select>
                        @include('alerts.feedback', ['field' => 'product_list'])
                    </div>
                    <div class="form-group ">
                        <label for="exampleFormControlInput1">Remarks</label>
                        <input type="text" class="form-control{{ $errors->has('jobcard_remarks') ? ' is-invalid' : '' }} " id="jobcard_remarks" style=" color: black;" name="jobcard_remarks" placeholder="Remarks" value="{{ old('jobcard_remarks') }}">
                        @include('alerts.feedback', ['field' => 'jobcard_remarks'])
                    </div>

                    <div id="servicelist" style="display: none">
                        {{-- List all services --}}
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="button" id="submitForm" class="btn btn-primary" style="display: none">Save </button>
                    <button type="button" id="updateForm" class="btn btn-primary" style="display: none">Update </button>
                </div>
            </form>

          </div>
        </div>
      </div>


    <div class="modal fade" id="delete_services" tabindex="-1" role="dialog" aria-labelledby="smallModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-sm" role="document">
            <div class="modal-content">
                <div class="modal-body" id="smallBody">
                    <div>
                        <form action="{{ route('jobcard_delete') }}" method="post">
                            <input type="hidden" id="jobcardreference" name="jobcardreference" >
                            <input type="hidden" id="cartid" name="cartid" >
                            <input type="hidden" id="jobcardnumber" name="jobcardnumber" >
                            <input type="hidden" id="customerid" name="customerid" >
                            <input type="hidden" id="jobcardid" name="jobcardid" >
                            <div class="modal-body">
                                @csrf

                                <div class="text-center">Are you sure you want to delete <span id="delref"> </span>? </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                                <button type="submit" class="btn btn-danger">Yes, Delete Service</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
<script src="{{ asset('black') }}/js/core/jquery-3.4.1.min.js"></script>
<!-- CSS -->
<link rel="stylesheet" type="text/css" href="{{ asset('black') }}/css/jquery-ui.min.css">
<!-- Script -->
<script src="{{ asset('black') }}/js/jquery-ui.min.js"></script>
<script language="JavaScript" type="text/javascript">
 $(document).ready(function() {
    $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

    var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
    $(document).on('keydown', '.typeahead', function() {
        var id = this.id;
        $( '#'+id).autocomplete({
            source: function( request, response ) {
            // Fetch data
            $.ajax({
                url:"{{route('jobcard.searchnumber')}}",
                type: 'post',
                dataType: "json",

                data: {search:request.term,request:id},
                success: function( data ) {
                response( data );
                }
            });
            },
            select: function (event, ui) {
            // Set selection
            $('#jobcard_name').val(ui.item.name); // display the selected text
            $('#jobcard_mobile').val(ui.item.mobile); // save selected id to input
            $('#jobcard_email').val(ui.item.email); // save selected id to input
            return false;
            }
        });
    });
});
</script>
<script language="JavaScript" type="text/javascript">
    $(document).ready(function() {

    var ref=$('#jobcardrefnumber').val();//alert(ref);
    load_service_list(ref);
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

            $('select[name="product_list"]').on('change', function() {
                var product_id = $(this).val();
                var pservice='';
                var gservice='';
                var servicetype=$("#jobcard_service").val();
                    if(product_id) {
                        var data={"product_id":product_id,"pservice":pservice,"gservice":gservice,"servicetype":servicetype};
                        $.ajax({
                            method: "post",
                            url : "api/service_list",
                            data : data,
                            cache : false,
                            crossDomain : true,
                            async : false,
                            dataType :'text',
                            success : function(data)
                            {
                                $('#servicelist').css('display','block');
                                $('#servicelist').html(data);
                                }
                            });
                    }else{
                        //$('select[name="product_list"]').empty();
                        }
            });

            $('#productsInsert').on('show.bs.modal',function(event){
                var button =$(event.relatedTarget)
                var id = button.data('jobcardref');
                var type = button.data('type');
                var modal=$(this)
                modal.find('.modal-body #jobcardnumber_ref').val(id);

                if(type=="add")
                {
                    $(":checkbox"). attr("checked", false);
                    $('#type_title').html("Add");
                    $('#product_list').val("");
                    modal.find('.modal-body #product_list').attr("style", "pointer-events: auto;");
                    modal.find('.modal-body #product_list').css("color", "black");
                    $('#servicelist').css('display','none');
                    $('#submitForm').css('display','block');
                    $('#updateForm').css('display','none');
                }
                else if(type=="update")
                {
                    $('#type_title').html("Update");
                    var pid = button.data('pdtid');
                    var pservice = button.data('pdtservice');
                    var gservice = button.data('genservice');
                    var jobcarnmbr = button.data('jobcardnmbr');
                    var id = button.data('id');
                modal.find('.modal-body #product_list').val(pid);
                modal.find('.modal-body #jobcardnumber_update').val(jobcarnmbr);
                modal.find('.modal-body #jobcardid_update').val(id);
                modal.find('.modal-body #product_list').attr("style", "pointer-events: none;");
                modal.find('.modal-body #product_list').css("color", "black");
                var data={"product_id":pid,"pservice":pservice,"gservice":gservice};
                        $.ajax({
                            method: "post",
                            url : "api/service_list",
                            data : data,
                            cache : false,
                            crossDomain : true,
                            async : false,
                            dataType :'text',
                            success : function(data)
                            {
                                $('#servicelist').css('display','block');
                                $('#servicelist').html(data);
                                }
                            });

                //$('#servicelist').css('display','block');
                    $('#updateForm').css('display','block');
                    $('#submitForm').css('display','none');

                }

            });
        });
    // display a modal (small modal)
    $(document).on('click', '#deleteButton', function(event) {
        event.preventDefault();
        var cartid =$(this).attr('data-id');
        var jobcardnumber=$(this).attr('data-jobcardnmbr');
        var jobcardreference =$(this).attr('data-jobcardref');
        var customerid =$(this).attr('data-customerid');
        var jobcardid =$(this).attr('data-jobcardid');
        $('#jobcardreference').val(jobcardreference);
        $('#cartid').val(cartid);
        $('#jobcardnumber').val(jobcardnumber);
        $('#customerid').val(customerid);
        $('#jobcardid').val(jobcardid);
        $('#customerid_update').val(customerid);
        $('#delref').html(jobcardnumber);
        $('#delete_services').modal("show");

    });
    $(document).on('click', '#submitForm', function(){
        var registerForm = $("#jobcardcreate");
        var formData = registerForm.serialize();
        var ref=$('#jobcardnumber_ref').val();
        $.ajax({
            url: 'jobcard_serviceinsert' ,
            method:'post',
            data:formData,
            success:function(data) {
                if(data.errors) {
                    if(data.errors.product_list){
                        $( '#product_list' ).addClass( ' is-invalid' );
                    }else{
                        $( '#product_list' ).removeClass( ' is-invalid' );
                    }
                }else{
                   // alert(data)
                    $( '.alert-success' ).show().delay(1000).hide('slow');
                    $('#productsInsert').modal('hide');
                   load_service_list(ref);
                }


            },
        });

    });


    $(document).on('click', '#updateForm', function(){
        var registerForm = $("#jobcardcreate");
        var formData = registerForm.serialize();
        var ref=$('#jobcardnumber_ref').val();
        $.ajax({
            url: 'jobcard_serviceupdate' ,
            method:'post',
            data:formData,
            success:function(data) {
               // console.log(data);
                if(data.errors) {
                    if(data.errors.product_list){
                        $( '#product_list' ).addClass( ' is-invalid' );
                    }else{
                        $( '#product_list' ).removeClass( ' is-invalid' );
                    }

                }else{
                    $( '.alert-success' ).show().delay(1000).hide('slow');
                    $('#productsInsert').modal('hide');
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
                method:'get',
                data:data,
                cache : false,
                crossDomain : true,
                async : false,
                dataType :'text',
                success:function(data) {
                    var json_x= JSON.parse(data);
                   // $( '.alert-success' ).css('display','block');
                    $('#service_full_list').append(json_x.append);
                    $('.loadpagination').html(json_x.links);
                },
            });

        }
</script>
