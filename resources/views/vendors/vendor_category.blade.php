<?php if($mode=="category") {
        $vcat='vendor_category/category';
        $title="Vendor Category ";
        $add_url='vendorcategory_add/category';
    } else if($mode=="type") {
        $vcat='vendor_category/type';
        $title="Vendor Type ";
        $add_url='vendorcategory_add/type';
    } else if($mode=="service_type") {
        $vcat='vendor_category/service_type';
        $title="Service Type ";
        $add_url='vendorcategory_add/service_type';
    }else if($mode=="status") {
        $vcat='vendor_category/status';
        $title="Vendor Status";
        $add_url='vendorcategory_add/status';
    }
    ?>

@extends('layouts.app', ['page' => __($title), 'pageSlug' =>$vcat])
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
                <h4 class="card-title">{{ $title }} List</h4>
            </div>
           <div class="col-4 text-right">
                <a class="btn btn-sm btn-primary " href="{{ url($add_url)  }}">Add {{ $title }}</a>
            </div>
            <div class="col-8">
                <nav aria-label="breadcrumb" role="navigation">
                    <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="home">Home</a></li>
                    <li class="breadcrumb-item "><a href="#">{{ $title }}</a></li>
                    <li class="breadcrumb-item active" aria-current="page" id="bc_current">View</li>
                    </ol>
                </nav>
            </div>
        </div>
        @include('alerts.success')



      </div>
      @if($mode=="service_type")
      <div class="card-body">
        <form method="post" action="" autocomplete="off">
            @csrf
        <div class="form-row">
            <input type="hidden" id="mode" name="mode" value="{{ $mode }}" >
            <div class="form-group col-md-4">
                <label>{{ __('Vendors') }}</label>
                <select class="form-control{{ $errors->has('vendor_name') ? ' is-invalid' : '' }} selectpicker " data-style="select-with-transition" title="Single Select" data-size="7" placeholder="{{ __('Vendors') }}" name="vendor_name" id="vendor_name" value="{{ old('vendor_name') }}">

                    @foreach($vendor_list as $list)
                            <option value="{{$list->id}}">{{$list->name}}</option>
                        @endforeach
                </select>
                @include('alerts.feedback', ['field' => 'vendor_name'])
            </div>
            {{-- <div class="form-group col-md-4">
              <label for="inputState">State</label>
              <select id="inputState" class="form-control">
                <option selected>Choose...</option>
                <option>...</option>
              </select>
            </div>
            <div class="form-group col-md-2">
              <label for="inputZip">Zip</label>
              <input type="text" class="form-control" id="inputZip">
            </div> --}}
          </div>
        </form>
      </div>
@endif
      <div class="card-body" style="display: block" id="view_package">
        <div class="table-responsive">
        <table class="table tablesorter " id="">
            <thead class=" text-primary">
            <tr>
                <th class='text-center'>
                    Slno
                </th>
                <th class='text-center'>
                    Name
                </th>
                @if($mode=="status")
                @if(empty(Session::get('logged_vendor_id')))
                    <th class="text-center">
                        Vendor
                    </th>
                    @endif
                    <th class="text-center">
                        Send Sms
                    </th>
                    <th class="text-center">
                        Send Email
                    </th>
                    <th class="text-center">
                        Display Order
                    </th>
                    <th class="text-center">
                        Ending Status
                    </th>
                    @elseif($mode=="service_type")
                    {{-- <th class="text-center">
                        Vendor
                    </th> --}}
                @endif
                <th class="text-center">
                    Status
                </th>
                <th class='text-center'>
                    Action
                </th>
            </tr>
            </thead>
            <tbody id="loadcat_details">
                @if(count($category)>0)
                    @foreach($category as $key=>$value)
                        <tr>
                            <td class='text-center'>
                                {{ $category->firstItem() + $key }}
                            </td>
                            <td class='text-center'>
                                {{ $value->name }}
                            </td>
                                @if($mode=="status")
                                    @if(empty(Session::get('logged_vendor_id')))
                                    <td class="text-center">
                                        {{ $value->vname }}
                                    </td>
                                    @endif

                                    <td class="text-center">
                                        {{ $value->send_sms }}
                                    </td>
                                    <td class="text-center">
                                        {{ $value->send_email }}
                                    </td>
                                    <td class="text-center">
                                        {{ $value->display_order }}
                                    </td>
                                    <td class="text-center">
                                        {{ $value->ending_status }}
                                    </td>
                                    <td class="text-center">
                                        {{ $value->active }}
                                    </td>
                                @elseif($mode=="service_type")
                                {{-- <td class="text-center">
                                   <a >List</a>
                                </td> --}}
                                <td class="text-center">
                                    {{ $value->status }}
                                </td>
                                @else
                                <td class="text-center">
                                    {{ $value->status }}
                                </td>
                            @endif
                            <td class='text-center'>

                                <a href="{{ url("vendor_category_edit/".$mode."/".$value->id)  }}" >  <i class='tim-icons icon-pencil'></i> </a>
                            </td>
                        </tr>
                @endforeach

            @endif
            </tbody>
        </table>

        </div>
        <div class="card-footer py-4 loadpagination">
            @if(count($category)>0)
            {{ $category->links() }}
            @endif
        </div>

    </div>
  </div>

</div>
<script src="{{ asset('black') }}/js/core/jquery.min.js"></script>

    <script language="JavaScript" type="text/javascript">
        $(document).ready(function() {
            var mode=$('#mode').val();
            if(mode=="service_type")
            {
                //$("#vendor_name").trigger('change');
                var vendor_id = $("#vendor_name").val();
                load_pagedetails(vendor_id,mode)
            }
            $('select[name="vendor_name"]').on('change', function() {
                var vendor_id = $(this).val();
                var mode = $('#mode').val();
                load_pagedetails(vendor_id,mode)
            });

            function load_pagedetails(vendor_id,mode)
            {
                $('#loadcat_details').html("");
                $('.loadpagination').html("");
                    if(vendor_id) {
                        var data={"vendor_id":vendor_id,"mode":mode};
                        $.ajax({
                            method: "get",
                            url : "../filter_by_vendorid",
                            data : data,
                            cache : false,
                            crossDomain : true,
                            async : false,
                            dataType :'text',
                            success : function(data)
                            {
                                var json_x= JSON.parse(data);
                   // $( '.alert-success' ).css('display','block');
                    $('#loadcat_details').append(json_x.append);
                    $('.loadpagination').html(json_x.links);
                                }
                            });
                    }else{

                        }
            }
        });
    </script>

@endsection
