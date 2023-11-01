
@php
    $user_role = auth()->user()->role;
    $admin  = 1;
    $auth_staff  = 0;
    $auth_branch = 3;
    $auth_client = 4;

    $branches = Modules\Cargo\Entities\Branch::where('is_archived', 0)->get();
    $clients = Modules\Cargo\Entities\Client::where('is_archived', 0)->get();
    $receivers = Modules\Cargo\Entities\Receiver::where('is_archived', 0)->get();
    

    $countries = Modules\Cargo\Entities\Country::where('covered',1)->get();
    $packages = Modules\Cargo\Entities\Package::all();


    $paymentSettings = resolve(\Modules\Payments\Entities\PaymentSetting::class)->toArray();
@endphp


@extends('cargo::adminLte.layouts.master')

@section('pageTitle')
    {{ __('cargo::view.create_new_shipment') }}
@endsection

@section('content')

    <style>      
        .notification {
            display: flex;
            justify-content: space-between;
            background-color: #ff1a1ac9;
            color: white;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
        }
    </style>  
        
    <div>

        @if(auth()->user()->can('shipping-rates') || $user_role == $admin )
            @if( Modules\Cargo\Entities\ShipmentSetting::getVal('def_shipping_cost') == null)
            <div class="row">
                <div class=" notification alert alert-danger col-lg-8" style="margin: auto;margin-top:10px;" role="alert">
                    {{ __('cargo::view.please_configure_shipping_rates_in_creation_will_be_zero_without_configuration') }},
                    <a class="alert-link" href="{{ route('shipments.settings.fees') }}">{{ __('cargo::view.configure_now') }}</a>
                </div>
            </div>
            @endif
        @endif



        @if(auth()->user()->can('add-covered-countries') || $user_role == $admin )
            @if(count($countries) == 0 || Modules\Cargo\Entities\State::where('covered', 1)->count() == 0)
            <div class="row">
                <div class=" notification alert alert-danger col-lg-8" style="margin: auto;margin-top:10px;" role="alert">
                    {{ __('cargo::view.please_configure_your_covered_countries_and_regions') }},
                    <a class="alert-link" href="{{ route('countries.index') }}">{{ __('cargo::view.configure_now') }}</a>
                </div>
            </div>
            @endif
        @endif

        @if(auth()->user()->can('manage-areas') || $user_role == $admin )
            @if(Modules\Cargo\Entities\Area::count() == 0)
            <div class="row">
                <div class=" notification alert alert-danger col-lg-8" style="margin: auto;margin-top:10px;" role="alert">
                    {{ __('cargo::view.please_add_areas_before_creating_your_first_shipment') }},
                    <a class="alert-link" href="{{ route('areas.create') }}">{{ __('cargo::view.configure_now') }}</a>
                </div>
            </div>
            @endif
        @endif

        @if(auth()->user()->can('manage-packages') || $user_role == $admin )
            @if(count($packages) == 0)
            <div class="row">
                <div class=" notification alert alert-danger col-lg-8" style="margin: auto;margin-top:10px;" role="alert">
                    {{ __('cargo::view.please_add_package_types_before_creating_your_first_shipment') }},
                    <a class="alert-link" href="{{ route('packages.create') }}">{{ __('cargo::view.configure_now') }}</a>
                </div>
            </div>
            @endif
        @endif

        @if(auth()->user()->can('manage-branches') || $user_role == $admin )
            @if($branches->count() == 0)
            <div class="row">
                <div class=" notification alert alert-danger col-lg-8" style="margin: auto;margin-top:10px;" role="alert">
                    {{ __('cargo::view.please_add_branches_types_before_creating_your_first_shipment') }},
                    <a class="alert-link" href="{{ route('branches.create') }}">{{ __('cargo::view.configure_now') }}</a>
                </div>
            </div>
            @endif
        @endif

        @if(auth()->user()->can('manage-clients') || $user_role == $admin )
            @if($clients->count() == 0)
            <div class="row">
                <div class=" notification alert alert-danger col-lg-8" style="margin: auto;margin-top:10px;" role="alert">
                    {{ __('cargo::view.please_add_clients_types_before_creating_your_first_shipment') }},
                    <a class="alert-link" href="{{ route('clients.create') }}">{{ __('cargo::view.configure_now') }}</a>
                </div>
            </div>
            @endif
        @endif
        

        @if(auth()->user()->can('manage-clients') || $user_role == $admin )
            @if($receivers->count() == 0)
            <div class="row">
                <div class=" notification alert alert-danger col-lg-8" style="margin: auto;margin-top:10px;" role="alert">
                    {{ __('cargo::view.please_add_receivers_types_before_creating_your_first_shipment') }},
                    <a class="alert-link" href="{{ route('receivers.create') }}">{{ __('cargo::view.configure_now') }}</a>
                </div>
            </div>
            @endif
        @endif
        

        @if(auth()->user()->can('payments-settings') || $user_role == $admin )
            @if(count($paymentSettings) == 0)
            <div class="row">
                <div class=" notification alert alert-danger col-lg-8" style="margin: auto;margin-top:10px;" role="alert">
                    {{ __('cargo::view.please_add_payments_before_creating_your_first_shipment') }},
                    <a class="alert-link" href="{{ route('payments.index') }}">{{ __('cargo::view.configure_now') }}</a>
                </div>
            </div>
            @endif
        @endif

        @if($user_role == $auth_branch || $user_role == $auth_staff || $user_role == $auth_client )
            @if( Modules\Cargo\Entities\ShipmentSetting::getVal('def_shipping_cost') == null || count($countries) == 0 || Modules\Cargo\Entities\State::where('covered', 1)->count() == 0 || Modules\Cargo\Entities\Area::count() == 0 || count($packages) == 0 || $branches->count() == 0 || $clients->count() == 0)
                <div class="row">
                    <div class=" notification text-center alert alert-danger col-lg-8" style="margin: auto;margin-top:10px;" role="alert">
                        {{ __('cargo::view.please_ask_your_administrator_to_configure_shipment_settings_first_before_you_can_create_a_new_shipment') }}
                    </div>
                </div>
            @endif
        @endif
    </div>

    <br><br>
    <!--begin::Basic info-->
    <div class="card mb-5 mb-xl-10">
        <!--begin::Card header-->
        {{-- <div class="card-header border-0 cursor-pointer" role="button" data-bs-toggle="collapse" data-bs-target="#kt_account_profile_details" aria-expanded="true" aria-controls="kt_account_profile_details"> --}}
        <div class="card-header">
            <!--begin::Card title-->
            <div class="card-title m-0">
                <h3 class="fw-bolder m-0">{{ __('cargo::view.create_new_shipment') }}</h3>
            </div>
            <!--end::Card title-->
        </div>
        <!--begin::Card header-->
        <!--begin::Content-->
        <div>
            <!--begin::Form-->
            <form id="kt_form_1" class="form" action="{{ fr_route('shipments.store') }}" method="post" enctype="multipart/form-data" novalidate>
                <!--begin::Card body-->
                <div class="card-body border-top p-9">
                    @include('cargo::adminLte.pages.shipments.form', ['typeForm' => 'create'])
                </div>
                <!--end::Card body-->
                <!--begin::Actions-->
                <div class="card-footer d-flex justify-content-end py-6 px-9">
                    <a href="{{ url()->previous() }}" class="btn btn-light btn-active-light-primary me-2">@lang('view.discard')</a>
                    <button type="button" class="btn btn-primary" onclick="get_estimation_cost()" id="kt_account_profile_details_submit">@lang('view.create')</button>
                </div>
                <!--end::Actions-->
            </form>
            <!--end::Form-->
        </div>
        <!--end::Content-->
    </div>
    <!--end::Basic info-->

@endsection

{{-- Inject Scripts --}}
@push('js-component')
<script>
    
</script>
@endpush