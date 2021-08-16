{{--
Template Name: Arabapps - Responsive Admin Dashboard  build with Bootstrap 4 & Angular 8
Author: Arabapps
Website: http://Araba-pps.com/
Contact: support@Araba-pps.com
Follow: www.twitter.com/Araba-pps
Dribbble: www.dribbble.com/Araba-pps
Like: www.facebook.com/Araba-pps
Purchase: http://themeforest.net/item/metronic-responsive-admin-dashboard-template/4021469?ref=keenthemes
Renew Support: http://themeforest.net/item/metronic-responsive-admin-dashboard-template/4021469?ref=keenthemes
License: You must have a valid license purchased only from themeforest(the above link) in order to legally use the theme for your project.
 --}}
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" {{ \App\Classes\Theme\Metronic::printAttrs('html') }} {{ \App\Classes\Theme\Metronic::printClasses('html') }}>
    <head>
        <meta charset="utf-8"/>

        {{-- Title Section --}}
        <title>{{  $site_settings->name ?? config('app.name') }} | @yield('title', $page_title ?? '')</title>

        {{-- Meta Data --}}
        <meta name="description" content="@yield('page_description', $page_description ?? '')"/>
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no"/>

        <!-- CSRF Token -->
        <meta name="csrf-token" content="{{ csrf_token() }}">

        {{-- Favicon --}}
        <link rel="shortcut icon" href="{{ $site_settings->title_icon_path ?? asset('media/logos/favicon.ico') }}" />

        {{-- Fonts --}}
        {{ \App\Classes\Theme\Metronic::getGoogleFontsInclude() }}

        {{-- Global Theme Styles (used by all pages) --}}
        @foreach(config('layout.resources.css') as $style)
            <link href="{{ app()->getLocale() == 'ar' ? asset(\App\Classes\Theme\Metronic::rtlCssPath($style)) : asset($style) }}" rel="stylesheet" type="text/css"/>
        @endforeach

        {{-- Layout Themes (used by all pages) --}}
        @foreach (\App\Classes\Theme\Metronic::initThemes() as $theme)
            <link href="{{ app()->getLocale() == 'ar' ? asset(\App\Classes\Theme\Metronic::rtlCssPath($theme)) : asset($theme) }}" rel="stylesheet" type="text/css"/>
        @endforeach

        <style>
            .invalid-feedback{
                display: block;
            }
        </style>
        @if (app()->getLocale() == 'ar')
            <style>
                body{
                    direction: rtl;
                    font-family: 'cairo', sans-serif;
                    font-size: 50px;
                }
                .select2-selection__rendered{
                    direction: rtl;
                }
                .ck-editor__main *{
                    text-align: right;
                }
            </style>
        @endif
        {{-- Includable CSS --}}
        @yield('styles')
    </head>

    <body {{ \App\Classes\Theme\Metronic::printAttrs('body') }} {{ \App\Classes\Theme\Metronic::printClasses('body') }}>

        @if (config('layout.page-loader.type') != '')
            @include('layouts.dashboard.partials._page-loader')
        @endif

        @include('layouts.dashboard.base._layout')

{{--        <script>var HOST_URL = "{{ route('quick-search') }}";</script>--}}

        {{-- Global Config (global config for global JS scripts) --}}
        <script>
            var KTAppSettings = {!! json_encode(config('layout.js'), JSON_PRETTY_PRINT|JSON_UNESCAPED_SLASHES) !!};
        </script>

        {{-- Global Theme JS Bundle (used by all pages)  --}}
        @foreach(config('layout.resources.js') as $script)
            <script src="{{ asset($script) }}" type="text/javascript"></script>
        @endforeach
        <script src="{{asset('plugins/custom/ckeditor/ckeditor-classic.bundle.js')}}"></script>
        <script src="{{asset('js/pages/crud/forms/widgets/bootstrap-touchspin.js')}}"></script>
        <script src="{{asset('js/pages/crud/forms/widgets/select2.js')}}"></script>
        <script src="{{asset('js/pages/crud/forms/widgets/bootstrap-datetimepicker.js')}}"></script>
        @include('layouts.dashboard.scripts.touchspin')
        @include('layouts.dashboard.scripts.select2')
        @include('layouts.dashboard.scripts.datetimepicker')

        {{-- Includable JS --}}
        @yield('scripts')
        @include('layouts.dashboard.partials._errors')
        @include('layouts.dashboard.partials._session')
        <script>
            moment("12-25-1995", "MM-DD-YYYY");
        </script>
    </body>
</html>

