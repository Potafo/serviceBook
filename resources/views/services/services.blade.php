@extends('layouts.app', ['page' => __('Services'), 'pageSlug' => 'services'])
<script src="{{ asset('black') }}/js/core/jquery-3.4.1.min.js"></script>
@section('content')
<div class="row">
  <div class="col-md-12">
    <div class="card ">
      <div class="card-header">
        <div class="row">
            <div class="col-8">
                <h4 class="card-title">Services List</h4>
            </div>
            <div class="col-4 text-right">
                <a class="btn btn-sm btn-primary addpackage" href="services_add">Add Services</a>
            </div>
            <div class="col-8">
                <nav aria-label="breadcrumb" role="navigation">
                    <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="home">Home</a></li>
                    <li class="breadcrumb-item "><a href="services">Services</a></li>
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
                @if(Session::get('logged_user_type') =='1')
                <th>
                    Vendor
                  </th>
                @endif
                <th>
                    Type
                  </th>
                <th >
                    Product
                  </th>
              </tr>
            </thead>
            <tbody>
                @if(count($services)>0)
                    @foreach($services as $key=>$value)
                        <tr>
                            <td>
                                {{ $services->firstItem() + $key }}
                            </td>

                            <td>
                                {{ $value->sername }}
                            </td>
                            @if(Session::get('logged_user_type') =='1')
                            <td>
                                {{ $value->vname }}
                            </td>
                            @endif
                            <td>

                                {{ $value->sname }}

                            </td>
                             <td>

                                {{ $value->pdtname }}

                            </td>
                            {{-- <td >
                                <button type="button" rel="tooltip" class="btn btn-info btn-sm btn-icon">
                                    <a href="products_edit/{{ $value->id }}" ><i class="tim-icons icon-settings"></i></a>
                                </button>


                               </td> --}}
                        </tr>
                @endforeach

             @endif
            </tbody>
          </table>

        </div>
        <div class="card-footer py-4">
            @if(count($services)>0)
            {{ $services->links() }}
            @endif
        </div>
      </div>


    </div>
  </div>

</div>

@endsection
