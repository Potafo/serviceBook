@extends('layouts.app', ['page' => __('User Profile'), 'pageSlug' => 'vendors'])
<style>
    select > option {
        color: black;
    }
        </style>
@section('content')
    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h5 class="title">{{ __('Details') }}</h5>
                </div>
                <div class="col-8">
                    <nav aria-label="breadcrumb" role="navigation">
                        <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="home">Home</a></li>
                        <li class="breadcrumb-item "><a href="../vendors">Vendors</a></li>
                        <li class="breadcrumb-item active" aria-current="page" id="bc_current">View</li>
                        </ol>
                    </nav>
                </div>

                <form method="post" action="{{ route('profile.update') }}" autocomplete="off">
                    @include('alerts.success')

                    <div class="card-body">

                            <div class="form-group">
                                <label>{{ __('Refferal By') }} : </label>
                                <label>{{ $vendor[0]->refferal_by }}</label>
                            </div>

                            <div class="form-group">
                                <label>{{ __('Email') }} : </label>
                                <label>{{ $vendor[0]->email }}</label>
                            </div>
                            <div class="form-group">
                                <label>{{ __('Website') }} : </label>
                                <label>{{ $vendor[0]->website }}</label>
                            </div>
                            <div class="form-group">
                                <label>{{ __('Last Login') }} : </label>
                                <label>{{ date("d-m-Y H:i:s",strtotime($userlogin[0]->logintime))  }}</label>
                            </div>
                    </div>
                    <div class="card-footer" style="display: none">
                        <button type="submit" class="btn btn-fill btn-primary">{{ __('Save') }}</button>
                    </div>
                </form>
            </div>



            <div class="card">
                <div class="card-header">
                    <a href="#renewview" class="btn btn-fill btn-primary" style="float: right; ">Renew</a>
                <div>
                    <h5 class="title">{{ __('Package Details') }}</h5>
                </div>

                </div>

                    <div class="card-body">

                        <div class="form-group">
                            <table class="table tablesorter " id="">
                                <thead class=" text-primary">
                                    <th>Slno</th>
                                    <th>Package Name</th>
                                    <th>Renewed Date</th>
                                    <th>Amount</th>
                                </thead>
                                <tr>
                                    <td>1</td>
                                    <td>{{ $vendor[0]->type }}</td>
                                    <td>{{ date("d-m-Y",strtotime($vendor[0]->joined_on)) }}</td>
                                    <td>{{ $vendor[0]->amount }}</td>
                                </tr>
                                @if(count($renewal)>0)
                                <?php $i=2; ?>
                                @foreach($renewal as $key=>$value)
                                <tr>
                                    <td>{{ $i++  }}</td>
                                    <td>{{ $value->type }}</td>
                                    <td>{{ date("d-m-Y",strtotime($value->renewal_date)) }}</td>
                                    <td>{{ $value->amount_paid }}</td>
                                </tr>
                                @endforeach
                                @endif

                            </table>
                        </div>
                        <div class="card-footer py-4">
                            {{ $renewal->render() }}
                        </div>

                    </div>


            </div>
            <div class="card" id="renewview">
                <div class="card-header">
                    <h5 class="title">{{ __('Renewal') }}</h5>
                </div>
                <form method="post" action="{{ route('vendors.renew') }}" autocomplete="off">
                    @csrf
                    @method('put')
                    <div class="card-body">
                    <input type="hidden" id="vendor_id" name="vendor_id" value="{{ $id }}">
                            <div class="form-group">
                                <label>{{ __('Package Name') }} : </label>
                                <label><select class="form-control selectpicker " data-style="select-with-transition" title="Single Select" data-size="7" placeholder="{{ __('Type') }}" name="package_to_renew" id="package_to_renew">
                                    <option value="">Select Package</option>
                                    @foreach($packagetype as $list)
                                        <option value="{{$list->id}}" days="{{ $list->days }}" amount="{{ $list->amount }}" > {{$list->type}}</option>
                                    @endforeach
                            </select></label>
                            </div>

                            <div class="form-group">
                                <label>{{ __('Days') }} : </label>
                                <label><input type="text" id="pack_days_renew" name="pack_days_renew" readonly></label>
                            </div>
                            <div class="form-group">
                                <label>{{ __('Amount') }} : </label>
                                <label><input type="text" id="pack_amount_renew" name="pack_amount_renew" readonly></label>
                            </div>
                            <div class="form-group">
                                <label>{{ __('Next Renewal') }} : </label>
                                <label><input type="text" id="next_renewal" name="next_renewal" readonly></label>
                            </div>
                    </div>
                    <div class="card-footer" >
                        <button type="submit" class="btn btn-fill btn-primary">{{ __('Renew') }}</button>
                    </div>
                </form>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card card-user">
                <div class="card-body">
                    <p class="card-text">
                        <div class="author">
                            <div class="block block-one"></div>
                            <div class="block block-two"></div>
                            <div class="block block-three"></div>
                            <div class="block block-four"></div>
                            <a href="#">
                                <img class="avatar" src="{{ asset('black') }}/img/bg5.jpg" alt="">
                                <h5 class="title">{{ $vendor[0]->name }}</h5>
                            </a>
                            <p class="description">
                                {{ $vendor[0]->contact_number }}
                            </p>
                        </div>
                    </p>
                    <div class="card-description">
                        {{ $vendor[0]->description }}
                    </div>
                </div>
               <!-- <div class="card-footer">
                    <div class="button-container">
                        <button class="btn btn-icon btn-round btn-facebook">
                            <i class="fab fa-facebook"></i>
                        </button>
                        <button class="btn btn-icon btn-round btn-twitter">
                            <i class="fab fa-twitter"></i>
                        </button>
                        <button class="btn btn-icon btn-round btn-google">
                            <i class="fab fa-google-plus"></i>
                        </button>
                    </div>
                </div>-->
            </div>
        </div>
    </div>
    <script src="{{ asset('black') }}/js/jquery.min.js"></script>

    <script language="JavaScript" type="text/javascript">
        $(document).ready(function(){
            $("#package_to_renew").change(function(e){
                if($(this).find(':selected').val() != "")
                {
                    var dayscount=$(this).find(':selected').attr('days');
                    var amountcount=$(this).find(':selected').attr('amount');
                    $('#pack_days_renew').val(dayscount);
                    $('#pack_amount_renew').val(amountcount);
                    $('#next_renewal').val(calduedate(dayscount));
                }else{
                    $('#pack_days_renew').val("");
                    $('#pack_amount_renew').val("");
                    $('#next_renewal').val("");
                }



            });


            function calduedate(ndays){

                var newdt = new Date(); var chrday; var chrmnth;
                newdt.setDate(newdt.getDate() + parseInt(ndays));

                var newdate = newdt.getFullYear();

                if(newdt.getDate() < 10){
                    chrday =   '0'+newdt.getDate()  ;
                }else{
                    chrday =  newdt.getDate()  ;
                }


                var mth=newdt.getMonth()+1;
                if(mth < 10){
                    chrmnth = '0'+ mth  ;
                }else{
                    chrmnth =  mth  ;
                }

                var newdate_final= chrday +'-'+chrmnth+'-'+newdate;
                return newdate_final;


            }
        });
    </script>
@endsection

