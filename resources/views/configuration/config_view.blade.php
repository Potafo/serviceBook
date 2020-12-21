@extends('layouts.app', ['page' => __('Configurations'), 'pageSlug' => 'config_view'])

<script src="{{ asset('black') }}/js/core/jquery-3.4.1.min.js"></script>

@section('content')
<div class="row">
  <div class="col-md-12">
    <div class="card ">
      <div class="card-header">
        <div class="row">




            <div class="col-8">
                <h4 class="card-title">Configurations</h4>
            </div>
            <div class="col-4 text-right">
                <a class="btn btn-sm btn-primary addpackage" href="config_add">Add Configuration</a>
            </div>
            <div class="col-12">
                <nav aria-label="breadcrumb" role="navigation">
                    <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="home">Home</a></li>
                    <li class="breadcrumb-item "><a href="jobcard">Configurations</a></li>
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
            <thead class=" text-primary" >
              <tr>
                <th>
                  Slno
                </th>
                <th>
                  Configuration Name
                </th>

                <th>
                    Type
                  </th>

                  <th>
                    Page View
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
                <?php $i=1; ?>
                @if(count($configurations)>0)
                    @foreach($configurations as $key=>$value)

                        <tr >

                            <td>
                                {{ $configurations->firstItem() + $key }}
                            </td>
                            <td>
                                {{ $value->config_name }}
                            </td>
                            <td>
                                {{ $value->typename }}
                            </td>


                            <td>
                                {{ $value->page_view }}
                            </td>

                            <td>
                                {{ $value->status }}
                            </td>

                            <td >
                                {{-- <button type="button" rel="tooltip" class="btn btn-info btn-sm btn-icon">
                                    <a href="jobcard_edit/{{ $value->jobid }}" ><i class="tim-icons icon-settings"></i></a>
                                </button> --}}

                                 {{-- <a  href="jobcard_edit/{{ $value->id }}"  >
                                    <i class='tim-icons icon-pencil'></i>
                                </a>

                                <a style="color: #ba54f5; cursor: pointer;" data-toggle='modal' id='deleteButton' data-target='#delete_jobcards'  data-id='{{  $value->id }}' data-jobcardref='{{  $value->jcnmbr }}' data-jobcardnmbr='{{  $value->jobcard_number }}' title='Delete Service'>
                                    <i class='tim-icons icon-trash-simple'></i>
                                </a> --}}
                            </td>
                        </tr>
                @endforeach

             @endif
            </tbody>
          </table>

        </div>
        <div class="card-footer py-4">
            @if(count($configurations)>0)
            {{ $configurations->links() }}
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
        $(document).on('click', '#deleteButton', function(event) {
            event.preventDefault();
            var jobcardid =$(this).attr('data-id');
            var jobcardref =$(this).attr('data-jobcardref');
            var jobcard =$(this).attr('data-jobcardnmbr');
            $('#referencenumber').val(jobcardref);
            $('#referenceid').val(jobcardid);
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
            window.location='jobcard_edit/'+jobcardid;

        });

    });
</script>
