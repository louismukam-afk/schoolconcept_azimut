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
                <a href="{{URL::to('/eleves/notes/')}}" >
                    <i class="fa fa-pencil-square-o fa-5x"></i>
                    <h4>Notes</h4>
                </a>
            </div>


        </div>
        <div class="col-lg-3 col-md-3 col-sm-3 col-xs-6" style="display: none;">
            <div class="div-square">
                <a   href="{{URL::to('/eleves/bulletins/')}}" >
                    <i class="fa fa-mortar-board fa-5x"></i>
                    <h4>{{trans('eleve.bulletins')}}</h4>
                </a>
            </div>


        </div>


        <div class="col-lg-3 col-md-3 col-sm-3 col-xs-6" >
            <div class="div-square">
                <a   href="{{url('/eleves/releves/')}}" >
                    <i class="fa fa-mortar-board fa-5x"></i>
                    <h4>{{trans('admin.releve_notes')}}</h4>
                </a>
            </div>


        </div>

    </div>


@endsection