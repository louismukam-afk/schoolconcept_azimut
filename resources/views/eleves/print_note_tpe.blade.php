@extends('skeleton')

@section('sub-content')

    @include('perso.functions')

    <a href="#" class="btn btn-lg btn-warning" onclick="window.print()">
        <span class="glyphicon glyphicon-print"></span> @lang('inscription.imprimer')
    </a>
    @include('perso.header')

    <link rel="stylesheet" href="{{ URL::asset('css/note_matiere.css') }}">

    <?php
        // Sécurisation des pourcentages (au cas où le contrôleur ne les enverrait pas)
        $pcCC  = isset($pourcentage_cc)  ? (float) $pourcentage_cc  : 0.0;
        $pcTPE = isset($pourcentage_tpe) ? (float) $pourcentage_tpe : 0.0;

        // Coefficient "global" utilisé pour ramener la somme des notes stockées à /20
        // coef_total = (pcCC + pcTPE) / 20  (ex : 30% + 70% = 100 -> coefTotal = 5)
        $coefTotal = ($pcCC + $pcTPE) > 0 ? (($pcCC + $pcTPE) / 20.0) : 0.0;

        // Stats globales
        $totalNotes = 0;
        $notesQuant = 0;
        $notesCap   = 0;
        $echecs     = 0;
    ?>

    <div class="titre">
        <p style="font-size: 19px">
            <strong>Travaux pratiques du semestre {{ $semestre }} en {{ $matiere->nom }}</strong>
        </p>
        <strong class="traduction">
            Semester {{ $semestre }} practical work of {{ $matiere->nom_anglais }}
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
                <strong>Filière: {{ \gesecole\niveau::find($classe->id_niveau)->nom }}</strong>
            </p>
            <strong class="traduction">
                Path : {{ \gesecole\niveau::find($classe->id_niveau)->nom_anglais }}
            </strong>
        </div>
    </div>

    <div class="table">
        <table class="table table-bordered table-striped table-condensed table-inverse" style="margin-top: 5px;">
            <thead>
            <tr>
                <th class="number-column">#</th>
                <th class="mat-column">Matricule/Matricle</th>
                <th>Nom/Name</th>
                <th class="note-column">CC /20</th>
                <th class="note-column">TP /20</th>
                <th class="note-column">Note /20</th>
                <th class="note-column">GP</th>
                <th class="note-column">Grade</th>
            </tr>
            </thead>
            <tbody>
            @php $i = 1; @endphp

            @foreach($eleves as $e)
                <?php
                    $totalNotes++;

                    // Notes stockées (pondérées) pour cet élève
                    $noteStockeeCC  = 0.0;
                    $noteStockeeTPE = 0.0;

                    // Valeurs affichées sur 20
                    $affCC  = '';
                    $affTPE = '';

                    // --- Récup CC ---
                    if (isset($notes_cc)) {
                        foreach ($notes_cc as $n) {
                            if ($n->id_eleve == $e->id) {
                                $noteStockeeCC = (float) $n->note;
                                if ($pcCC > 0) {
                                    // note_saisie = note_stockée * 20 / pourcentage
                                    $affCC = round($noteStockeeCC * 20 / $pcCC, 2);
                                } else {
                                    // fallback : on affiche la note stockée telle quelle
                                    $affCC = round($noteStockeeCC, 2);
                                }
                                break;
                            }
                        }
                    }

                    // --- Récup TPE ---
                    if (isset($notes_tpe)) {
                        foreach ($notes_tpe as $n) {
                            if ($n->id_eleve == $e->id) {
                                $noteStockeeTPE = (float) $n->note;
                                if ($pcTPE > 0) {
                                    $affTPE = round($noteStockeeTPE * 20 / $pcTPE, 2);
                                } else {
                                    $affTPE = round($noteStockeeTPE, 2);
                                }
                                break;
                            }
                        }
                    }

                    // Somme des notes pondérées (comme avant, pour getGrade + stats)
                    $totalStocke = $noteStockeeCC + $noteStockeeTPE;

                    // Note finale /20 en utilisant les pourcentages (si coefTotal > 0)
                    $noteFinale20 = '';
                    if ($coefTotal > 0 && $totalStocke > 0) {
                        $noteFinale20 = round($totalStocke / $coefTotal, 2);
                    }

                    // Statistiques (Cap / Quant / Echec) sur l'échelle 0-100 comme avant
                    if ($totalStocke >= 50) {
                        $notesCap++;
                    } elseif ($totalStocke >= 35) {
                        $notesQuant++;
                    } else {
                        $echecs++;
                    }

                    // Grade & GP (fonction existante)
                    $grade  = getGrade($totalStocke);
                    $gradeLettre = $grade[0];
                    $gradeGP     = $grade[1];
               ?>

                <tr>
                    <td class="number-column">{{ $i++ }}</td>
                    <td class="mat-column">{{ $e->matricule }}</td>
                    <td>{{ $e->nom.' '.$e->prenom }}</td>

                    {{-- CC --}}
                    <td class="note-column">
                        {{ $affCC !== '' ? $affCC : '-' }}
                    </td>

                    {{-- TP --}}
                    <td class="note-column">
                        {{ $affTPE !== '' ? $affTPE : '-' }}
                    </td>

                    {{-- Note /20 --}}
                    <td class="note-column">
                        {{ $noteFinale20 !== '' ? $noteFinale20 : '-' }}
                    </td>

                    {{-- GP / Grade (sur la base de totalStocke 0–100) --}}
                    <td class="note-column">{{ $gradeGP }}</td>
                    <td class="note-column">{{ $gradeLettre }}</td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>

    <?php
        if ($totalNotes == 0) {
            $totalNotes = 1; // éviter division par 0
        }
        $pcCap   = round(($notesCap   / $totalNotes) * 100, 2);
        $pcQuant = round(($notesQuant / $totalNotes) * 100, 2);
        $pcEchec = round(($echecs     / $totalNotes) * 100, 2);
    ?>

    <div>
        <p><b>Capitalisation :</b> {{ $pcCap }} %</p>
        <p><b>Quantification :</b> {{ $pcQuant }} %</p>
        <p><b>Echec :</b> {{ $pcEchec }} %</p>
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

    <li class="active"><strong>{{ $titre }}</strong></li>
@endsection
