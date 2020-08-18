@extends('index')
@section('content')
    <div class="row text-center pad-top">
        <div class="col-lg-3 col-md-3 col-sm-3 col-xs-6">
            <div class="div-square">
                <a href="{{URL::to('/inscriptions/')}}" >
                    <i class="fa fa-table fa-5x"></i>
                    <h4>{{trans('menu.gestion_inscriptions')}} </h4>
                </a>
            </div>

        </div>
        <div class="col-lg-3 col-md-3 col-sm-3 col-xs-6">
            <div class="div-square">
                <a href="{{URL::to('/eleves/')}}" >
                    <i class="fa fa-edit fa-5x"></i>
                    <h4>{{trans('menu.gestion_notes')}} </h4>
                </a>
            </div>

        </div>

        <div class="col-lg-3 col-md-3 col-sm-3 col-xs-6">
            <div class="div-square">
                <a href="{{url('/personnel/')}}" >
                    <i class="fa fa-steam fa-5x"></i>
                    <h4>{{trans('menu.gestion_personnel')}} </h4>
                </a>
            </div>

        </div>

        <div class="col-lg-3 col-md-3 col-sm-3 col-xs-6">
            <div class="div-square">
                <a href="{{url('/comptabilite/')}}" >
                    <i class="fa fa-money fa-5x"></i>
                    <h4>{{trans('admin.comptabilite')}} </h4>
                </a>
            </div>

        </div>

    </div>
    <div class="row text-center pad-top">
        <div class="col-lg-3 col-md-3 col-sm-3 col-xs-6">
            <div class="div-square">
                <a href="{{URL::to('/admin/')}}" >
                    <i class="fa fa-user fa-5x"></i>
                    <h4>{{trans('menu.admin')}} </h4>
                </a>
            </div>

        </div>
    </div>
@endsection

@section('breadcrumb')
    <ul class="breadcrumb" style="margin-bottom: 0;padding: 2px 15px;font-size: 18px">
        <li class="active"><strong>Accueil</strong></li>
    </ul>
@endsection
