@extends('skeleton')
@section('sub-content')
    @if(isset($success))

        <div class="alert alert-success">
            {{  trans('admin.succes')  }}
        </div>

    @endif

    <div class="panel panel-success">
        <div class="panel-heading">
            <h3 class="panel-title">Générer l'état des absences</h3>
        </div>

        <div class="panel-body">

            <form method="POST" action="{{ route('appel.etat.generer') }}">
                <input type="hidden" name="_token" value="{{ csrf_token() }}"/>
                <div class="row">
                    <div class="col-md-4">
                        <label>Date début</label>
                        <input type="date" name="date_debut" class="form-control" required>
                    </div>

                    <div class="col-md-4">
                        <label>Date fin</label>
                        <input type="date" name="date_fin" class="form-control" required>
                    </div>

                    <div class="col-md-4" style="margin-top:25px;">
                        <button type="submit" class="btn btn-primary">
                            Générer l'état
                        </button>
                    </div>
                </div>

            </form>

        </div>
    </div>

    <div id="monaccordeon" class="panel-group col-md-12">
        <h3>{{trans('inscription.choix_liste')}}</h3>
        <div class="panel panel-primary">
            <div class="panel-heading">
                <h3 class="panel-title">
                    <a class="accordion-toggle" href="#item1" dataparent="#monaccordeon"
                       data-toggle="collapse">
                        Générer l'état de discipline par spécialité
                    </a>
                </h3>
            </div>

            <div id="item1"
                 class="panel-collapse collapsing">
                <div
                        class="panel-body">
                    {{--                    <li class="col-md-12"><a href={{route('etat_disciplinaire').'?id=0'}}>{{trans('inscription.all_eleves')}}</a></li>--}}




                    @foreach($niveaux as $n)
                        <div class="col-md-6">
                            <h4 class="page-header"> {{$n->getNom()}}</h4>

                            @foreach($classes as $c)
                                <div class="col-md-12">
                                    @if($c->id_niveau==$n->id)
                                        <li class="col-md-12">
                                            <a href="{{ route('appel.etat.periode', $c->id) }}"
                                               class="btn btn-info btn-sm">
                                                <span class="fa fa-calendar"></span>
                                                {{$c->getNom()}}
                                            </a>
                                        </li>



                                    @endif
                                </div>
                            @endforeach
                        </div>
                    @endforeach



                </div>

            </div>
        </div>
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

                            <div class="btn-group">
                                <a href="#" class=" btn btn-primary dropdown-toggle" data-toggle="dropdown">Actions <b class="caret"></b></a>
                                <ul class="dropdown-menu">
                                    <li> <a href="{{URL::to('/eleves/discipline/show/'.$e->id)}}"  class=" btn btn-success" ><span class="fa fa-bell-o"></span> {{trans('eleve.titre_discipline')}}</a></li>
                                    <li>  <a href="{{URL::to('/eleves/discipline/index_injustifier/'.$e->id)}}"  class="btn btn-toolbar" ><span class="fa fa-bell-o"></span> Justifié(s) une absence</a>
                                    <li>  <a href="{{URL::to('/eleves/discipline/index_justifier/'.$e->id)}}"  class="btn btn-group-justified" ><span class="fa fa-bell-o"></span>Absences justifiées</a>
                                    </li>
                                </ul>
                            </div>


                        </td>

                        {{-- <td>
                             <a href="{{URL::to('/eleves/discipline/show_presnce/'.$e->id)}}"  class=" btn btn-success" ><span class="fa fa-bell-o"></span> Justifier une absence</a>

                        </td>--}}
                    </tr>
                @endforeach
                </tbody>
            </table>

            {{Session::forget('search_result')}}
        @elseif(session('info_discipline'))


            {{Session::forget('info_discipline')}}
        @endif
    </div>
    <div id="monaccordeon" class="panel-group col-md-12">
    <div class="panel panel-info">
        <div class="panel-heading">
            <h3 class="panel-title">
                <a class="accordion-toggle"
                   href="#itemDetaille"
                   data-parent="#monaccordeon"
                   data-toggle="collapse">
                    Générer l'état détaillé des absences
                </a>
            </h3>
        </div>

        <div id="itemDetaille"
             class="panel-collapse collapse">
            <div class="panel-body">

                @foreach($niveaux as $n)
                    <div class="col-md-6">
                        <h4 class="page-header">{{ $n->getNom() }}</h4>

                        @foreach($classes as $c)
                            @if($c->id_niveau == $n->id)

                                <li class="col-md-12">
                                    <a href="{{ route('appel.etat.periode.detaille', $c->id) }}"
                                       class="btn btn-warning btn-sm">
                                        <span class="fa fa-table"></span>
                                        {{ $c->getNom() }}
                                    </a>
                                </li>

                            @endif
                        @endforeach

                    </div>
                @endforeach

            </div>
        </div>
    </div>
    </div>



@endsection
@section('breadcrumb')
    <li><strong><a href="{{url('eleves')}}">@lang('menu.gestion_notes')</a></strong></li>
    <li class="active"><strong>{{$titre}}</strong></li>
@endsection
