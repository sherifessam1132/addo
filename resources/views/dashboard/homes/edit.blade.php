{{-- Extends layout --}}
@extends('layouts.dashboard.default')

{{-- Content --}}
@section('content')
    <div class="card card-custom">
        <div class="card-header flex-wrap border-0 pt-6 pb-0">
            <div class="card-title">
                <h3 class="card-label">{{__('site.home.edit')}}
                    {{--                    <div class="text-muted pt-2 font-size-sm">Datatable initialized from HTML table</div>--}}
                </h3>
            </div>

        </div>

        <div class="card-body">
            <form class="form" method="post" action="{{route('dashboard.homes.update',$home->id)}}" enctype="multipart/form-data">
                @csrf
                {{method_field('PUT')}}
                <div class="card-body">
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
                    <div class="separator separator-dashed my-8"></div>
                </div>
                <div class="card-footer">
                    <button type="submit" class="btn btn-success font-weight-bold mr-2">{{__('site.operation.edit')}}</button>
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
                ajax(e.target.value , 0)
            })

            var parent_id = $('#parent_id').val();
            var category_id = '{{$home->category_id}}';
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
        }
    </script>
@endsection
