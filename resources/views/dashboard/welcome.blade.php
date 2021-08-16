{{-- Extends layout --}}
@extends('layouts.dashboard.default')

{{-- Content --}}
@section('content')

    {{-- Dashboard 1 --}}

    <div id="kt_content_container" class="container">
        <!--begin::Row-->
        <div class="row gy-5 g-xl-8">

            {{-- start of accepted adv --}}
            <div class="col-xl-3">
                <!--begin::Statistics Widget 5-->
                <a  href="{{url('/dashboard/advertisements?status=accepted')}}" class="card bg-danger hoverable card-xl-stretch mb-xl-8">
                    <!--begin::Body-->
                    <div style="height: 160px" class="card-body">
                        <!--begin::Svg Icon | path: icons/duotone/Shopping/Cart3.svg-->
                        <span class="svg-icon svg-icon-white svg-icon-3x ms-n1">
                            <i style="color: white" class="fas fa-calendar-check fa-2x"></i>
											</span>
                        <!--end::Svg Icon-->
                        <div class="text-inverse-danger fw-bolder fs-2 mb-2 mt-5">{{__('site.advertisement.advertisement')}} {{__('site.advertisement.accepted')}}</div>
                        <div class="fw-bold text-inverse-danger fs-7">
                            @if($acceptedAdv->isempty())
                            <p>{{__('site.advertisement.Not Have advertisement')}}</p>
     
                           @else
                           <h2>{{__('site.advertisement.total')}}: {{$acceptedAdv->count()}}</h2>
     
                            @endif
                        </div>
                    </div>
                    <!--end::Body-->
                </a>
                <!--end::Statistics Widget 5-->
            </div>

             {{-- start of pendding Adv --}}
            <div class="col-xl-3">
                <!--begin::Statistics Widget 5-->
                <a href="{{url('/dashboard/advertisements?status=pending')}}"  class="card bg-info  card-xl-stretch mb-xl-8">
                    <!--begin::Body-->
                    <div style="height: 160px;" class="card-body">
                        <!--begin::Svg Icon | path: icons/duotone/Shopping/Cart3.svg-->
                        <span class="svg-icon svg-icon-white svg-icon-3x ms-n1">
                            <i style="color: white;" class="fas fa-spinner fa-2x"></i>
											</span>
                        <!--end::Svg Icon-->
                        <div class="text-inverse-danger fw-bolder fs-2 mb-2 mt-5">{{__('site.advertisement.advertisement')}} {{__('site.advertisement.pending')}}</div>
                        <div class="fw-bold text-inverse-danger fs-7">
                            @if($pendingAdv->isempty())
                       <p>{{__('site.advertisement.Not Have advertisement')}}</p>

                      @else
                      <h2>{{__('site.advertisement.total')}}: {{$pendingAdv->count()}}</h2>

                       @endif

                             </div>
                    </div>
                    <!--end::Body-->
                </a>
                <!--end::Statistics Widget 5-->
            </div>

      {{-- start of refused Adv --}}

            <div class="col-xl-3">
                <!--begin::Statistics Widget 5-->
                <a href="{{url('/dashboard/advertisements?status=refused')}}" class="card bg-primary hoverable card-xl-stretch mb-xl-8">
                    <!--begin::Body-->
                    <div style="height: 160px" class="card-body">
                        <!--begin::Svg Icon | path: icons/duotone/Home/Building.svg-->
                        <span class="svg-icon svg-icon-white svg-icon-3x ms-n1">
                            <i style="color: white;" class="fas fa-times-circle fa-2x"></i>
											</span>
                        <!--end::Svg Icon-->
                        <div class="text-inverse-danger fw-bolder fs-2 mb-2 mt-5">{{__('site.advertisement.advertisement')}} {{__('site.advertisement.refused')}}</div>
                        <div class="text-inverse-primary fw-bolder fs-2 mb-2 mt-5">
                           
                      
                       @if($refusedAdv->isempty())
                       <p>{{__('site.advertisement.Not Have advertisement')}}</p>

                      @else
                      <h2>{{__('site.advertisement.total')}}: {{$refusedAdv->count()}}</h2>

                       @endif

                        </div>
                    
                    
                    </div>
                    <!--end::Body-->
                </a>
                <!--end::Statistics Widget 5-->
            </div>

            {{-- star of expired adv --}}
            <div class="col-xl-3">
                <!--begin::Statistics Widget 5-->
                <a href="{{url('/dashboard/advertisements?status=expired')}}"  class="card bg-success hoverable card-xl-stretch mb-5 mb-xl-8">
                    <!--begin::Body-->
                    <div style="height: 160px;" class="card-body">
                        <!--begin::Svg Icon | path: icons/duotone/Shopping/Chart-bar1.svg-->
                        <span class="svg-icon svg-icon-white svg-icon-3x ms-n1">
                            <i style="color: white;" class="fas fa-ban fa-2x"></i>
											</span>
                        <!--end::Svg Icon-->
                        <div class="text-inverse-success fw-bolder fs-2 mb-2 mt-5">{{__('site.advertisement.advertisement')}} {{__('site.advertisement.expired')}}</div>
                        
                        <div class="fw-bold text-inverse-danger fs-7">

                            @if($expire==0)
                            <p>{{__('site.advertisement.Not Have advertisement')}}</p>
     
                           @else
                           <h2>{{__('site.advertisement.total')}}: {{$expire}}</h2>
     
                            @endif
                          
                        </div>
                    </div>
                    <!--end::Body-->
                </a>
                <!--end::Statistics Widget 5-->
            </div>

         {{-- start of paid Adv --}}

            <div class="col-xl-4">
                <!--begin::Statistics Widget 5-->
                <a  class="card bg-success hoverable card-xl-stretch mb-5 mb-xl-8">
                    <!--begin::Body-->
                    <div style="height: 160px;" class="card-body">
                        <!--begin::Svg Icon | path: icons/duotone/Shopping/Chart-bar1.svg-->
                        <span class="svg-icon svg-icon-white svg-icon-3x ms-n1">
												
													<i style="color: white" class="fas fa-money-check-alt fa-2x "></i>
												
											</span>
                        <!--end::Svg Icon-->
                        <div class="text-inverse-success fw-bolder fs-2 mb-2 mt-5"> {{__('site.advertisement.paid')}} {{__('site.advertisement.advertisement')}}</div>
                        
                        <div class="fw-bold text-inverse-danger fs-7">

                            @if($paidAdv==0)
                            <p>{{__('site.advertisement.Not Have advertisement')}}</p>
     
                           @else
                           <h2>{{__('site.advertisement.total')}}: {{$paidAdv}}</h2>
     
                            @endif
                          
                        </div>
                    </div>
                    <!--end::Body-->
                </a>
                <!--end::Statistics Widget 5-->
            </div>

         {{-- start of non paid Adv --}}

            <div class="col-xl-4">
                <!--begin::Statistics Widget 5-->
                <a  class="card bg-primary hoverable card-xl-stretch mb-xl-8">
                    <!--begin::Body-->
                    <div style="height: 160px" class="card-body">
                        <!--begin::Svg Icon | path: icons/duotone/Home/Building.svg-->
                        <span class="svg-icon svg-icon-white svg-icon-3x ms-n1">
                            <i style="color: white ;" class="fab fa-creative-commons-nc fa-2x"></i>
											</span>
                        <!--end::Svg Icon-->
                        <div class="text-inverse-danger fw-bolder fs-2 mb-2 mt-5"> {{__('site.advertisement.nonpaid')}} {{__('site.advertisement.advertisement')}}</div>
                        <div class="text-inverse-primary fw-bolder fs-2 mb-2 mt-5">
                           
                      
                       @if($nonpaidAdv ==0)
                       <p>{{__('site.advertisement.Not Have advertisement')}}</p>

                      @else
                      <h2>{{__('site.advertisement.total')}}: {{$nonpaidAdv}}</h2>

                       @endif

                        </div>
                    
                    
                    </div>
                    <!--end::Body-->
                </a>
                <!--end::Statistics Widget 5-->
            </div>

            {{-- start of profit from paid packages --}}
            <div class="col-xl-4">
                <!--begin::Statistics Widget 5-->
                <a  class="card bg-danger hoverable card-xl-stretch mb-xl-8">
                    <!--begin::Body-->
                    <div style="height: 160px" class="card-body">
                        <!--begin::Svg Icon | path: icons/duotone/Shopping/Cart3.svg-->
                        <span class="svg-icon svg-icon-white svg-icon-3x ms-n1">
                            <i style="color: white ;" class="fas fa-chart-line fa-2x"></i>
											</span>
                        <!--end::Svg Icon-->
                        <div class="text-inverse-danger fw-bolder fs-2 mb-2 mt-5"> {{__('site.advertisement.profit of paid Adv')}}</div>
                        <div class="fw-bold text-inverse-danger fs-7">
                            <div style="display: none">
                                {{ $total = 0 }}
                            </div>
                            @foreach($advs as $adv)
                           
    
                            <div style="display:none">{{$total += $adv->price}}</div>
                          
                            @endforeach
                           
                        
                            @if($total==0)
                            <p>{{__('site.advertisement.Not Have advertisement')}}</p>
     
                           @else
                           <h2>{{__('site.advertisement.total')}}: {{$total}}</h2>
     
                            @endif
                        </div>
                    </div>
                    <!--end::Body-->
                </a>
                <!--end::Statistics Widget 5-->
            </div>

            <div class="col-xl-12">

                <div class="row">
                    <div class="col-xl-8">
             <p>
         <button style="margin-top: 30px;  margin-left: 360px;" class="btn btn-primary" type="button" data-bs-toggle="collapse" data-bs-target="#collapseExample" aria-expanded="false" aria-controls="collapseExample">
            {{__('site.advertisement.show more details')}}
              </button>
            </p>
            <div class="collapse" id="collapseExample">
              <div style="margin-top: 20px;" class="card card-body">

                <table class="table">
                    <thead class="table-dark">
                      <th>#</th>
                       <th>{{__('site.advertisement.description of package')}} </th>
                       <th>{{__('site.advertisement.number of Adv')}} </th>
                      <th>{{__('site.advertisement.profit of Package')}} </th>
                     
                     
                    </thead>
                    <tbody>

                  @foreach ($packages as $item)
                

                    <tr>
                        <td>{{$i++}}</td>
                        <td>{{$item->description}}</td>
                        <td>{{$item->advertisements->count()}}</td>
                        <td> {{($item->advertisements->count()* $item->price )}}</td>
                       
                       
                      
                        

                    </tr>
                 
                    @endforeach

                    </tbody>
                  </table>


                  

              </div>
            </div>


           
               

                </div>

                <div class="col-xl-4">
                    <div class="card">
                        <div style="font-weight: bold;" class="card-header">{{__('site.global.The Last Five Registered Clients')}}</div>
                        <div class="list-group list-group-flush"> 
                            @foreach($clients as $client)
                          <a href="" class="list-group-item"><strong>{{__('site.global.Client Name')}}</strong>: {{$client->name}} </a>
                          @endforeach
                    
                         
                          
                        </div>
                      </div>

                </div>

            </div>
            </div>


            





        </div>
            

          
        <!--end::Row-->
        <!--begin::Row-->
        

        <!--end::Row-->
    </div>

@endsection


{{-- Scripts Section --}}
@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js" integrity="sha384-eMNCOe7tC1doHpGoWe/6oMVemdAVTMs2xqW4mwXrXsW0L84Iytr2wi5v2QjrP/xp" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.0/dist/js/bootstrap.min.js" integrity="sha384-cn7l7gDp0eyniUwwAZgrzD06kc/tftFf19TOAs2zVinnD/C7E91j9yyk5//jjpt/" crossorigin="anonymous"></script>
{{--    <script src="{{ asset('js/pages/widgets.js') }}?v=7.2.3" type="text/javascript"></script>--}}
@endsection
