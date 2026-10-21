@extends('index')
@section('content')
    <link rel="stylesheet" href="{{URL::asset('css/tableauh.css')}}">
    <style media="screen">
        .tableau-liste{
            visibility: hidden;
        }
        #printBtn{
            visibility: hidden;
        }
    </style>
    <div class="print">
        @if($eleves->count()<=0)
            <h3 class="alert alert-info"> @lang('admin.aucun')</h3>
        @else
            <p id="printBtn" class="text-center" style="padding-top: 200px"> <a href="" onclick="window.print(); return false;" class="btn btn-warning btn-lg" style="font-size: 30px">@lang('admin.clictp',['n'=>$eleves->count()])</a> </p>

            <script>
                $(function () {
                    //startLoading();
                    setTimeout(waitForBack,200);
                });

                function waitForBack() {
                    stopLoading();
                    $('#printBtn').css('visibility','visible');
                }
            </script>
        @endif
    </div>


  <div class="tableau-liste">
      @foreach($eleves as $e)
          <div class="tableau" >

              <div class="titre">
                  <p class="text-uppercase"><strong>Tableau d'honneur</strong> </p>

              </div>
              <div class="body text-center" style="padding-top: 2.5cm">
                  <p class=""><i>A l'issue du conseil de classe ,
                          Vu le procès verbal de délibération @if(isset($annuel)) Annuelle @else du <strong class="text-uppercase">trimestre {{$sequence}}</strong> @endif  de la classe <strong class="text-uppercase"> {{$classe->nom}} </strong>
                          , la distinction de tableau d'honneur est conférée à l'élève
                  <p style="font-size: 30px;"><i><strong class="text-uppercase" style="color: #be9337!important;"> {{$e->nom.' '.$e->prenom}} </strong></i></p>
                  Né(e) le  <strong> {{(new DateTime($e->date_naiss))->format('d-m-Y')}} <i>à</i> <strong class="text-uppercase"> {{$e->lieu_naiss}} </strong></strong>
                  Ayant obtenu une moyenne de  <strong>@foreach($moyennes as $m) @if($m->id_eleve==$e->id){{round($m->moyenne,2)}}/20 <?php break; ?> @endif @endforeach</strong></p>
                  <div class="foot" style="padding-top: 1.75cm">
                      <p class="pull-left text-left"><strong style="text-decoration: underline">Date</strong><br>
                          {{date('d-m-Y')}}
                      </p>

                      <p class="pull-right"><strong style="text-decoration: underline">Signature</strong></p>

                  </div>
              </div>
          </div>



      @endforeach
  </div>




@endsection

@section('breadcrumb')
    <li><strong><a href="{{url('inscriptions')}}">@lang('menu.gestion_inscriptions')</a></strong></li>
    <li><strong><a href="{{url('select/classe').'?url='.url('/eleves/tableau-honneur/session')}}">@lang('admin.selectionner_classe')</a></strong></li>
    <li><strong><a href="{{url('/eleves/tableau-honneur/session').'?id='.$classe->id}}">@lang('admin.session')</a></strong></li>
    <li class="active"><strong>{{$titre}}</strong></li>
@endsection