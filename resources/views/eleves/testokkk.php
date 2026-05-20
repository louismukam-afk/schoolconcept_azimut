@extends('skeleton')

@section('sub-content')
@include('perso.functions')

<?php
// Paramètres d'affichage (comme dans print_bilan)
$standartLimite = isset($standartLimite) ? $standartLimite : 5;
$current_matiere = 0;
$current_note = 0;
?>

<style>
    .table-note { display: block; }
    div.table { overflow-x:auto; }
</style>

<style media="print">
    #note-excel,
    .dt-buttons,
    #note-excel_filter,
    #note-excel_info,
    #note-excel_paginate { display: none; }
    .tabletoprint { page-break-after: always; }
</style>

<a href="#" class="btn btn-lg btn-warning" onclick="window.print()">
    <span class="glyphicon glyphicon-print"></span> @lang('inscription.imprimer')
</a>

<link rel="stylesheet" href="{{ asset('css/pv.css') }}">

{{-- TITRE --}}

<div class="titre">
    <p style="font-size:19px"><strong>Résultats des Examens du semestre {{ $semestre }}</strong></p>
    <strong class="traduction">Semester {{ $semestre }} exam results</strong> <br>
    <strong>Année académique / Academic year : {{ $annee }}</strong>
</div>

<div class="classe">
    <div class="specialite">
        <p><strong>Spécialité : {{ $classe->nom }} — Niveau {{ $nniveau->nom }}</strong></p>
        <strong class="traduction">Speciality : {{ $classe->nom_anglais }} — Level {{ $nniveau->nom }}</strong>
    </div>
    <div class="filiere">
        <p><strong>Filière : {{ \gesecole\niveau::find($classe->id_niveau)->nom }}</strong></p>
        <strong class="traduction">{{ \gesecole\niveau::find($classe->id_niveau)->nom_anglais }}</strong>
    </div>
</div>

{{-- Indexer PV par id_eleve pour accès rapide (compatible anciennes versions) --}}
<?php
$PVIndex = array();
if (!empty($PV)) {
    foreach ($PV as $p) {
        if (isset($p->id_eleve)) $PVIndex[$p->id_eleve] = $p;
    }
}
?>

{{-- TABLEAU PRINCIPAL (une colonne par matière, totals) --}}
<div class="table">
    <table id="note-excel" class="table table-bordered table-striped table-condensed">
        <thead>
        <tr>
            <th>#</th>
            <th>Matricule</th>
            <th>Nom</th>

            {{-- Colonnes matières (affiche uniquement le Total /20 dans la colonne principale) --}}
            @foreach($matieres as $m)
            <th>
                {{ (App::getLocale()=='en') ? $m->nom_anglais : $m->nom }}
            </th>
            @endforeach

            <th>Moy/20</th>
            <th>MGP</th>
        </tr>
        </thead>
        <tbody>
        <?php $i = 1; ?>
        @foreach($eleves as $e)
        <?php
        $pv = isset($PVIndex[$e->id]) ? $PVIndex[$e->id] : null;
        ?>
        <tr>
            <td>{{ $i++ }}</td>
            <td>{{ $e->matricule }}</td>
            <td>{{ $e->nom . ' ' . $e->prenom }}</td>

            {{-- Totaux par matière --}}
            @foreach($matieres as $m)
            <?php
            $matNote = null;
            if ($pv && !empty($pv->matieres)) {
                foreach ($pv->matieres as $mm) {
                    if (isset($mm->id_matiere) && $mm->id_matiere == $m->id) {
                        $matNote = $mm;
                        break;
                    }
                }
            }
            ?>
            <td class="note-column">
                @if($matNote && isset($matNote->note) && $matNote->note !== null)
                {{ number_format($matNote->note, 2) }}
                @else
                -
                @endif
            </td>
            @endforeach

            {{-- Moyenne et MGP (même logique que print_bilan) --}}
            <td class="note-column">
                {{ number_format( (isset($pv->mgp) ? $pv->mgp : 0) , 2) }}
            </td>
            <td class="note-column">
                {{ number_format( (isset($pv->mgp) ? $pv->mgp : 0) / 5, 2) }}
            </td>
        </tr>
        @endforeach
        </tbody>
    </table>
</div>

{{-- TABLEAUX DÉTAILLÉS PAR BLOCS (CC / SN / TPE / TOTAL) — découpage par $standartLimite comme dans print_bilan --}}
<?php $current_matiere = 0; $nb_matieres = count($matieres); ?>
{{--  <div class="titre">
    <p style="font-size:19px"><strong>Résultats des Examens du semestre {{ $semestre }}</strong></p>
    <strong class="traduction">Semester {{ $semestre }} exam results</strong>
</div>--}}

@while($current_matiere < $nb_matieres)
<?php
$limite = $current_matiere + $standartLimite;
if ($limite > $nb_matieres) $limite = $nb_matieres;
?>
<div class="tabletoprint">

    <div class="classe">
        <div class="specialite">
            <p><strong>Spécialité : {{ $classe->nom }} — Niveau {{ $nniveau->nom }}</strong></p>
        </div>
        <div class="filiere">
            <p><strong>Filière : {{ \gesecole\niveau::find($classe->id_niveau)->nom }}</strong></p>
        </div>
    </div>

    <table class="table table-note table-bordered table-striped">
        <thead>
        <tr>
            <th rowspan="2">#</th>
            <th rowspan="2">Matricule</th>
            <th rowspan="2">Nom</th>

            {{-- Entêtes des matières pour ce bloc --}}
            @for($idx = $current_matiere; $idx < $limite; $idx++)
            <?php $m = $matieres[$idx]; ?>
            <th colspan="4">
                {{ (App::getLocale()=='en') ? $m->nom_anglais : $m->nom }}
                <br><small>Credit: {{ isset($m->credit) ? $m->credit : '-' }}</small>
            </th>
            @endfor

            @if($limite == $nb_matieres)
            <th rowspan="2">Moy/20</th>
            <th rowspan="2">MGP</th>
            @endif
        </tr>

        <tr>
            {{-- Sous-entêtes CC / SN / TPE / Total --}}
            @for($idx = $current_matiere; $idx < $limite; $idx++)
            <th>CC</th>
            <th>SN</th>
            <th>TPE</th>
            <th>Total</th>
            @endfor
        </tr>
        </thead>

        <tbody>
        <?php $i = 1; ?>
        @foreach($eleves as $e)
        <?php $pv = isset($PVIndex[$e->id]) ? $PVIndex[$e->id] : null; ?>
        <tr>
            <td>{{ $i++ }}</td>
            <td>{{ $e->matricule }}</td>
            <td>{{ $e->nom . ' ' . $e->prenom }}</td>

            {{-- Pour chaque matière du bloc afficher CC / SN / TPE / Total --}}
            @for($idx = $current_matiere; $idx < $limite; $idx++)
            <?php
            $m = $matieres[$idx];
            $matNote = null;
            if ($pv && !empty($pv->matieres)) {
                foreach ($pv->matieres as $mm) {
                    if (isset($mm->id_matiere) && $mm->id_matiere == $m->id) {
                        $matNote = $mm;
                        break;
                    }
                }
            }
            ?>
            {{-- CC --}}
            <td class="note-column">
                @if($matNote && isset($matNote->cc) && $matNote->cc !== null)
                {{ number_format($matNote->cc, 2) }}
                @else
                -
                @endif
            </td>

            {{-- SN / RAT --}}
            <td class="note-column">
                @if($matNote && isset($matNote->sn) && $matNote->sn !== null)
                {{ number_format($matNote->sn, 2) }}
                @else
                -
                @endif
            </td>

            {{-- TPE --}}
            <td class="note-column">
                @if($matNote && isset($matNote->tpe) && $matNote->tpe !== null)
                {{ number_format($matNote->tpe, 2) }}
                @else
                -
                @endif
            </td>

            <td class="note-column">
                @if($matNote && isset($matNote->total20))
                <?php $val = $matNote->total20; ?>
                @if($val < 10)
                <span style="background-color:red; color:#fff; padding:2px 6px;">
                {{ number_format($val,2) }}
            </span>
                @elseif($val >= 8 && $val < 10)
                <span style="background-color:orange; color:#fff; padding:2px 6px;">
                {{ number_format($val,2) }}
            </span>
                @else
                <span style="background-color:green; color:#fff; padding:2px 6px;">
                {{ number_format($val,2) }}
            </span>
                @endif
                @else
                -
                @endif
            </td>

            @endfor

            {{-- Moyenne / MGP affichés uniquement sur le dernier bloc pour garder largeur --}}
            @if($limite == $nb_matieres)
            <td class="note-column">{{ number_format( (isset($pv->mgp) ? $pv->mgp : 0), 2) }}</td>
            <td class="note-column">{{ number_format( (isset($pv->mgp) ? $pv->mgp : 0) /5, 2) }}</td>
            @endif
        </tr>
        @endforeach

        {{-- Ligne pourcentages de validation dépendants des pourcentages de session --}}
        <tr style="font-size:13px; font-weight:bold;">
            <td></td><td></td><td>% validation</td>

            @for($idx = $current_matiere; $idx < $limite; $idx++)
            <?php $m = $matieres[$idx]; ?>

            {{-- Récupérer la session_exams pour cette matière / classe_matiere / année --}}
            <?php
            // Tentative : la table matieres issue de computePVSemestreGlobal contient id_classe_matiere si tu l'as sélectionnée.
            // Si non, on recherche via classe_matiere / session_exams.
            $sessionConf = null;
            try {
                // On essaye d'utiliser id_classe_matiere si présent
                if (isset($m->id_classe_matiere)) {
                    $sessionConf = \gesecole\Session_exam::where('id_classe_matiere', $m->id_classe_matiere)
                        ->where('annee_academique', $annee)
                        ->first();
                } else {
                    // fallback : cherche session_exams par id_matiere et année (moins précis mais utile)
                    $sessionConf = \gesecole\Session_exam::where('id_matiere', $m->id)
                        ->where('annee_academique', $annee)
                        ->first();
                }
            } catch(\Exception $ex) {
                $sessionConf = null;
            }

            // Déterminer les pourcentages pour ce semestre
            $p_cc = $p_sn = $p_tpe = 0.0;
            if ($sessionConf) {
                if ($semestre == 1) {
                    $p_cc  = isset($sessionConf->cc1) ? floatval($sessionConf->cc1) : 0.0;
                    $p_sn  = isset($sessionConf->sn1) ? floatval($sessionConf->sn1) : 0.0;
                    $p_tpe = isset($sessionConf->tpe1) ? floatval($sessionConf->tpe1) : 0.0;
                    // rattrapage1 -> si tu veux l'afficher aussi
                } else {
                    $p_cc  = isset($sessionConf->cc2) ? floatval($sessionConf->cc2) : 0.0;
                    $p_sn  = isset($sessionConf->sn2) ? floatval($sessionConf->sn2) : 0.0;
                    $p_tpe = isset($sessionConf->tpe2) ? floatval($sessionConf->tpe2) : 0.0;
                }
            }

            // Seuils sur la composante (en /20) : 50% de la contribution maximale de la composante
            // contribution max de la composante = p_comp/100 * 20
            // seuil retenu = 50% * contribution max = p_comp * 0.1
            $seuil_cc  = round($p_cc  * 0.1, 2);
            $seuil_sn  = round($p_sn  * 0.1, 2);
            $seuil_tpe = round($p_tpe * 0.1, 2);

            // Défaut si p_comp == 0 : on marquera '-' (pas configuré)
            ?>
            {{-- CC % --}}
            <td class="note-column">
                <?php $nbValid = 0; $nbNotes = 0; ?>
                @foreach($PV as $pvRow)
                @foreach($pvRow->matieres as $matRow)
                @if($matRow->id_matiere == $m->id)
                {{-- matRow->cc contient la valeur CC en /20 après conversion --}}
                @if(isset($matRow->cc) && $matRow->cc !== null && $p_cc > 0)
                <?php $nbNotes++; if (floatval($matRow->cc) >= $seuil_cc) $nbValid++; ?>
                @elseif(isset($matRow->cc) && $matRow->cc !== null && $p_cc == 0)
                {{-- si composante non configurée pour ce semestre, on ne compte pas --}}
                @endif
                <?php break; ?>
                @endif
                @endforeach
                @endforeach
                @if($nbNotes > 0) {{ round(($nbValid/$nbNotes)*100,2) }} % @else - @endif
            </td>

            {{-- SN % --}}
            <td class="note-column">
                <?php $nbValid = 0; $nbNotes = 0; ?>
                @foreach($PV as $pvRow)
                @foreach($pvRow->matieres as $matRow)
                @if($matRow->id_matiere == $m->id)
                @if(isset($matRow->sn) && $matRow->sn !== null && $p_sn > 0)
                <?php $nbNotes++; if (floatval($matRow->sn) >= $seuil_sn) $nbValid++; ?>
                @elseif(isset($matRow->sn) && $matRow->sn !== null && $p_sn == 0)
                {{-- composante non configurée --}}
                @endif
                <?php break; ?>
                @endif
                @endforeach
                @endforeach
                @if($nbNotes > 0) {{ round(($nbValid/$nbNotes)*100,2) }} % @else - @endif
            </td>

            {{-- TPE % --}}
            <td class="note-column">
                <?php $nbValid = 0; $nbNotes = 0; ?>
                @foreach($PV as $pvRow)
                @foreach($pvRow->matieres as $matRow)
                @if($matRow->id_matiere == $m->id)
                @if(isset($matRow->tpe) && $matRow->tpe !== null && $p_tpe > 0)
                <?php $nbNotes++; if (floatval($matRow->tpe) >= $seuil_tpe) $nbValid++; ?>
                @elseif(isset($matRow->tpe) && $matRow->tpe !== null && $p_tpe == 0)
                {{-- composante non configurée --}}
                @endif
                <?php break; ?>
                @endif
                @endforeach
                @endforeach
                @if($nbNotes > 0) {{ round(($nbValid/$nbNotes)*100,2) }} % @else - @endif
            </td>

            {{-- Total % : on compte les élèves dont total20 (note finale /20) >= 10 (50%) --}}
            <td class="note-column">
                <?php $nbValid = 0; $nbNotes = 0; ?>
                @foreach($PV as $pvRow)
                @foreach($pvRow->matieres as $matRow)
                @if($matRow->id_matiere == $m->id)
                @if(isset($matRow->total20) && $matRow->total20 !== null)
                <?php $nbNotes++; if (floatval($matRow->total20) >= 10.0) $nbValid++; ?>
                @endif
                <?php break; ?>
                @endif
                @endforeach
                @endforeach
                @if($nbNotes > 0) {{ round(($nbValid/$nbNotes)*100,2) }} % @else - @endif
            </td>

            @endfor

            @if($limite == $nb_matieres)
            <td></td><td></td>
            @endif
        </tr>

        </tbody>
    </table>
</div>

<?php $current_matiere = $limite; ?>
@endwhile
<script>

    $('#note-excel').dataTable( {
        dom: 'Bfrtip',
        pageLength:2000,
        buttons: [
            //'copy', 'csv', 'excel', 'pdf', 'print'
            {
                extend: 'excelHtml5',
                text: '<i class="fa fa-file-excel-o"></i> Excel',
                titleAttr: 'Export to Excel',
                title: 'Bilan annuel des notes  de la Spécialité {{$classe->nom}} - {{$nniveau->nom}}'

            }

        ]
    } );
</script>

@endsection

@section('breadcrumb')
<li><strong><a href="{{url('eleves')}}">@lang('menu.gestion_notes')</a></strong></li>
<li><strong><a href="{{url('eleves/notes')}}">Notes</a></strong></li>
<li><a href="{{url('eleves/notes/show')}}"><strong>@lang('admin.choix_classe')</strong></a></li>
<li class="active"><strong>{{ $titre }}</strong></li>
@endsection
