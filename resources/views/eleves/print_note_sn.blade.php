@extends('skeleton')

@section('sub-content')

    @include('perso.functions')

    <a href="#" class="btn btn-lg btn-warning" onclick="window.print()">
        <span class="glyphicon glyphicon-print"></span> @lang('inscription.imprimer')
    </a>

    @include('perso.header')
    <link rel="stylesheet" href="{{ URL::asset('css/note_matiere.css') }}">

    <div class="titre">
        <p style="font-size: 19px">
            <strong>Examen de fin de semestre {{ $semestre }} en {{ $matiere->nom }}</strong>
        </p>
        <strong class="traduction">
            Semester {{ $semestre }} end exam of {{ $matiere->nom_anglais }}
        </strong>
    </div>

    <div class="classe">
        <div class="specialite">
            <p><strong>Spécialité : {{ $classe->nom }} - Niveau  {{ $nniveau->nom }}</strong></p>
            <strong class="traduction">
                Speciality : {{ $classe->nom_anglais }} - Level  {{ $nniveau->nom }}
            </strong>
        </div>

        <div class="filiere">
            <p><strong>Filière: {{ \gesecole\niveau::find($classe->id_niveau)->nom }}</strong></p>
            <strong class="traduction">
                Path : {{ \gesecole\niveau::find($classe->id_niveau)->nom_anglais }}
            </strong>
        </div>
    </div>

    <?php
        $totalNotes = 0;
        $notesQuant = 0;
        $notesCap   = 0;
        $echecs     = 0;

        // Pourcentages envoyés par le contrôleur
        $pCC  = isset($pourcentage_cc)  ? (float)$pourcentage_cc  : 0.0;
        $pSN  = isset($pourcentage_sn)  ? (float)$pourcentage_sn  : 0.0;
        $pTPE = isset($pourcentage_tpe) ? (float)$pourcentage_tpe : 0.0;

        // Multiplicateurs (ex : 30% -> saisie_sur_20 * (30/20) = note_stockée)
        $mulCC  = ($pCC  > 0) ? ($pCC  / 20) : 0;
        $mulSN  = ($pSN  > 0) ? ($pSN  / 20) : 0;
        $mulTPE = ($pTPE > 0) ? ($pTPE / 20) : 0;

        // Est-ce qu'on affiche la colonne TPE ?
        $hasTPE = isset($notes_tpe) && count($notes_tpe) > 0 && $pTPE > 0;
    ?>

    <div class="table">
        <table class="table table-bordered table-striped table-condensed table-inverse" style="margin-top: 5px;">
            <thead>
            <tr>
                <th class="number-column">#</th>
                <th class="mat-column">Matricule</th>
                <th>Nom</th>
                <th class="note-column">CC /20</th>
                <th class="note-column">SN /20</th>
                @if($hasTPE)
                    <th class="note-column">TPE /20</th>
                @endif
                <th class="note-column">Total /20</th>
                <th class="note-column">GP</th>
                <th class="note-column">Grade</th>
            </tr>
            </thead>

            <tbody>
            <?php $i = 1; ?>

            @foreach($eleves as $e)
                <?php
                    $totalNotes++;

                    // valeurs /20 (affichées)
                    $cc20  = null;
                    $sn20  = null;
                    $tpe20 = null;

                    // valeurs stockées (pondérées %)
                    $noteCC  = 0;
                    $noteSN  = 0;
                    $noteTPE = 0;

                    // --- CC ---
                    if (isset($notes_cc)) {
                        foreach ($notes_cc as $n) {
                            if ($n->id_eleve == $e->id) {
                                $noteCC = (float)$n->note; // en "points stockés"
                                if ($mulCC > 0) {
                                    $cc20 = round($noteCC / $mulCC, 2); // on revient sur 20
                                } else {
                                    $cc20 = round($noteCC, 2);
                                }
                                break;
                            }
                        }
                    }

                    // --- SN ---
                    if (isset($notes)) {
                        foreach ($notes as $n) {
                            if ($n->id_eleve == $e->id) {
                                $noteSN = (float)$n->note;
                                if ($mulSN > 0) {
                                    $sn20 = round($noteSN / $mulSN, 2);
                                } else {
                                    $sn20 = round($noteSN, 2);
                                }
                                break;
                            }
                        }
                    }

                    // --- TPE éventuel ---
                    if ($hasTPE) {
                        foreach ($notes_tpe as $n) {
                            if ($n->id_eleve == $e->id) {
                                $noteTPE = (float)$n->note;
                                if ($mulTPE > 0) {
                                    $tpe20 = round($noteTPE / $mulTPE, 2);
                                } else {
                                    $tpe20 = round($noteTPE, 2);
                                }
                                break;
                            }
                        }
                    }

                    // Somme des points stockés (échelle "sur 100" si pCC+pSN(+pTPE)=100)
                    $sumStock = $noteCC + $noteSN + $noteTPE;

                    // Dénominateur global (ex: (30+70+TPE)/20)
                    $den = 0.0;
                    if ($pCC  > 0) $den += $pCC  / 20;
                    if ($pSN  > 0) $den += $pSN  / 20;
                    if ($pTPE > 0) $den += $pTPE / 20;

                    if ($den > 0) {
                        // Moyenne /20 dans la logique des pourcentages
                        $total20 = round($sumStock / $den, 2);
                    } else {
                        // fallback si pas de pourcentage défini
                        $nbParts = 0;
                        $somme   = 0;
                        if ($cc20 !== null) { $nbParts++; $somme += $cc20; }
                        if ($sn20 !== null) { $nbParts++; $somme += $sn20; }
                        if ($tpe20 !== null){ $nbParts++; $somme += $tpe20; }
                        $total20 = $nbParts > 0 ? round($somme / $nbParts, 2) : 0;
                    }

                    // Note sur 100 pour GP / Grade (comme avant : total /20 * 5)
                    $note100 = $total20 * 5;

                    if ($note100 >= 50) {
                        $notesCap++;
                    } elseif ($note100 >= 35) {
                        $notesQuant++;
                    } else {
                        $echecs++;
                    }

                    $g = getGrade($note100);
                ?>

                <tr>
                    <td class="number-column">{{ $i++ }}</td>
                    <td class="mat-column">{{ $e->matricule }}</td>
                    <td>{{ $e->nom.' '.$e->prenom }}</td>

                    {{-- CC /20 --}}
                    <td class="note-column">
                        @if($cc20 !== null)
                            {{ $cc20 }}
                        @endif
                    </td>

                    {{-- SN /20 --}}
                    <td class="note-column">
                        @if($sn20 !== null)
                            {{ $sn20 }}
                        @endif
                    </td>

                    {{-- TPE /20 (optionnel) --}}
                    @if($hasTPE)
                        <td class="note-column">
                            @if($tpe20 !== null)
                                {{ $tpe20 }}
                            @endif
                        </td>
                    @endif

                    {{-- TOTAL /20 --}}
                    <td class="note-column">
                        {{ $total20 }}
                    </td>

                    {{-- GP & Grade (sur base note100) --}}
                    <td class="note-column">{{ $g[1] }}</td>
                    <td class="note-column">{{ $g[0] }}</td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>

    <?php
        if ($totalNotes == 0) {
            $totalNotes = 1; // pour éviter division par 0
        }
    ?>

    <div>
        <p><b>Capitalisation :</b> {{ round(($notesCap / $totalNotes) * 100, 2) }} %</p>
        <p><b>Quantification :</b> {{ round(($notesQuant / $totalNotes) * 100, 2) }} %</p>
        <p><b>Echec :</b> {{ round(($echecs / $totalNotes) * 100, 2) }} %</p>
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
