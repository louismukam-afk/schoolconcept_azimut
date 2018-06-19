@extends('skeleton')
@section('sub-content')
    <a href="#" class="btn btn-warning btn-lg" onclick="window.print()"><span class="glyphicon glyphicon-print"></span> {{trans('inscription.imprimer')}}</a>

    @include('perso.header')
    <link rel="stylesheet" media="print" href="{{URL::asset('css/print_timetable.css')}}">

    <style media="screen">
        .table{
            margin-top: 30px;
        }
    </style>
     <h1 style="font-size: 30px;margin-top: 30px;text-align: center">{{  trans('admin.emploi_temps_de').' '.((App::getLocale()=='en')?$classe->nom_anglais:$classe->nom.' '.$nniveau->nom)  }}</h1>
    <table class="table table-bordered table-striped table-condensed table-inverse">
        <thead>
       
        <th>@lang('admin.horaire')</th>
        <th>@lang('admin.jour1')</th>
        <th>@lang('admin.jour2')</th>
        <th>@lang('admin.jour3')</th>
        <th>@lang('admin.jour4')</th>
        <th>@lang('admin.jour5')</th>
        <th>@lang('admin.jour6')</th>
        </thead>
        <tbody>
        @foreach($horaires as $h)
            <tr>
                <td>{{$h->intervalle}}</td>
                <td>
                    @foreach($dispenses as $d)
                        @if($d->jour==1 && $d->id_horaire==$h->id)
                            <strong>
                                @foreach($matieres as $m)
                                    @if($m->id==$d->id_matiere)
                                        {{((App::getLocale()=='en')?$m->nom_anglais:$m->nom)}}
                                        <?php break; ?>

                                    @endif

                                @endforeach
                            </strong>
                            <div class="traduction">
                                @foreach($personnels as $p)
                                    @if($p->id==$d->id_personnel)
                                        {{$p->nom}}
                                        <?php break; ?>

                                    @endif

                                @endforeach
                            </div>
                            <?php break; ?>
                        @endif
                    @endforeach
                    @if($h->type==1)
                            <strong class="pause"  style="font-size: 12px;color: gray;" >Pause</strong>
                    @endif
                </td>
                <td> @foreach($dispenses as $d)
                        @if($d->jour==2 && $d->id_horaire==$h->id)
                            <strong>
                                @foreach($matieres as $m)
                                    @if($m->id==$d->id_matiere)
                                        {{((App::getLocale()=='en')?$m->nom_anglais:$m->nom)}}
                                        <?php break; ?>

                                    @endif

                                @endforeach
                            </strong>
                            <div class="traduction">
                                @foreach($personnels as $p)
                                    @if($p->id==$d->id_personnel)
                                        {{$p->nom}}
                                        <?php break; ?>

                                    @endif

                                @endforeach
                            </div>
                            <?php break; ?>
                        @endif
                    @endforeach
                     @if($h->type==1)
                            <strong class="pause" style="font-size: 12px;color: gray;" >Pause</strong>
                    @endif
                </td>
                <td> @foreach($dispenses as $d)
                        @if($d->jour==3 && $d->id_horaire==$h->id)
                            <strong>
                                @foreach($matieres as $m)
                                    @if($m->id==$d->id_matiere)
                                        {{((App::getLocale()=='en')?$m->nom_anglais:$m->nom)}}
                                        <?php break; ?>

                                    @endif

                                @endforeach
                            </strong>
                            <div class="traduction">
                                @foreach($personnels as $p)
                                    @if($p->id==$d->id_personnel)
                                        {{$p->nom}}
                                        <?php break; ?>

                                    @endif

                                @endforeach
                            </div>
                            <?php break; ?>
                        @endif
                    @endforeach
                     @if($h->type==1)
                            <strong class="pause" style="font-size: 12px;color: gray;" >Pause</strong>
                    @endif
                </td>
                <td> @foreach($dispenses as $d)
                        @if($d->jour==4 && $d->id_horaire==$h->id)
                            <strong>
                                @foreach($matieres as $m)
                                    @if($m->id==$d->id_matiere)
                                        {{((App::getLocale()=='en')?$m->nom_anglais:$m->nom)}}
                                        <?php break; ?>

                                    @endif

                                @endforeach
                            </strong>
                            <div class="traduction">
                                @foreach($personnels as $p)
                                    @if($p->id==$d->id_personnel)
                                        {{$p->nom}}
                                        <?php break; ?>

                                    @endif

                                @endforeach
                            </div>
                            <?php break; ?>
                        @endif
                    @endforeach
                     @if($h->type==1)
                            <strong class="pause" style="font-size: 12px;color: gray;" >Pause</strong>
                    @endif
                </td>
                <td> @foreach($dispenses as $d)
                        @if($d->jour==5 && $d->id_horaire==$h->id)
                            <strong>
                                @foreach($matieres as $m)
                                    @if($m->id==$d->id_matiere)
                                        {{((App::getLocale()=='en')?$m->nom_anglais:$m->nom)}}
                                        <?php break; ?>

                                    @endif

                                @endforeach
                            </strong>
                            <div class="traduction">
                                @foreach($personnels as $p)
                                    @if($p->id==$d->id_personnel)
                                        {{$p->nom}}
                                        <?php break; ?>

                                    @endif

                                @endforeach
                            </div>
                            <?php break; ?>
                        @endif
                    @endforeach
                     @if($h->type==1)
                            <strong  class="pause" style="font-size: 12px;color: gray;" >Pause</strong>
                    @endif
                </td>
                <td> @foreach($dispenses as $d)
                        @if($d->jour==6 && $d->id_horaire==$h->id)
                            <strong>
                                @foreach($matieres as $m)
                                    @if($m->id==$d->id_matiere)
                                        {{((App::getLocale()=='en')?$m->nom_anglais:$m->nom)}}
                                        <?php break; ?>

                                    @endif

                                @endforeach
                            </strong>
                            <div class="traduction">
                                @foreach($personnels as $p)
                                    @if($p->id==$d->id_personnel)
                                        {{$p->nom}}
                                        <?php break; ?>

                                    @endif

                                @endforeach
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
@endsection