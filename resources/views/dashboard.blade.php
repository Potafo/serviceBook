@extends('layouts.app', ['pageSlug' => 'dashboard'])
<script src="{{ asset('black') }}/js/core/jquery-3.4.1.min.js"></script>
@section('content')
<div class="card-body">
    <div class="form-row">

        @if(Session::get('logged_user_type') == "3")
            {{-- <div class="form-group col-md-4">
                {{-- <a href="{{ route('jobcard.jobcard')  }}">
                    <p>{{ __('Job Card') }}</p>
                </a>
            </div> --}}
                <div class="form-group col-md-4" >
                    <a href="{{ route('jobcard.jobcard')  }}" class="btn btn-fill btn-primary " style="float: left; ">
                        <span data-notify="icon" class="tim-icons icon-double-right"></span>
                        JobCard Page</a>
                </div>


                @if($alerttype=="red")
                    <div class="form-group col-md-4">
                        <div class="alert alert-danger alert-with-icon" style=" background-color: #b10b0b !important;">
                            <span data-notify="icon" class="tim-icons icon-bell-55"></span>
                            <span data-notify="message">{{ $pending }}</span>
                        </div>
                    </div>
                    <div class="form-group col-md-4">
                        <a href="vendor_view/{{ Session::get('logged_vendor_id') }}#renewview"  class="btn btn-fill btn-primary" style="float: right; ">Renew</a>
                    </div>
                @elseif($alerttype=="green")
                    <div class="form-group col-md-4">
                        <div class="alert alert-info alert-with-icon"   style=" background-color: #0bb11c !important;">
                            <span data-notify="icon" class="tim-icons icon-bell-55"></span>
                            <span data-notify="message">{{ $pending }}</span>
                        </div>
                    </div>
                @endif
            </div>
        @endif
    </div>

<h5 class="title">{{ $title }}</h5>
    <div class="row"  >
        @foreach($dashboard_list as $key=>$value)
            <div class="font-icon-list col-lg-2 col-md-3 col-sm-4 col-xs-6 col-xs-6">
                <div class="font-icon-detail">
                <i class="tim-icons icon-sound-wave"></i>
                <h1>{{  $value['count'] }}</h1>
                <p style="font-size: 17px;">{{  $value['name'] }}</p>
                </div>
            </div>
        @endforeach
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card card-chart">
                <div class="container">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="panel panel-default">
                                <div class="panel-heading my-2" style="color: blanchedalmond">{{ $title }}</div>
                                <div class="col-lg-8">
                                        <canvas id="userChart" class="rounded shadow"></canvas>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-12">
            <div class="card card-chart">
                <div class="container">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="panel panel-default">
                                <div class="panel-heading my-2" style="color: blanchedalmond">{{ $title }}</div>
                                <div class="col-lg-8">
                                        <canvas id="userChart1" class="rounded shadow"></canvas>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    {{-- <div class="row">
        <div class="col-lg-4">
            <div class="card card-chart">
                <div class="card-header">
                    <h5 class="card-category">Total Shipments</h5>
                    <h3 class="card-title"><i class="tim-icons icon-bell-55 text-primary"></i> 763,215</h3>
                </div>
                <div class="card-body">
                    <div class="chart-area">
                        <canvas id="chartLinePurple"></canvas>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="card card-chart">
                <div class="card-header">
                    <h5 class="card-category">Daily Sales</h5>
                    <h3 class="card-title"><i class="tim-icons icon-delivery-fast text-info"></i> 3,500€</h3>
                </div>
                <div class="card-body">
                    <div class="chart-area">
                        <canvas id="CountryChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="card card-chart">
                <div class="card-header">
                    <h5 class="card-category">Completed Tasks</h5>
                    <h3 class="card-title"><i class="tim-icons icon-send text-success"></i> 12,100K</h3>
                </div>
                <div class="card-body">
                    <div class="chart-area">
                        <canvas id="chartLineGreen"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div> --}}
</div>
@endsection
@push('js')


<script src="{{ asset('black') }}/js/plugins/chart.js"></script>

<!-- CHARTS -->


<script>
    var optionsBar = {
        responsive: true
    };

    var dataBar = {
        labels: {!!json_encode($chart->labels)!!},
        datasets: [
              {
                  label: {!! json_encode($chart->label)!!},
                  backgroundColor: {!! json_encode($chart->colours)!!} ,
                  data:  {!! json_encode($chart->dataset)!!} ,
              },
          ]
    };

    var optionsLine = {
        responsive: true
    };

    var dataLine = {
        labels: {!!json_encode($chart->labels)!!},
        datasets: [
              {
                  label: {!! json_encode($chart->label)!!},
                  backgroundColor: {!! json_encode($chart->colours)!!} ,
                  data:  {!! json_encode($chart->dataset)!!} ,
              },
          ]
    };

    function start(){
    var ctx = document.getElementById("userChart").getContext("2d");
    var BarChart = new Chart(ctx, {
          type: 'bar',
          data: dataBar,
          options: {
          scales: {
              yAxes: [{
                  ticks: {
                      beginAtZero: true,
                      callback: function(value) {if (value % 1 === 0) {return value;}}
                  },
                  scaleLabel: {
                      display: false
                  }
              }]
          },
          legend: {
              labels: {
                  // This more specific font property overrides the global property
                  fontColor: '#fff',
                  fontFamily: "'Muli', sans-serif",
                  padding: 25,
                  boxWidth: 25,
                  fontSize: 14,
              }
          },
          layout: {
              padding: {
                  left: 10,
                  right: 10,
                  top: 0,
                  bottom: 10
              }
          }
      }
    });

    var ctx2 = document.getElementById("userChart1").getContext("2d");
    var LineChart = new Chart(ctx2, {
          type: 'line',
          data: dataLine,
          options: {
          scales: {
              yAxes: [{
                  ticks: {
                      beginAtZero: true,
                      callback: function(value) {if (value % 1 === 0) {return value;}}
                  },
                  scaleLabel: {
                      display: false
                  }
              }]
          },
          legend: {
              labels: {
                  // This more specific font property overrides the global property
                  fontColor: '#fff',
                  fontFamily: "'Muli', sans-serif",
                  padding: 25,
                  boxWidth: 25,
                  fontSize: 14,
              }
          },
          layout: {
              padding: {
                  left: 10,
                  right: 10,
                  top: 0,
                  bottom: 10
              }
          }
      }
    });
}
    window.onload = start;
</script>




@endpush
