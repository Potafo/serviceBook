@extends('layouts.app', ['page' => __('Job Card'), 'pageSlug' => 'jobcard'])
<script src="{{ asset('black') }}/js/core/jquery-3.4.1.min.js"></script>
{{-- <meta name="csrf-token" content="{{ csrf_token() }}" /> --}}
<style>
    #servicelist  tbody>tr:hover td{background: #fff  !important;}

    select > option {
        color: black;
    }
    .edit{
    width: 100%;
    height: 25px;
}
    .editMode{
    border: 1px solid black;

}

.txtedit{
    display: none;
    width: 30%;
    height: 30px;
}
        </style>
@section('content')

<script language="JavaScript" type="text/javascript">

    $(document).ready(function() {
        $('.edit').click(function(){
        $(this).addClass('editMode');

    });
    $(".edit").focusout(function(){
        //alert("dsdfsd");
        $(this).removeClass("editMode");

        var id = this.id;
        var split_id = id.split("_");
        var field_name = split_id[0];
        var edit_id = split_id[1];

        var value = $(this).text();

        $.ajax({
            url: '../edit_jobcardservice',
            method: 'post',
            data: { field:field_name, value:value, id:edit_id },

            success:function(response){//alert("dsfsd");
                var ref=$('#jobcardnumber').val();
                load_service_list(ref);
            }
        });

    });

    });
    </script>

    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    {{-- <h5 class="title">{{ __('Jobcard') }}</h5> --}}
                </div>
                <div class="col-12">
                    <nav aria-label="breadcrumb" role="navigation">
                        <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="home">Home</a></li>
                        <li class="breadcrumb-item "><a href="{{ url('jobcard') }}">Job Card</a></li>
                        <li class="breadcrumb-item active" aria-current="page" id="bc_current">Edit</li>
                        </ol>
                    </nav>
                </div>

                <div class="col-12" >
                    {{-- <div class="card-header">
                        <h5 class="title">{{ __('Details') }}</h5>
                    </div> --}}
                    <form method="post" action="{{ route('jobcard.insert') }}" autocomplete="off">
                    <div class="card-body">
                    @csrf
                    {{-- @method('put') --}}
                    <div class="alert alert-success" style="display: none">
                        <button type="button" aria-hidden="true" class="close" data-dismiss="alert" aria-label="Close">
                          <i class="tim-icons icon-simple-remove"></i>
                        </button>
                        <span>
                          <b> JobCard Successfully Added </b></span>
                      </div>
                    @include('alerts.success')
                    <input type="hidden" name="user_id" id="user_id" value="{{  Session::get('logged_user_id') }}" >
                    <input type="hidden" name="jobcardrefnumber" id="jobcardrefnumber" value="{{  Session::get('jobcard_reference') }}" >
                    <input type="hidden" name="jobcardnumber" id="jobcardnumber" value="{{ $jobcard_cust[0]->jobcard_number }}" >
                    <div class="form-row">
                        <div class="form-group col-md-3">
                            <label for="exampleFormControlInput1">Name</label>
                            <input type="text" class="form-control{{ $errors->has('jobcard_name') ? ' is-invalid' : '' }}" id="jobcard_name" name="jobcard_name" style="color: white" readonly placeholder="Name" value="{{ old('jobcard_name',$jobcard_cust[0]->name) }}">
                            @include('alerts.feedback', ['field' => 'jobcard_name'])
                        </div>
                        <div class="form-group col-md-2">
                            <label for="exampleFormControlInput1">Mobile</label>
                            <input type="text" class="form-control{{ $errors->has('jobcard_mobile') ? ' is-invalid' : '' }}" id="jobcard_mobile" name="jobcard_mobile" style="color: white" readonly placeholder="Mobile" value="{{ old('jobcard_mobile',$jobcard_cust[0]->mobile) }}">
                            @include('alerts.feedback', ['field' => 'jobcard_mobile'])
                        </div>
                        <div class="form-group col-md-2">
                            <label for="exampleFormControlInput1">Jobcard Number</label>
                            <input type="text" class="form-control{{ $errors->has('jobcard_number') ? ' is-invalid' : '' }}" id="jobcard_number" name="jobcard_number" style="color: white" readonly placeholder="Jobcard Number" value="{{ $jobcard_cust[0]->jobcard_number }}">
                            @include('alerts.feedback', ['field' => 'jobcard_number'])
                        </div>
                        <div class="form-group col-md-2">
                            <label for="exampleFormControlInput1">Product</label>
                            <input type="text" class="form-control{{ $errors->has('jobcard_pdt') ? ' is-invalid' : '' }}" id="jobcard_pdt" name="jobcard_pdt" style="color: white" readonly placeholder="Product Name" value="{{ $jobcard_cust[0]->pdtname }}">
                            @include('alerts.feedback', ['field' => 'jobcard_pdt'])
                        </div>
                        <div class="form-group col-md-2">
                            <label for="exampleFormControlInput1">Remarks</label>
                            <input type="text" class="form-control{{ $errors->has('jobcard_rmrks') ? ' is-invalid' : '' }}" id="jobcard_rmrks" name="jobcard_rmrks" style="color: white" readonly placeholder="Remarks" value="{{ $jobcard_cust[0]->remarks }}">
                            @include('alerts.feedback', ['field' => 'jobcard_rmrks'])
                        </div>
                    </div>
                  @if(Session::get('logged_user_type') =='1')
                    <div class="form-group  col-md-2">
                    <label>{{ __('Vendors') }}</label>
                    <select class="form-control{{ $errors->has('vendor_name') ? ' is-invalid' : '' }} selectpicker " data-style="select-with-transition" title="Single Select" data-size="7" placeholder="{{ __('Vendors') }}" name="vendor_name" id="vendor_name" value="{{ old('vendor_name') }}">
                        <option value="">Select Vendor</option>
                        @foreach($vendor_list as $list)
                                <option value="{{$list->id}}">{{$list->name}}</option>
                            @endforeach
                    </select>
                    @include('alerts.feedback', ['field' => 'vendor_name'])
                    </div>
                    @endif
                    <div class="form-row">
                              <div class="form-group col-md-4">
                                <div class="col-4 text-right">
                                    <button type="button" class="btn btn-primary" data-toggle='modal' data-target='#productsInsert' data-type='update' data-pdtservice='{{ $servicelist[0]->productservice }}' data-genservice='{{ $servicelist[0]->generalservice }}' data-pdtid='{{ $servicelist[0]->pid }}' data-jobcardref ='{{ Session::get('jobcard_reference') }}' data-jobcardnmbr='{{ $servicelist[0]->jobcard_number }}' data-id='{{ $id }}' >
                                       Add Services
                                    </button>
                               </div>
                             </div>
                             @if(Session::get('Parts_status') == 'Y')
                             <div class="form-group col-md-4">
                                <div class="col-4 text-right">
                                    <button type="button" class="btn btn-primary" data-toggle='modal' data-target='#partsSelect' data-type='update'  data-pdtid='{{ $servicelist[0]->pid }}' data-jobcardref ='{{ Session::get('jobcard_reference') }}' data-jobcardnmbr='{{ $servicelist[0]->jobcard_number }}' data-id='{{ $id }}' >
                                       Add Parts
                                    </button>
                               </div>
                             </div>
                             @endif
                </div>
                {{-- <div class="form-group">
                    <label>{{ __('Products') }}</label>
                        <button type="button" class="btn btn-primary" data-type='add' data-toggle="modal" data-target="#productsInsert" data-jobcardref ="{{   Session::get('jobcard_reference') }}" >
                            <i class="tim-icons icon-simple-add"></i>  Add Products
                          </button>
                 </div> --}}

                 {{-- <div class="form-group">
                    <div class="col-4 text-right">
                       <button type="submit" class="btn btn-fill btn-primary">{{ __('Submit') }}</button>
                   </div>
                 </div> --}}
                    </div>
                </form>


                <table class="table tablesorter " id="tableservicelist">
                    <thead class=" text-primary">
                      <tr>
                        <th>
                          Slno
                        </th>
                        {{-- <th>
                          JobCard Number
                        </th>

                        <th>
                            Product
                          </th> --}}
                          <th>
                            Service
                          </th>
                        <th >
                            Price
                          </th>
                          @if(Session::get('tax_enabled')=='Y' )
                          <th>
                            Tax %
                          </th>


                          @endif
                          <th>
                            Service Remarks
                          </th>
                          <th >
                            Total
                          </th>
                      </tr>
                    </thead>
                    <tbody id="service_full_list">
                        {{-- id="service_full_list" --}}
                        {{-- @if(count($servicelist)>0)
            @foreach($servicelist as $value)

                        <tr>
                            <td>
                                1
                            </td>
                            <td>
                                {{ $value->jobcard_number }}
                            </td>
                            <td>
                                 {{ $value->pdtname }}
                            </td>
                            <td>
                               {{ $value->sname }}
                            </td>
                            <td>
                    <a  data-toggle='modal' data-target='#productsInsert' data-type='update' data-pdtservice='{{ $value->productservice }}' data-genservice='{{ $value->generalservice }}' data-pdtid='{{ $value->pid }}' data-jobcardref ='{{ Session::get('jobcard_reference') }}' data-jobcardnmbr='{{ $value->jobcard_number }}' data-id='{{ $value->id }}' >
                            <i class='tim-icons icon-pencil'></i>
                                      </a>

                                <a data-toggle='modal' id='deleteButton' data-target='#delete_services' data-jobcardnmbr='{{ $value->jobcard_number }}' data-id='{{ $value->id }}' title='Delete Service'>
                                <i class='tim-icons icon-trash-simple'></i>
                            </a>
                                 </td>

                            </tr>
                            @endforeach
                            @endif --}}

                    </tbody>
                  </table>
                </div>
                <div class="card-footer py-4 loadpagination">
                </div>

                <div class="col-12" >
                    <div class="card-body">
                    <form method="get"  action="../jobcard_updatestatus" autocomplete="off" id="updatestatusform">
                        @csrf
                        {{-- @method('put') --}}
                        <div class="form-row">
                            <input type="hidden" name="jobcardnumber_up" id="jobcardnumber_up" value="{{ $jobcard_cust[0]->jobcard_reference }}" >
                            <input type="hidden" name="jobcardendingstatus" id="jobcardendingstatus"  >
                            <div class="form-group col-md-4" >
                                <label for="exampleFormControlInput1">Current Status</label>
                                <input type="text" class="form-control" id="jobcard_crstatus" name="jobcard_crstatus" style="color: white" readonly placeholder="Name" value="{{ $vendor_current_status[0]->stname }}">
                            </div>
                            <div class="form-group col-md-4" >
                                <label for="exampleFormControlInput1">Change to</label>
                                <select class="form-control{{ $errors->has('vendor_status') ? ' is-invalid' : '' }} selectpicker " data-style="select-with-transition" title="Single Select" data-size="7" placeholder="{{ __('Status') }}" name="vendor_status" id="vendor_status" value="{{ old('vendor_status') }}">
                                    <option value="">Select Status</option>
                                        @foreach($vendor_status as $list)
                                            <option value="{{$list->id}}" endingstatus="{{  $list->ending_status }}" >{{$list->name}}</option>
                                        @endforeach
                                </select>
                            </div>
                            <br/>

                        {{-- <span style="display: none" id="finish_jobcard" class="col-md-12"> --}}

                            <input type="hidden" class="form-control" id="tax_amount" name="tax_amount" >
                            <div class="form-group col-md-6 finish_jobcard" style="display: none" >
                                <label for="exampleFormControlInput1">Bill Amount</label>
                                <input type="text" class="form-control" id="bill_amount" name="bill_amount" style="color: white" readonly   placeholder="Bill Amount"  >
                            </div>
                            <div class="form-group col-md-6 finish_jobcard" style="display: none">
                                <label for="exampleFormControlInput1">Discount Amount</label>
                                <input type="text" class="form-control" id="discount_amount" name="discount_amount"   placeholder="Discount Amount"  >
                            </div>
                            <div class="form-group col-md-6 finish_jobcard" style="display: none">
                                <label for="exampleFormControlInput1">Received Amount</label>
                                <input type="text" class="form-control" id="received_amount" name="received_amount"   placeholder="Received Amount"  >
                            </div>
                        {{-- </span> --}}
                        <br/>
                        <div class="form-group col-md-4" >
                            {{-- <label for="exampleFormControlInput1">Update</label> --}}
                            <button type="submit" id="updatestatus" class="btn btn-fill btn-primary">{{ __('Update Status') }}</button>

                        </div>

                        </div>
                    </form>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <div class="modal fade " id="productsInsert" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
          <div class="modal-content">
            <div class="modal-header">
              <h1 class="modal-title" id="exampleModalLabel" style="    text-align: center !important;color: purple;margin-left: 28%;
          "><span> Select Services</span></h1>
          {{--  id="type_title" --}}
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <form method="post" action="" autocomplete="off" id="jobcardcreate">
                @csrf
                <div class="modal-body">
                    <div class="alert alert-danger" style="display: none">
                        <button type="button" aria-hidden="true" class="close" data-dismiss="alert" aria-label="Close">
                          <i class="tim-icons icon-simple-remove"></i>
                        </button>
                        <span>
                          <b> Warning - </b> Select any service</span>
                      </div>
                    <input type="hidden" name="jobcardnumber_ref" id="jobcardnumber_ref"  >
                    <input type="hidden" name="jobcardnumber_update" id="jobcardnumber_update"  >
                    <input type="hidden" name="jobcardid_update" id="jobcardid_update"  >
                    <div class="form-group" id="product_list_div" style="display: none">
                        <label>{{ __('Products') }}</label>
                        <select  class="  productstyle form-control"  title="Single Select" data-size="7" placeholder="{{ __('Products') }}" name="product_list" id="product_list"  style="color: black;"  value="{{ old('product_list') }}">
                            <option value="">Select Products</option>
                            @foreach($products as $list)
                                <option value="{{$list->id}}">{{$list->name}}</option>
                            @endforeach
                        </select>
                        @include('alerts.feedback', ['field' => 'product_list'])
                    </div>
                <div >
                    {{-- id="servicelist" style="display: none" --}}
                    <div class="form-group" >
                        <label>{{ __('General Service ') }}</label><br>
                        <table >
                        <?php $i=0; ?>
                        @foreach($general_service as $list)
                            <?php $i++; ?>
                            @if($i==1)
                                <tr >
                            @endif
                                <td width="40%">
                                    <div class="form-check form-check-inline">
                                        <label class="form-check-label">
                                            <input class="form-check-input" type="checkbox" name="service[]" id="inlineCheckbox_gs{{$list->id}}" value="{{$list->id}}" @if(in_array($list->id,$serviceids)) checked @endif > {{$list->name}}
                                            <span class="form-check-sign"></span>
                                        </label>
                                    </div>
                                </td>
                                @if($i%3 == 0)
                                    </tr> <tr>
                                @endif
                        @endforeach
                        </table>
                        @include('alerts.feedback', ['field' => 'generalservice'])
                    </div>
                    <br>
                    <div class="form-group" >
                        <label>{{ __('Product Service ') }}</label><br>
                        <table >
                        <?php $i=0; ?>
                        @foreach($product_service as $list)
                        <?php $i++; ?>
                            @if($i==1 )
                                <tr>
                            @endif
                                <td width="40%">
                                    <div class="form-check form-check-inline">
                                        <label class="form-check-label">
                                            <input class="form-check-input" type="checkbox"  name="service[]" id="inlineCheckbox_ps{{$list->id}}" value="{{$list->id}}" @if(in_array($list->id,$serviceids)) checked @endif> {{$list->name}}
                                            <span class="form-check-sign"></span>
                                        </label>
                                    </div>
                                </td>
                            @if($i%3 == 0)
                                </tr> <tr>
                            @endif
                        @endforeach
                        </table>
                        @include('alerts.feedback', ['field' => 'productservice'])
                    </div>
                </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="button" id="submitForm" class="btn btn-primary" style="display: none">Save </button>
                    <button type="button" id="updateForm" class="btn btn-primary" style="display: none">Update </button>
                </div>
            </form>

          </div>
        </div>
    </div>





    <div class="modal fade " id="partsSelect" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
          <div class="modal-content">
            <div class="modal-header">
              <h1 class="modal-title" id="exampleModalLabel" style="    text-align: center !important;color: purple;margin-left: 28%;
          "><span> Select Parts</span></h1>
          {{--  id="type_title" --}}
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <form method="post" action="" autocomplete="off" id="partsinsert">
                @csrf
                <div class="modal-body">
                    <div class="alert alert-danger" style="display: none">
                        <button type="button" aria-hidden="true" class="close" data-dismiss="alert" aria-label="Close">
                          <i class="tim-icons icon-simple-remove"></i>
                        </button>
                        <span>
                          <b> Warning - </b> Select any parts</span>
                      </div>
                      <input type="hidden" name="jobcardref_parts" id="jobcardref_parts"  >
                      <input type="hidden" name="jobcardnumber_ref" id="jobcardnumber_ref"  >
                    {{--
                    <input type="hidden" name="jobcardnumber_update" id="jobcardnumber_update"  >
                    <input type="hidden" name="jobcardid_update" id="jobcardid_update"  > --}}
                    <div class="form-group"  >
                        <label>{{ __('Parts') }}</label>
                        <select  class="form-control"  title="Single Select" data-size="7" placeholder="{{ __('Parts') }}" name="parts_list" id="parts_list"  style="color: black;"  value="{{ old('parts_list') }}">
                            <option value="">Select parts</option>
                            @foreach($vendor_partslist as $list)
                                <option value="{{$list->id}}" jobcardref="{{ $jobcard_cust[0]->jobcard_number }}"  fieldtext="{{$list->name}}" price="{{$list->actual_price}}">{{$list->name}}</option>
                            @endforeach
                        </select>
                        @include('alerts.feedback', ['field' => 'parts_list'])
                    </div>

                    <div class="form-group ">
                        <label for="exampleFormControlInput1">Name</label>
                        <input type="text" class="form-control{{ $errors->has('parts_name') ? ' is-invalid' : '' }}" id="parts_name" name="parts_name" style="color: black;"  placeholder="Name" >
                        @include('alerts.feedback', ['field' => 'parts_name'])
                    </div>
                    <div class="form-group ">
                        <label for="exampleFormControlInput1">Price</label>
                        <input type="text" class="form-control{{ $errors->has('parts_price') ? ' is-invalid' : '' }}" id="parts_price" name="parts_price" style="color: black;"  placeholder="Price" >
                        @include('alerts.feedback', ['field' => 'parts_price'])
                    </div>
                <div >


                </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="button" id="submitParts" class="btn btn-primary" style="display: none">Save </button>
                    <button type="button" id="updateParts" class="btn btn-primary" style="display: none">Update </button>
                </div>
            </form>

          </div>
        </div>
    </div>




    <div class="modal fade" id="delete_services" tabindex="-1" role="dialog" aria-labelledby="smallModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-sm" role="document">
            <div class="modal-content">
                <div class="modal-body" id="smallBody">
                    <div>
                        <form action="{{ route('jobcard_delete') }}" method="post">
                            <input type="hidden" id="jobcardnumber" name="jobcardnumber" >
                            <input type="hidden" id="referencenumber" name="referencenumber" >
                            <input type="hidden" id="referenceid" name="referenceid" >
                            <input type="hidden" id="fromeditpage" name="fromeditpage" value="1" >
                            <div class="modal-body">
                                @csrf

                                <div class="text-center">Are you sure you want to delete <span id="delref"> </span>? </div>
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

    $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });





    // setTimeout(function() {
    //     $("table#tableservicelist  .loadeditpage").trigger('click');
    // },10);


    var ref=$('#jobcardnumber').val();
    load_service_list(ref);
            $('select[name="vendor_name"]').on('change', function() {
                var vendor_id = $(this).val();
                    if(vendor_id) {
                        var data={"vendor_id":vendor_id};
                        $.ajax({
                            method: "post",
                            url : "api/product_list",
                            data : data,
                            cache : false,
                            crossDomain : true,
                            async : false,
                            dataType :'text',
                            success : function(data)
                            {
                            $('select[name="product_list"]').empty();
                                $('#product_list').html(data);


                                }
                            });
                    }else{
                        $('select[name="product_list"]').empty();
                        }
            });

            $('select[name="product_list"]').on('change', function() {
                var product_id = $(this).val();
                var pservice='';
                var gservice='';
                    if(product_id) {
                        var data={"product_id":product_id,"pservice":pservice,"gservice":gservice};
                        $.ajax({
                            method: "post",
                            url : "api/service_list",
                            data : data,
                            cache : false,
                            crossDomain : true,
                            async : false,
                            dataType :'text',
                            success : function(data)
                            {
                                $('#servicelist').css('display','block');
                                $('#servicelist').html(data);
                                }
                            });
                    }else{
                        //$('select[name="product_list"]').empty();
                        }
            });


            $('select[name="vendor_status"]').on('change', function() {
                var element = $(this).find('option:selected');
                var endstatus = element.attr("endingstatus");
                if(endstatus=='1')
                {
                    $('.finish_jobcard').css('display','block');

                }
                    $('#jobcardendingstatus').val(endstatus);

            });
            $('#received_amount').on('change', function() {
                var received=parseFloat($(this).val());
                var bill_amount=parseFloat($('#bill_amount').val());
                var disc=parseInt(received -  bill_amount) ;
                if(disc < 0)
                {
                    //disc.replace('-', '');
                    $('#discount_amount').val(Math.abs(disc));
                }

            });
           // parts_list part_name part_price

           $('select[name="parts_list"]').on('change', function() {
                var element = $(this).find('option:selected');
                var serv_name = element.attr("fieldtext");
                var serv_price = element.attr("price");
                var jobcard=element.attr("jobcardref");//alert(jobcard);
                $('#parts_name').val(serv_name);
                $('#parts_price').val(serv_price);
                $('#jobcardref_parts').val(jobcard);

                $('#submitParts').css('display','block');

            });
            $(document).on('click', '#submitParts', function(){
                var ref=$('#jobcardref_parts').val();
                var registerForm = $("#partsinsert");
                registerForm.find('#jobcardnumber_ref').val(ref);
                var formData = registerForm.serialize();

                $.ajax({
                    url: '../jobcard_partsinsert' ,
                    method:'post',
                    data:formData,
                    success:function(data) {//alert(data);

                            $( '.alert-success' ).show().delay(1000).hide('slow');
                            $('#partsSelect').modal('hide');
                        load_service_list(ref);
location.reload();


                    },
                });


            });
//jobcardref jobcardnmbr
//$.noConflict();
            $('#productsInsert').on('show.bs.modal',function(event){
                //event.preventDefault();
                var button =$(event.relatedTarget)
                var id = button.data('jobcardref');
                var jnumber = button.data('jobcardnmbr');
                var type = button.data('type');
                var modal=$(this)
                modal.find('.modal-body #jobcardnumber_ref').val(id);
//alert(type);
                if(type=="add")
                {
                    $(":checkbox"). attr("checked", false);
                    $('#product_list').val("");
                    $('#type_title').html("Add Products");
                    $('#servicelist').css('display','none');
                    $('#submitForm').css('display','block');
                    $('#updateForm').css('display','none');
                }
                else if(type=="update")
                {

                    var pid = button.data('pdtid');
                    var pservice = button.data('pdtservice');
                    var gservice = button.data('genservice');
                    var jobcarnmbr = button.data('jobcardnmbr');
                    var id = button.data('id');
                modal.find('.modal-body #product_list').val(pid);
                modal.find('.modal-body #jobcardnumber_update').val(jobcarnmbr);
                modal.find('.modal-body #jobcardid_update').val(id);
                modal.find('.modal-body #product_list').attr("style", "pointer-events: none;");
                modal.find('.modal-body #product_list').css("color", "black");
                var product=$( "#product_list option:selected" ).text();
                $('#type_title').html(product+" - Update");
                var data={"product_id":pid,"pservice":pservice,"gservice":gservice};
                //$('#servicelist').css('display','block');
                             //   $('#servicelist').html(result);
                        $.ajax({
                            type: "post",
                           // url : "api/service_list",
                            data : data,
                            cache : false,
                            crossDomain : true,
                            async : false,
                            dataType :'text',
                            url: "../api/service_list",
                            success : function(result)
                            {
                                $('#servicelist').css('display','block');
                                $('#servicelist').html(result);
                                }
                            }).fail(function(jqXHR, textStatus, error){
                                alert(jqXHR.responseText);
                            });
                            //  headers: {
                            // 'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                            //     },

                //$('#servicelist').css('display','block');
                    $('#updateForm').css('display','block');
                    $('#submitForm').css('display','none');

                }

            });
        });
    // display a modal (small modal)
    $(document).on('click', '#deleteButton', function(event) {
        //event.preventDefault();
        var jobcardid =$(this).attr('data-id');
        var jobcardnmbr =$(this).attr('data-jobcardnmbr');
        var jobcardrefnmbr =$(this).attr('data-jobcardrefnmbr');//alert(jobcardref);
        $('#referencenumber').val(jobcardrefnmbr);
        $('#jobcardnumber').val(jobcardnmbr);
        $('#referenceid').val(jobcardid);
        //jobcardnumber referencenumber
        //$('#delref').html(jobcardref);
        $('#delete_services').modal("show");
        // $.ajax({
        //     url: route('jobcard_delete')
        //     , beforeSend: function() {
        //     },
        //     // return the result
        //     success: function(result) {
        //         $('#delete_services').modal("show");
        //     }
        //     , complete: function() {
        //     }
        //     , error: function(jqXHR, testStatus, error) {
        //         console.log(error);
        //         alert("Page " + href + " cannot open. Error:" + error);
        //         $('#loader').hide();
        //     }
        //     , timeout: 10000
        // })
    });
    $(document).on('click', '#submitForm', function(){alert("sdfsdf");
        var registerForm = $("#jobcardcreate");
        var formData = registerForm.serialize();
        var ref=$('#jobcardnumber_ref').val();
        $.ajax({
            url: 'jobcard_serviceinsert' ,
            method:'post',
            data:formData,
            success:function(data) {
                if(data.errors) {
                    if(data.errors.product_list){
                        $( '#product_list' ).addClass( ' is-invalid' );
                    }else{
                        $( '#product_list' ).removeClass( ' is-invalid' );
                    }
                    // if(data.errors.generalservice || data.errors.productservice){
                    //     $( '.alert-danger' ).css('display','block');
                    // }else{
                    //     $( '.alert-danger' ).css('display','none');
                    // }

                }else{
                    $( '.alert-success' ).show().delay(1000).hide('slow');
                    $('#productsInsert').modal('hide');
                   load_service_list(ref);
                }


            },
        });


    });


    $(document).on('click', '#updateForm', function(){
        var registerForm = $("#jobcardcreate");
        var formData = registerForm.serialize();
        var ref=$('#jobcardnumber_ref').val();
        var ref1=$('#jobcardnumber_update').val();
        $.ajax({
            url: '../jobcard_serviceupdate' ,
            method:'post',
            data:formData,
            success:function(data) {
               // console.log(data);
                if(data.errors) {
                    if(data.errors.product_list){
                        $( '#product_list' ).addClass( ' is-invalid' );
                    }else{
                        $( '#product_list' ).removeClass( ' is-invalid' );
                    }
                    // if(data.errors.generalservice || data.errors.productservice){
                    //     $( '.alert-danger' ).css('display','block');
                    // }else{
                    //     $( '.alert-danger' ).css('display','none');
                    // }

                }else{
                    $( '.alert-success' ).show().delay(1000).hide('slow');
                    $('#productsInsert').modal('hide');
                   // location.reload();
                   load_service_list(ref);
                }


            },
        });

    });

    function load_service_list(ref){
            var data={'ref':ref};
//alert(ref);
            $('#service_full_list').html('');
            $.ajax({
                url: '../jobcard_servicelist_edit',
                method:'get',
                data:data,
                cache : false,
                crossDomain : true,
                async : false,
                dataType :'text',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success:function(data) {
                    var json_x= JSON.parse(data);//alert(json_x.append);
                   // $( '.alert-success' ).css('display','block');
                    $('#service_full_list').append(json_x.append);
                    $('.loadpagination').html(json_x.links);
                    $('#bill_amount').val(json_x.final);
                    $('#tax_amount').val(json_x.tax_total);
                },
            });

        }
</script>
