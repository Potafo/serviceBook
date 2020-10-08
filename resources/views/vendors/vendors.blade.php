@extends('layouts.app', ['page' => __('Vendors'), 'pageSlug' => 'vendors'])

@section('content')
<div class="row">
  <div class="col-md-12">
    <div class="card ">
      <div class="card-header">
        <div class="row">




            <div class="col-8">
                <h4 class="card-title">Vendor List</h4>
            </div>
           <div class="col-4 text-right">
                <a class="btn btn-sm btn-primary " href="vendor_add">Add Vendors</a>
            </div>
            <div class="col-8">
                <nav aria-label="breadcrumb" role="navigation">
                    <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="home">Home</a></li>
                    <li class="breadcrumb-item "><a href="vendors">Vendors</a></li>
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
                  Name
                </th>
                <th>
                  Phone
                </th>
                <th>
                    Category
                  </th>
                  <th>
                    Type
                  </th>
                <th>
                    Current package
                  </th>
                  <th>
                    Pending Days
                  </th>
                  <th>
                    View
                  </th>
                  <th>
                    Edit
                  </th>
              </tr>
            </thead>
            <tbody>
                @if(count($vendor)>0)
                    @foreach($vendor as $key=>$value)
                        <?php
                        $package_days_count=$value->days;
                         $joined_date=date("Y-m-d",strtotime($value->joined_on));
                         $current_date=date("Y-m-d");
                         $diff=(new DateTime($joined_date))->diff(new DateTime($current_date))->days;
                         $pending=intval($package_days_count) - intval($diff);


                        ?>
                        <tr>
                            <td>
                                {{ $vendor->firstItem() + $key }}
                            </td>
                            <td>
                                {{ $value->vname }}
                            </td>

                            <td>
                                {{ $value->contact_number }}
                            </td>
                            <td >
                                {{ $value->vcategory }}
                            </td>
                            <td >
                                {{ $value->vtype }}
                            </td>
                            <td >
                                {{ $value->pname }}
                            </td>
                            <td >
                                {{ $pending }} Days more
                            </td>
                            <td >
                             <a href="vendor_view/{{ $value->vid }}" >  View </a>

                            </td>
                            <td >

                                <a href="vendor_edit/{{ $value->vid }}" >  Edit </a>
                               </td>
                        </tr>
                @endforeach

             @endif
            </tbody>
          </table>

        </div>
        <div class="card-footer py-4">
            {{ $vendor->links() }}
        </div>
      </div>

      <div class="card-body" id="add_packages" style="display: none">
        <form>
          <div class="form-group">
            <label for="exampleFormControlInput1">Package Type</label>
            <input type="email" class="form-control" id="package_type" name="package_type" placeholder="Package Type/Name">
          </div>
          <div class="form-group">
            <label for="exampleFormControlInput1">Days</label>
            <input type="email" class="form-control" id="package_days" name="package_days" placeholder="Days">
          </div>
          <div class="form-group">
             <div class="col-4 text-right">
            <a class="btn btn-sm btn-primary submitpackage">Submit</a>
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>

</div>
<script src="{{ asset('black') }}/js/jquery.min.js"></script>

    <script language="JavaScript" type="text/javascript">
        $(document).ready(function(){
            $(".addpackage").click(function(){
                 $("#view_package").hide();
                 $("#add_packages").show();
                 $(".addpackage").hide();
                 $("#bc_current").html('Add');
            });
            $(".submitpackage").click(function(){

                var package_type=''
                var package_days='';
                if($("#package_type").val() != '')
                    package_type=$("#package_type").val();
                else{
                    showNotification('warning','top','right','Please add package type');
                    return false;
                }


                 if($("#package_days").val() != '')
                 package_days=$("#package_days").val();
                else{
                    showNotification('warning','top','right','Please add package days');
                    return false;
                }


                var data={"package_type":package_type,"package_days":package_days};
                $.ajax({
                    method: "post",
                    url : "api/insert_packages",
                    data:data,
                    cache : false,
                    crossDomain : true,
                    async : false,
                    dataType :'text',
                    success : function(result)
                    {
                        var json_x= JSON.parse(result);
                        if(json_x.status=="success")
                        {
                            $("#view_package").show();
                            $("#add_packages").hide();
                            $(".addpackage").show();
                            $("#bc_current").html('View');

                            $("#successalert").show();
                            $("#successalert").delay(2000).fadeOut(500);
                            timedRefresh(1000);
                           //showNotification('success','top','right','Successfully Added Package');
                           // location.reload();


                        }
                        //$("#arealisting").html('');
                        //$("#arealisting").html(result) ;



                    }
                    });

            });
            function timedRefresh(time) {
                setTimeout(() => {
                    location.reload(true);
                }, time)
                }

            //notifications starts
            type = ['','info','success','warning','danger'];

            function showNotification(type,from, align,message_to){
              color = Math.floor((Math.random() * 4) + 1);

              $.notify({
                  icon: "tim-icons icon-bell-55",
                  message: message_to

                },{
                    type: type,
                    timer: 1000,
                    placement: {
                        from: from,
                        align: align
                    }
                });
            }
            //notification ends
        });
    </script>

@endsection
