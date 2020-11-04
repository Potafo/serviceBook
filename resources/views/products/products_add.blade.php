@extends('layouts.app', ['page' => __('Job Card'), 'pageSlug' => 'jobcard'])
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
                    <h5 class="title">{{ __('Add Products') }}</h5>
                </div>
                <div class="col-8">
                    <nav aria-label="breadcrumb" role="navigation">
                        <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="home">Home</a></li>
                        <li class="breadcrumb-item "><a href="Products">Products</a></li>
                        <li class="breadcrumb-item active" aria-current="page" id="bc_current">Add</li>
                        </ol>
                    </nav>
                </div>
                <form method="post" action="{{ route('products.insert') }}" autocomplete="off" enctype="multipart/form-data">
                    <div class="card-body">
                    @csrf
                    @method('put')

                    @include('alerts.success')
                    <input type="hidden" name="user_id" id="user_id" value="{{  Session::get('logged_user_id') }}" >
                  <div class="form-group">
                    <label for="exampleFormControlInput1">Product Name</label>
                    <input type="text" class="form-control{{ $errors->has('productname') ? ' is-invalid' : '' }}"  id="productname" name="productname" placeholder="Product Name" value="{{ old('productname') }}">
                    @include('alerts.feedback', ['field' => 'productname'])
                  </div>
                  <label>{{ __('Product Image') }}</label>
                  <input type="file" name="file" class="form-control{{ $errors->has('file') ? ' is-invalid' : '' }}" />
                  @include('alerts.feedback', ['field' => 'file'])

                  <div class="form-group">
                     <div class="col-4 text-right">
                        <button type="submit" class="btn btn-fill btn-primary">{{ __('Save') }}</button>
                    {{-- <a class="btn btn-sm btn-primary submitpackage">Submit</a> --}}
                    </div>
                  </div>
                    </div>
                </form>
            </div>


        </div>

    </div>
@endsection
<script src="{{ asset('black') }}/js/jquery.min.js"></script>
<script language="JavaScript" type="text/javascript">
 $(document).ready(function() {

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
                        // $.each(data, function(key, value) {
                        //     $('select[name="product_list"]').append('<option value="'+ value +'">'+ value +'</option>');
                        // });

                        }
                    });
            }else{
                $('select[name="product_list"]').empty();
                }
           });

        });
</script>
