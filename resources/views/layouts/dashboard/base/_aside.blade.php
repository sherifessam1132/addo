    <!--begin::Aside-->
<div class="aside aside-left aside-fixed d-flex flex-column flex-row-auto" id="kt_aside">
    <!--begin::Brand-->
    <div class="brand flex-column-auto" id="kt_brand">
        <!--begin::Logo-->
        <a href="{{route('dashboard.welcome')}}" class="brand-logo">
            <img alt="Logo" src="{{ $site_settings->logo_path ?? asset('media/logos/logo-default-inverse.png')}}" />
        </a>
        <!--end::Logo-->
        <!--begin::Toggle-->
        <button class="brand-toggle btn btn-sm px-0" id="kt_aside_toggle">
            <span class="svg-icon svg-icon svg-icon-xl">
                <!--begin::Svg Icon | path:assets/media/svg/icons/Navigation/Angle-double-left.svg-->
                <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                    <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                        <polygon points="0 0 24 0 24 24 0 24" />
                        <path d="M5.29288961,6.70710318 C4.90236532,6.31657888 4.90236532,5.68341391 5.29288961,5.29288961 C5.68341391,4.90236532 6.31657888,4.90236532 6.70710318,5.29288961 L12.7071032,11.2928896 C13.0856821,11.6714686 13.0989277,12.281055 12.7371505,12.675721 L7.23715054,18.675721 C6.86395813,19.08284 6.23139076,19.1103429 5.82427177,18.7371505 C5.41715278,18.3639581 5.38964985,17.7313908 5.76284226,17.3242718 L10.6158586,12.0300721 L5.29288961,6.70710318 Z" fill="#000000" fill-rule="nonzero" transform="translate(8.999997, 11.999999) scale(-1, 1) translate(-8.999997, -11.999999)" />
                        <path d="M10.7071009,15.7071068 C10.3165766,16.0976311 9.68341162,16.0976311 9.29288733,15.7071068 C8.90236304,15.3165825 8.90236304,14.6834175 9.29288733,14.2928932 L15.2928873,8.29289322 C15.6714663,7.91431428 16.2810527,7.90106866 16.6757187,8.26284586 L22.6757187,13.7628459 C23.0828377,14.1360383 23.1103407,14.7686056 22.7371482,15.1757246 C22.3639558,15.5828436 21.7313885,15.6103465 21.3242695,15.2371541 L16.0300699,10.3841378 L10.7071009,15.7071068 Z" fill="#000000" fill-rule="nonzero" opacity="0.3" transform="translate(15.999997, 11.999999) scale(-1, 1) rotate(-270.000000) translate(-15.999997, -11.999999)" />
                    </g>
                </svg>
                <!--end::Svg Icon-->
            </span>
        </button>
        <!--end::Toolbar-->
    </div>
    <!--end::Brand-->


    <!--begin::Aside Menu-->
    <div class="aside-menu-wrapper flex-column-fluid" id="kt_aside_menu_wrapper">
        <!--begin::Menu Container-->
        <div id="kt_aside_menu" class="aside-menu my-4" data-menu-vertical="1" data-menu-scroll="1" data-menu-dropdown-timeout="500">
            <!--begin::Menu Nav-->
            <ul class="menu-nav">
                <li class="menu-item {{url()->current() == route('dashboard.welcome') ?'menu-item-active' : ''}}" aria-haspopup="true">
                    <a href="{{route('dashboard.welcome')}}" class="menu-link">
                        <span class="svg-icon menu-icon">
                            <!--begin::Svg Icon | path:assets/media/svg/icons/Design/Layers.svg-->
                            <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                                <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                    <polygon points="0 0 24 0 24 24 0 24" />
                                    <path d="M3.95709826,8.41510662 L11.47855,3.81866389 C11.7986624,3.62303967 12.2013376,3.62303967 12.52145,3.81866389 L20.0429,8.41510557 C20.6374094,8.77841684 21,9.42493654 21,10.1216692 L21,19.0000642 C21,20.1046337 20.1045695,21.0000642 19,21.0000642 L4.99998155,21.0000673 C3.89541205,21.0000673 2.99998155,20.1046368 2.99998155,19.0000673 L2.99999828,10.1216672 C2.99999935,9.42493561 3.36258984,8.77841732 3.95709826,8.41510662 Z M10,13 C9.44771525,13 9,13.4477153 9,14 L9,17 C9,17.5522847 9.44771525,18 10,18 L14,18 C14.5522847,18 15,17.5522847 15,17 L15,14 C15,13.4477153 14.5522847,13 14,13 L10,13 Z" fill="#000000"/>
                                </g>
                            </svg>
                            <!--end::Svg Icon-->
                        </span>
                        <span class="menu-text">{{__('site.global.dashboard')}}</span>
                    </a>
                </li>
               

                @role('admin,user')
                {{-- Start users--}}
                <li class="menu-item menu-item-submenu  {{ url()->current() == route('dashboard.users.index') || url()->current() == route('dashboard.users.create')
                            ? 'menu-item-open' : ''}}" aria-haspopup="true" data-menu-toggle="hover">
                    <a href="javascript:;" class="menu-link menu-toggle">
                        <span class="menu-icon fas fa-user-shield">
                        </span>
                        <span class="menu-text">{{__('site.user.show')}}</span>
                        <i class="menu-arrow"></i>
                    </a>
{{--                    <div class="menu-submenu">--}}
{{--                        <i class="menu-arrow"></i>--}}
{{--                        <ul class="menu-subnav">--}}
{{--                            <li class="menu-item menu-item-parent" aria-haspopup="true">--}}
{{--                                <span class="menu-link">--}}
{{--                                    <span class="menu-text">{{__('site.user.show')}}</span>--}}
{{--                                </span>--}}
{{--                            </li>--}}
                            @permission('view-user,full-permissions')
                            <li class="menu-item {{url()->current() == route('dashboard.users.index') ? 'menu-item-active' : ''}}" aria-haspopup="true">
                                <a href="{{route('dashboard.users.index')}}" class="menu-link">
                                    <i class="menu-bullet menu-bullet-line">
                                        <span></span>
                                    </i>
                                    <span class="menu-text">{{__('site.user.all')}}</span>
                                </a>
                            </li>
                @endpermission
                            @endpermission
                            @permission('create-user,full-permissions')
                            <li class="menu-item {{url()->current() == route('dashboard.users.create') ? 'menu-item-active' : ''}}" aria-haspopup="true">
                                <a href="{{route('dashboard.users.create')}}" class="menu-link">
                                    <i class="menu-bullet menu-bullet-line">
                                        <span></span>
                                    </i>
                                    <span class="menu-text">{{__('site.user.create')}}</span>
                                </a>
                            </li>

                            @endpermission
                @role('admin,role')
                {{-- Start roles--}}
                <li class="menu-item menu-item-submenu  {{ url()->current() == route('dashboard.roles.index') || url()->current() == route('dashboard.roles.create')
                    ? 'menu-item-open' : ''}}" aria-haspopup="true" data-menu-toggle="hover">
                    <a href="javascript:;" class="menu-link menu-toggle">
                        <span class="svg-icon menu-icon">
                            <span class="menu-icon fas fa-user-edit">

                            </span>
                        </span>
                        <span class="menu-text">{{__('site.role.show')}}</span>
                        <i class="menu-arrow"></i>
                    </a>
                    <div class="menu-submenu">
                        <i class="menu-arrow"></i>
                        <ul class="menu-subnav">
                            <li class="menu-item menu-item-parent" aria-haspopup="true">
                                <span class="menu-link">
                                    <span class="menu-text">{{__('site.role.show')}}</span>
                                </span>
                            </li>
                            @permission('view-role,full-permissions')
                            <li class="menu-item {{url()->current() == route('dashboard.roles.index') ? 'menu-item-active' : ''}}" aria-haspopup="true">
                                <a href="{{route('dashboard.roles.index')}}" class="menu-link">
                                    <i class="menu-bullet menu-bullet-line">
                                        <span></span>
                                    </i>
                                    <span class="menu-text">{{__('site.role.all')}}</span>
                                </a>
                            </li>
                            @endpermission
                            @permission('create-role,full-permissions')
                            <li class="menu-item {{url()->current() == route('dashboard.roles.create') ? 'menu-item-active' : ''}}" aria-haspopup="true">
                                <a href="{{route('dashboard.roles.create')}}" class="menu-link">
                                    <i class="menu-bullet menu-bullet-line">
                                        <span></span>
                                    </i>
                                    <span class="menu-text">{{__('site.role.create')}}</span>
                                </a>
                            </li>
                            @endpermission
                        </ul>
                    </div>
                </li>
                {{-- End roles--}}
                @endrole

                @role('admin,category')
                {{-- Start categories--}}
                <li class="menu-item menu-item-submenu  {{ url()->current() == route('dashboard.categories.index') || url()->current() == route('dashboard.categories.create')
                            ? 'menu-item-open' : ''}}" aria-haspopup="true" data-menu-toggle="hover">
                    <a href="javascript:;" class="menu-link menu-toggle">
                        <span class="menu-icon fas fa-sitemap">
                            </span>
                        </span>
                        <span class="menu-text">{{__('site.category.show')}}</span>
                        <i class="menu-arrow"></i>
                    </a>
                    <div class="menu-submenu">
                        <i class="menu-arrow"></i>
                        <ul class="menu-subnav">
                            <li class="menu-item menu-item-parent" aria-haspopup="true">
                                <span class="menu-link">
                                    <span class="menu-text">{{__('site.category.show')}}</span>
                                </span>
                            </li>
                            @permission('view-category,full-permissions')
                            <li class="menu-item {{url()->current() == route('dashboard.categories.index') ? 'menu-item-active' : ''}}" aria-haspopup="true">
                                <a href="{{route('dashboard.categories.index')}}" class="menu-link">
                                    <i class="menu-bullet menu-bullet-line">
                                        <span></span>
                                    </i>
                                    <span class="menu-text">{{__('site.category.all')}}</span>
                                </a>
                            </li>
                            @endpermission
                            @permission('create-category,full-permissions')
                            <li class="menu-item {{url()->current() == route('dashboard.categories.create') ? 'menu-item-active' : ''}}" aria-haspopup="true">
                                <a href="{{route('dashboard.categories.create')}}" class="menu-link">
                                    <i class="menu-bullet menu-bullet-line">
                                        <span></span>
                                    </i>
                                    <span class="menu-text">{{__('site.category.create')}}</span>
                                </a>
                            </li>
                            @endpermission
                        </ul>
                    </div>
                </li>
                {{-- End categories--}}
                @endrole

                @role('admin,attribute')
                {{-- Start attributes--}}
                <li class="menu-item menu-item-submenu  {{ url()->current() == route('dashboard.attributes.index') || url()->current() == route('dashboard.attributes.create')
                            ? 'menu-item-open' : ''}}" aria-haspopup="true" data-menu-toggle="hover">
                    <a href="javascript:;" class="menu-link menu-toggle">
                        <span class="menu-icon fas fa-tasks">
                            </span>
                        </span>
                        <span class="menu-text">{{__('site.attribute.show')}}</span>
                        <i class="menu-arrow"></i>
                    </a>
                    <div class="menu-submenu">
                        <i class="menu-arrow"></i>
                        <ul class="menu-subnav">
                            <li class="menu-item menu-item-parent" aria-haspopup="true">
                                <span class="menu-link">
                                    <span class="menu-text">{{__('site.attribute.show')}}</span>
                                </span>
                            </li>
                            @permission('view-attribute,full-permissions')
                            <li class="menu-item {{url()->current() == route('dashboard.attributes.index') ? 'menu-item-active' : ''}}" aria-haspopup="true">
                                <a href="{{route('dashboard.attributes.index')}}" class="menu-link">
                                    <i class="menu-bullet menu-bullet-line">
                                        <span></span>
                                    </i>
                                    <span class="menu-text">{{__('site.attribute.all')}}</span>
                                </a>
                            </li>
                            @endpermission
                            @permission('create-attribute,full-permissions')
                            <li class="menu-item {{url()->current() == route('dashboard.attributes.create') ? 'menu-item-active' : ''}}" aria-haspopup="true">
                                <a href="{{route('dashboard.attributes.create')}}" class="menu-link">
                                    <i class="menu-bullet menu-bullet-line">
                                        <span></span>
                                    </i>
                                    <span class="menu-text">{{__('site.attribute.create')}}</span>
                                </a>
                            </li>
                            @endpermission
                        </ul>
                    </div>
                </li>
                {{-- End attributes--}}
                @endrole

                @role('admin,country')
                {{-- Start countries--}}
                <li class="menu-item menu-item-submenu  {{ url()->current() == route('dashboard.countries.index') || url()->current() == route('dashboard.countries.create')
                            ? 'menu-item-open' : ''}}" aria-haspopup="true" data-menu-toggle="hover">
                    <a href="javascript:;" class="menu-link menu-toggle">
                        <span class="menu-icon fas fa-flag">

                            </span>
                        </span>
                        <span class="menu-text">{{__('site.country.show')}}</span>
                        <i class="menu-arrow"></i>
                    </a>
                    <div class="menu-submenu">
                        <i class="menu-arrow"></i>
                        <ul class="menu-subnav">
                            <li class="menu-item menu-item-parent" aria-haspopup="true">
                                <span class="menu-link">
                                    <span class="menu-text">{{__('site.country.show')}}</span>
                                </span>
                            </li>
                            @permission('view-country,full-permissions')
                            <li class="menu-item {{url()->current() == route('dashboard.countries.index') ? 'menu-item-active' : ''}}" aria-haspopup="true">
                                <a href="{{route('dashboard.countries.index')}}" class="menu-link">
                                    <i class="menu-bullet menu-bullet-line">
                                        <span></span>
                                    </i>
                                    <span class="menu-text">{{__('site.country.all')}}</span>
                                </a>
                            </li>
                            @endpermission
                            @permission('create-country,full-permissions')
                            <li class="menu-item {{url()->current() == route('dashboard.countries.create') ? 'menu-item-active' : ''}}" aria-haspopup="true">
                                <a href="{{route('dashboard.countries.create')}}" class="menu-link">
                                    <i class="menu-bullet menu-bullet-line">
                                        <span></span>
                                    </i>
                                    <span class="menu-text">{{__('site.country.create')}}</span>
                                </a>
                            </li>
                            @endpermission
                        </ul>
                    </div>
                </li>
                {{-- End countries--}}
                @endrole

                @role('admin,governorate')
                {{-- Start governorates--}}
                <li class="menu-item menu-item-submenu  {{ url()->current() == route('dashboard.governorates.index') || url()->current() == route('dashboard.governorates.create')
                            ? 'menu-item-open' : ''}}" aria-haspopup="true" data-menu-toggle="hover">
                    <a href="javascript:;" class="menu-link menu-toggle">
                        <span class="menu-icon fas fa-archway">

                            </span>
                        </span>
                        <span class="menu-text">{{__('site.governorate.show')}}</span>
                        <i class="menu-arrow"></i>
                    </a>
                    <div class="menu-submenu">
                        <i class="menu-arrow"></i>
                        <ul class="menu-subnav">
                            <li class="menu-item menu-item-parent" aria-haspopup="true">
                                <span class="menu-link">
                                    <span class="menu-text">{{__('site.governorate.show')}}</span>
                                </span>
                            </li>
                            @permission('view-governorate,full-permissions')
                            <li class="menu-item {{url()->current() == route('dashboard.governorates.index') ? 'menu-item-active' : ''}}" aria-haspopup="true">
                                <a href="{{route('dashboard.governorates.index')}}" class="menu-link">
                                    <i class="menu-bullet menu-bullet-line">
                                        <span></span>
                                    </i>
                                    <span class="menu-text">{{__('site.governorate.all')}}</span>
                                </a>
                            </li>
                            @endpermission
                            @permission('create-governorate,full-permissions')
                            <li class="menu-item {{url()->current() == route('dashboard.governorates.create') ? 'menu-item-active' : ''}}" aria-haspopup="true">
                                <a href="{{route('dashboard.governorates.create')}}" class="menu-link">
                                    <i class="menu-bullet menu-bullet-line">
                                        <span></span>
                                    </i>
                                    <span class="menu-text">{{__('site.governorate.create')}}</span>
                                </a>
                            </li>
                            @endpermission
                        </ul>
                    </div>
                </li>
                {{-- End governorates--}}
                @endrole

                @role('admin,city')
                {{-- Start cities--}}
                <li class="menu-item menu-item-submenu  {{ url()->current() == route('dashboard.cities.index') || url()->current() == route('dashboard.cities.create')
                            ? 'menu-item-open' : ''}}" aria-haspopup="true" data-menu-toggle="hover">
                    <a href="javascript:;" class="menu-link menu-toggle">
                        <span class="menu-icon fas fa-city">

                        </span>
                        <span class="menu-text">{{__('site.city.show')}}</span>
                        <i class="menu-arrow"></i>
                    </a>
                    <div class="menu-submenu">
                        <i class="menu-arrow"></i>
                        <ul class="menu-subnav">
                            <li class="menu-item menu-item-parent" aria-haspopup="true">
                                <span class="menu-link">
                                    <span class="menu-text">{{__('site.city.show')}}</span>
                                </span>
                            </li>
                            @permission('view-city,full-permissions')
                            <li class="menu-item {{url()->current() == route('dashboard.cities.index') ? 'menu-item-active' : ''}}" aria-haspopup="true">
                                <a href="{{route('dashboard.cities.index')}}" class="menu-link">
                                    <i class="menu-bullet menu-bullet-line">
                                        <span></span>
                                    </i>
                                    <span class="menu-text">{{__('site.city.all')}}</span>
                                </a>
                            </li>
                            @endpermission
                            @permission('create-city,full-permissions')
                            <li class="menu-item {{url()->current() == route('dashboard.cities.create') ? 'menu-item-active' : ''}}" aria-haspopup="true">
                                <a href="{{route('dashboard.cities.create')}}" class="menu-link">
                                    <i class="menu-bullet menu-bullet-line">
                                        <span></span>
                                    </i>
                                    <span class="menu-text">{{__('site.city.create')}}</span>
                                </a>
                            </li>
                            @endpermission
                        </ul>
                    </div>
                </li>
                {{-- End cities--}}
                @endrole

                @role('admin,package')
                {{-- Start packages--}}
                <li class="menu-item menu-item-submenu  {{ url()->current() == route('dashboard.packages.index') || url()->current() == route('dashboard.packages.create')
                            ? 'menu-item-open' : ''}}" aria-haspopup="true" data-menu-toggle="hover">
                    <a href="javascript:;" class="menu-link menu-toggle">
                        <span class="menu-icon fas fa-gem">
                            </span>
                        </span>
                        <span class="menu-text">{{__('site.package.show')}}</span>
                        <i class="menu-arrow"></i>
                    </a>
                    <div class="menu-submenu">
                        <i class="menu-arrow"></i>
                        <ul class="menu-subnav">
                            <li class="menu-item menu-item-parent" aria-haspopup="true">
                                <span class="menu-link">
                                    <span class="menu-text">{{__('site.package.show')}}</span>
                                </span>
                            </li>
                            @permission('view-package,full-permissions')
                            <li class="menu-item {{url()->current() == route('dashboard.packages.index') ? 'menu-item-active' : ''}}" aria-haspopup="true">
                                <a href="{{route('dashboard.packages.index')}}" class="menu-link">
                                    <i class="menu-bullet menu-bullet-line">
                                        <span></span>
                                    </i>
                                    <span class="menu-text">{{__('site.package.all')}}</span>
                                </a>
                            </li>
                            @endpermission
                            @permission('create-package,full-permissions')
                            <li class="menu-item {{url()->current() == route('dashboard.packages.create') ? 'menu-item-active' : ''}}" aria-haspopup="true">
                                <a href="{{route('dashboard.packages.create')}}" class="menu-link">
                                    <i class="menu-bullet menu-bullet-line">
                                        <span></span>
                                    </i>
                                    <span class="menu-text">{{__('site.package.create')}}</span>
                                </a>
                            </li>
                            @endpermission
                        </ul>
                    </div>
                </li>
                {{-- End packages--}}
                @endrole

                @role('admin,packageType')
                {{-- Start packageTypes--}}
                <li class="menu-item menu-item-submenu  {{ url()->current() == route('dashboard.packageTypes.index') || url()->current() == route('dashboard.packageTypes.create')
                            ? 'menu-item-open' : ''}}" aria-haspopup="true" data-menu-toggle="hover">
                    <a href="javascript:;" class="menu-link menu-toggle">
                        <span class="menu-icon fas fa-handshake">
                            </span>
                        </span>
                        <span class="menu-text">{{__('site.packageType.show')}}</span>
                        <i class="menu-arrow"></i>
                    </a>
                    <div class="menu-submenu">
                        <i class="menu-arrow"></i>
                        <ul class="menu-subnav">
                            <li class="menu-item menu-item-parent" aria-haspopup="true">
                                <span class="menu-link">
                                    <span class="menu-text">{{__('site.packageType.show')}}</span>
                                </span>
                            </li>
                            @permission('view-packageType,full-permissions')
                            <li class="menu-item {{url()->current() == route('dashboard.packageTypes.index') ? 'menu-item-active' : ''}}" aria-haspopup="true">
                                <a href="{{route('dashboard.packageTypes.index')}}" class="menu-link">
                                    <i class="menu-bullet menu-bullet-line">
                                        <span></span>
                                    </i>
                                    <span class="menu-text">{{__('site.packageType.all')}}</span>
                                </a>
                            </li>
                            @endpermission
                            @permission('create-packageType,full-permissions')
                            <li class="menu-item {{url()->current() == route('dashboard.packageTypes.create') ? 'menu-item-active' : ''}}" aria-haspopup="true">
                                <a href="{{route('dashboard.packageTypes.create')}}" class="menu-link">
                                    <i class="menu-bullet menu-bullet-line">
                                        <span></span>
                                    </i>
                                    <span class="menu-text">{{__('site.packageType.create')}}</span>
                                </a>
                            </li>
                            @endpermission
                        </ul>
                    </div>
                </li>
                {{-- End packageTypes--}}
                @endrole

                @role('admin,home')
                {{-- Start homes--}}
                <li class="menu-item menu-item-submenu  {{ url()->current() == route('dashboard.homes.index') || url()->current() == route('dashboard.homes.create')
                            ? 'menu-item-open' : ''}}" aria-haspopup="true" data-menu-toggle="hover">
                    <a href="javascript:;" class="menu-link menu-toggle">
                        <span class="menu-icon fas fa-tags">
                            </span>
                        </span>
                        <span class="menu-text">{{__('site.home.show')}}</span>
                        <i class="menu-arrow"></i>
                    </a>
                    <div class="menu-submenu">
                        <i class="menu-arrow"></i>
                        <ul class="menu-subnav">
                            <li class="menu-item menu-item-parent" aria-haspopup="true">
                                <span class="menu-link">
                                    <span class="menu-text">{{__('site.home.show')}}</span>
                                </span>
                            </li>
                            @permission('view-home,full-permissions')
                            <li class="menu-item {{url()->current() == route('dashboard.homes.index') ? 'menu-item-active' : ''}}" aria-haspopup="true">
                                <a href="{{route('dashboard.homes.index')}}" class="menu-link">
                                    <i class="menu-bullet menu-bullet-line">
                                        <span></span>
                                    </i>
                                    <span class="menu-text">{{__('site.home.all')}}</span>
                                </a>
                            </li>
                            @endpermission
                            @permission('create-home,full-permissions')
                            <li class="menu-item {{url()->current() == route('dashboard.homes.create') ? 'menu-item-active' : ''}}" aria-haspopup="true">
                                <a href="{{route('dashboard.homes.create')}}" class="menu-link">
                                    <i class="menu-bullet menu-bullet-line">
                                        <span></span>
                                    </i>
                                    <span class="menu-text">{{__('site.home.create')}}</span>
                                </a>
                            </li>
                            @endpermission
                        </ul>
                    </div>
                </li>
                {{-- End homes--}}
                @endrole


                @role('admin,advertisement')
                {{-- Start advertisements--}}
                <li class="menu-item menu-item-submenu  {{ url()->current() == route('dashboard.advertisements.index')
                            ? 'menu-item-open' : ''}}" aria-haspopup="true" data-menu-toggle="hover">
                    <a href="javascript:;" class="menu-link menu-toggle">
                        <span class="menu-icon fas fa-ad">
                            </span>
                        </span>
                        <span class="menu-text">{{__('site.advertisement.show')}}</span>
                        <i class="menu-arrow"></i>
                    </a>
                    <div class="menu-submenu">
                        <i class="menu-arrow"></i>
                        <ul class="menu-subnav">
                            <li class="menu-item menu-item-parent" aria-haspopup="true">
                                <span class="menu-link">
                                    <span class="menu-text">{{__('site.advertisement.show')}}</span>
                                </span>
                            </li>
                            @permission('view-advertisement,full-permissions')
                            <li class="menu-item {{url()->current() == route('dashboard.advertisements.index') ? 'menu-item-active' : ''}}" aria-haspopup="true">
                                <a href="{{route('dashboard.advertisements.index')}}" class="menu-link">
                                    <i class="menu-bullet menu-bullet-line">
                                        <span></span>
                                    </i>
                                    <span class="menu-text">{{__('site.advertisement.all')}}</span>
                                </a>
                            </li>
                            @endpermission
                        </ul>
                    </div>
                </li>
                {{-- End advertisements--}}
                @endrole

                @role('admin,client')
                {{-- Start clients--}}
                <li class="menu-item {{url()->current() == route('dashboard.clients.index') ?'menu-item-active' : ''}}" aria-haspopup="true">
                    @permission('view-client,full-permissions')
                    <a href="{{route('dashboard.clients.index')}}" class="menu-link">
                        <span class="menu-icon fas fa-users">
                        </span>
                        <span class="menu-text">{{__('site.client.show')}}</span>
                    </a>
                    @endpermission
                </li>
                {{-- End clients--}}
                @endrole

                @role('admin,pushNotification')
                {{-- Start clients--}}
{{--                <li class="menu-item {{url()->current() == route('dashboard.pushNotifications.index') ?'menu-item-active' : ''}}" aria-haspopup="true">--}}
                    @permission('view-pushNotification,full-permissions')
{{--                    <a href="{{route('dashboard.pushNotifications.index')}}" class="menu-link">--}}
                        <span class="menu-icon fas fa-bell">
                        </span>
                        <span class="menu-text">{{__('site.pushNotification.show')}}</span>
                    </a>
                    @endpermission
                </li>
                {{-- End clients--}}
                @endrole

                {{-- Start Setting--}}
                <li class="menu-item {{url()->current() == route('dashboard.settings.index') ?'menu-item-active' : ''}}" aria-haspopup="true">
                    <a href="{{route('dashboard.settings.index')}}" class="menu-link">
                        <span class="svg-icon menu-icon">
                            <!--begin::Svg Icon | path:C:\wamp64\www\keenthemes\themes\metronic\theme\html\demo1\dist/../src/media/svg/icons\Shopping\Settings.svg-->
                            <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                                <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                    <rect opacity="0.200000003" x="0" y="0" width="24" height="24"/>
                                    <path d="M4.5,7 L9.5,7 C10.3284271,7 11,7.67157288 11,8.5 C11,9.32842712 10.3284271,10 9.5,10 L4.5,10 C3.67157288,10 3,9.32842712 3,8.5 C3,7.67157288 3.67157288,7 4.5,7 Z M13.5,15 L18.5,15 C19.3284271,15 20,15.6715729 20,16.5 C20,17.3284271 19.3284271,18 18.5,18 L13.5,18 C12.6715729,18 12,17.3284271 12,16.5 C12,15.6715729 12.6715729,15 13.5,15 Z" fill="#000000" opacity="0.3"/>
                                    <path d="M17,11 C15.3431458,11 14,9.65685425 14,8 C14,6.34314575 15.3431458,5 17,5 C18.6568542,5 20,6.34314575 20,8 C20,9.65685425 18.6568542,11 17,11 Z M6,19 C4.34314575,19 3,17.6568542 3,16 C3,14.3431458 4.34314575,13 6,13 C7.65685425,13 9,14.3431458 9,16 C9,17.6568542 7.65685425,19 6,19 Z" fill="#000000"/>
                                </g>
                            </svg><!--end::Svg Icon-->
                        </span>
                        <span class="menu-text">{{__('site.global.settings')}}</span>
                    </a>
                </li>
                {{-- End Setting--}}
            </ul>
            <!--end::Menu Nav-->
        </div>
        <!--end::Menu Container-->
    </div>
    <!--end::Aside Menu-->
</div>
<!--end::Aside-->
