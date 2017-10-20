@extends('index')

@section('content')
<div class="alert alert-warning">
    <h4>@lang('admin.total_entrees') : <strong>{{$entrees.' '.trans('inscription.devise')}}</strong></h4>
    <h4>@lang('admin.total_sorties') : <strong>{{$sorties.' '.trans('inscription.devise')}}</strong></h4>
    <h4>@lang('admin.solde_caisse') : <strong>{{($entrees-$sorties).' '.trans('inscription.devise')}}</strong></h4>
</div>
    <div class="row text-center pad-top">
        <div class="col-lg-3 col-md-3 col-sm-3 col-xs-6">
            <div class="div-square">
                <a href="{{URL::to('admin/retrait/')}}" >
                    <i class="fa fa-money fa-5x"></i>
                    <h4>{{trans('admin.effectuer_retrait')}} </h4>
                </a>
            </div>

        </div>
        <div class="col-lg-3 col-md-3 col-sm-3 col-xs-6">
            <div class="div-square">
                <a href="{{URL::to('comptabilite/payement-salaire/')}}" >
                    <i class="fa fa-money fa-5x"></i>
                    <h4>{{trans('admin.payer_personnel')}} </h4>
                </a>
            </div>

        </div>

        <div class="col-lg-3 col-md-3 col-sm-3 col-xs-6">
            <div class="div-square">
                <a href="{{url('/comptabilite/bilan/entrees')}}" >
                    <i class="fa fa-download fa-5x"></i>
                    <h4>{{trans('admin.bilan_entrees')}} </h4>
                </a>
            </div>

        </div>

        <div class="col-lg-3 col-md-3 col-sm-3 col-xs-6">
            <div class="div-square">
                <a href="{{url('/comptabilite/bilan/sorties')}}" >
                    <i class="fa fa-upload fa-5x"></i>
                    <h4>{{trans('admin.bilan_sorties')}} </h4>
                </a>
            </div>

        </div>

    </div>
    @endsection