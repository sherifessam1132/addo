{{-- Extends layout --}}
@extends('layouts.dashboard.default')

{{-- Content --}}
@section('content')
    <div class="card card-custom">
        <div class="card-header flex-wrap border-0 pt-6 pb-0">
            <div class="card-title">
                <h3 class="card-label">{{__('site.attribute.edit_child')}}
                    <div class="text-muted pt-2 font-size-sm">{{$attribute->name}} - {{$value->value}} - {{$child->name}}</div>
                </h3>
            </div>

        </div>

        <div class="card-body">
            <form class="form" method="post" action="{{route('dashboard.attributes.updateValues',[
                                            'attribute'=>$attribute->id,
                                            'child'=>$child->id,
                                            'value'=>$value->id
                                        ])}}" enctype="multipart/form-data">
                @csrf
                {{method_field('PUT')}}
                <div class="card-body">
                    <div class="separator separator-dashed my-8"></div>
                    <div id="kt_repeater_1">
                        <div class="form-group row" id="kt_repeater_1" >
                            <label class="col-lg-2 col-form-label text-right">{{__('site.attribute.values')}}:</label>
                            <div data-repeater-list="values" class="col-lg-10">
                                @if($child->values)
                                    @foreach($child->values as $key => $myValue)
                                        <div data-repeater-item class="form-group row align-items-center">
                                            @foreach (LaravelLocalization::getSupportedLocales() as $locale => $properties)
                                                <div class="col-md-6">
                                                    <label for="">{{__('site.attribute.'.$locale.'.value')}}:</label>
                                                    <input type="text"  name="values[{{$key}}][{{$locale}}][]" class="form-control" value="{{$myValue->translate($locale)->value}}"/>
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
                                                    <input type="hidden" name="values[{{$key}}][value_id]" value="{{$value->id}}" />
                                                    <div class="d-md-none mb-2"></div>
                                                </div>
                                                <input type="hidden" name="values[{{$key}}][id]" value="{{$myValue->id}}">
                                            </div>
                                            <div class="col-md-4 mt-6">
                                                <a href="javascript:;" data-repeater-delete="" data-delete-link="{{route('dashboard.attribute.value.delete',$myValue->id)}}" class="btn btn-sm font-weight-bolder btn-light-danger">
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
            KTFormRepeater.init();
        });

        $(function (){
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
    </script>
@endsection
