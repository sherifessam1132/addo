{{-- Extends layout --}}
@extends('layouts.dashboard.default')

{{-- Content --}}
@section('content')
    <div class="card card-custom">
        <div class="card-header flex-wrap border-0 pt-6 pb-0">
            <div class="card-title">
                <h3 class="card-label">{{__('site.attribute.edit')}}
                    {{--                    <div class="text-muted pt-2 font-size-sm">Datatable initialized from HTML table</div>--}}
                </h3>
            </div>

        </div>

        <div class="card-body">
            <form class="form" method="post" action="{{route('dashboard.attributes.update',$attribute->id)}}" enctype="multipart/form-data">
                @csrf
                {{method_field('PUT')}}
                <div class="card-body">
                    @foreach (LaravelLocalization::getSupportedLocales() as $locale => $properties)
                        <div class="form-group">
                            <label for="{{$locale}}_name">@lang('site.'.$locale.'.name')</label>
                            <input type="text" name="{{$locale}}[name]" class="form-control @error($locale.'.name') is-invalid @enderror" id="{{$locale}}_name" value="{{$attribute->translate($locale)->name}}"  >
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
                                    <option value="{{$key}}" {{ $parent_id == $key ? 'selected' : '' }}>{{$value}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div id="show_sub_category"></div>
                    <div id="show_sub_sub_category"></div>
                    <div class="separator separator-dashed my-8"></div>
                    <div id="kt_repeater_1">
                        <div class="form-group row" id="kt_repeater_1" >
                            <label class="col-lg-2 col-form-label text-right">{{__('site.attribute.values')}}:</label>
                            <div data-repeater-list="values" class="col-lg-10">
                                @if($attribute->values)
                                    @foreach($attribute->values as $key => $value)
                                        <div data-repeater-item class="form-group row align-items-center">
                                            @foreach (LaravelLocalization::getSupportedLocales() as $locale => $properties)
                                                <div class="col-md-6">
                                                    <label for="">{{__('site.attribute.'.$locale.'.value')}}:</label>
                                                    <input type="text"  name="values[{{$key}}][{{$locale}}][]" class="form-control" value="{{$value->translate($locale)->value}}"/>
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
                                                <input type="hidden" name="values[{{$key}}][id]" value="{{$value->id}}">
                                            </div>
                                            <div class="col-md-4 mt-6">
                                                <a href="javascript:;" data-repeater-delete="" data-delete-link="{{route('dashboard.attribute.value.delete',$value->id)}}" class="btn btn-sm font-weight-bolder btn-light-danger">
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
                    <button type="submit" class="btn btn-success font-weight-bold mr-2">{{__('site.operation.edit')}}</button>
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
                        $(this).find('[data-delete-link]').removeAttr('data-delete-link')
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
                ajax(e.target.value , 0)
            })

            var parent_id = $('#parent_id').val();
            var category_id = '{{$attribute->category_id}}';
            ajax(parent_id , category_id)
            $(function (){
                $('body').on('click', '[data-repeater-delete]',function (){
                    $.ajax({
                        url:$(this).data('delete-link'),
                        method: "POST",
                        data: {
                            "_token": "{{ csrf_token() }}",
                        },
                        success:function (data){
                            console.log(data)
                        },
                        error:function (error){
                            console.log(data)
                        }
                    })
                })
            })
        })

        function ajax(parent_id , category_id){
            $.ajax({
                url:'{{route('dashboard.categories.index')}}/'+parent_id,
                data:{
                    _token:'{{csrf_token()}}'
                },
                success:function (data){
                    var sub_category='';
                    $.each(data,function (key,value){
                        sub_category += `<option value="${value.id}"  ${'{{$category_id}}'==value.id ? 'selected' : ''}>${value.name}</option>`
                        console.log('{{$category_id}}');
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
                    
                    
                    
                    
                    
                    @if($sub_category_id!=null)
                    $.ajax({
                url:'{{route('dashboard.categories.index')}}/'+'{{$category_id}}',
                data:{
                    _token:'{{csrf_token()}}'
                },
                success:function (data){
                    var sub_sub_category='';
                    $.each(data,function (key,value){
                        sub_sub_category += `<option value="${value.id}"  ${ {{$sub_category_id}}==value.id ? 'selected' : ''}>${value.name}</option>`
                    })
                    $('#show_sub_sub_category').html(`
                            <div class="form-group row">
                                <label for="sub_category_id" class="col-form-label col-lg-3 col-sm-12">{{__('site.attribute.category')}}</label>
                                <div class=" col-lg-4 col-md-9 col-sm-12">
                                    <select class="form-control select2" id="sub_category_id" name="sub_category_id">
                                        <option value="">Select one</option>
                                        ${sub_sub_category}
                                    </select>
                                </div>
                            </div>
                        `);

                    // Class definition
                    var KTSelect2_2_2 = function() {
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
                        KTSelect2_2_2.init();
                    });
                    
                }
            })
                    
                    
                    
                    
                    
                    
                    @endif
                    
                    
                    
                }
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
                                        <option value="">All</option>
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
        }
    </script>
@endsection
