@extends('skeleton')
@section('sub-content')

    <style>
        .panel-success>.panel-heading {
            background-color: #3c763d;
            color:white;
            border:solid 1px #3c763d;
        }
        .panel-success {

            border:solid 1px #3c763d;
        }
    </style>
    <div id="monaccordeon" class="panel-group col-md-12">
        <h3>{{trans('inscription.choix_liste')}}</h3>

        <div class="panel panel-primary">
            <div class="panel-heading">
                <h3 class="panel-title">
                    <a class="accordion-toggle" href="#item1" dataparent="#monaccordeon"
                       data-toggle="collapse">
                        Imprimer toutes les fiches de report des notes d'examens de rattrapage
                    </a>
                </h3>
            </div>

            <div id="item1"
                 class="panel-collapse collapsing">
                <div
                        class="panel-body">
                    <li class="col-md-12"><a  href={{URL::to('/rattrappages/listes/show?id=0')}}>toutes les fiches de report des notes</a></li>


                    @foreach($niveaux as $n)
                        <div class="col-md-6">
                            <h4 class="page-header"> {{$n->getNom()}}</h4>

                            @foreach($classes as $c)
                                <div class="col-md-12">
                                    @if($c->id_niveau==$n->id)
                                        <li class="col-md-12"><a data-toggle="modal" data-backdrop="false" href="#choose_level" onclick="choose_level({{$c->id}}, '{{URL::to('/rattrappages/listes/show')}}', '')">{{$c->getNom()}}</a></li>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    @endforeach


                </div>

            </div>
        </div>








        <script>

            $(function (){
                classForFraisChange()
                classForPayChange();
            })

            function classForFraisChange()
            {
                selectClass = $('#select_classe_for_frais');
                selectNNiveau = $('#select_nniveau_for_frais');

                if(selectClass.val() == 0) {
                    selectNNiveau.attr('disabled','disabled');
                } else {
                    selectNNiveau.removeAttr('disabled');

                }
            }

            function classForPayChange()
            {
                selectClass = $('#select_classe_for_pay');
                selectNNiveau = $('#select_nniveau_for_pay');

                if(selectClass.val() == 0) {
                    selectNNiveau.attr('disabled','disabled');
                } else {
                    selectNNiveau.removeAttr('disabled');

                }
            }
        </script>
        @endsection

        @section('breadcrumb')
            <li><strong><a href="{{url('inscriptions')}}">@lang('menu.gestion_inscriptions')</a></strong></li>
            <li class="active"><strong>{{$titre}}</strong></li>
@endsection