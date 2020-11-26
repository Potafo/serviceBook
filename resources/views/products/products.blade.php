@extends('layouts.app', ['page' => __('Products'), 'pageSlug' => 'products'])
<script src="{{ asset('black') }}/js/core/jquery-3.4.1.min.js"></script>
@section('content')
<div class="row">
  <div class="col-md-12">
    <div class="card ">
      <div class="card-header">
        <div class="row">




            <div class="col-8">
                <h4 class="card-title">Products List</h4>
            </div>
            <div class="col-4 text-right">
                <a class="btn btn-sm btn-primary addpackage" href="products_add">Add Products</a>
            </div>
            <div class="col-8">
                <nav aria-label="breadcrumb" role="navigation">
                    <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="home">Home</a></li>
                    <li class="breadcrumb-item "><a href="products">Products</a></li>
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
                  Name
                </th>
                @if(Session::get('logged_user_type') =='1')
                <th>
                    Vendor
                  </th>
                @endif
                <th>
                    Image
                  </th>
                <th >
                    Action
                  </th>
              </tr>
            </thead>
            <tbody>
                @if(count($products)>0)
                    @foreach($products as $key=>$value)
                        <tr>
                            <td>
                                {{ $products->firstItem() + $key }}
                            </td>

                            <td>
                                {{ $value->name }}
                            </td>
                            @if(Session::get('logged_user_type') =='1')
                            <td>
                                {{ $value->vname }}
                            </td>
                            @endif
                            <td>
                                <?php
                                $url =Storage::url('app/public/'.$value->image);
                                ?>
                                <div  style="width:30%; height:30%" >
                                    <a href='{{ url($url) }}' target="_blank" >Image</a>
                                </div>
                            </td>
                            <td >
                                {{-- <button type="button" rel="tooltip" class="btn btn-info btn-sm btn-icon"> --}}
                                    <a href="products_edit/{{ $value->id }}" > <i class='tim-icons icon-pencil'></i></a>
                                {{-- </button> --}}


                               </td>
                        </tr>
                @endforeach

             @endif
            </tbody>
          </table>

        </div>
        <div class="card-footer py-4">
            @if(count($products)>0)
            {{ $products->links() }}
            @endif
        </div>
      </div>


    </div>
  </div>

</div>

@endsection
