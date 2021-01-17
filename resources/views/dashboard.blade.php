@extends('layouts.app', ['pageSlug' => 'dashboard'])
<script src="{{ asset('black') }}/js/core/jquery-3.4.1.min.js"></script>
@section('content')
    <div class="row">
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
                                <div class="panel-heading my-2" style="color: blanchedalmond">Chart Demo</div>
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
                                <div class="panel-heading my-2" style="color: blanchedalmond">Chart Demo</div>
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

@endsection

{{-- <link rel="stylesheet" type="text/css" href="{{ asset('black') }}/css/material-dashboard.css"> --}}
@push('js')
    {{-- <script src="{{ asset('black') }}/js/plugins/chartjs.min.js"></script>
    <script>
        $(document).ready(function() {
          demo.initDashboardPageCharts();
        });
    </script> --}}

{{-- <script src="https://code.jquery.com/jquery-3.4.1.min.js"></script> --}}
{{-- <script src="{{ asset('black') }}/js/core/jquery-3.4.1.min.js"></script> --}}
<script src="{{ asset('black') }}/js/plugins/chart.js"></script>
{{-- <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script> --}}
{{-- <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script> --}}
{{-- <script src="https://cdn.jsdelivr.net/npm/chart.js@2.8.0"></script> --}}
<!-- CHARTS -->


<script>
    var optionsBar = {
        responsive: true
    };

    var dataBar = {
        labels: {!!json_encode($chart->labels)!!},
        datasets: [
              {
                  label: 'Status',
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
                  label: 'Status',
                  backgroundColor: {!! json_encode($chart->colours)!!} ,
                  data:  {!! json_encode($chart->dataset)!!} ,
              },
          ]
    };

    // function start(){
    //     var ctx = document.getElementById("graficoBarra").getContext("2d");
    //     var BarChart = new Chart(ctx).Bar(dataBar, optionsBar);

    //     var ctx2 = document.getElementById("graficoLinha").getContext("2d");
    //     var LineChart = new Chart(ctx2).Line(dataLine, optionsLine);
    // }
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



{{-- <script>




  var ctx = document.getElementById('userChart1').getContext('2d');
  var chart = new Chart(ctx, {
      // The type of chart we want to create bar,pie,line
      type: 'bar',
// The data for our dataset
      data: {
          labels:  {!!json_encode($chart->labels)!!} ,
          datasets: [
              {
                  label: 'Status',
                  backgroundColor: {!! json_encode($chart->colours)!!} ,
                  data:  {!! json_encode($chart->dataset)!!} ,
              },
          ]
      },
// Configuration options go here
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
</script> --}}
@endpush
