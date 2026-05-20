@extends('skeleton')

@section('sub-content')
@include('perso.functions')

<?php
/* =========================
   OUTILS DE CALCUL
   ========================= */

function noteSur20($note, $pourcentage){
    if ($note === null) return 0;
    if ($pourcentage > 0) {
        return round(($note * 20) / $pourcentage, 2);
    }
    return round($note, 2);
}

function getPourcentage($m, $semestre){
    if ($semestre == 1) {
        if ($m->ratrappage1 > 0) return $m->ratrappage1;
        if ($m->sn1 > 0) return $m->sn1;
        if ($m->cc1 > 0) return $m->cc1;
        if ($m->tpe1 > 0) return $m->tpe1;
    } else {
        if ($m->ratrappage2 > 0) return $m->ratrappage2;
        if ($m->sn2 > 0) return $m->sn2;
        if ($m->cc2 > 0) return $m->cc2;
        if ($m->tpe2 > 0) return $m->tpe2;
    }
    return 0;
}

function hasSession($m, $semestre){
    if ($semestre == 1) {
        return ($m->cc1 + $m->sn1 + $m->tpe1 + $m->ratrappage1) > 0;
    }
    return ($m->cc2 + $m->sn2 + $m->tpe2 + $m->ratrappage2) > 0;
}

function findMatNote($pv, $id){
    if (!$pv || !isset($pv->matieres)) return null;
    foreach ($pv->matieres as $pm) {
        if ($pm->id_matiere == $id) return $pm;
    }
    return null;
}

/* =========================
   PRÉPARATION DONNÉES
   ========================= */

$PVmapS1 = isset($PV_s1_map) ? $PV_s1_map : array();
$PVmapS2 = isset($PV_s2_map) ? $PV_s2_map : array();

$matieres_s1 = array();
$matieres_s2 = array();
$creditPrevu = 0;

foreach ($matieres as $m) {
    $creditPrevu += $m->credit;
    if (hasSession($m, 1)) $matieres_s1[] = $m;
    if (hasSession($m, 2)) $matieres_s2[] = $m;
}
?>

<style>
    .table-officiel{display:block;}
    @media print{
        body *{visibility:hidden;}
        .table-officiel,.table-officiel *{visibility:visible;}
        .table-officiel{position:absolute;top:0;left:0;width:100%;}
        .btn{display:none;}
    }
</style>

<a href="#" class="btn btn-warning" onclick="window.print()">IMPRIMER</a>

<h3>{{ $titre }} — {{ $annee }}</h3>

<div class="classe">
    <div class="specialite">
        <p><strong>Spécialité : {{ $classe->nom }} — Niveau {{ $nniveau->nom }}</strong></p>
    </div>
    <div class="filiere">
        <p><strong>Filière : {{ \gesecole\niveau::find($classe->id_niveau)->nom }}</strong></p>
    </div>
</div>


{{-- ================= TABLEAU OFFICIEL ================= --}}
<div class="table table-officiel">
    <table class="table table-bordered">
        <thead>
        <tr>
            <th rowspan="2">#</th>
            <th rowspan="2">Matricule</th>
            <th rowspan="2">Nom</th>

            <th colspan="{{ count($matieres_s1) }}">SEMESTRE 1</th>
            <th rowspan="2">Moy S1</th>
            <th rowspan="2">Cr S1</th>

            <th colspan="{{ count($matieres_s2) }}">SEMESTRE 2</th>
            <th rowspan="2">Moy S2</th>
            <th rowspan="2">Cr S2</th>

            <th rowspan="2">Moy Annuelle</th>
            <th rowspan="2">Cr Validés</th>
            <th rowspan="2">Cr Prévus</th>
        </tr>
        <tr>
            @foreach($matieres_s1 as $m)<th>{{ $m->nom }}</th>@endforeach
            @foreach($matieres_s2 as $m)<th>{{ $m->nom }}</th>@endforeach
        </tr>
        </thead>

        <tbody>
        <?php $i = 1; ?>
        @foreach($eleves as $e)

        <?php
        $pv1 = isset($PVmapS1[$e->id]) ? $PVmapS1[$e->id] : null;
        $pv2 = isset($PVmapS2[$e->id]) ? $PVmapS2[$e->id] : null;

        $sum1 = 0; $sum2 = 0;
        $cr1 = 0;  $cr2 = 0;
        $hasAll = true;
        ?>

        <tr>
            <td>{{ $i++ }}</td>
            <td>{{ $e->matricule }}</td>
            <td>{{ $e->nom }} {{ $e->prenom }}</td>

            {{-- SEMESTRE 1 --}}
            @foreach($matieres_s1 as $m)
            <?php
            $n = findMatNote($pv1, $m->id);
            if ($n && $n->note !== null) {
                $v = noteSur20($n->note, getPourcentage($m,1));
                if ($v >= 10) $cr1 += $m->credit;
            } else {
                $v = 0;
                $hasAll = false;
            }
            $sum1 += $v;
            ?>
            <td>{{ number_format($v,2) }}</td>
            @endforeach

            <td>{{ count($matieres_s1)?number_format($sum1/count($matieres_s1),2):'0.00' }}</td>
            <td>{{ $cr1 }}</td>

            {{-- SEMESTRE 2 --}}
            @foreach($matieres_s2 as $m)
            <?php
            $n = findMatNote($pv2, $m->id);
            if ($n && $n->note !== null) {
                $v = noteSur20($n->note, getPourcentage($m,2));
                if ($v >= 10) $cr2 += $m->credit;
            } else {
                $v = 0;
                $hasAll = false;
            }
            $sum2 += $v;
            ?>
            <td>{{ number_format($v,2) }}</td>
            @endforeach

            <td>{{ count($matieres_s2)?number_format($sum2/count($matieres_s2),2):'0.00' }}</td>
            <td>{{ $cr2 }}</td>

            <td>
                {{ $hasAll ? number_format((($sum1/count($matieres_s1))+($sum2/count($matieres_s2)))/2,2) : '-' }}
            </td>

            <td>{{ $cr1 + $cr2 }}</td>
            <td>{{ $creditPrevu }}</td>
        </tr>

        @endforeach
        </tbody>
    </table>
</div>
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
