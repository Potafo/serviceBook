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
                @if($mode=="status")
                    <th class="text-center">
                        Notification
                    </th>
                    <th class="text-center">
                        Display Order
                    </th>

                @endif
                <th class="text-center">
                    Status
                </th>
                <th >
                    Action
                </th>
            </tr>
            </thead>
            <tbody>
                @if(count($category)>0)
                    @foreach($category as $key=>$value)
                        <tr>
                            <td>
                                {{ $category->firstItem() + $key }}
                            </td>
                            <td>
                                {{ $value->name }}
                            </td>
                            @if($mode=="status")
                                <td class="text-center">
                                    {{ $value->notification }}
                                </td>
                                <td class="text-center">
                                    {{ $value->display_order }}
                                </td>
                                <td class="text-center">
                                    {{ $value->active }}
                                </td>
                                @else
                                <td class="text-center">
                                    {{ $value->status }}
                                </td>
                            @endif
                            <td >

                                <a href="{{ url("vendor_category_edit/".$mode."/".$value->id)  }}" >  Edit </a>
                            </td>
                        </tr>
                @endforeach

            @endif
            </tbody>
        </table>

        </div>
        <div class="card-footer py-4">
            {{ $category->links() }}
        </div>

    </div>
  </div>

</div>
<script src="{{ asset('black') }}/js/core/jquery.min.js"></script>

    <script language="JavaScript" type="text/javascript">

    </script>

@endsection
