{{-- Extends layout --}}
@extends('layouts.dashboard.default')

{{-- Content --}}
@section('content')
    <div class="card card-custom">
        <div class="card-header flex-wrap border-0 pt-6 pb-0">
            <div class="card-title">
                <h3 class="card-label">{{__('site.attribute.create')}}
                    {{--                    <div class="text-muted pt-2 font-size-sm">Datatable initialized from HTML table</div>--}}
                </h3>
            </div>

        </div>

        <div class="card-body">
            <form class="form" method="post" action="{{route('dashboard.attributes.store')}}" enctype="multipart/form-data">
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
                        <label for="parent_id" class="col-form-label col-lg-3 col-sm-12">{{__('site.category.parent_id')}}</label>
                        <div class=" col-lg-4 col-md-9 col-sm-12">
                            <select class="form-control select2" id="parent_id" name="parent_id">
                                <option value=""></option>
                                @foreach($main_Categories as $key=>$value)
                                    <option value="{{$key}}" {{old('parent_id') == $value ? 'selected' : '' }}>{{$value}}</option>
                                @endforeach
                            </select>
                            @error('parent_id')
                            <div class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </div>
                            @enderror
                        </div>
                    </div>
                    <div id="show_sub_category"></div>
                    <div id="show_sub_sub_category"></div>
                    <div class="separator separator-dashed my-8"></div>
                    <div id="kt_repeater_1">
                        <div class="form-group row" id="kt_repeater_1" >
                            <label class="col-lg-2 col-form-label text-right">{{__('site.attribute.values')}}:</label>
                            <div data-repeater-list="values" class="col-lg-10">
                                @if(old('values'))
                                    @foreach(old('values') as $key => $value)
                                        <div data-repeater-item class="form-group row align-items-center">
                                            @foreach (LaravelLocalization::getSupportedLocales() as $locale => $properties)
                                                <div class="col-md-6">
                                                    <label for="">{{__('site.attribute.'.$locale.'.value')}}:</label>
                                                    <input type="text"  name="values[{{$key}}][{{$locale}}][]" class="form-control" value="{{old('values.'.$key.'.'.$locale)}}"/>
                                                    @error('values.'.$key.'.'.$locale)
                                                    <div class="invalid-feedback" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </div>
                                                    @enderror
                                                    <div class="d-md-none mb-2"></div>
                                                </div>
                                            @endforeach
                                            <div class="col-md-6">
                                                <div class="form-group row mt-5">
                                                    <label for="file">{{__('site.attribute.value_image')}}:</label>
                                                    <input type="file" name="values[{{$key}}][image]" class="form-control" />
                                                    <div class="d-md-none mb-2"></div>
                                                </div>

                                            </div>
                                            <div class="col-md-4 mt-6">
                                                <a href="javascript:;" data-repeater-delete="" class="btn btn-sm font-weight-bolder btn-light-danger">
                                                    <i class="la la-trash-o"></i>{{__('site.global.delete')}}
                                                </a>
                                            </div>
                                        </div>
                                    @endforeach
                                @else
                                    <div data-repeater-item class="form-group row align-items-center">
                                        @foreach (LaravelLocalization::getSupportedLocales() as $locale => $properties)
                                            <div class="col-md-6">
                                                <label for="">{{__('site.attribute.'.$locale.'.value')}}:</label>
                                                <input type="text"  name="values[0][{{$locale}}][]"  class="form-control"/>
                                                <div class="d-md-none mb-2"></div>
                                            </div>
                                        @endforeach
                                        <div class="col-md-6">
                                            <div class="form-group row mt-5">
                                                <div class="form-group row mt-5">
                                                    <label for="file">{{__('site.attribute.value_image')}}:</label>
                                                    <input type="file" name="values[0][image]" class="form-control" />
                                                    <div class="d-md-none mb-2"></div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-4 mt-6">
                                            <a href="javascript:;" data-repeater-delete="" class="btn btn-sm font-weight-bolder btn-light-danger">
                                                <i class="la la-trash-o"></i>{{__('site.global.delete')}}
                                            </a>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-lg-2 col-form-label text-right"></label>
                            <div class="col-lg-4">
                                <a href="javascript:;" data-repeater-create="" class="btn btn-sm font-weight-bolder btn-light-primary">
                                    <i class="la la-plus"></i>Add
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-footer">
                    <button type="submit" class="btn btn-success font-weight-bold mr-2">{{__('site.operation.add')}}</button>
                    <a href="{{route('dashboard.attributes.index')}}" class="btn btn-light-success font-weight-bold">{{__('site.operation.back')}}</a>
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
                $('#parent_id').select2({
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

        // Class definition
        var KTFormRepeater = function() {

            // Private functions
            var demo1 = function() {
                $('#kt_repeater_1').repeater({
                    initEmpty: false,

                    show: function () {
                        // $(this).find('.form-control').attr('disabled', false);
                        $(this).slideDown();

                    },
                    hide: function (deleteElement) {
                        $(this).slideUp(deleteElement);
                    },
                    // isFirstItemUndeletable: true
                });
            }

            return {
                // public functions
                init: function() {
                    demo1();
                }
            };
        }();

        // Initialization
        jQuery(document).ready(function() {
            KTSelect2.init();
            KTFormRepeater.init();
        });

        $(function (){
            $('body').on('change','#parent_id',function (e){
                var sub_category='';
                $.ajax({
                    url:'{{route('dashboard.categories.index')}}/'+e.target.value,
                    data:{
                        _token:'{{csrf_token()}}'
                    },
                    success:function (data){
                        $.each(data,function (key,value){
                            sub_category += `<option value="${value.id}">${value.name}</option>`
                        })
                        $('#show_sub_category').html(`
                            <div class="form-group row">
                                <label for="category_id" class="col-form-label col-lg-3 col-sm-12">{{__('site.attribute.category')}}</label>
                                <div class=" col-lg-4 col-md-9 col-sm-12">
                                    <select class="form-control select2" id="category_id" name="category_id">
                                        <option value=""></option>
                                        ${sub_category}
                                    </select>
                                </div>
                            </div>
                        `);

                        // Class definition
                        var KTSelect2_2 = function() {
                            // Private functions
                            var demos = function() {
                                // basic
                                $('#category_id').select2({
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
                            KTSelect2_2.init();
                        });
                    }
                })
            })
             $('body').on('change','#category_id',function (e){
                var sub_category='';
                $.ajax({
                    url:'{{route('dashboard.categories.index')}}/'+e.target.value,
                    data:{
                        _token:'{{csrf_token()}}'
                    },
                    success:function (data){
                        $.each(data,function (key,value){
                            sub_category += `<option value="${value.id}">${value.name}</option>`
                        })
                        $('#show_sub_sub_category').html(`
                            <div class="form-group row">
                                <label for="sub_category_id" class="col-form-label col-lg-3 col-sm-12">{{__('site.attribute.category')}}</label>
                                <div class=" col-lg-4 col-md-9 col-sm-12">
                                    <select class="form-control select2" id="sub_category_id" name="sub_category_id">
                                        <option value=""></option>
                                        ${sub_category}
                                    </select>
                                </div>
                            </div>
                        `);

                        // Class definition
                        var KTSelect2_2 = function() {
                            // Private functions
                            var demos = function() {
                                // basic
                                $('#sub_category_id').select2({
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
                            KTSelect2_2.init();
                        });
                    }
                })
            })
        })
    </script>
@endsection
