{{-- Extends layout --}}
@extends('layouts.dashboard.default')

{{-- Content --}}
@section('content')
    <div class="card card-custom">
        <div class="card-header flex-wrap border-0 pt-6 pb-0">
            <div class="card-title">
                <h3 class="card-label">{{__('site.packageType.edit')}}
                    {{--                    <div class="text-muted pt-2 font-size-sm">Datatable initialized from HTML table</div>--}}
                </h3>
            </div>
        </div>

        <div class="card-body">
            <form class="form" method="post" action="{{route('dashboard.packageTypes.update',$packageType->id)}}" enctype="multipart/form-data">
                @csrf
                {{method_field('PUT')}}
                <div class="card-body">
                    <div class="form-group row">
                        <label class="col-form-label col-lg-3 col-sm-12" for="price">{{__('site.packageType.price')}}</label>
                        <div class=" col-lg-4 col-md-9 col-sm-12">
                            <input type="number" id="price" name="price" class="form-control" value="{{$packageType->price }}" />
                        </div>
                        @error('price')
                        <div class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </div>
                        @enderror
                    </div>
                    <div class="form-group row">
                        <label class="col-form-label col-lg-3 col-sm-12" for="days">{{__('site.packageType.days')}}</label>
                        <div class=" col-lg-4 col-md-9 col-sm-12">
                            <input type="number" id="days" name="days" class="form-control" value="{{$packageType->days }}" />
                        </div>
                        @error('days')
                        <div class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </div>
                        @enderror
                    </div>
                    <div class="form-group row">
                        <label class="col-form-label col-lg-3 col-sm-12" for="visible_loop_times">{{__('site.packageType.visible_loop_times')}}</label>
                        <div class=" col-lg-4 col-md-9 col-sm-12">
                            <input type="number" id="visible_loop_times" name="visible_loop_times" class="form-control" value="{{$packageType->visible_loop_times }}" />
                        </div>
                        @error('visible_loop_times')
                        <div class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </div>
                        @enderror
                    </div>
                    <div class="form-group row">
                        <label class="col-form-label col-lg-3 col-sm-12" for="visible_toClient_perDay_times">{{__('site.packageType.visible_toClient_perDay_times')}}</label>
                        <div class=" col-lg-4 col-md-9 col-sm-12">
                            <input type="number" id="visible_toClient_perDay_times" name="visible_toClient_perDay_times" class="form-control" value="{{$packageType->visible_toClient_perDay_times }}" />
                        </div>
                        @error('visible_toClient_perDay_times')
                        <div class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </div>
                        @enderror
                    </div>
                    <div class="form-group row">
                        <label for="package_id" class="col-form-label col-lg-3 col-sm-12">{{__('site.packageType.package_id')}}</label>
                        <div class=" col-lg-4 col-md-9 col-sm-12">
                            <select class="form-control select2" id="package_id" name="package_id">
                                <option value=""></option>
                                @foreach($packages as $key=>$value)
                                    <option value="{{$key}}" {{$packageType->package_id == $key ? 'selected' : '' }}>{{$value}}</option>
                                @endforeach
                            </select>
                            @error('package_id')
                            <div class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </div>
                            @enderror
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-2 col-form-label">Pinned</label>
                        <div class="col-4 col-form-label">
                            <div class="radio-inline">
                                <label class="radio radio-success">
                                    <input type="radio" name="pinned" value="1" {{$packageType->pinned == '1' ? 'checked' : '' }}  />
                                    <span></span>
                                    Yes
                                </label>
                                <label class="radio radio-success">
                                    <input type="radio" name="pinned" value="0" {{$packageType->pinned == '0' ? 'checked' : '' }} />
                                    <span></span>
                                    No
                                </label>
                            </div>
                        </div>
                    </div>
                    @foreach (LaravelLocalization::getSupportedLocales() as $locale => $properties)
                        <div class="form-group">
                            <label for="{{$locale}}_description">@lang('site.'.$locale.'.description')</label>
                            <input type="text" name="{{$locale}}[description]" class="form-control @error($locale.'.description') is-invalid @enderror" id="{{$locale}}_description" value="{{$packageType->translate($locale)->description??''}}" >
                            @error($locale.'.description')
                            <div class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </div>
                            @enderror
                        </div>
                    @endforeach
                    <div class="separator separator-dashed my-8"></div>
                </div>
                <div class="card-footer">
                    <button type="submit" class="btn btn-success font-weight-bold mr-2">{{__('site.operation.edit')}}</button>
                    <a href="{{route('dashboard.packageTypes.index')}}" class="btn btn-light-success font-weight-bold">{{__('site.operation.back')}}</a>
                </div>
            </form>
        </div>

    </div>
@endsection

@section('scripts')
    <script !src="">
        var avatar3 = new KTImageInput('kt_image_3');
        // Class definition
        var KTSelect2 = function() {
            // Private functions
            var demos = function() {
                // basic
                $('#package_id').select2({
                    placeholder: "Select a state"
                });
            }
            // Public functions
            return {
                init: function() {
                    demos();
                }
            };
        }();

        // Initialization
        jQuery(document).ready(function() {
            KTSelect2.init();
        });
    </script>
@endsection
