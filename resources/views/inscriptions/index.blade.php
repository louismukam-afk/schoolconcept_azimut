@extends('index')

@section('content')
    <div class="row text-center pad-top">
        <div class="col-lg-3 col-md-3 col-sm-3 col-xs-6">
            <div class="div-square">
                <a href="{{URL::to('/inscriptions/enregistrement/create')}}" >
                    <i class="fa fa-save fa-5x"></i>
                    <h4>{{trans('inscription.title_register')}}</h4>
                </a>
            </div>


        </div>

        <div class="col-lg-3 col-md-3 col-sm-3 col-xs-6">
            <div class="div-square">
                <a href="{{URL::to('/inscriptions/new')}}" >
                    <i class="fa fa-user fa-5x"></i>
                    <h4>{{trans('inscription.title_inscription')}}</h4>
                </a>
            </div>


        </div>

        <div class="col-lg-3 col-md-3 col-sm-3 col-xs-6">
            <div class="div-square">
                <a href="{{URL::to('/inscriptions/payement/')}}" >
                    <i class="fa fa-money fa-5x"></i>
                    <h4>{{trans('inscription.title_paiement')}}</h4>
                </a>
            </div>


        </div>
        <div class="col-lg-3 col-md-3 col-sm-3 col-xs-6">
            <div class="div-square">
                <a href="{{URL::to('/inscriptions/selection-listes/')}}" >
                    <i class="fa fa-list-alt fa-5x"></i>
                    <h4>{{trans('inscription.listes')}}</h4>
                </a>
            </div>


        </div>



    </div>
    <div class="row text-center pad-top">
        <div class="col-lg-3 col-md-3 col-sm-3 col-xs-6">
            <div class="div-square">
                <a href="{{URL::to('/inscriptions/eleves/search')}}" >
                    <i class="fa fa-search fa-5x"></i>
                    <h4>{{trans('admin.see_edit').' '.trans('inscription.eleve')}}</h4>
                </a>
            </div>


        </div>

        <div class="col-lg-3 col-md-3 col-sm-3 col-xs-6">
            <div class="div-square">
                <a href="{{URL::to('/select/classe').'?url='.URL::to('/inscriptions/photos')}}" >
                    <i class="fa fa-image fa-5x"></i>
                    <h4>{{trans('admin.add_photo_to').' '.trans('inscription.eleve')}}</h4>
                </a>
            </div>


        </div>
    </div>
@endsection