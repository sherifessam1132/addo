{{-- Extends layout --}}
@extends('layouts.dashboard.default')

{{-- Content --}}
@section('content')
    <div class="card card-custom">
        <div class="card-header flex-wrap border-0 pt-6 pb-0">
            <div class="card-title">
                <h3 class="card-label">{{__('site.attribute.showOne')}}
                    {{--                    <div class="text-muted pt-2 font-size-sm">Datatable initialized from HTML table</div>--}}
                </h3>
            </div>

        </div>

        <div class="card-body">
            <div class="container">
                <div class="row mb-5">
                    <div class="col-md-3 text-capitalize text-hover-info">{{__('site.attribute.name')}}</div>
                    <div class="col-md-9">{{$attribute->name}}</div>
                </div>
                <div class="row mb-5">
                    <div class="col-md-3 text-capitalize text-hover-info">{{__('site.attribute.values')}}</div>
                    <div class="col-md-9">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <td>{{__('site.attribute.values')}}</td>
                                </tr>
                            </thead>
                            <tbody>
                            @foreach($attribute->values as $value)
                                <tr>
                                    <td><a href="{{route('dashboard.attributes.addValues',[
                                            'attribute'=>$attribute->id,
                                            'child'=>$attribute->child->id,
                                            'value'=>$value->id
                                        ])}}">{{$value->value}}</a></td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

    </div>
@endsection

@section('scripts')
    <script !src="">

    </script>
@endsection
