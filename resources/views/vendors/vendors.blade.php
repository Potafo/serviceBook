@extends('layouts.app', ['page' => __('Vendors'), 'pageSlug' => 'vendors'])
<script src="{{ asset('black') }}/js/core/jquery-1.9.1.js"></script>
<link href="{{ asset('black') }}/css/bootstrap.min.css" rel="stylesheet">
<link href="{{ asset('black') }}/css/bootstrap-datepicker.css" rel="stylesheet">
<script src="{{ asset('black') }}/js/core/bootstrap-datepicker.js"></script>
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
                         $joined_date=date("Y-m-d",strtotime($value->joined_on));
                         $current_date=date("Y-m-d");
                         $diff=(new DateTime($joined_date))->diff(new DateTime($current_date))->days;
                         $pending=intval($package_days_count) - intval($diff);
                        if($pending<=0)
                        {
                            $pending= "Expired";
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


                            </td>
                            <td >
                                {{-- <button type="button" rel="tooltip" class="btn btn-success btn-sm btn-icon"> --}}
                                    <a href="vendor_edit/{{ $value->vid }}" title="Edit Vendor"><i class='tim-icons icon-pencil'></i></a>
                                    <a href="vendor_configuration/{{ $value->vid }}" title="Vendor Configuration"><i class='tim-icons icon-settings'></i></a>

                                    {{-- href="vendor_category/status" --}}
                                {{-- </button> --}}
                                {{-- <button type="button" rel="tooltip" class="btn btn-danger btn-sm btn-icon">
                                    <i class="tim-icons icon-simple-remove"></i>
                                </button> --}}
                               </td>
                               <td>
                                <a  class="loadvendor_status" vendor_id='{{ $value->vid }}' title="Vendor Status"><i class='tim-icons icon-shape-star'></i></a>
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
{{-- <script src="{{ asset('black') }}/js/core/jquery-3.4.1.min.js"></script> --}}
    <script language="JavaScript" type="text/javascript">

        $(document).ready(function() {
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
