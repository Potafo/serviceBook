@extends('layouts.app', ['page' => __('Job Card Report'), 'pageSlug' => 'jobcard_report'])
<script src="{{ asset('black') }}/js/core/jquery-1.9.1.js"></script>
<link href="{{ asset('black') }}/css/bootstrap.min.css" rel="stylesheet">
<link href="{{ asset('black') }}/css/bootstrap-datepicker.css" rel="stylesheet">
<script src="{{ asset('black') }}/js/core/bootstrap-datepicker.js"></script><style>
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
                <h4 class="card-title">Job Card Report</h4>
            </div>
            <div class="col-4 text-right">
                {{-- <a class="btn btn-sm btn-primary addpackage" href="jobcard_add">Add Job Card</a> --}}
            </div>
            <div class="col-8">
                <nav aria-label="breadcrumb" role="navigation">
                    <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="home">Home</a></li>
                    <li class="breadcrumb-item "><a href="jobcard_report">JobCard Report</a></li>
                    <li class="breadcrumb-item active" aria-current="page" id="bc_current">View</li>
                    </ol>
                </nav>
            </div>
        </div>
        @include('alerts.success')
      </div>
        <div class="col-12">
            <form method="post"  action="{{ url('jobcard_history_filter') }}" autocomplete="off" id="historyfilter">
                {{ csrf_field() }}

                <input type="hidden" id="pageid" name="pageid" value="report" >
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

                        <select class="form-control{{ $errors->has('filter_status') ? ' is-invalid' : '' }} selectpicker " data-style="select-with-transition" title="Single Select" data-size="7"   name="filter_status" id="filter_status" >
                            <option value="">Select Status</option>
                            @foreach($jobcard_status as $list)
                                    <option value="{{$list->id}}" @if($filter_details['filter_status']==$list->id) selected @endif>{{$list->name}}</option>
                                @endforeach
                        </select>

                    </div>
                    <div class="form-group col-md-2">
                        <label for="exampleFormControlInput1">Products</label>

                        <select class="form-control{{ $errors->has('filter_products') ? ' is-invalid' : '' }} selectpicker " data-style="select-with-transition" title="Single Select" data-size="7"   name="filter_products" id="filter_products" >
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
            {{-- <form method='post' action='export'>
                {{ csrf_field() }}
                <input type="hidden"  id="filter_fromdate_export" name="filter_fromdate" value="{{ $filter_details['filter_fromdate'] }}" >
                <input type="hidden"  id="filter_todate_export" name="filter_todate" value="{{ $filter_details['filter_todate'] }}">
                <input type="hidden"  id="filter_globalsearch_export" name="filter_globalsearch" value="{{ $filter_details['filter_globalsearch'] }}">

                <div class="form-row">
                    <div class="form-group col-md-2">


                            <button type="submit" class="btn btn-fill btn-primary searchbutton" style="color: #87f554; cursor: pointer;"><i class='tim-icons icon-attach-87' style="color: #87f554; cursor: pointer;"></i>{{ __('Excel') }}</button>


                    </div>
                    <div class="form-group col-md-2">
                        <br/> <br/>
                        <a style="color: #ff1111; cursor: pointer;">
                            <i class='tim-icons icon-single-copy-04'> PDF Download</i>
                        </a>
                    </div>
                </div>
            </form> --}}
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
                    Customer
                  </th>
                  <th>
                   Sub Total
                  </th>
                  <th>
                    Tax Amount
                  </th>
                  <th>
                    Discount
                  </th>
                  <th>
                    Amount Received
                  </th>
              </tr>
            </thead>
            <tbody>
                <?php $billamount=0;
                      $taxamount=0;
                      $discount=0;
                      $amountrecieved=0; ?>
                @if(count($jobcard)>0)
                    @foreach($jobcard as $key=>$value)
                    <?php
                        if (strlen($value->pdtname) > 20){
                            $pdt = substr($value->pdtname, 0, 12) . '...';
                        }
                        else {
                           $pdt=$value->pdtname;
                        }
                        $billamount= intval($billamount)  + intval($value->bill_amount);
                      $taxamount=intval($taxamount)  + intval($value->tax_amount);
                      $discount=intval($discount)  + intval($value->discount_amount);
                      $amountrecieved=intval($amountrecieved)  + intval($value->received_amount);


                    ?>
                        <tr  data-id='{{ $value->id }}' style="cursor: pointer">
                            {{-- class="viewjobcards" --}}
                            <td>
                                {{ $jobcard->firstItem() + $key }}
                            </td>
                            <td>
                                {{ $value->jobcard_date }}
                            </td>
                            <td>
                                {{ $value->jobcard_number }}
                            </td>

                            <td>
                                {{ $value->custname }} - {{ $value->custmobile }}
                            </td>

                            <td>
                                {{ $value->bill_amount }}
                            </td>
                            <td>
                                {{ $value->tax_amount }}
                            </td>
                            <td>
                                {{ $value->discount_amount }}
                            </td>
                            <td>
                                {{ $value->received_amount }}
                            </td>



                        </tr>
                @endforeach

             @endif
             <tr>
                <td></td>
                <td></td>
                <td></td>
                <td></td>


                <td>{{ $billamount }}</td>
                <td>{{ $taxamount }}</td>
                <td>{{ $discount }}</td>
                <td>{{ $amountrecieved }}</td>
             </tr>
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

$(document).on('change', '#filter_fromdate', function(event) {
    $('#filter_fromdate_export').val($(this).val());
});
$(document).on('change', '#filter_todate', function(event) {
    $('#filter_todate_export').val($(this).val());
});
$(document).on('change', '#filter_globalsearch', function(event) {
    $('#filter_globalsearch_export').val($(this).val());
});
    // filter_globalsearch_export filter_globalsearch filter_fromdate_export filter_fromdate filter_todate_export filter_todate

//  setTimeout(function() {
//                $(".searchbutton").trigger('click');
//            },10);
     //load_full_list();

    //  $(document).on('click', '.searchbutton', function(event) {
    //     var registerForm = $("#historyfilter");
    //     var formData = registerForm.serialize();
    //     $.ajax({
    //         url: 'jobcard_history_filter' ,
    //         method:'post',
    //         data:formData,
    //         headers: {
    //       'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    //   },


    //         },
    //     });

    // });
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
        // $(document).on('click', '.viewjobcards', function(event) {
        //     //event.preventDefault();
        //     var jobcardid =$(this).attr('data-id');
        //     //window.location='jobcard_view/'+jobcardid;
        //     window.location='jobcard_history_view/'+jobcardid;

        // });

    });


</script>
