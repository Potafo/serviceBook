@extends('layouts.app', ['page' => __('Job Card'), 'pageSlug' => 'jobcard'])

@section('content')
<div class="row">
  <div class="col-md-12">
    <div class="card ">
      <div class="card-header">
        <div class="row">




            <div class="col-8">
                <h4 class="card-title">Job Card List</h4>
            </div>
            <div class="col-4 text-right">
                <a class="btn btn-sm btn-primary addpackage" href="jobcard_add">Add Job Card</a>
            </div>
            <div class="col-8">
                <nav aria-label="breadcrumb" role="navigation">
                    <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="home">Home</a></li>
                    <li class="breadcrumb-item "><a href="jobacrd">JobCard</a></li>
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
                  JobCard Number
                </th>
                <th>
                  Vendor
                </th>
                <th>
                    Product
                  </th>

                <th >
                    Action
                  </th>
              </tr>
            </thead>
            <tbody>
                @if(count($jobcard)>0)
                    @foreach($jobcard as $key=>$value)
                        <tr>
                            <td>
                                {{ $jobcard->firstItem() + $key }}
                            </td>
                            <td>
                                {{ $value->jobcard_number }}
                            </td>
                            <td>
                                {{ $value->pdtname }}
                            </td>
                            <td>
                                {{ $value->vname }}
                            </td>

                            <td >
                                <button type="button" rel="tooltip" class="btn btn-info btn-sm btn-icon">
                                    <a href="jobcard_edit/{{ $value->jobid }}" ><i class="tim-icons icon-settings"></i></a>
                                </button>


                               </td>
                        </tr>
                @endforeach

             @endif
            </tbody>
          </table>

        </div>
        <div class="card-footer py-4">
            {{ $jobcard->links() }}
        </div>
      </div>


    </div>
  </div>

</div>

@endsection
