@extends('skeleton')

@section('sub-content')
@include('perso.functions')

<?php
// paramètres
$standartLimite = isset($standartLimite) ? $standartLimite : 5;
$current_matiere = 0;
$current_note = 0;

// Indexation S1 / S2 PV par élève pour accès rapide (compatible si le contrôleur
// a passé PV_s1_map / PV_s2_map ou S1['PV'] / S2['PV'])
$PVmapS1 = array();
$PVmapS2 = array();

if (isset($PV_s1_map) && is_array($PV_s1_map) && count($PV_s1_map) > 0) {
    $PVmapS1 = $PV_s1_map;
} elseif (isset($S1) && isset($S1['PV'])) {
    foreach ($S1['PV'] as $pv) {
        if (isset($pv->id_eleve)) $PVmapS1[$pv->id_eleve] = $pv;
    }
}

if (isset($PV_s2_map) && is_array($PV_s2_map) && count($PV_s2_map) > 0) {
    $PVmapS2 = $PV_s2_map;
} elseif (isset($S2) && isset($S2['PV'])) {
    foreach ($S2['PV'] as $pv) {
        if (isset($pv->id_eleve)) $PVmapS2[$pv->id_eleve] = $pv;
    }
}

// Helper : retrouver la pv_matiere dans un PVsem (compatible ancien format)
function findMatNote($pvObj, $matiereId) {
    if (!$pvObj) return null;
    if (!isset($pvObj->matieres) || !is_array($pvObj->matieres)) return null;
    foreach ($pvObj->matieres as $m) {
        if (isset($m->id_matiere) && $m->id_matiere == $matiereId) return $m;
        // Si ton objet utilise 'idMatiere' ou 'id' adapte ici.
    }
    return null;
}
?>

<style>
    .table-note { display:block; }
    div.table { overflow-x:auto; }
</style>

<style media="print">
    #note-excel, .dt-buttons, #note-excel_filter, #note-excel_info, #note-excel_paginate { display:none; }
    .tabletoprint { page-break-after: always; }
</style>

<a href="#" class="btn btn-lg btn-warning" onclick="window.print()">
    <span class="glyphicon glyphicon-print"></span> @lang('inscription.imprimer')
</a>

<link rel="stylesheet" href="{{ asset('css/pv.css') }}">

{{-- ENTETE --}}
<div class="titre">
    <p style="font-size:19px">
        <strong>{{ isset($titre) ? $titre : 'Bilan annuel' }}</strong>
    </p>
    <strong class="traduction">Annual review</strong>
</div>

<div class="classe">
    <div class="specialite">
        <p><strong>Spécialité : {{ $classe->nom }} - Niveau {{ $nniveau->nom }}</strong></p>
        <strong class="traduction">Speciality : {{ $classe->nom_anglais }} - Level {{ $nniveau->nom }}</strong>
    </div>
    <div class="filiere">
        <p><strong>Filière : {{ \gesecole\niveau::find($classe->id_niveau)->nom }}</strong></p>
        <strong class="traduction">{{ \gesecole\niveau::find($classe->id_niveau)->nom_anglais }}</strong>
    </div>
    <div class="annee">
        <p><strong>Année académique : {{ isset($annee) ? $annee : session('year') }}</strong></p>
    </div>
</div>

{{-- TABLEAU PRINCIPAL : une colonne par matière (valeurs annuelles) --}}
<div class="table">
    <table id="note-excel" class="table table-bordered table-striped table-condensed">
        <thead>
        <tr>
            <th>#</th>
            <th>Matricule</th>
            <th>Nom</th>

            @foreach($matieres as $m)
            <th>{{ (App::getLocale()=='en') ? $m->nom_anglais : $m->nom }}<br><small>Cr:{{ isset($m->credit) ? $m->credit : 1 }}</small></th>
            @endforeach

            <th>Moy. Générale /20</th>
            <th>Crédits validés</th>
        </tr>
        </thead>
        <tbody>
        <?php $i = 1; ?>
        @foreach($eleves as $e)
        <?php
        $pv1 = isset($PVmapS1[$e->id]) ? $PVmapS1[$e->id] : null;
        $pv2 = isset($PVmapS2[$e->id]) ? $PVmapS2[$e->id] : null;

        $sumAnnual = 0.0;
        $countMat = count($matieres);
        $creditsValidated = 0.0;
        ?>
        <tr>
            <td>{{ $i++ }}</td>
            <td>{{ $e->matricule }}</td>
            <td>{{ $e->nom.' '.$e->prenom }}</td>

            @foreach($matieres as $m)
            <?php
            // chercher note S1 et S2 pour cette matière et cet élève
            $m1 = findMatNote($pv1, $m->id);
            $m2 = findMatNote($pv2, $m->id);

            // total20 par semestre (champ -> note dans pv_matiere)
            $valS1 = (isset($m1->note) && $m1->note !== null) ? floatval($m1->note) : null;
            $valS2 = (isset($m2->note) && $m2->note !== null) ? floatval($m2->note) : null;

            // Si tu veux appliquer priorité rattrapage : dans renderPVSemestre
            // on avait mis sn = (rat ? rat : sn) ; donc valS1/valS2 contiennent déjà RAT si présent.
            // Moyenne annuelle matière = moyenne des deux semestres (si une des deux missing, on prend celle disponible)
            if ($valS1 === null && $valS2 === null) {
                $annual = null;
            } elseif ($valS1 === null) {
                $annual = $valS2;
            } elseif ($valS2 === null) {
                $annual = $valS1;
            } else {
                $annual = round( ($valS1 + $valS2) / 2.0, 2 );
            }

            if ($annual !== null) {
                $sumAnnual += $annual;
                // crédits validés si annual >= 10
                $credit = isset($m->credit) ? floatval($m->credit) : 1.0;
                if ($annual >= 10.0) $creditsValidated += $credit;
            }
            ?>

            {{-- cellule : affichage S1 / S2 / annual en tooltip (ou simple) --}}
            <td style="text-align:center;">
                <div style="font-size:11px">
                    <div><small>S1: {{ $valS1 !== null ? number_format($valS1,2) : '-' }}</small></div>
                    <div><small>S2: {{ $valS2 !== null ? number_format($valS2,2) : '-' }}</small></div>
                    <div style="margin-top:4px;">
                        @if($annual !== null)
                        <?php $color = ($annual >= 10) ? 'green' : 'red'; ?>
                        <span style="background-color:{{ $color }}; color:#fff; padding:2px 6px;">
                                            {{ number_format($annual,2) }}
                                        </span>
                        @else
                        -
                        @endif
                    </div>
                </div>
            </td>
            @endforeach

            {{-- Moyenne générale /20 (moyenne des annual sur toutes les matières) --}}
            <td style="text-align:center;">
                @if($countMat > 0)
                <?php
                // On inclut matières sans note comme 0 dans la moyenne si tu veux les compter.
                // Ici on suit ta demande "moyenne = total des notes / nombre de matière"
                // ==> si une matière n'a pas de note, on la compte comme 0.
                // Si tu veux ignorer les matières sans note, remplace $countMat par $nbMatWithNote.
                $moyGenerale = round( ($sumAnnual) / $countMat, 2 );
                ?>
                {{ number_format($moyGenerale, 2) }}
                @else
                -
                @endif
            </td>

            {{-- Crédits validés --}}
            <td style="text-align:center;">
                {{ number_format($creditsValidated, 2) }}
            </td>

        </tr>
        @endforeach
        </tbody>
    </table>
</div>

{{-- TABLEAUX DETAILLES (par blocs de matières) --}}
<?php $nb_matieres = count($matieres); $current_matiere = 0; ?>
@while($current_matiere < $nb_matieres)
<?php
$limite = $current_matiere + $standartLimite;
if ($limite > $nb_matieres) $limite = $nb_matieres;
?>
<div class="tabletoprint">
    <div class="titre">
        <p style="font-size:19px"><strong>{{ isset($titre) ? $titre : 'Bilan annuel' }}</strong></p>
        <strong class="traduction">Annual review</strong>
    </div>

    <table class="table table-note table-bordered table-striped">
        <thead>
        <tr>
            <th rowspan="2">#</th>
            <th rowspan="2">Matricule</th>
            <th rowspan="2">Nom</th>
            @for($idx = $current_matiere; $idx < $limite; $idx++)
            <?php $m = $matieres[$idx]; ?>
            <th colspan="3">
                {{ (App::getLocale()=='en') ? $m->nom_anglais : $m->nom }}
                <br><small>Cr: {{ isset($m->credit) ? $m->credit : 1 }}</small>
            </th>
            @endfor
            <th rowspan="2">Moy Générale /20</th>
            <th rowspan="2">Crédits validés</th>
        </tr>
        <tr>
            @for($idx = $current_matiere; $idx < $limite; $idx++)
            <th>S1</th>
            <th>S2</th>
            <th>Annuel</th>
            @endfor
        </tr>
        </thead>

        <tbody>
        <?php $i = 1; ?>
        @foreach($eleves as $e)
        <?php
        $pv1 = isset($PVmapS1[$e->id]) ? $PVmapS1[$e->id] : null;
        $pv2 = isset($PVmapS2[$e->id]) ? $PVmapS2[$e->id] : null;
        $sumAnn = 0.0; $countMat = count($matieres);
        $creditsVal = 0.0;
        ?>
        <tr>
            <td>{{ $i++ }}</td>
            <td>{{ $e->matricule }}</td>
            <td>{{ $e->nom.' '.$e->prenom }}</td>

            @for($idx = $current_matiere; $idx < $limite; $idx++)
            <?php $m = $matieres[$idx]; ?>
            <?php
            $m1 = findMatNote($pv1, $m->id);
            $m2 = findMatNote($pv2, $m->id);
            $v1 = (isset($m1->note) && $m1->note !== null) ? floatval($m1->note) : null;
            $v2 = (isset($m2->note) && $m2->note !== null) ? floatval($m2->note) : null;
            if ($v1 === null && $v2 === null) $ann = null;
            elseif ($v1 === null) $ann = $v2;
            elseif ($v2 === null) $ann = $v1;
            else $ann = round(($v1 + $v2)/2.0, 2);

            if ($ann !== null) {
                $sumAnn += $ann;
                $credit = isset($m->credit) ? floatval($m->credit) : 1.0;
                if ($ann >= 10.0) $creditsVal += $credit;
            }
            ?>
            <td style="text-align:center;">{{ $v1 !== null ? number_format($v1,2) : '-' }}</td>
            <td style="text-align:center;">{{ $v2 !== null ? number_format($v2,2) : '-' }}</td>
            <td style="text-align:center;">
                @if($ann !== null)
                <?php $col = ($ann >= 10.0) ? '#4CAF50' : '#E53935'; ?>
                <span style="background-color:{{ $col }}; color:#fff; padding:2px 6px;">
                                        {{ number_format($ann,2) }}
                                    </span>
                @else
                -
                @endif
            </td>
            @endfor

            {{-- Moy et crédits --}}
            <td style="text-align:center;">
                @if($countMat > 0)
                {{ number_format( round($sumAnn / $countMat, 2), 2) }}
                @else
                -
                @endif
            </td>
            <td style="text-align:center;">{{ number_format($creditsVal,2) }}</td>
        </tr>
        @endforeach

        {{-- Ligne % validation (exemple : % eleves >= seuil par composante) --}}
        <tr style="font-weight:bold; font-size:13px;">
            <td></td><td></td><td>% validation</td>
            @for($idx = $current_matiere; $idx < $limite; $idx++)
            <?php $m = $matieres[$idx]; ?>
            <?php $nbNotes=0; $nbValid=0; ?>
            @foreach($eleves as $e)
            <?php
            $pv1 = isset($PVmapS1[$e->id]) ? $PVmapS1[$e->id] : null;
            $pv2 = isset($PVmapS2[$e->id]) ? $PVmapS2[$e->id] : null;
            $m1 = findMatNote($pv1, $m->id);
            $m2 = findMatNote($pv2, $m->id);
            $v1 = (isset($m1->note) && $m1->note !== null) ? floatval($m1->note) : null;
            $v2 = (isset($m2->note) && $m2->note !== null) ? floatval($m2->note) : null;
            if ($v1 !== null) { $nbNotes++; if ($v1 >= 10.0) $nbValid++; }
            if ($v2 !== null) { $nbNotes++; if ($v2 >= 10.0) $nbValid++; }
            ?>
            @endforeach
            <td colspan="3">
                @if($nbNotes > 0) {{ round(($nbValid / $nbNotes) * 100, 2) }} % @else - @endif
            </td>
            @endfor
            <td></td><td></td>
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
<li><strong><a href="{{ url('eleves') }}">@lang('menu.gestion_notes')</a></strong></li>
<li><strong><a href="{{ url('eleves/notes') }}">Notes</a></strong></li>
<li><a href="{{ url('eleves/notes/show') }}"><strong>@lang('admin.choix_classe')</strong></a></li>
<li class="active"><strong>{{ isset($titre) ? $titre : 'Bilan' }}</strong></li>
@endsection
