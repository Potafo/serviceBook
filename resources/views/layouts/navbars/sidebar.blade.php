<div class="sidebar" style="max-height: 90% !important;">
    <div class="sidebar-wrapper">
        <?php  $name = Auth::user()->name; ?>
        <div class="logo">
            <a href="#" class="simple-text logo-mini"></a>
           <a href="#" class="simple-text logo-normal">{{ $name }}</a>
        </div>
        <div class="logo">
            <a href="#" class="simple-text logo-mini"></a>
            <a href="#" class="simple-text logo-normal">{{ __('Service Book') }}</a>
        </div>
        <ul class="nav">
            {{-- @if(Session::get('logged_user_type') == "1") --}}
            <li @if ($pageSlug  == 'dashboard') class="active " @endif>
                <a href="{{ route('home') }}">
                    <i class="tim-icons icon-chart-pie-36"></i>
                    <p>{{ __('Dashboard') }}</p>
                </a>
            </li>
            {{-- @endif --}}
            <li>
                <a data-toggle="collapse" href="#config_tab" aria-expanded="true">
                    <i class="fab fa-laravel" ></i>
                    <span class="nav-link-text" >{{ __('Configuration') }}</span>
                    <b class="caret mt-1"></b>
                </a>

                <div class="collapse show" id="config_tab">
                    <ul class="nav pl-4">
                        @if(Session::get('logged_user_type') == "1")
                        <li @if ($pageSlug == 'main_configuration')  class="active " @endif>
                            <a href="{{ url('main_configuration')  }}">
                                <i class="tim-icons icon-settings"></i>
                                <p>{{ __('Main Configuration') }}</p>
                            </a>
                        </li>
                        @endif
                        @if(Session::get('logged_user_type') == "1")
                        <li @if ($pageSlug  == 'config_view') class="active " @endif>
                            <a href="{{ route('configuration.config_view')  }}">
                                <i class="tim-icons icon-settings-gear-63"></i>
                                <p>{{ __('All Configurations') }}</p>
                            </a>
                        </li>
                        @endif
                        {{-- @if(Session::get('logged_user_type') == "1")
                        <li @if ($pageSlug == 'vendor_configuration')  class="active " @endif>
                            <a href="{{ url('vendor_configuration')  }}">
                                <i class="tim-icons icon-settings-gear-63"></i>
                                <p>{{ __('Vendor Configuration') }}</p>
                            </a>
                        </li>
                        @endif --}}
                    </ul>
                </div>
            </li>
            <li @if ($pageSlug  == 'profile') class="active " @endif>
                <a href="{{ route('profile.edit')  }}">
                    <i class="tim-icons icon-single-02"></i>
                    <p>{{ __('User Profile') }}</p>
                </a>
            </li>
            <li >
                <a data-toggle="collapse" href="#jobcard_tab" aria-expanded="true">
                    <i class="fab fa-laravel" ></i>
                    <span class="nav-link-text" >{{ __('JobCard') }}</span>
                    <b class="caret mt-1"></b>
                </a>

                <div class="collapse show" id="jobcard_tab">
                    <ul class="nav pl-4" >
                        <li @if ($pageSlug  == 'jobcard') class="active " @endif >
                            <a href="{{ route('jobcard.jobcard')  }}" @if(Session::get('vendor_expired') == "Y") style="pointer-events: none;" @endif>
                                <i class="tim-icons icon-badge"></i>
                                <p>{{ __('Job Card') }}</p>
                            </a>
                        </li>
                        <li @if ($pageSlug  == 'jobcard_history') class="active " @endif>
                            <a href="{{ route('jobcard.jobcard_history')  }}" >
                                <i class="tim-icons icon-single-copy-04"></i>
                                <p>{{ __('Job Card History') }}</p>
                            </a>
                        </li>
                        <li @if ($pageSlug  == 'jobcard_report') class="active " @endif>
                            <a href="{{ route('jobcard.jobcard_report')  }}">
                                <i class="tim-icons icon-notes"></i>
                                <p>{{ __('Job Card Report') }}</p>
                            </a>
                        </li>
                        <li @if ($pageSlug  == 'jobcard_reviews') class="active " @endif>
                            <a href="{{ route('jobcard.jobcard_reviews')  }}">
                                <i class="tim-icons icon-shape-star"></i>
                                <p>{{ __('Reviews') }}</p>
                            </a>
                        </li>
                    </ul>
                </div>
            </li>
            @if(Session::get('logged_user_type') == "1")
            <li @if ($pageSlug  == 'packages') class="active " @endif>
                <a href="{{ route('package.packages') }}">
                    <i class="tim-icons icon-world"></i>
                    <p>{{ __('Packages') }}</p>
                </a>
            </li>
            @endif
            <li @if ($pageSlug  == 'products') class="active " @endif>
                <a href="{{ route('products.products')  }}">
                    <i class="tim-icons icon-atom"></i>
                    <p>{{ __('Products') }}</p>
                </a>
            </li>
            @if(Session::get('Parts_status') == 'Y')
            <li @if ($pageSlug == 'vendorservice/parts')  class="active " @endif>
                <a href="{{ url('vendorservice/parts')  }}">
                    <i class="tim-icons icon-atom"></i>
                    <p>{{ __('Parts') }}</p>
                </a>
            </li>
            @endif
            <li @if ($pageSlug == 'services')  class="active " @endif>
                <a href="{{ route('services.services')  }}">
                    <i class="tim-icons icon-bullet-list-67"></i>
                    <p>{{ __('Services') }}</p>
                </a>
            </li>
            <li>
                <a data-toggle="collapse" href="#vendor_tab" aria-expanded="true">
                    <i class="fab fa-laravel" ></i>
                    <span class="nav-link-text" >{{ __('Vendor') }}</span>
                    <b class="caret mt-1"></b>
                </a>

                <div class="collapse show" id="vendor_tab">
                    <ul class="nav pl-4">
                        @if(Session::get('logged_user_type') == "1")
                        <li @if ($pageSlug == 'vendors') class="active " @endif>
                            <a href="{{ route('vendors.vendors')  }}">
                                <i class="tim-icons icon-single-02"></i>
                                <p>{{ __('Vendors') }}</p>
                            </a>
                        </li>
                        @endif


                        @if(Session::get('logged_user_type') == "1")
                        <li @if ($pageSlug == 'vendor_category/service_type')  class="active " @endif>
                            <a href="{{ url('vendor_category/service_type')  }}">
                                <i class="tim-icons icon-bullet-list-67"></i>
                                <p>{{ __('Service Type') }}</p>
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

                        <li @if ($pageSlug == 'vendor_category/status')  class="active " @endif>
                            <a href="{{ url('vendor_category/status')  }}">
                                <i class="tim-icons icon-bullet-list-67"></i>
                                <p>{{ __('Vendor Status') }}</p>
                            </a>
                        </li>
                        @endif

                    </ul>
                </div>
            </li>







        </ul>
    </div>
</div>


