@extends('layouts.app', ['page' => __('Packages'), 'pageSlug' => 'packages'])
<script src="{{ asset('black') }}/js/core/jquery-3.4.1.min.js"></script>
@section('content')
<div class="row">
  <div class="col-md-12">
    <div class="card ">
      <div class="card-header">
        <div class="row">




            <div class="col-8">
                <h4 class="card-title">Package List</h4>
            </div>
            <div class="col-4 text-right">
                <a class="btn btn-sm btn-primary addpackage" href="package_add">Add Package</a>
            </div>
            <div class="col-8">
                <nav aria-label="breadcrumb" role="navigation">
                    <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="home">Home</a></li>
                    <li class="breadcrumb-item "><a href="packages">Packages</a></li>
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
      <div class="card-body" style="display: block" id="view_package">
        <div class="table-responsive">
          <table class="table tablesorter " id="">
            <thead class=" text-primary">
              <tr>
                <th>
                  Slno
                </th>
                <th>
                  Type
                </th>
                <th>
                  Days
                </th>
                <th>
                    Amount
                  </th>
                <th class="text-center">
                  Status
                </th>
                <th >
                    Action
                  </th>
              </tr>
            </thead>
            <tbody>
                @if(count($package)>0)
                    @foreach($package as $key=>$value)
                        <tr>
                            <td>
                                {{ $package->firstItem() + $key }}
                            </td>
                            <td>
                                {{ $value->type }}
                            </td>
                            <td>
                                {{ $value->days }}
                            </td>
                            <td>
                                {{ $value->amount }}
                            </td>
                            <td class="text-center">
                                {{ $value->status }}
                            </td>
                            <td >

                                <a href="package_edit/{{ $value->id }}" >  Edit </a>
                               </td>
                        </tr>
                @endforeach

             @endif
            </tbody>
          </table>

        </div>
        <div class="card-footer py-4">
            {{ $package->links() }}
        </div>
      </div>


    </div>
  </div>

</div>

@endsection
