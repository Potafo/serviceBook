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
                    <li class="breadcrumb-item "><a href="jobcard">JobCard</a></li>
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
                @if(Session::get('logged_user_type') =='1')
                <th>
                  Vendor
                </th>
                @endif
                <th>
                    Name
                  </th>
                  <th>
                    Mobile
                  </th>
                  <th>
                    Product
                  </th>
                  <th>
                    Service
                  </th>
                  <th>
                    Days
                  </th>
                  <th>
                    Status
                  </th>
                {{-- <th >
                    Action
                  </th> --}}
              </tr>
            </thead>
            <tbody>
                @if(count($jobcard)>0)
                    @foreach($jobcard as $key=>$value)
                    <?php
                    //service length checking
                        if (strlen($value->sname) > 20){
                            $str = substr($value->sname, 0, 17) . '...';
                        }
                        else {
                           $str=$value->sname;
                        }
                        //product length checking
                        if (strlen($value->pdtname) > 20){
                            $pdt = substr($value->pdtname, 0, 12) . '...';
                        }
                        else {
                           $pdt=$value->pdtname;
                        }
                    //days
                        $timezone = 'ASIA/KOLKATA';
                        $date = new DateTime('now', new DateTimeZone($timezone));
                        //$package_days_count=$value->days;
                         $joined_date=date("Y-m-d",strtotime($value->created_at));
                         $current_date=date("Y-m-d");
                         $diff=(new DateTime($joined_date))->diff(new DateTime($current_date))->days;
                         $pending=$diff;//intval($package_days_count) - intval($diff);
                        if($pending==0)
                        {
                            $pending= "Today";
                        }elseif($pending==1) {
                            $pending=$pending ." Day";
                        }elseif($pending>1) {
                            $pending=$pending ." Days";
                        }

                    ?>
                        <tr>
                            <td>
                                {{ $jobcard->firstItem() + $key }}
                            </td>
                            <td>
                                {{ $value->jobcard_number }}
                            </td>
                            @if(Session::get('logged_user_type') =='1')
                            <td>
                                {{ $value->vname }}
                            </td>
                            @endif
                            <td>
                                {{ $value->custname }}
                            </td>
                            <td>
                                {{ $value->custmobile }}
                            </td>
                            <td>
                                {{ $pdt }}
                            </td>
                            <td>
                                {{ $str }}
                            </td>
                            <td>
                                {{ $pending }}
                            </td>
                            <td>
                                {{ $value->statusname }}
                            </td>
                            {{-- <td >
                                <button type="button" rel="tooltip" class="btn btn-info btn-sm btn-icon">
                                    <a href="jobcard_edit/{{ $value->jobid }}" ><i class="tim-icons icon-settings"></i></a>
                                </button>


                               </td> --}}
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
