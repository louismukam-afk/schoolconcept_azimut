@extends('index')

@section('content')
    <div class="row text-center pad-top">
        <div class="col-lg-3 col-md-3 col-sm-3 col-xs-6">
            <div class="div-square">
                <a href="{{URL::to('/eleves/discipline/')}}" >
                    <i class="fa fa-bell-o fa-5x"></i>
                    <h4>{{trans('eleve.titre_discipline')}}</h4>
                </a>
            </div>


        </div>
        <div class="col-lg-3 col-md-3 col-sm-3 col-xs-6">
            <div class="div-square">
                <a href="{{URL::to('/eleves/disciplineetd')}}">
                    <i class="fa fa-pencil-square-o fa-5x"></i>
                    <h4>Effectuer l'appel en salle par specialite</h4>
                </a>
            </div>
        </div>
        <div class="col-lg-3 col-md-3 col-sm-3 col-xs-6">
            <div class="div-square">
                <a href="{{URL::to('/eleves/releves_bts/')}}">
                    <i class="fa fa-file-text-o fa-5x"></i>
                    <h4>Releves de notes BTS par session</h4>
                </a>
            </div>
        </div>
    </div>
@endsection

@section('breadcrumb')
    <li class="active"><strong>@lang('menu.gestion_notes')</strong></li>
@endsection
