@extends('layouts.app', ['page' => __('Job Card Reviews'), 'pageSlug' => 'jobcard_reviews'])
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
                <h4 class="card-title">Job Card Reviews</h4>
            </div>
            <div class="col-4 text-right">
                {{-- <a class="btn btn-sm btn-primary addpackage" href="jobcard_add">Add Job Card</a> --}}
            </div>
            <div class="col-8">
                <nav aria-label="breadcrumb" role="navigation">
                    <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="home">Home</a></li>
                    <li class="breadcrumb-item "><a href="jobcard_reviews">JobCard Reviews</a></li>
                    <li class="breadcrumb-item active" aria-current="page" id="bc_current">View</li>
                    </ol>
                </nav>
            </div>
        </div>
        @include('alerts.success')
      </div>
        <div class="col-12">
            <form method="post" action="{{ route('jobcard.review_filter') }}" autocomplete="off" id="historyfilter">
                @csrf
                <input type="hidden" id="pageid" name="pageid" value="history" >
                <div class="form-row">
                    <div class="form-group col-md-2">
                        <label for="exampleFormControlInput1">From Date</label>
                        <input type="text" class="form-control date" id="filter_fromdate" name="filter_fromdate"  placeholder="From Date" value="{{ $filter_details['filter_fromdate'] }}">

                    </div>
                    <div class="form-group col-md-2">
                        <label for="exampleFormControlInput1">To Date</label>
                        <input type="text" class="form-control date" id="filter_todate" name="filter_todate"  placeholder="To Date" value="{{ $filter_details['filter_todate'] }}">

                    </div>
                    {{-- <div class="form-group col-md-2">
                        <label for="exampleFormControlInput1">Status</label>
                        {{-- <input type="text" class="form-control" id="filter_status" name="filter_status"  placeholder="Status" value="{{ old('filter_status') }}"> --}}
                       {{-- <select class="form-control{{ $errors->has('filter_status') ? ' is-invalid' : '' }} selectpicker " data-style="select-with-transition" title="Single Select" data-size="7"   name="filter_status" id="filter_status" >
                            <option value="">Select Status</option>
                            @foreach($jobcard_status as $list)
                                    <option value="{{$list->id}}" @if($filter_details['filter_status']==$list->id) selected @endif>{{$list->name}}</option>
                                @endforeach
                        </select>

                    </div> --}}
                    {{-- <div class="form-group col-md-2">
                        <label for="exampleFormControlInput1">Products</label>
                        {{-- <input type="text" class="form-control" id="filter_status" name="filter_status"  placeholder="Status" value="{{ old('filter_status') }}"> --}}
                       {{--  <select class="form-control{{ $errors->has('filter_products') ? ' is-invalid' : '' }} selectpicker " data-style="select-with-transition" title="Single Select" data-size="7"   name="filter_products" id="filter_products" >
                            <option value="">Select Products</option>
                            @foreach($products as $list)
                                    <option value="{{$list->id}}" @if($filter_details['filter_products']==$list->id) selected @endif>{{$list->name}}</option>
                                @endforeach
                        </select>

                    </div> --}}
                    <div class="form-group col-md-2">
                        <label for="exampleFormControlInput1">Search</label>
                        <input type="text" class="form-control" id="filter_globalsearch" name="filter_globalsearch"  placeholder="Search" value="{{ $filter_details['filter_globalsearch'] }}">

                    </div>
                    <div class="form-group col-md-2">

                        <br/>
                        <button type="submit" class="btn btn-fill btn-primary searchbutton">{{ __('Search') }}</button>
                    </div>
                </div>
            </form>
        </div>
      <div class="card-body" style="display: block" id="view_package">
        <div class="table-responsive">
          <table class="table tablesorter " id="">
            <thead class=" text-primary" >
              <tr>
                <th>
                  Slno
                </th>
                <th>
                    Date
                  </th>
                <th>
                  JobCard Number
                </th>

                    <th>
                    Service name
                    </th>

                <th>
                    Cust. details
                  </th>
                  <th>
                    Star Rating
                  </th>
                  <th>
                    Content
                  </th>

                  <th>
                    Status
                  </th>

              </tr>
            </thead>
            <tbody>
                @if(count($jobcard)>0)
                    @foreach($jobcard as $key=>$value)

                        <tr class="viewjobcards" data-id='{{ $value->rid }}' style="cursor: pointer">

                            <td>
                                {{ $jobcard->firstItem() + $key }}
                            </td>
                            <td>
                                {{ $value->rdate }}
                            </td>

                            <td>
                                {{ $value->jobcard_number }}
                            </td>

                            <td>
                                {{ $value->service_name }}
                            </td>
                            <td>
                                {{ $value->custname }} - {{ $value->custmobile }}
                            </td>


                            <td>
                                {{ $value->star_rating }}
                            </td>
                            <td>
                                {{ $value->review }}
                            </td>
                            <td>
                                {{ $value->review }}
                            </td>

                        </tr>
                @endforeach

             @endif
            </tbody>
          </table>

        </div>
        <div class="card-footer py-4">
            @if(count($jobcard)>0)
            {{ $jobcard->links() }}
            @endif
        </div>
      </div>


    </div>
  </div>

</div>


<div class="modal fade" id="delete_jobcards" tabindex="-1" role="dialog" aria-labelledby="smallModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-sm" role="document">
        <div class="modal-content">
            <div class="modal-body" id="smallBody">
                <div>
                    <form action="{{ route('jobcard_delete_each') }}" method="post">
                        <input type="hidden" id="referencenumber" name="referencenumber" >
                        <input type="hidden" id="customerid" name="customerid" >
                        <input type="hidden" id="referenceid" name="referenceid" >
                        <div class="modal-body">
                            @csrf

                            <div class="text-center">Are you sure you want to delete Jobcard <span id="delref"> </span>? </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn btn-danger">Yes, Delete Service</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
<script language="JavaScript" type="text/javascript">

    $(document).ready(function() {
        $('.date').datepicker({
       format: 'dd-mm-yyyy',
       todayHighlight: true,
        autoclose: true,
     });
//  setTimeout(function() {
//                $(".searchbutton").trigger('click');
//            },10);
     //load_full_list();

        $(document).on('click', '#deleteButton', function(event) {
            event.preventDefault();
            var jobcardid =$(this).attr('data-id');
            var jobcardref =$(this).attr('data-jobcardref');
            var jobcard =$(this).attr('data-jobcardnmbr');
            var cust =$(this).attr('data-custid');
            $('#referencenumber').val(jobcardref);
            $('#referenceid').val(jobcardid);
            $('#customerid').val(cust);
            $('#delref').html(jobcard);
            $.ajax({
                url: route('jobcard_delete_each')
                , beforeSend: function() {
                },
                // return the result
                success: function(result) {
                    $('#delete_services').modal("show");
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
        $(document).on('click', '.viewjobcards', function(event) {
            //event.preventDefault();
            var jobcardid =$(this).attr('data-id');
            //window.location='jobcard_view/'+jobcardid;
            window.location='jobcard_history_view/'+jobcardid;

        });

    });


</script>
