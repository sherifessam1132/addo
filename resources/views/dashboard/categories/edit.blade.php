{{-- Extends layout --}}
@extends('layouts.dashboard.default')

{{-- Content --}}
@section('content')
    <div class="card card-custom">
        <div class="card-header flex-wrap border-0 pt-6 pb-0">
            <div class="card-title">
                <h3 class="card-label">{{__('site.category.edit')}}
                    {{--                    <div class="text-muted pt-2 font-size-sm">Datatable initialized from HTML table</div>--}}
                </h3>
            </div>

        </div>

        <div class="card-body">
            <form class="form" method="post" action="{{route('dashboard.categories.update',$category->id)}}" enctype="multipart/form-data">
                @csrf
                {{method_field('PUT')}}
                <div class="card-body">
                    @foreach (LaravelLocalization::getSupportedLocales() as $locale => $properties)
                        <div class="form-group">
                            <label for="{{$locale}}_name">@lang('site.'.$locale.'.name')</label>
                            <input type="text" name="{{$locale}}[name]" class="form-control @error($locale.'.name') is-invalid @enderror" id="{{$locale}}_name" value="{{$category->translate($locale)->name}}" >
                            @error($locale.'.name')
                            <div class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </div>
                            @enderror
                        </div>
                    @endforeach
                    <div class="form-group row">
                        <label class="col-xl-3 col-lg-3 col-form-label">{{__('site.global.image')}}</label>
                        <div class="col-lg-6 col-xl-6">
                            <div class="image-input image-input-outline image-input-circle" id="kt_image_3">
                                <div class="image-input-wrapper" style="background-image: url({{$category->image_path}})"></div>

                                <label class="btn btn-xs btn-icon btn-circle btn-white btn-hover-text-primary btn-shadow" data-action="change" data-toggle="tooltip" title="" data-original-title="Change avatar">
                                    <i class="fa fa-pen icon-sm text-muted"></i>
                                    <input type="file" name="image" accept=".png, .jpg, .jpeg"/>
                                    <input type="hidden" name="has_image"/>
                                </label>

                                <span class="btn btn-xs btn-icon btn-circle btn-white btn-hover-text-primary btn-shadow" data-action="cancel" data-toggle="tooltip" title="Cancel avatar">
                            <i class="ki ki-bold-close icon-xs text-muted"></i>
                        </span>
                            </div>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="parent_id" class="col-form-label col-lg-3 col-sm-12">{{__('site.category.parent_id')}}</label>
                        <div class=" col-lg-4 col-md-9 col-sm-12">
                            <select class="form-control select2" id="parent_id" name="parent_id">
                                <option value=""></option>
                                @foreach($main_Categories as $key=>$value)
                                    <option value="{{$key}}" {{$parent_id == $key ? 'selected' : '' }}>{{$value}}</option>
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
                      @if($category->parent_id != null)
                        <div class="form-group row">
                            <label for="attribute_id" class="col-form-label col-lg-3 col-sm-12">{{__('site.attribute.name')}}</label>
                            <div class=" col-lg-4 col-md-9 col-sm-12">
                                <select class="form-control select2" id="attribute_id" name="attribute_id">
                                    <option value=""></option>
                                    @foreach($attributes as $key=>$value)
                                        <option value="{{$key}}" {{$category->attribute_id == $key ? 'selected' : '' }}>{{$value}}</option>
                                    @endforeach
                                </select>
                                @error('attribute_id')
                                <div class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </div>
                                @enderror
                            </div>
                        </div>
                        @endif
                    <div class="form-group row">
                        <label for="max_price">{{__('site.category.max_price')}}</label>
                        <div class=" col-sm-12">
                            <div class="input-group " >
                                <input type="text" name="max_price" id="max_price" class="form-control  @error('max_price') is-invalid @enderror" value="{{$category->max_price}}"  />
                                @error('max_price')
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
                    <button type="submit" class="btn btn-success font-weight-bold mr-2">{{__('site.operation.edit')}}</button>
                    <a href="{{route('dashboard.categories.index')}}" class="btn btn-light-success font-weight-bold">{{__('site.operation.back')}}</a>
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
                @if($category->parent_id != null)
                 @if($category->parent->parent_id != null)
                    $('#attribute_id').select2({
                        placeholder: "Select a state"
                    });
                @endif
                @endif
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
            $('body').on('change','#parent_id',function (e){
                ajax(e.target.value , 0)
            })

            var parent_id = $('#parent_id').val();
            var category_id = '{{$category->parent_id}}';
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
                        sub_category += `<option value="${value.id}"  ${category_id != 0 && category_id==value.id ? 'selected' : ''}>${value.name}</option>`
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
                            @if($category->parent_id != null)
                            @if($category->parent->parent_id != null)
                                $('#attribute_id').select2({
                                    placeholder: "Select a state"
                                });
                                @endif
                            @endif
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
        }
    </script>
@endsection
