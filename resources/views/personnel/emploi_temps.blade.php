@extends('skeleton')
@section('sub-content')
    <a href="#" class="btn btn-warning btn-lg" onclick="window.print()"><span class="glyphicon glyphicon-print"></span> {{trans('inscription.imprimer')}}</a>

    @include('perso.header')
    <link rel="stylesheet" media="print" href="{{URL::asset('css/print_timetable.css')}}">

    <style media="screen">
        .table{
            margin-top: 30px;
        }

        .item-emploi:hover
        {
            background: dodgerblue;
            color: white;
        }

        .timetable-col
        {
            width: 40mm;
        }

    </style>
    <h1 style="font-size: 30px;margin-top: 30px;text-align: center">{{  trans('admin.emploi_temps_de').' '.((App::getLocale()=='en')?$classe->nom_anglais:$classe->nom).' : '.$nniveau->nom  }}</h1>
    <table class="table table-bordered table-striped table-condensed table-inverse">
        <thead>

        <th class="timetable-col">@lang('admin.horaire')</th>
        <th class="timetable-col">@lang('admin.jour1')</th>
        <th class="timetable-col">@lang('admin.jour2')</th>
        <th class="timetable-col">@lang('admin.jour3')</th>
        <th class="timetable-col">@lang('admin.jour4')</th>
        <th class="timetable-col">@lang('admin.jour5')</th>
        <th class="timetable-col">@lang('admin.jour6')</th>
        </thead>
        <tbody>
        @foreach($horaires as $h)
            <tr>
                <td class="timetable-col">{{$h->intervalle}}</td>
                <td class="item-emploi timetable-col" @if($h->type!=1)onclick="editer_emplois_temps({{$h->id}},1)" @endif>
                    @foreach($dispenses as $d)
                        @if($d->jour==1 && $d->id_horaire==$h->id)
                            <strong>
                                    @if($d->matiere)
                                        {{$d->matiere->getNom()}}
                                    @endif

                            </strong>
                                <div class="traduction">
                                    @if($d->personnel)
                                        {{$d->personnel->nom}}
                                    @endif
                                </div>
                            <div class="traduction">
                                @if($d->salle)
                                    <strong style="color: grey !important;">{{$d->salle->nom}}</strong>
                                @endif

                            </div>

                            <?php break; ?>
                        @endif
                    @endforeach
                    @if($h->type==1)
                        <strong class="pause"  style="font-size: 12px;color: gray;" >Pause</strong>
                    @endif
                </td>
                <td class="item-emploi timetable-col" @if($h->type!=1)onclick="editer_emplois_temps({{$h->id}},2)" @endif>
                    @foreach($dispenses as $d)
                        @if($d->jour==2 && $d->id_horaire==$h->id)
                            <strong>
                                @if($d->matiere)
                                    {{$d->matiere->getNom()}}
                                @endif

                            </strong>
                            <div class="traduction">
                                @if($d->personnel)
                                    {{$d->personnel->nom}}
                                @endif
                            </div>
                            <div class="traduction">
                                @if($d->salle)
                                    <strong style="color: grey !important;">{{$d->salle->nom}}</strong>
                                @endif

                            </div>
                            <?php break; ?>
                        @endif
                    @endforeach
                    @if($h->type==1)
                        <strong class="pause" style="font-size: 12px;color: gray;" >Pause</strong>
                    @endif
                </td>
                <td class="item-emploi timetable-col" @if($h->type!=1)onclick="editer_emplois_temps({{$h->id}},3)" @endif>
                    @foreach($dispenses as $d)
                        @if($d->jour==3 && $d->id_horaire==$h->id)
                            <strong>
                                @if($d->matiere)
                                    {{$d->matiere->getNom()}}
                                @endif

                            </strong>
                            <div class="traduction">
                                @if($d->personnel)
                                    {{$d->personnel->nom}}
                                @endif
                            </div>
                            <div class="traduction">
                                @if($d->salle)
                                    <strong style="color: grey !important;">{{$d->salle->nom}}</strong>
                                @endif

                            </div>
                            <?php break; ?>
                        @endif
                    @endforeach
                    @if($h->type==1)
                        <strong class="pause" style="font-size: 12px;color: gray;" >Pause</strong>
                    @endif
                </td>
                <td class="item-emploi timetable-col" @if($h->type!=1)onclick="editer_emplois_temps({{$h->id}},4)" @endif>
                    @foreach($dispenses as $d)
                        @if($d->jour==4 && $d->id_horaire==$h->id)
                            <strong>
                                @if($d->matiere)
                                    {{$d->matiere->getNom()}}
                                @endif

                            </strong>
                            <div class="traduction">
                                @if($d->personnel)
                                    {{$d->personnel->nom}}
                                @endif
                            </div>
                            <div class="traduction">
                                @if($d->salle)
                                    <strong style="color: grey !important;">{{$d->salle->nom}}</strong>
                                @endif

                            </div>
                            <?php break; ?>
                        @endif
                    @endforeach
                    @if($h->type==1)
                        <strong class="pause" style="font-size: 12px;color: gray;" >Pause</strong>
                    @endif
                </td>
                <td class="item-emploi timetable-col" @if($h->type!=1)onclick="editer_emplois_temps({{$h->id}},5)" @endif>
                    @foreach($dispenses as $d)
                        @if($d->jour==5 && $d->id_horaire==$h->id)
                            <strong>
                                @if($d->matiere)
                                    {{$d->matiere->getNom()}}
                                @endif

                            </strong>
                            <div class="traduction">
                                @if($d->personnel)
                                    {{$d->personnel->nom}}
                                @endif
                            </div>
                            <div class="traduction">
                                @if($d->salle)
                                    <strong style="color: grey !important;">{{$d->salle->nom}}</strong>
                                @endif

                            </div>
                            <?php break; ?>
                        @endif
                    @endforeach
                    @if($h->type==1)
                        <strong  class="pause" style="font-size: 12px;color: gray;" >Pause</strong>
                    @endif
                </td>
                <td class="item-emploi timetable-col" @if($h->type!=1)onclick="editer_emplois_temps({{$h->id}},6)" @endif>
                    @foreach($dispenses as $d)
                        @if($d->jour==6 && $d->id_horaire==$h->id)
                            <strong>
                                @if($d->matiere)
                                    {{$d->matiere->getNom()}}
                                @endif

                            </strong>
                            <div class="traduction">
                                @if($d->personnel)
                                    {{$d->personnel->nom}}
                                @endif
                            </div>
                            <div class="traduction">
                                @if($d->salle)
                                    <strong style="color: grey !important;">{{$d->salle->nom}}</strong>
                                @endif

                            </div>
                            <?php break; ?>
                        @endif
                    @endforeach
                    @if($h->type==1)
                        <strong class="pause" style="font-size: 12px;color: gray;" >Pause</strong>
                    @endif
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>


    <div class="modal fade" id="edit-horaire">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">x</button>
                    <h4 class="modal-title">{{  trans('admin.remplir_champs')  }}</h4>
                </div>
                <div
                        class="modal-body">



                    <form accept-charset="UTF-8" role="form" method="POST" id="edit_horaire_form" action="{{ url('/personnel/emploi-temps/edit').'/'.$classe->id }}">
                        <input type="hidden" name="_token" value="{{ csrf_token() }}"/>
                        <input type="hidden" name="nniveau" id="nniveau" value="{{$nniveau->id}}" required/>
                        <input type="hidden" name="id_horaire" id="id_horaire" value="" required/>
                        <input type="hidden" name="jour" id="jour" value="" required/>


                        <fieldset>

                            <div class="form-group">
                                <label for="personnel">{{trans('admin.personnel')}} : </label>
                                <select id="personnel" name="personnel" form="edit_horaire_form" class="form-control autocomplete">
                                    @foreach($personnels as $p)
                                        <option value="{{  $p->id  }}" @if($p->id == old('personnel')) selected @endif>{{$p->nom}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="matiere">{{trans('admin.matiere')}} :</label>
                                <select id="matiere" name="matiere" form="edit_horaire_form" class="form-control autocomplete">
                                    @foreach($matieres as $m)
                                        <option value="{{  $m->id  }}" @if($m->id == old('matiere')) selected @endif>{{(App::getLocale()=='en')?$m->nom_anglais:$m->nom}}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="form-group">
                                <label for="salle">{{trans('admin.salle')}} :</label>
                                <select id="salle" name="salle" form="edit_horaire_form" class="form-control autocomplete">
                                    <option value="0">@lang('admin.aucun')</option>
                                    @foreach($salles as $s)
                                        <option value="{{  $s->id  }}" >{{$s->nom}}</option>
                                    @endforeach
                                </select>
                            </div>

                            <h3>@lang('admin.ou')</h3>

                            <div class="form-group">
                                <label class="text-danger">@lang('admin.q_del_entry')</label>
                                <select name="must_delete" form="edit_horaire_form">
                                    <option value="no" selected>@lang('inscription.non')</option>
                                    <option value="yes">@lang('inscription.oui')</option>
                                </select>
                            </div>

                            <input class="btn  btn-success " type="submit" value="{{  trans('admin.confirmer')  }}">


                        </fieldset>
                    </form>


                </div>





            </div>


        </div>
    </div>





    <script>



        function editer_emplois_temps(id,jour)
        {
            document.getElementById("id_horaire").value = id;
            document.getElementById("jour").value = jour;

            jQuery.noConflict();
            $('#edit-horaire').modal('show');


        }
    </script>
@endsection

@section('breadcrumb')
    <li><strong><a href="{{url('personnel')}}">@lang('menu.gestion_personnel')</a></strong></li>
    <li><strong><a href="{{url('personnel/dispenses')}}">@lang('admin.search_personnel')</a></strong></li>

    <li class="active"><strong>{{$titre}}</strong></li>
@endsection