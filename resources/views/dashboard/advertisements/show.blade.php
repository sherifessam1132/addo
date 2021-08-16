{{-- Extends layout --}}
@extends('layouts.dashboard.default')

{{-- Content --}}
@section('content')
    <div class="card card-custom">
        <div class="card-header flex-wrap border-0 pt-6 pb-0">
            <div class="card-title">
                <h3 class="card-label">{{__('site.advertisement.showOne')}}
                    {{--                    <div class="text-muted pt-2 font-size-sm">Datatable initialized from HTML table</div>--}}
                </h3>
            </div>

        </div>

        <div class="card-body">
            <div class="container">
                <div class="row mb-5">
                    <div class="col-md-3 text-capitalize text-hover-info">{{__('site.advertisement.name')}}</div>
                    <div class="col-md-9">{{$advertisement->name}}</div>
                </div>
                <div class="row mb-5">
                    <div class="col-md-3 text-capitalize text-hover-info">{{__('site.advertisement.description')}}</div>
                    <div class="col-md-9">{{$advertisement->description}}</div>
                </div>
                <div class="row mb-5">
                    <div class="col-md-3 text-capitalize text-hover-info">{{__('site.advertisement.price')}}</div>
                    <div class="col-md-9">{{$advertisement->price}}</div>
                </div>
                <div class="row mb-5">
                    <div class="col-md-3 text-capitalize text-hover-info">{{__('site.advertisement.expired_at')}}</div>
                    <div class="col-md-9">{{$advertisement->expired_at}}</div>
                </div>
                <div class="row mb-5">
                    <div class="col-md-3 text-capitalize text-hover-info">{{__('site.advertisement.video_link')}}</div>
                    <div class="col-md-9">
                        <a href="{{$advertisement->video_link}}" target="_blank" class="btn-link">Go to Video</a>
                    </div>
                </div>
                <div class="row mb-5">
                    <div class="col-md-3 text-capitalize text-hover-info">{{__('site.advertisement.category')}}</div>
                    <div class="col-md-9">{{$advertisement->category->name}}</div>
                </div>
                <div class="row mb-5">
                    <div class="col-md-3 text-capitalize text-hover-info">{{__('site.advertisement.client')}}</div>
                    <div class="col-md-9">{{$advertisement->client->name}}</div>
                </div>
                <div class="row mb-5">
                    <div class="col-md-3 text-capitalize text-hover-info">{{__('site.advertisement.city')}}</div>
                    <div class="col-md-9">{{$advertisement->city->name}}</div>
                </div>
                <div class="row mb-5">
                    <div class="col-md-3 text-capitalize text-hover-info">{{__('site.advertisement.cover')}}</div>
                    <div class="col-md-9"><img src="{{$advertisement->cover_path}}" class="img-thumbnail" width="200" height="200" alt=""></div>
                </div>
            </div>
            @if(count($advertisement->images)>0)
            <div class="row mb-5">
                <div class="col-md-3 text-capitalize text-hover-info">{{__('site.advertisement.images')}}</div>
                <div class="col-md-9">
                    <div id="carouselExampleIndicators" class="carousel slide" data-ride="carousel">
                        <ol class="carousel-indicators">
                            @foreach($advertisement->images as $key => $image)
                                <li data-target="#carouselExampleIndicators" data-slide-to="{{$key}}" class="{{$key == 0 ? 'active' : ''}}"></li>
                            @endforeach
                        </ol>
                        <div class="carousel-inner">
                            @foreach($advertisement->images as $key => $image)
                                <div class="carousel-item {{$key == 0 ? 'active' : ''}}">
                                    <img class="d-block w-100" height="400" src="{{$image->image_path}}" alt="First slide">
                                </div>
                            @endforeach

                        </div>
                        <a class="carousel-control-prev" href="#carouselExampleIndicators" role="button" data-slide="prev">
                            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                            <span class="sr-only">Previous</span>
                        </a>
                        <a class="carousel-control-next" href="#carouselExampleIndicators" role="button" data-slide="next">
                            <span class="carousel-control-next-icon" aria-hidden="true"></span>
                            <span class="sr-only">Next</span>
                        </a>
                    </div>
                </div>
            </div>
            @endif
            <div class="row mb-5">
                <div class="col-md-3 text-capitalize text-hover-info">{{__('site.advertisement.attributes')}}</div>
                <div class="col-md-9">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <td>{{__('site.attribute.name')}}</td>
                                <td>{{__('site.attribute.values')}}</td>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($advertisement->attributesWithValue as $attributeWithValue)
                                <tr>
                                    <td>{{$attributeWithValue->attribute->name}}</td>
                                    <td>{{$attributeWithValue->attributeValue->value}}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="row mb-5">
                <div class="col-md-3 text-capitalize text-hover-info">{{__('site.advertisement.status')}}</div>
                <div class="col-md-9">{{$advertisement->status}}</div>
                <div class="col-md-12 mt-5 mb-5">
                    <form class="form" method="post" action="{{route('dashboard.advertisements.update',$advertisement->id)}}" enctype="multipart/form-data">
                        @csrf
                        {{method_field('PUT')}}
                        <div class="form-group row">
                            <div class=" col-lg-4 col-md-9 col-sm-12">
                                <select class="form-control select2" id="status" name="status">
                                    <option value=""></option>
                                    <option value="accepted" {{$advertisement->status=='accepted' ? 'selected' : ''}}>{{__('site.advertisement.all_status.accepted')}}</option>
                                    <option value="pending" {{$advertisement->status=='pending' ? 'selected' : ''}}>{{__('site.advertisement.all_status.pending')}}</option>
                                    <option value="refused" {{$advertisement->status=='refused' ? 'selected' : ''}}>{{__('site.advertisement.all_status.refused')}}</option>
                                </select>
                                @error('status')
                                <div class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </div>
                                @enderror
                            </div>
                            <div class="col-lg-3 col-sm-12">
                                <button type="submit" class="btn btn-success font-weight-bold mr-2">{{__('site.operation.edit')}}</button>
                            </div>
                        </div>

                    </form>
                </div>
            </div>
        </div>

    </div>
@endsection

@section('scripts')
    <script !src="">
        // Class definition
        var KTSelect2 = function() {
            // Private functions
            var demos = function() {
                // basic
                $('#status').select2({
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
        $('.carousel').carousel()
    </script>
@endsection
