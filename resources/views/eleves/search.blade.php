@extends('skeleton')
@section('sub-content')
    @if(isset($success))

        <div class="alert alert-success">
            {{  trans('admin.succes')  }}
        </div>

    @endif


    <div id="monaccordeon" class="panel-group col-md-12">
        <h3>{{trans('inscription.choix_liste')}}</h3>
        <div class="panel panel-primary">
            <div class="panel-heading">
                <h3 class="panel-title">
                    <a class="accordion-toggle" href="#item1" dataparent="#monaccordeon"
                       data-toggle="collapse">
                        {{trans('admin.etat_disc')}}
                    </a>
                </h3>
            </div>

            <div id="item1"
                 class="panel-collapse collapsing">
                <div
                        class="panel-body">
                    <li class="col-md-12"><a href={{route('etat_disciplinaire').'/?id=0'}}>{{trans('inscription.all_eleves')}}</a></li>


                    @foreach($cycles as $cy)
                        <h3 class="page-header col-md-12">{{$cy->nom}}</h3>

                        @foreach($niveaux as $n)
                            <div class="col-md-6">
                                <h4 class="page-header"> {{(App::getLocale()=='en')?$n->nom_anglais:$n->nom}}</h4>

                                @foreach($classes as $c)
                                    <div class="col-md-12">
                                        @if($c->id_niveau==$n->id)
                                            <li class="col-md-12"><a data-toggle="modal" data-backdrop="false" href="#choose_level" onclick="getNniveau({{$cy->id}});choix_lvl({{$c->id}},{{$cy->id}})">{{(App::getLocale()=='en')?$c->nom_anglais:$c->nom}}</a></li>
                                        @endif
                                    </div>
                                @endforeach
                            </div>
                        @endforeach

                    @endforeach


                </div>

            </div>
        </div>
    </div>

    <div class="col-md-8">
        <form class="form-horizontal" id="add_inscription_form" method="get" action="{{URL::to('/eleves/discipline/search')}}">
            <input type="hidden" name="_token" value="{{ csrf_token() }}"/>
            <div class="col-md-8">
                <div class="form-group">
                    <label for="search">{{  trans('inscription.recherche')  }} : </label>
                    <div class="input-group">
                        <input type="search" name="search" placeholder="{{trans('inscription.nom')}} ou Matricule" class="form-control" autofocus>
                        <div class="input-group-btn">
                            <button type="submit" class="btn btn-primary" ><span class="fa fa-search"> </span>{{trans('inscription.search')}}</button>
                        </div>
                    </div>
                </div>
            </div>
        </form>

    </div>

    <div class="col-md-10">
        @if(session('search_result'))
            <table class="table table-bordered table-striped table-condensed table-inverse" style="margin-top: 15px;">
                <thead>
                <caption style="font-size: 22px">{{  trans('inscription.search_result')  }}</caption>
                <th>#</th>
                <th>Matricule</th>
                <th>{{  trans('admin.nom')  }}</th>
                <th>{{  trans('inscription.date_naiss')  }}</th>
                <th>{{  trans('inscription.lieu_naiss')  }}</th>
                <th>{{  trans('inscription.sexe')  }}</th>
                <th>@lang('admin.specialites')</th>

                </thead>
                <tbody>
                <?php $i=1; ?>
                @foreach($eleves as $e)
                    <tr>
                        <td>{{$i++}}</td>
                        <td>{{$e->matricule}}</td>
                        <td><strong >{{$e->nom.' '.$e->prenom}}</strong></td>
                        <td>{{$e->date_naiss}}</td>
                        <td>{{$e->lieu_naiss}}</td>
                        <td>{{$e->sexe}}</td>
                        <td>{{(session('locale')=='en')?$e->nom_classe_anglais:$e->nom_classe}}</td>
                        <td>

                                    <a href="{{URL::to('/eleves/discipline/show/'.$e->id)}}"  class=" btn btn-success" ><span class="fa fa-bell-o"></span> {{trans('eleve.titre_discipline')}}</a


                    </tr>
                @endforeach
                </tbody>
            </table>

            {{Session::forget('search_result')}}
        @elseif(session('info_discipline'))


                {{Session::forget('info_discipline')}}
                @endif
    </div>


    <div class="modal fade" id="choose_level">
        <div class="modal-dialog" style="width: 20%">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">x</button>
                    <h4 class="modal-title">{{  trans('admin.remplir_champs')  }}</h4>
                </div>
                <div
                        class="modal-body">



                    <form accept-charset="UTF-8" role="form" method="GET" id="choix_level_form" action="{{route('etat_disciplinaire')}}">
                        <input type="hidden" name="_token" value="{{ csrf_token() }}"/>
                        <input type="hidden" name="id" id="id" value=""/>
                        <input type="hidden" name="cycle" id="id_cycle" value=""/>

                        <fieldset>
                            <div class="form-group">
                                <label for="niveau">{{  trans('admin.nniveau')  }} : </label>
                                <select  name="nniveau" id="nniveau" form="choix_level_form" class="form-control" required>

                                </select>
                            </div>


                            <input class="btn  btn-success " type="submit" value="{{  trans('admin.confirmer')  }}" autofocus>


                        </fieldset>
                    </form>


                </div>





            </div>


        </div>
    </div>

    <script>

        function choix_lvl(id,id_cycle)
        {
            document.getElementById("id").value = id;
            document.getElementById("id_cycle").value = id_cycle;
        }
    </script>
@endsection
