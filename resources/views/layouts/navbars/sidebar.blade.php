<div class="sidebar">
    <div class="sidebar-wrapper">
        <div class="logo">
            <a href="#" class="simple-text logo-mini">{{ __('SB') }}</a>
            <a href="#" class="simple-text logo-normal">{{ __('Service Book') }}</a>
        </div>
        <ul class="nav">
            <li @if ($pageSlug  == 'dashboard') class="active " @endif>
                <a href="{{ route('home') }}">
                    <i class="tim-icons icon-chart-pie-36"></i>
                    <p>{{ __('Dashboard') }}</p>
                </a>
            </li>
            <li @if ($pageSlug  == 'profile') class="active " @endif>
                <a href="{{ route('profile.edit')  }}">
                    <i class="tim-icons icon-single-02"></i>
                    <p>{{ __('User Profile') }}</p>
                </a>
            </li>


            {{-- <li @if ($pageSlug ?? '' == 'vendors') class="active "  @endif>
                <a href="{{ route('vendors.vendors') }}">
                    <i class="tim-icons icon-world"></i>
                    <p>{{ __('Vendors') }}</p>
                </a>
            </li> --}}

            <li>
                <a data-toggle="collapse" href="#vendor_tab" aria-expanded="true">
                    <i class="fab fa-laravel" ></i>
                    <span class="nav-link-text" >{{ __('Vendor') }}</span>
                    <b class="caret mt-1"></b>
                </a>

                <div class="collapse show" id="vendor_tab">
                    <ul class="nav pl-4">
                        <li @if ($pageSlug == 'vendors') class="active " @endif>
                            <a href="{{ route('vendors.vendors')  }}">
                                <i class="tim-icons icon-single-02"></i>
                                <p>{{ __('Vendors') }}</p>
                            </a>
                        </li>
                         <li @if ($pageSlug == 'vendor_category/category') class="active "  @endif>
                            <a href="{{ url('vendor_category/category')  }}">
                                <i class="tim-icons icon-bullet-list-67"></i>
                                <p>{{ __('Vendor Category') }}</p>
                            </a>
                        </li>
                       <li @if ($pageSlug == 'vendor_category/type')  class="active " @endif>
                            <a href="{{ url('vendor_category/type')  }}">
                                <i class="tim-icons icon-bullet-list-67"></i>
                                <p>{{ __('Vendor Type') }}</p>
                            </a>
                        </li>
                    </ul>
                </div>
            </li>

            <li @if ($pageSlug  == 'packages') class="active " @endif>
                <a href="{{ route('package.packages') }}">
                    <i class="tim-icons icon-world"></i>
                    <p>{{ __('Packages') }}</p>
                </a>
            </li>




        </ul>
    </div>
</div>

