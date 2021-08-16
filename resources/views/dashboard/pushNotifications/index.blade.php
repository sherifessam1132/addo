{{-- Extends layout --}}
@extends('layouts.dashboard.default')

{{-- Content --}}
@section('content')

    <div class="card card-custom">
        <div class="card-header flex-wrap border-0 pt-6 pb-0">
            <div class="card-title">
                <h3 class="card-label">{{__('site.pushNotification.all')}}
                    {{--                    <div class="text-muted pt-2 font-size-sm">Datatable initialized from HTML table</div>--}}
                </h3>
            </div>
            <div class="card-toolbar">
            </div>
        </div>

        <div class="card-body">
            <div class="mb-7">
                <div class="row align-items-center">
                    <div class="col-lg-9 col-xl-8">
                        <div class="row align-items-center">
                            <div class="col-md-4 my-2 my-md-0">
                                <div class="input-icon">
                                    <input type="text" class="form-control" placeholder="Search..." id="kt_subheader_search_form" />
                                    <span>
                                    <i class="flaticon2-search-1 text-muted"></i>
                                </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="datatable datatable-bordered datatable-head-custom" id="kt_datatable"></div>

        </div>

    </div>

@endsection

{{-- Scripts Section --}}
@section('scripts')
    <script !src="">
        "use strict";
        // Class definition

        // import date from "../../../metronic/plugins/formvalidation/src/js/validators/date";

        var KTAppsrolesListDatatable = function() {
            // Private functions

            // basic demo
            var _demo = function() {
                var token= $('meta[name="csrf-token"]').attr('content')
                var datatable = $('#kt_datatable').KTDatatable({
                    // datasource definition
                    data: {
                        type: 'remote',
                        source: {
                            read: {
                                url: '{{url()->current()}}',
                                method: 'GET',

                            },
                        },
                        pageSize: 20, // display 20 records per page
                        serverPaging: true,
                        serverFiltering: true,
                        serverSorting: true,

                    },
                    @if (app()->getLocale() == 'ar')
                    translate: {
                        records: {
                            processing: 'انتظر من فضلك...',
                            noRecords: 'لا توجد سجلات',
                        },
                        toolbar: {
                            pagination: {
                                items: {
                                    default: {
                                        first: 'الاول',
                                        prev: 'السابق',
                                        next: 'التالي',
                                        last: 'الأخير',
                                        more: 'More pages',
                                        input: 'رقم الصفحة',
                                        select: 'حدد حجم الصفحة',
                                        all: 'الكل',
                                    },

                                },
                            },
                        },
                    },
                    @endif
                    // layout definition
                    layout: {
                        scroll: false, // enable/disable datatable scroll both horizontal and vertical when needed.
                        footer: false, // display/hide footer
                    },

                    // column sorting
                    sortable: true,

                    pagination: true,

                    search: {
                        input: $('#kt_subheader_search_form'),
                        delay: 400,
                        key: 'generalSearch'
                    },

                    // columns definition
                    columns: [
                        {
                            field: 'id',
                            title: '#',
                            sortable: 'asc',
                            width: 40,
                            type: 'number',
                            selector: false,
                            textAlign: 'left',
                            template: function(data) {
                                return '<span class="font-weight-bolder">' + data.id + '</span>';
                            }
                        },
                        {
                            field: 'name',
                            title: '{{__('site.advertisement.name')}}',
                            template: function(data) {
                                return `<a href="{{route('dashboard.advertisements.index')}}/${data.id}">${data.name}</a>` ;
                            }
                        },
                        {
                            field: 'category.name',
                            sortable: false,
                            title: '{{__('site.advertisement.category')}}',
                            template: function(data) {
                                return data.category.name  ;
                            }
                        },
                        {
                            field: 'cover_path',
                            title: '{{__('site.global.image')}}',
                            sortable: false,
                            width: 130,
                            overflow: 'visible',
                            autoHide: false,
                            template: function (data) {
                                return `<img class="img-thumbnail" src="${data.cover_path}" width="50" height="50" />`;
                            }
                        },
                        {
                            field: 'client.name',
                            title: '{{__('site.client.name')}}',
                            sortable: false,
                            overflow: 'visible',
                            autoHide: false,
                            template: function(data) {
                                return data.client.name ;
                            }
                        },
                        {
                            field: 'status',
                            title: '{{__('site.advertisement.status')}}',
                            sortable: false,
                            overflow: 'visible',
                            autoHide: false,
                            template: function(data) {
                                let status=data.status;
                                let className=null;
                                if (status === 'accepted')
                                {
                                    className = 'success'
                                }else if (status === 'pending')
                                {
                                    className = 'warning'
                                }else {
                                    className = 'danger'
                                }
                                return `<span class="label font-weight-bold label-lg  label-light-${className} label-inline">${status}</span>` ;
                            }
                        },
                        {
                            field: 'package_types[0].visible_toClient_perDay_times',
                            title: '{{__('site.pushNotification.all_times')}}',
                            sortable: false,
                            overflow: 'visible',
                            autoHide: false,
                            template: function(data) {
                                return data.package_types[0].visible_toClient_perDay_times ;
                            }
                        },
                        {
                            field: 'package_types[0].pivot.visible_current_toClient_perDay_times',
                            title: '{{__('site.pushNotification.current')}}',
                            sortable: false,
                            overflow: 'visible',
                            autoHide: false,
                            template: function(data) {
                                return data.package_types[0].pivot.visible_current_toClient_perDay_times ;
                            }
                        },
                        {
                            field: 'Actions',
                            title: '{{__('site.global.action')}}',
                            sortable: false,
                            overflow: 'visible',
                            autoHide: false,
                            template: function(data) {
                                return `<a href="{{route('dashboard.pushNotifications.index')}}/${data.id}" id="push" class="btn btn-sm btn-primary btn-text-primary btn-hover-bg-success btn-icon mr-2" >
                                            <span class="svg-icon svg-icon-md">
                                                <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                                                    <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                                        <rect x="0" y="0" width="24" height="24"/>
                                                        <circle fill="#000000" opacity="0.3" cx="12" cy="12" r="10"/>
                                                        <path d="M16.7689447,7.81768175 C17.1457787,7.41393107 17.7785676,7.39211077 18.1823183,7.76894473 C18.5860689,8.1457787 18.6078892,8.77856757 18.2310553,9.18231825 L11.2310553,16.6823183 C10.8654446,17.0740439 10.2560456,17.107974 9.84920863,16.7592566 L6.34920863,13.7592566 C5.92988278,13.3998345 5.88132125,12.7685345 6.2407434,12.3492086 C6.60016555,11.9298828 7.23146553,11.8813212 7.65079137,12.2407434 L10.4229928,14.616916 L16.7689447,7.81768175 Z" fill="#000000" fill-rule="nonzero"/>
                                                    </g>
                                                </svg>
                                            </span>
                                        </a>` ;
                            }
                        }
                    ],
                });

                $('#kt_datatable_search_status').on('change', function() {
                    datatable.search($(this).val().toLowerCase(), 'Status');
                });

                $('#kt_datatable_search_type').on('change', function() {
                    datatable.search($(this).val().toLowerCase(), 'Type');
                });

                $('#kt_datatable_search_status, #kt_datatable_search_type').selectpicker();
                $('body').on('click','#push',function (e){
                    e.preventDefault();
                    $.ajax({
                        url:$(this).attr('href'),
                        data:{
                            _token:'{{csrf_token()}}'
                        },
                        success:function (data){
                            datatable.reload()
                        },
                        error:function (error)
                        {
                            console.log(error)
                        }
                    })
                })
            };

            return {
                // public functions
                init: function() {
                    _demo();
                },
            };
        }();



        jQuery(document).ready(function() {
            KTAppsrolesListDatatable.init();
        });
    </script>
@endsection

