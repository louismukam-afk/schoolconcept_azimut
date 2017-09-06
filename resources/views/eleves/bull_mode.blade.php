@extends('index')

@section('content')
    <div class="row text-center pad-top">
        <div class="col-lg-3 col-md-3 col-sm-3 col-xs-6">
            <div class="div-square">
                <a href="{{URL::to('/eleves/bulletins/search/')}}" >
                    <i class="fa fa-user fa-5x"></i>
                    <h4>{{trans('inscription.eleve')}}</h4>
                </a>
            </div>


        </div>
        <div class="col-lg-3 col-md-3 col-sm-3 col-xs-6">
            <div class="div-square">
                <a href="#" >
                    <i class="fa fa-group fa-5x"></i>
                    <h4>Classe</h4>
                </a>
            </div>


        </div>

    </div>


@endsection