@extends('skeleton')

@section('sub-content')

    @include('perso.functions')
    @include('perso.header')

    <a href="#" class="btn btn-lg btn-warning" onclick="window.print()">
        <span class="glyphicon glyphicon-print"></span> @lang('inscription.imprimer')
    </a>

    <link rel="stylesheet" href="{{ URL::asset('css/pv.css') }}">

    @php
        // Détection si au moins un élève a un TPE non nul
        $hasTPE = false;
        foreach ($PV as $pv) {
            if (isset($pv->matieres) && count($pv->matieres) > 0) {
                $m = $pv->matieres[0];
                if (isset($m->tpe) && $m->tpe !== null && $m->tpe !== '') {
                    $hasTPE = true;
                    break;
                }
            }
        }

        $totalNotes = 0;
        $notesCap   = 0;
        $notesQuant = 0;
        $echecs     = 0;
    @endphp

    <div class="titre">
        <p style="font-size: 19px">
            <strong>Procès-verbal du semestre {{ $semestre }} en {{ $matiere->nom }}</strong>
        </p>
        <strong class="traduction">
            Semester {{ $semestre }} exam report in {{ $matiere->nom_anglais }}
        </strong>
    </div>

    <div class="classe">
        <div class="specialite">
            <p>
                <strong>Spécialité : {{ $classe->nom }} - Niveau {{ $nniveau->nom }}</strong>
            </p>
            <strong class="traduction">
                Speciality : {{ $classe->nom_anglais }} - Level {{ $nniveau->nom }}
            </strong>
        </div>

        <div class="filiere">
            <p>
                <strong>Filière : {{ \gesecole\niveau::find($classe->id_niveau)->nom }}</strong>
            </p>
            <strong class="traduction">
                Path : {{ \gesecole\niveau::find($classe->id_niveau)->nom_anglais }}
            </strong>
        </div>
    </div>

    <div class="table">
        <table class="table table-bordered table-striped table-condensed table-inverse" id="note-excel" style="margin-top: 5px;">
            <thead>
            <tr>
                <th class="number-column">#</th>
                <th class="mat-column">Matricule</th>
                <th>Nom / Name</th>
                <th class="note-column">CC /20</th>
                <th class="note-column">
                    @if($sequence == 3 || $sequence == 4)
                        SN /20
                    @else
                        SN / RAT /20
                    @endif
                </th>
                @if($hasTPE)
                    <th class="note-column">TPE /20</th>
                @endif
                <th class="note-column">Total /20</th>
                <th class="note-column">GP</th>
                <th class="note-column">Grade</th>
            </tr>
            </thead>

            <tbody>
            @php $i = 1; @endphp

            @foreach($eleves as $e)
                @php
                    $totalNotes++;
                    $pvEleve = null;

                    foreach ($PV as $pvTmp) {
                        if ($pvTmp->id_eleve == $e->id) {
                            $pvEleve = $pvTmp;
                            break;
                        }
                    }

                    $cc   = null;
                    $sn   = null;
                    $tpe  = null;
                    $tot20 = null;

                    if ($pvEleve && isset($pvEleve->matieres) && count($pvEleve->matieres) > 0) {
                        $m = $pvEleve->matieres[0];

                        $cc    = isset($m->cc)   ? $m->cc   : null;
                        $sn    = isset($m->sn)   ? $m->sn   : null;
                        $tpe   = isset($m->tpe)  ? $m->tpe  : null;
                        $tot20 = isset($m->note) ? $m->note : null;
                    }

                    // Pour le grade, on suppose que mgp stocké dans $pvEleve->mgp est sur 0–20
                    // donc on repasse en /100 pour getGrade()
                    $note100 = ($pvEleve && isset($pvEleve->mgp)) ? ($pvEleve->mgp * 5) : 0;
                    $g = getGrade($note100);

                    // Statistiques
                    if ($note100 >= 50) {
                        $notesCap++;
                    } elseif ($note100 >= 35) {
                        $notesQuant++;
                    } else {
                        $echecs++;
                    }
                @endphp

                <tr>
                    <td class="number-column">{{ $i++ }}</td>
                    <td class="mat-column">{{ $e->matricule }}</td>
                    <td>{{ $e->nom.' '.$e->prenom }}</td>

                    <td class="note-column">
                        @if($cc === null || $cc === '')
                            -
                        @else
                            {{ number_format($cc, 2) }}
                        @endif
                    </td>

                    <td class="note-column">
                        @if($sn === null || $sn === '')
                            -
                        @else
                            {{ number_format($sn, 2) }}
                        @endif
                    </td>

                    @if($hasTPE)
                        <td class="note-column">
                            @if($tpe === null || $tpe === '')
                                -
                            @else
                                {{ number_format($tpe, 2) }}
                            @endif
                        </td>
                    @endif

                    <td class="note-column">
                        @if($tot20 === null || $tot20 === '')
                            -
                        @else
                            {{ number_format($tot20, 2) }}
                        @endif
                    </td>

                    <td class="note-column">{{ $g[0] }}</td>
                    <td class="note-column">{{ $g[1] }}</td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>

    @php
        if ($totalNotes == 0) $totalNotes = 1;
    @endphp

    <div>
        <p><b>Capitalisation :</b> {{ round(($notesCap   / $totalNotes) * 100, 2) }} %</p>
        <p><b>Quantification :</b> {{ round(($notesQuant / $totalNotes) * 100, 2) }} %</p>
        <p><b>Echec :</b>         {{ round(($echecs    / $totalNotes) * 100, 2) }} %</p>
    </div>

@endsection

@section('breadcrumb')
    <li><strong><a href="{{ url('eleves') }}">@lang('menu.gestion_notes')</a></strong></li>
    <li><strong><a href="{{ url('eleves/notes') }}">Notes</a></strong></li>
    <li><a href="{{ url('eleves/notes/show') }}"><strong>@lang('admin.choix_classe')</strong></a></li>
    <li>
        <a href="{{ url('eleves/notes/matieres/show').'?id='.$classe->id.'&cycle='.$id_cycle.'&nniveau='.$nniveau->id }}">
            <strong>@lang('admin.titre_matiere')</strong>
        </a>
    </li>
    <li class="active"><strong>{{ isset($titre) ? $titre : '' }}</strong></li>
@endsection
