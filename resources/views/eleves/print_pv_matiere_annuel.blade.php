@extends('skeleton')

@section('sub-content')

    @include('perso.functions')
    @include('perso.header')

    <a href="#" class="btn btn-lg btn-warning" onclick="window.print()">
        <span class="glyphicon glyphicon-print"></span> @lang('inscription.imprimer')
    </a>

    <link rel="stylesheet" href="{{ URL::asset('css/pv.css') }}">

    @php
        $totalNotes = 0;
        $notesCap   = 0;
        $notesQuant = 0;
        $echecs     = 0;
    @endphp

    <div class="titre">
        <p style="font-size: 19px">
            <strong>Procès-verbal annuel en {{ $matiere->nom }}</strong>
        </p>
        <strong class="traduction">
            Annual exam report in {{ $matiere->nom_anglais }}
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
                <th class="note-column">Sem. 1 /20</th>
                <th class="note-column">Sem. 2 /20</th>
                <th class="note-column">Annuel /20</th>
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

                    $s1   = null;
                    $s2   = null;
                    $ann  = null;

                    if ($pvEleve && isset($pvEleve->matieres) && count($pvEleve->matieres) > 0) {
                        $m = $pvEleve->matieres[0];

                        $s1  = isset($m->cc)   ? $m->cc   : null;
                        $s2  = isset($m->sn)   ? $m->sn   : null;
                        $ann = isset($m->note) ? $m->note : null;
                    }

                    $note100 = ($pvEleve && isset($pvEleve->mgp)) ? ($pvEleve->mgp * 5) : 0;
                    $g = getGrade($note100);

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
                        @if($s1 === null || $s1 === '')
                            -
                        @else
                            {{ number_format($s1, 2) }}
                        @endif
                    </td>

                    <td class="note-column">
                        @if($s2 === null || $s2 === '')
                            -
                        @else
                            {{ number_format($s2, 2) }}
                        @endif
                    </td>

                    <td class="note-column">
                        @if($ann === null || $ann === '')
                            -
                        @else
                            {{ number_format($ann, 2) }}
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
