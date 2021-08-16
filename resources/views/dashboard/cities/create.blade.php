{{-- Extends layout --}}
@extends('layouts.dashboard.default')

{{-- Content --}}
@section('content')
    <div class="card card-custom">
        <div class="card-header flex-wrap border-0 pt-6 pb-0">
            <div class="card-title">
                <h3 class="card-label">{{__('site.city.create')}}
                    {{--                    <div class="text-muted pt-2 font-size-sm">Datatable initialized from HTML table</div>--}}
                </h3>
            </div>

        </div>

        <div class="card-body">
            <form class="form" method="post" action="{{route('dashboard.cities.store')}}" enctype="multipart/form-data">
                @csrf
                <div class="card-body">
                    @foreach (LaravelLocalization::getSupportedLocales() as $locale => $properties)
                        <div class="form-group">
                            <label for="{{$locale}}_name">@lang('site.'.$locale.'.name')</label>
                            <input type="text" name="{{$locale}}[name]" class="form-control @error($locale.'.name') is-invalid @enderror" id="{{$locale}}_name" value="{{old($locale.'.name')}}" >
                            @error($locale.'.name')
                            <div class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </div>
                            @enderror
                        </div>
                    @endforeach
                    <div class="form-group row">
                            <label for="country_id" class="col-form-label col-lg-3 col-sm-12">{{__('site.city.country_id')}}</label>
                            <div class=" col-lg-4 col-md-9 col-sm-12">
                                <select class="form-control select2" id="country_id" name="country_id">
                                    <option value=""></option>
                                    @foreach($countries as $key=>$value)
                                        <option value="{{$key}}" {{old('country_id') == $value ? 'selected' : '' }}>{{$value}}</option>
                                    @endforeach
                                </select>
                                @error('country_id')
                                <div class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </div>
                                @enderror
                            </div>
                        </div>
                    <div class="form-group row">
                        <label for="governorate_id" class="col-form-label col-lg-3 col-sm-12">{{__('site.city.governorate_id')}}</label>
                        <div class=" col-lg-4 col-md-9 col-sm-12">
                            <select class="form-control select2" id="governorate_id" name="governorate_id">
                                <option value=""></option>
                            </select>
                            @error('governorate_id')
                            <div class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </div>
                            @enderror
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="longitude">{{__('site.country.longitude')}}</label>
                        <div class="col-lg-4 col-md-9 col-sm-12">
                            <div class="input-group date" id="longitude" >
                                <input type="text" name="longitude" id="longitude" class="form-control  @error('longitude') is-invalid @enderror"  value="{{old('longitude')}}"/>
                                @error('longitude')
                                <div class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </div>
                                @enderror
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="latitude">{{__('site.country.latitude')}}</label>
                        <div class="col-lg-4 col-md-9 col-sm-12">
                            <div class="input-group date" id="latitude" >
                                <input type="text" name="latitude" id="latitude" class="form-control  @error('latitude') is-invalid @enderror"  value="{{old('latitude')}}"/>
                                @error('latitude')
                                <div class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </div>
                                @enderror
                            </div>
                        </div>
                    </div>
                    <div class="separator separator-dashed my-8"></div>
                </div>
                <div class="card-footer">
                    <button type="submit" class="btn btn-success font-weight-bold mr-2">{{__('site.operation.add')}}</button>
                    <a href="{{route('dashboard.cities.index')}}" class="btn btn-light-success font-weight-bold">{{__('site.operation.back')}}</a>
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
                $('#governorate_id').select2({
                    placeholder: "Select a state"
                });
                $('#country_id').select2({
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

        $(function (){
            $('body').on('change','#country_id',function (e){
                if (e.target.value){
                    $.ajax({
                        url:'{{route('dashboard.countries.index')}}/'+e.target.value,
                        data:{
                            _token:'{{csrf_token()}}'
                        },
                        success:function (data){
                            $('#governorate_id').html(`
                                <option value="">All</option>
                            `)
                            $.each(data,function (key,value){
                                $('#governorate_id').append(`
                                    <option value="${value.id}">${value.name}</option>
                                `);
                            })
                        }
                    })
                }else {
                    $('#governorate_id').html(`
                            <option value="">All</option>
                        `);}
            })
        })
    </script>
@endsection
