

<!DOCTYPE html>

<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" {{ \App\Classes\Theme\Metronic::printAttrs('html') }} {{ \App\Classes\Theme\Metronic::printClasses('html') }}>

<head>
    <meta charset="utf-8" />
{{--    <title>{{ $site_settings->name }} | @yield('title', $page_title ?? __('site.global.login'))</title>--}}

    {{-- Meta Data --}}
    <meta name="description" content="@yield('page_description', $page_description ?? '')"/>
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <link rel="canonical" href="https://keenthemes.com/metronic" />
    {{-- Fonts --}}
    {{ \App\Classes\Theme\Metronic::getGoogleFontsInclude() }}

    {{-- Global Theme Styles (used by all pages) --}}
    @foreach(config('layout.resources.css_login') as $style)
        <link href="{{ app()->getLocale() == 'ar' ? asset(\App\Classes\Theme\Metronic::rtlCssPath($style)) : asset($style) }}" rel="stylesheet" type="text/css"/>
    @endforeach

    @if (app()->getLocale() == 'ar')
        <style>
            body{
                direction: rtl;
            }
        </style>
    @endif
    <style>
        .login.login-1.login-signin-on .login-signup {
            display: block;
        }
    </style>
    {{-- Includable CSS --}}
    @yield('styles')


    <link rel="shortcut icon" href="{{$site_settings->title_icon_path ?? asset('media/logos/favicon.ico')}}" />
</head>
<!--end::Head-->
<!--begin::Body-->
<body id="kt_body" class="header-fixed header-mobile-fixed subheader-enabled subheader-fixed aside-enabled aside-fixed aside-minimize-hoverable page-loading">
<!--begin::Main-->
<div class="d-flex flex-column flex-root">
    <!--begin::Login-->
    <div class="login login-1 login-signin-on d-flex flex-column flex-lg-row flex-column-fluid bg-white" id="kt_login">
        <!--begin::Aside-->
        <div class="login-aside d-flex flex-column flex-row-auto" style="background-color: #F2C98A;">
            <!--begin::Aside Top-->
            <div class="d-flex flex-column-auto flex-column pt-lg-40 pt-15 mt-16">
                <!--begin::Aside header-->
                <a href="#" class="text-center mb-10">
                    <img src="{{$site_settings->loader_image_path??asset('media/logos/logo-wihte.png')}}" class="max-h-150px" alt="" />
                </a>
                <!--end::Aside header-->
                <!--begin::Aside title-->
                <!--<h3 class="font-weight-bolder text-center font-size-h4 font-size-h1-lg" style="color: #986923;">Discover Amazing Metronic-->
                <!--    <br />with great build tools</h3>-->
                <!--end::Aside title-->
            </div>
            <!--end::Aside Top-->
            <!--begin::Aside Bottom-->
            <!--<div class="aside-img d-flex flex-row-fluid bgi-no-repeat bgi-position-y-bottom bgi-position-x-center" style="background-image: url({{asset('media/svg/illustrations/login-visual-4.svg')}})"></div>-->
            <!--end::Aside Bottom-->
        </div>
        <!--begin::Aside-->
        <!--begin::Content-->
        <div class="login-content flex-row-fluid d-flex flex-column justify-content-center position-relative overflow-hidden p-7 mx-auto">
            <!--begin::Content body-->
            <div class="d-flex flex-column-fluid flex-center">
                @yield('content')
            </div>
            <!--end::Content body-->
            <!--begin::Content footer-->
            <div class="d-flex justify-content-lg-start justify-content-center align-items-end py-7 py-lg-0">
                <div class="text-dark-50 font-size-lg font-weight-bolder mr-10">
                    <span class="mr-1">{{ date("Y") }} &copy; Made with  &hearts;	</span>
                    <a href="https://arab-apps.com/" target="_blank" class="text-dark-75 text-hover-primary">Arabapps KW</a>
                </div>
                <!--<a href="#" class="text-primary font-weight-bolder font-size-lg">Terms</a>-->
                <!--<a href="#" class="text-primary ml-5 font-weight-bolder font-size-lg">Plans</a>-->
                <!--<a href="#" class="text-primary ml-5 font-weight-bolder font-size-lg">Contact Us</a>-->
            </div>
            <!--end::Content footer-->
        </div>
        <!--end::Content-->
    </div>
    <!--end::Login-->
</div>
<!--end::Main-->
<script>var HOST_URL = "https://preview.keenthemes.com/metronic/theme/html/tools/preview";</script>
<!--begin::Global Config(global config for global JS scripts)-->
<script>var KTAppSettings = { "breakpoints": { "sm": 576, "md": 768, "lg": 992, "xl": 1200, "xxl": 1400 }, "colors": { "theme": { "base": { "white": "#ffffff", "primary": "#3699FF", "secondary": "#E5EAEE", "success": "#1BC5BD", "info": "#8950FC", "warning": "#FFA800", "danger": "#F64E60", "light": "#E4E6EF", "dark": "#181C32" }, "light": { "white": "#ffffff", "primary": "#E1F0FF", "secondary": "#EBEDF3", "success": "#C9F7F5", "info": "#EEE5FF", "warning": "#FFF4DE", "danger": "#FFE2E5", "light": "#F3F6F9", "dark": "#D6D6E0" }, "inverse": { "white": "#ffffff", "primary": "#ffffff", "secondary": "#3F4254", "success": "#ffffff", "info": "#ffffff", "warning": "#ffffff", "danger": "#ffffff", "light": "#464E5F", "dark": "#ffffff" } }, "gray": { "gray-100": "#F3F6F9", "gray-200": "#EBEDF3", "gray-300": "#E4E6EF", "gray-400": "#D1D3E0", "gray-500": "#B5B5C3", "gray-600": "#7E8299", "gray-700": "#5E6278", "gray-800": "#3F4254", "gray-900": "#181C32" } }, "font-family": "Poppins" };</script>
<!--end::Global Config-->
<!--begin::Global Theme Bundle(used by all pages)-->
<script src="{{asset('plugins/global/plugins.bundle.js')}}"></script>
<script src="{{asset('plugins/custom/prismjs/prismjs.bundle.js')}}"></script>
<script src="{{asset('js/scripts.bundle.js')}}"></script>
<!--end::Global Theme Bundle-->
<!--begin::Page Scripts(used by this page)-->
{{--<script src="{{asset('js/pages/custom/login/login-general.js')}}"></script>--}}
<!--end::Page Scripts-->
</body>
<!--end::Body-->
</html>
