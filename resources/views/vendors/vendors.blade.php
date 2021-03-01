@extends('layouts.app', ['page' => __('Vendors'), 'pageSlug' => 'vendors'])
{{-- <script src="{{ asset('black') }}/js/core/jquery-3.4.1.min.js"></script> --}}
<script src="{{ asset('black') }}/js/core/jquery-1.9.1.js"></script>
<link href="{{ asset('black') }}/css/bootstrap.min.css" rel="stylesheet">
<link href="{{ asset('black') }}/css/bootstrap-datepicker.css" rel="stylesheet">
<script src="{{ asset('black') }}/js/core/bootstrap-datepicker.js"></script>
<script src="{{ asset('black') }}/js/validate.min.js"></script>
<style>
    select > option {
           color: black;
       }
   </style>
@section('content')
<div class="row">
  <div class="col-md-12">
    <div class="card ">
      <div class="card-header">
        <div class="row">




            <div class="col-8">
                <h4 class="card-title">Vendor List</h4>
            </div>
           <div class="col-4 text-right">
                <a class="btn btn-sm btn-primary " href="vendor_add">Add Vendors</a>
            </div>
            <div class="col-8">
                <nav aria-label="breadcrumb" role="navigation">
                    <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="home">Home</a></li>
                    <li class="breadcrumb-item "><a href="vendors">Vendors</a></li>
                    <li class="breadcrumb-item active" aria-current="page" id="bc_current">View</li>
                    </ol>
                </nav>
            </div>
        </div>
        @include('alerts.success')

        <div class="alert alert-danger" style="display: none">
          <button type="button" aria-hidden="true" class="close" data-dismiss="alert" aria-label="Close">
              <i class="tim-icons icon-simple-remove"></i>
          </button>
          <span><b> Danger - </b> This is a regular notification made with ".alert-danger"</span>
        </div>

          <div class="alert alert-info alert-with-icon" data-notify="container" id="successalert" style="display: none;background-color: #41f954 !important;">
            <button type="button" aria-hidden="true" class="close" data-dismiss="alert" aria-label="Close">
                <i class="tim-icons icon-simple-remove"></i>
            </button>
            <span data-notify="icon" class="tim-icons icon-bell-55"></span>
            <span data-notify="message">Succesfully Inserted Package.</span>
        </div>

      </div>
      <div class="col-12">
        <form method="get" action="{{ route('vendors.vendors') }}" autocomplete="off" id="vendorfilter">
            @csrf
            {{-- @method('put') --}}
            {{-- <input type="hidden" id="pageid" name="pageid" value="history" > --}}
            <div class="form-row">
                <div class="form-group col-md-2">
                    <label for="exampleFormControlInput1">From Date</label>
                    <input type="text" class="form-control date" id="filter_fromdate" name="filter_fromdate"  placeholder="From Date" value="{{ $filter_details['filter_fromdate'] }}">

                </div>
                <div class="form-group col-md-2">
                    <label for="exampleFormControlInput1">To Date</label>
                    <input type="text" class="form-control date" id="filter_todate" name="filter_todate"  placeholder="To Date" value="{{ $filter_details['filter_todate'] }}">

                </div>
                <div class="form-group col-md-2">
                    <label for="exampleFormControlInput1">Category</label>
                    {{-- <input type="text" class="form-control" id="filter_status" name="filter_status"  placeholder="Status" value="{{ old('filter_status') }}"> --}}
                    <select class="form-control{{ $errors->has('filter_category') ? ' is-invalid' : '' }} selectpicker " data-style="select-with-transition" title="Single Select" data-size="7"   name="filter_category" id="filter_category" >
                        <option value="">Select Category</option>
                        @foreach($vendor_cat as $list)
                                <option value="{{$list->id}}" @if($filter_details['filter_category']==$list->id) selected @endif>{{$list->name}}</option>
                            @endforeach
                    </select>

                </div>
                <div class="form-group col-md-2">
                    <label for="exampleFormControlInput1">Type</label>
                    {{-- <input type="text" class="form-control" id="filter_status" name="filter_status"  placeholder="Status" value="{{ old('filter_status') }}"> --}}
                    <select class="form-control{{ $errors->has('filter_type') ? ' is-invalid' : '' }} selectpicker " data-style="select-with-transition" title="Single Select" data-size="7"   name="filter_type" id="filter_type" >
                        <option value="">Select Type</option>
                        @foreach($vendor_type as $list)
                                <option value="{{$list->id}}" @if($filter_details['filter_type']==$list->id) selected @endif>{{$list->name}}</option>
                            @endforeach
                    </select>

                </div>
                <div class="form-group col-md-2">
                    <label for="exampleFormControlInput1">Search</label>
                    <input type="text" class="form-control" id="filter_globalsearch" name="filter_globalsearch"  placeholder="Search" value="{{ $filter_details['filter_globalsearch'] }}">

                </div>

                <div class="form-group col-md-2">

                    <br/>
                    <button type="submit" class="btn btn-fill btn-primary searchbutton">{{ __('Search') }}</button>
                </div>

            </div>
            <div class="form-row">
                {{-- <div class="form-group col-md-2">
                    <label for="exampleFormControlInput1">Mode</label>

                    <select class="form-control{{ $errors->has('filter_mode') ? ' is-invalid' : '' }} selectpicker " data-style="select-with-transition" title="Single Select" data-size="7"   name="filter_mode" id="filter_mode" >
                        <option value="">Select Mode</option>

                        <option value="1" @if($filter_details['filter_mode']==1) selected @endif>Expired</option>

                    </select>

                </div> --}}

            </div>
        </form>
    </div>

      <div class="card-body" style="display: block" id="view_package">
        <div class="table-responsive">
          <table class="table tablesorter " id="">
            <thead class=" text-primary">
              <tr>
                <th>
                  Slno
                </th>
                <th>
                  Name
                </th>
                <th>
                  Phone
                </th>
                <th>
                    Category
                  </th>
                  <th>
                    Type
                  </th>
                <th>
                    Current package
                  </th>
                  <th>
                    Pending Days
                  </th>
                  <th>
                    View
                  </th>
                  <th>
                    Actions
                  </th>
              </tr>
            </thead>
            <tbody>
                @if(count($vendor)>0)
                    @foreach($vendor as $key=>$value)
                        <?php
                        $timezone = 'ASIA/KOLKATA';
                        $date = new DateTime('now', new DateTimeZone($timezone));
                         //$datetime = $date->format('Y-m-d H:i:s');

                        $package_days_count=$value->days;
                        if($value->last_renewal_date == null)
                         $joined_date=date("Y-m-d",strtotime($value->joined_on));
                         else {
                            $joined_date=date("Y-m-d",strtotime($value->last_renewal_date));
                         }
                         $current_date=date("Y-m-d");
                         $diff=(new DateTime($joined_date))->diff(new DateTime($current_date))->days;
                         $pending=intval($package_days_count) - intval($diff);
                         $toblock='';
                        if($pending<=0)
                        {
                            $pending= "Expired";
                            $toblock='Y';
                        }else {
                            $pending=$pending ." Days more";

                        }

                        ?>
                        <tr >
                            <td class="viewvendors" data-id='{{ $value->vid }}' style="cursor: pointer;">
                                {{ $vendor->firstItem() + $key }}
                            </td>
                            <td class="viewvendors" data-id='{{ $value->vid }}' style="cursor: pointer;">
                                {{ $value->vname }}
                            </td class="viewvendors" data-id='{{ $value->vid }}' style="cursor: pointer;">

                            <td class="viewvendors" data-id='{{ $value->vid }}' style="cursor: pointer;">
                                {{ $value->contact_number }}
                            </td>
                            <td class="viewvendors" data-id='{{ $value->vid }}' style="cursor: pointer;">
                                {{ $value->vcategory }}
                            </td>
                            <td class="viewvendors" data-id='{{ $value->vid }}' style="cursor: pointer;">
                                {{ $value->vtype }}
                            </td>
                            <td class="viewvendors" data-id='{{ $value->vid }}' style="cursor: pointer;">
                                {{ $value->pname }}
                            </td>
                            <td class="viewvendors" data-id='{{ $value->vid }}' style="cursor: pointer;">
                                {{ $pending }}
                                                        </td>
                            <td class="viewvendors" data-id='{{ $value->vid }}' style="cursor: pointer;">
                                {{-- <button type="button" rel="tooltip" class="btn btn-info btn-sm btn-icon"> --}}
                                    <a href="vendor_view/{{ $value->vid }}" title="View,Renew,Package list"><i class="tim-icons icon-zoom-split"></i></a>
                                {{-- </button> --}}
                                {{-- <a href="vendor_view/{{ $value->vid }}" title="Edit Username/Password"><i class="tim-icons icon-single-02"></i></a> --}}


                            </td>
                            <td >
                                {{-- <button type="button" rel="tooltip" class="btn btn-success btn-sm btn-icon"> --}}
                                    <a href="vendor_edit/{{ $value->vid }}" title="Edit Vendor"><i class='tim-icons icon-pencil'></i></a>
                                    <a  data-toggle='modal'   data-target='#edit_userpass' id="edit_userpass_button" style="color: #ba54f5;"  data-vendor_id='{{ $value->userid }}' data-pass='{{ Hash::make($value->pass) }}' title="Delete"><i class='tim-icons icon-single-02' ></i></a>
                                    <a href="vendor_configuration/{{ $value->vid }}" title="Vendor Configuration"><i class='tim-icons icon-settings'></i></a>
                                    <a  data-toggle='modal'   data-target='#delete_vendor' id="delete_vendor_button" style="color: #ba54f5;"  data-vendor_id='{{ $value->vid }}' title="Edit Username/Password"><i class='tim-icons icon-trash-simple' ></i></a>
                                    {{-- href="vendor_category/status" --}}
                                {{-- </button> --}}
                                {{-- <button type="button" rel="tooltip" class="btn btn-danger btn-sm btn-icon">
                                    <i class="tim-icons icon-simple-remove"></i>
                                </button> --}}
                               </td>
                               <td>
                                <a  class="loadvendor_status" vendor_id='{{ $value->vid }}' title="Vendor Status"><i class='tim-icons icon-shape-star' style="color: #ba54f5;"></i></a>
                               @if($toblock=='Y')
                                @if($value->active=='Y')
                                    <a  data-toggle='modal'   data-target='#block_login' id="block_login_button"  data-user_id='{{ $value->userid }}' title="Block Vendor"><i class='tim-icons icon-lock-circle' style="color: red; font-size: 26px !important;"></i></a>
                                @else
                                    <a  href="vendor_view/{{ $value->vid }}#renewview"    title="Blocked"><i class='tim-icons icon-alert-circle-exc' style="color: red;font-size: 26px !important;"></i></a>
                                @endif
                                    @endif


                            </td>
                        </tr>
                @endforeach

             @endif
            </tbody>
          </table>

        </div>
        <div class="card-footer py-4">
            {{ $vendor->links() }}
        </div>
      </div>

      <div class="card-body" id="add_packages" style="display: none">
        <form>
          <div class="form-group">
            <label for="exampleFormControlInput1">Package Type</label>
            <input type="email" class="form-control" id="package_type" name="package_type" placeholder="Package Type/Name">
          </div>
          <div class="form-group">
            <label for="exampleFormControlInput1">Days</label>
            <input type="email" class="form-control" id="package_days" name="package_days" placeholder="Days">
          </div>
          <div class="form-group">
             <div class="col-4 text-right">
            <a class="btn btn-sm btn-primary submitpackage">Submit</a>
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>

</div>
<div class="modal fade" id="block_login" tabindex="-1" role="dialog" aria-labelledby="smallModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-sm" role="document">
        <div class="modal-content">
            <div class="modal-body" id="smallBody">
                <div>
                    <form action="{{ route('block_vendor_login') }}" method="post">

                        <input type="hidden" id="user_id" name="user_id" >

                        <div class="modal-body">
                            @csrf

                            <div class="text-center">Are you sure you want to Block this Vendor ?</div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">No, Sorry</button>
                            <button type="submit" class="btn btn-danger">Yes, Block Please</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="delete_vendor" tabindex="-1" role="dialog" aria-labelledby="smallModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-sm" role="document">
        <div class="modal-content">
            <div class="modal-body" id="smallBody">
                <div>
                    <form action="{{ route('delete_vendor') }}" method="post">

                        <input type="hidden" id="vendor_id" name="vendor_id" >

                        <div class="modal-body">
                            @csrf

                            <div class="text-center">Are you sure you want to Delete this Vendor ?</div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">No, Sorry</button>
                            <button type="submit" class="btn btn-danger">Yes, Delete Please</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>


<div class="modal fade" id="edit_userpass" tabindex="-1" role="dialog" aria-labelledby="smallModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-sm" role="document">
        <div class="modal-content" style="width: 160%">
            <div class="modal-header">
                <h4 class="modal-title" id="exampleModalLabel" style="    text-align: center !important;color: purple;margin-left: 18%;"> Edit Username & password</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
              </div>
              {{-- <form method="post" action="" autocomplete="off" id="edituspas">
                @csrf --}}
                <div class="modal-body">
                    <div class="alert alert-danger passworddanger" style="display: none">
                        <button type="button" aria-hidden="true" class="close" data-dismiss="alert" aria-label="Close">
                          <i class="tim-icons icon-simple-remove"></i>
                        </button>
                        <span>
                          <b> Warning - </b> Passwords do not match</span>
                      </div>


                      <form method="POST" action="{{ route('update_password') }}" class="form-signin" id="form-signin">
                        @csrf
                        @method('PUT')
                        <input type="hidden" id="vendor_id_pas" name="vendor_id_pas" >
                        {{-- <div class="form-group ">
                            <label >{{ __('Current password') }}</label>


                                <input id="old_password" name="old_password" type="password" class="form-control" required autofocus value="" style="color: black">
                                <div class="icon-bulb-63" onmouseover="mouseoverPass();" onmouseout="mouseoutPass();" />

                        </div> --}}
                        <div class="form-group ">
                            <label >{{ __('New password') }}</label>

                                <input id="new_password" name="new_password" type="password"  class="form-control" required style="color: black">
                                @include('alerts.feedback', ['field' => 'new_password'])
                        </div>
                        <div class="form-group ">
                            <label >{{ __('Confirm password') }}</label>


                                <input id="password_confirm" name="password_confirm" type="password"   class="form-control" required style="color: black">
                                @include('alerts.feedback', ['field' => 'password_confirm'])
                        </div>
                        <div class="form-group login-row row mb-0 viewpassbutton" style="display: none">
                            <div class="col-md-8 offset-md-2">
                                {{-- <button type="submit" class="btn btn-primary">
                                    {{ __('Submit') }}
                                </button> --}}
                                <button type="submit" class="btn" id="submit" name="submit"> Reset password</button>
                            </div>
                        </div>
                    </form>


                </div>
                {{-- <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="button" id="submitForm" class="btn btn-primary" style="display: none">Save </button>
                    <button type="button" id="updateForm" class="btn btn-primary" style="display: none">Update </button>
                </div>
            </form> --}}
        </div>
    </div>
</div>

    <script language="JavaScript" type="text/javascript">


        $(document).ready(function() {
            $(document).on('change', '#password_confirm', function(event) {
                var password =$('#new_password').val();
                var confirmPassword = $('#password_confirm').val();

                //alert(password);alert($('#vendor_id_pas').val())
                if(password === confirmPassword) {

                    $('.viewpassbutton').css('display','block');
                    $('.passworddanger').css('display','none');
                   // alert("Passwords do not match.");
                    return true;
                }else{
                    $('.passworddanger').css('display','block');
                    $('.viewpassbutton').css('display','none');
                    return false;
                }
                return true;
            });



            $(document).on('click', '#delete_vendor_button', function(event) {
            event.preventDefault();
            var vendor =$(this).attr('data-vendor_id');
            $('#vendor_id').val(vendor);
            $('#delete_vendor').modal("show");
        });

        $(document).on('click', '#edit_userpass_button', function(event) {
            event.preventDefault();
            var vendor =$(this).attr('data-vendor_id');
            $('#vendor_id_pas').val(vendor);
            $('#old_password').val($(this).attr('data-pass'));
            $('#edit_userpass').modal("show");
        });

            $(document).on('click', '#block_login_button', function(event) {
            event.preventDefault();
            var vendor =$(this).attr('data-user_id');

            $('#user_id').val(vendor);

            $.ajax({
                url: route('block_vendor_login')
                , beforeSend: function() {
                },
                // return the result
                success: function(result) {
                    $('#block_login').modal("show");
                }
                , complete: function() {
                }
                , error: function(jqXHR, testStatus, error) {
                    console.log(error);
                    alert("Page " + href + " cannot open. Error:" + error);
                    $('#loader').hide();
                }
                , timeout: 10000
            })
        });


            $('.date').datepicker({
       format: 'dd-mm-yyyy',
       todayHighlight: true,
        autoclose: true,
     });
           $.ajaxSetup({
                       headers: {
                           'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                       }
                   });
                   $(document).on('click', '.loadvendor_status', function(event) {

                        var vendorid =$(this).attr('vendor_id');
                        $.ajax({
                                type: "post",
                                dataType: "text",
                                url: 'set_vendorid',
                                data: {'vendorid': vendorid},
                                success: function (data) {
                                    window.location='vendor_category/status';
                                }
                            });
                        });
                });
            </script>
    <script language="JavaScript" type="text/javascript">
 $(document).on('click', '.viewvendors', function(event) {
            //event.preventDefault();
            var vendorid =$(this).attr('data-id');
            //window.location='jobcard_view/'+jobcardid;
            window.location='vendor_view/'+vendorid;

        });


    </script>

@endsection
