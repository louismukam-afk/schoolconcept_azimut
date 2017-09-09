@extends('index')

@section('content')
    <div class="page-header">
        <h3>{{trans('admin.personnel')}}</h3>
    </div>
    <div class="row text-center pad-top">
        <div class="col-lg-3 col-md-3 col-sm-3 col-xs-6">
            <div class="div-square">
                <a href="{{  URL::to('personnel/list/')  }}" >
                    <i class="fa fa-user fa-5x"></i>
                    <h4>{{trans('admin.ajouter_personnel')}}</h4>
                </a>
            </div>


        </div>

        <div class="col-lg-3 col-md-3 col-sm-3 col-xs-6">
            <div class="div-square">
                <a href="{{  URL::to('/personnel/fonction_pers/')  }}" >
                    <i class="fa fa-5x fa-wrench"></i>
                    <h4>{{trans('admin.ajouter_fonction_personnel')}}</h4>
                </a>
            </div>
        </div>

        <div class="col-lg-3 col-md-3 col-sm-3 col-xs-6">
            <div class="div-square">
                <a href="{{  URL::to('/personnel/dispenses/')  }}" >
                    <i class="fa fa-level-up fa-5x "></i>
                    <h4>Les cours dispensés</h4>
                </a>
            </div>


        </div>


            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-6">
                <div class="div-square">
                    <a href="{{  URL::to('/admin/matiere/')  }}" >
                        <i class="fa fa-5x fa-cloud"></i>
                        <h4>Matiere</h4>
                    </a>
                </div>
            </div>



    </div>
    <div class="row text-center pad-top">
        <div class="col-lg-3 col-md-3 col-sm-3 col-xs-6">
            <div class="div-square">
                <a href="{{  URL::to('/admin/classe_matiere/')  }}" >
                    <i class="fa fa-5x fa-cloud"></i>
                    <h4>Enregistrements des notes</h4>
                </a>
            </div>
        </div>
        <div class="col-lg-3 col-md-3 col-sm-3 col-xs-6">
            <div class="div-square">
                <a href="{{URL::to('/personnel/titulaire/')}}" >
                    <i class="fa fa-5x fa-money"></i>
                    <h4>Les representant de chaque classe</h4>
                </a>
            </div>


        </div>
    </div>



@endsection