@extends('index')

@section('content')


    <div class="row text-center pad-top">


          <div class="col-lg-3 col-md-3 col-sm-3 col-xs-6">
            <div class="div-square">
                <a href="{{URL::to('/fiche/selection-listes/')}}" >
                    <i class="fa fa-list-alt fa-5x"></i>
                    <h4>Imprimer les  fiche de report des notes de controles continus</h4>
                </a>
            </div>


        </div>
          <div class="col-lg-3 col-md-3 col-sm-3 col-xs-6">
              <div class="div-square">
                  <a href="{{URL::to('/fiches/selection-listes/')}}" >
                      <i class="fa fa-list-alt fa-5x"></i>
                      <h4>Imprimer les  fiche de report des notes de l'examen</h4>
                  </a>
              </div>


          </div>


              <div class="col-lg-3 col-md-3 col-sm-3 col-xs-6">
              <div class="div-square">
                  <a href="{{URL::to('/fichesblanc/selection-listes/')}}" >
                      <i class="fa fa-list-alt fa-5x"></i>
                      <h4>Imprimer les  fiche de report des notes de l'examen blanc</h4>
                  </a>
              </div>


          </div>

          <div class="col-lg-3 col-md-3 col-sm-3 col-xs-6">
            <div class="div-square">
                <a href="{{URL::to('/fichepresence/selection_liste/')}}" >
                    <i class="fa fa-list-alt fa-5x"></i>
                    <h4>Fiches de présences aux examens</h4>
                </a>
            </div>


        </div>
    </div>
 <div class="row text-center pad-top">
        
        <div class="col-lg-3 col-md-3 col-sm-3 col-xs-6">
            <div class="div-square">
                <a href="{{URL::to('/rattrappages/selection-listes/')}}" >
                    <i class="fa fa-print fa-5x"></i>
                    <h4>Imprimer les fiches de report des notes de rattrapages</h4>
                </a>
            </div>


        </div>

    </div>


@endsection

@section('breadcrumb')
    <li class="active"><strong>@lang('menu.gestion_inscriptions')</strong></li>

@endsection