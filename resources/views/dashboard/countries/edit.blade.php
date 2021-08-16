{{-- Extends layout --}}
@extends('layouts.dashboard.default')

{{-- Content --}}
@section('content')
    <div class="card card-custom">
        <div class="card-header flex-wrap border-0 pt-6 pb-0">
            <div class="card-title">
                <h3 class="card-label">{{__('site.country.edit')}}
                    {{--                    <div class="text-muted pt-2 font-size-sm">Datatable initialized from HTML table</div>--}}
                </h3>
            </div>

        </div>

        <div class="card-body">
            <form class="form" method="post" action="{{route('dashboard.countries.update',$country->id)}}" enctype="multipart/form-data">
                @csrf
                {{method_field('PUT')}}
                <div class="card-body">
                    @foreach (LaravelLocalization::getSupportedLocales() as $locale => $properties)
                        <div class="form-group">
                            <label for="{{$locale}}_name">@lang('site.'.$locale.'.name')</label>
                            <input type="text" name="{{$locale}}[name]" class="form-control @error($locale.'.name') is-invalid @enderror" id="{{$locale}}_name" value="{{$country->translate($locale)->name}}" >
                            @error($locale.'.name')
                            <div class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </div>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="{{$locale}}_currency_name">@lang('site.'.$locale.'.currency_name')</label>
                            <input type="text" name="{{$locale}}[currency_name]" class="form-control @error($locale.'.currency_name') is-invalid @enderror" id="{{$locale}}_currency_name" value="{{$country->translate($locale)->currency_name}}" >
                            @error($locale.'.currency_name')
                            <div class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </div>
                            @enderror
                        </div>
                    @endforeach
                    <div class="form-group">
                        <label for="currency_symbol">{{__('site.country.currency_symbol')}}</label>
                        <div class="col-lg-4 col-md-9 col-sm-12">
                            <div class="input-group date" id="currency_symbol" >
                                <input type="text" name="currency_symbol" id="currency_symbol" class="form-control  @error('currency_symbol') is-invalid @enderror"  value="{{$country->currency_symbol}}"/>
                                @error('currency_symbol')
                                <div class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </div>
                                @enderror
                            </div>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-xl-3 col-lg-3 col-form-label">{{__('site.global.image')}}</label>
                        <div class="col-lg-6 col-xl-6">
                            <div class="image-input image-input-outline image-input-circle" id="kt_image_3">
                                <div class="image-input-wrapper" style="background-image: url({{$country->image_path}});background-position: center"></div>

                                <label class="btn btn-xs btn-icon btn-circle btn-white btn-hover-text-primary btn-shadow" data-action="change" data-toggle="tooltip" title="" data-original-title="Change avatar">
                                    <i class="fa fa-pen icon-sm text-muted"></i>
                                    <input type="file" name="image" accept=".png, .jpg, .jpeg, .svg"/>
                                    <input type="hidden" name="has_image"/>
                                </label>

                                <span class="btn btn-xs btn-icon btn-circle btn-white btn-hover-text-primary btn-shadow" data-action="cancel" data-toggle="tooltip" title="Cancel avatar">
                                <i class="ki ki-bold-close icon-xs text-muted"></i>
                            </span>
                            </div>
                        </div>
                    </div>
                    <div class="separator separator-dashed my-8"></div>
                </div>
                <div class="card-footer">
                    <button type="submit" class="btn btn-success font-weight-bold mr-2">{{__('site.operation.edit')}}</button>
                    <a href="{{route('dashboard.countries.index')}}" class="btn btn-light-success font-weight-bold">{{__('site.operation.back')}}</a>
                </div>
            </form>
        </div>

    </div>
@endsection

@section('scripts')
    <script !src="">
        var avatar3 = new KTImageInput('kt_image_3');

    </script>
@endsection
