{{-- Extends layout --}}
@extends('layouts.dashboard.default')

{{-- Content --}}
@section('content')

    <div class="card card-custom">
        <div class="card-header flex-wrap border-0 pt-6 pb-0">
            <div class="card-title">
                <h3 class="card-label">{{__('site.advertisement.all')}}
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

      

       <script>
        setTimeout(() => {
            const urlParams = new URLSearchParams(window.location.search);
            const param = urlParams.get('status');
            const ele = document.getElementById('kt_subheader_search_form');

            ele.value = param;
            ele.focus();
           
            var evt = new CustomEvent('keyup');
            evt.which = 13;
            evt.keyCode = 13;
            ele.dispatchEvent(evt);
     

            var keyboardEvent = new KeyboardEvent('keydown', {
                code: 'Enter',
                key: 'Enter',
                charKode: 13,
                keyCode: 13,
                view: window
            });

            ele.dispatchEvent(keyboardEvent);

        },200);

    </script>

      

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
                                return data.name ;
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
                            field: 'action',
                            title: '{{__('site.global.action')}}',
                            sortable: false,
                            overflow: 'visible',
                            autoHide: false,
                            template: function(data) {
                                return data.action ;
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

