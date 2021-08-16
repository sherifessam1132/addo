{{-- Extends layout --}}
@extends('layouts.dashboard.default')

{{-- Content --}}
@section('content')
    <div class="card card-custom">
        <div class="card-header flex-wrap border-0 pt-6 pb-0">
            <div class="card-title">
                <h3 class="card-label">{{__('site.home.create')}}
                    {{--                    <div class="text-muted pt-2 font-size-sm">Datatable initialized from HTML table</div>--}}
                </h3>
            </div>

        </div>

        <div class="card-body">
            <form class="form" method="post" action="{{route('dashboard.homes.store')}}" enctype="multipart/form-data">
                @csrf
                <div class="card-body">
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
                    <div class="separator separator-dashed my-8"></div>
                </div>
                <div class="card-footer">
                    <button type="submit" class="btn btn-success font-weight-bold mr-2">{{__('site.operation.add')}}</button>
                    <a href="{{route('dashboard.homes.index')}}" class="btn btn-light-success font-weight-bold">{{__('site.operation.back')}}</a>
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


        // Initialization
        jQuery(document).ready(function() {
            KTSelect2.init();
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
                                <label for="category_id" class="col-form-label col-lg-3 col-sm-12">{{__('site.home.category')}}</label>
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
        })
    </script>
@endsection
