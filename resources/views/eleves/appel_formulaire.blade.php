@extends('skeleton')

@section('sub-content')
    <a href="#" class="btn btn-lg btn-warning" onclick="window.print()">
        <span class="glyphicon glyphicon-print"></span> @lang('inscription.imprimer')
    </a>
    @include('perso.header2')
    @if(session('success'))
        <div class="alert alert-success">
            Appel enregistré / mis à jour avec succès
        </div>
    @endif

    <div class="well">
        <h3>Fiche d'appel <strong>{{$jours->nom}}</strong></h3>
        <strong>Spécialité :</strong> {{ $classe->nom }} |
        <strong>Cycle :</strong> {{ isset($cycle->nom) ? $cycle->nom : '' }} |
        <strong>Flière :</strong> {{ isset($niveau->nom) ? $niveau->nom : '' }} |
        <strong>Niveau :</strong> {{ isset($n_niveau->nom) ? $n_niveau->nom : '' }}

        <strong>Date :</strong> {{ $date_discipline }}
    </div>


    <form method="POST" action="{{ url('/eleves/appel/store') }}">
        <input type="hidden" name="_token" value="{{ csrf_token() }}">

        <input type="hidden" name="id_classe" value="{{ $classe->id }}">
        <input type="hidden" name="id_jour" value="{{ $id_jour }}">
        <input type="hidden" name="date_discipline" value="{{ $date_discipline }}">
        <input type="hidden" name="id_cycle" value="{{ $id_cycle }}">
        <input type="hidden" name="id_niveau" value="{{ $id_niveau }}">
        <input type="hidden" name="id_n_niveau" value="{{ $id_n_niveau }}">

        <table class="table table-bordered table-condensed">
            <thead>
            <tr>
                <th>#</th>
                <th>Nom étudiant</th>

                @foreach($horaires as $h)
                    <th style="text-align:center;">
                        {{ $h->intervalle }}
                    </th>
                @endforeach

            </tr>
            </thead>

            <tbody>

            @foreach($eleves as $index => $e)

                <tr>
                    <td>{{ $index + 1 }}</td>

                    <td>
                        {{ $e->nom }} {{ $e->prenom }}
                    </td>

                    @foreach($horaires as $h)

                            <?php
                            $statut = null;

                            if (isset($presenceArray[$e->id]) &&
                                isset($presenceArray[$e->id][$h->id])) {

                                $statut = $presenceArray[$e->id][$h->id];
                            }
                            ?>

                        <td style="text-align:center;">

                            <label>
                                <input type="radio"
                                       name="presence[{{ $e->id }}][{{ $h->id }}]"
                                       value="0"
                                        <?php echo ($statut !== null && $statut == 0) ? 'checked' : ''; ?>>
                                P
                            </label>

                            <label style="margin-left:10px;">
                                <input type="radio"
                                       name="presence[{{ $e->id }}][{{ $h->id }}]"
                                       value="1"
                                        <?php echo ($statut !== null && $statut == 1) ? 'checked' : ''; ?>>
                                A
                            </label>

                        </td>

                    @endforeach

                </tr>

            @endforeach

            </tbody>

        </table>
        <div class="well" style="margin-top:20px;">

            <h4><strong>LÉGENDE</strong></h4>

            <p>
                <strong>P</strong> : Présent &nbsp;&nbsp;|
                <strong>A</strong> : Absent
            </p>

        </div>
        <div class="well" style="margin-top:10px;">

            <h4><strong>SYNTHÈSE DES ABSENCES PAR PLAGE HORAIRE</strong></h4>

            <table class="table table-bordered table-condensed">
                <thead>
                <tr>
                <tr>
                    <th>Plage horaire</th>
                    <th>Étudiants présents</th>
                    <th>Étudiants absents</th>
                    <th>Total heures d'absence</th>
                </tr>
                </tr>
                </thead>
                <tbody>

                @foreach($horaires as $h)

                        <?php
                        $nbAbsents = 0;
                        $nbPresents = 0;
                        $nbTotalHeuresAbsence = 0;

                        foreach($eleves as $e){

                            if(isset($presenceArray[$e->id][$h->id])){

                                if($presenceArray[$e->id][$h->id] == 1){
                                    $nbAbsents++;
                                    $nbTotalHeuresAbsence += 2; // 1 A = 2 heures
                                }

                                if($presenceArray[$e->id][$h->id] == 0){
                                    $nbPresents++;
                                }
                            }
                        }
                        ?>


                    <tr>
                        <tr>
                            <td>{{ $h->intervalle }}</td>
                            <td>{{ $nbPresents }}</td>
                            <td>{{ $nbAbsents }}</td>
                            <td>{{ $nbTotalHeuresAbsence }} h</td>
                        </tr>

                        </tr>

                @endforeach

                </tbody>
            </table>

        </div>


        <button type="submit" class="btn btn-success">
            Enregistrer l'appel
        </button>

    </form>

@endsection
@section('breadcrumb')

    <li>
        <strong>
            <a href="{{ url('eleves') }}">
                @lang('menu.gestion_notes')
            </a>
        </strong>
    </li>

    {{-- Liste des classes --}}
    <li>
        <a href="{{ route('appel.classesok.get', [$id_cycle, $id_niveau, $id_n_niveau]) }}">
            choisir autres specialites de la filière
        </a>
    </li>

    {{-- Choix cycle / niveau --}}
    <li>
        <a href="{{ route('appel.specialite', $classe->id) }}">
            Choix cycle / niveau
        </a>
    </li>
    {{-- Page choix spécialité --}}
    <li>
        <a href="{{ route('appel.specialitesok') }}">
            Choisir autres spécialités
        </a>
    </li>
    {{-- Sélection jour --}}
    <li>
        <a href="{{ route('appel.selection', $classe->id) }}">
            Sélection jour
        </a>
    </li>

    {{-- Page actuelle --}}
    <li class="active">
        <strong>Fiche d'appel</strong>
    </li>

@endsection

