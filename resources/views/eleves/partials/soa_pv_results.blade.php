<?php
    $soaSummaries = \gesecole\Services\SoaPvSummary::build($PV);
    $subjectList = array_values(is_array($matieres) ? $matieres : iterator_to_array($matieres));
    $subjectPages = array($subjectList);
    $admis = 0; $echec = 0;
    foreach ($soaSummaries as $summary) {
        if ($summary->average === null) continue;
        if ($summary->admitted) $admis++; else $echec++;
    }
?>
<style>
    .soa-pv-screen { overflow-x: auto; margin-top: 12px; }
    .soa-pv-print { display: none; }
    .soa-pv-results.table { margin-top: 5px; }
    .soa-pv-results th, .soa-pv-results td { text-align: center; vertical-align: middle !important; }
    .soa-pv-results .soa-pv-name { min-width: 150px; text-align: left; }
    .soa-pv-summary { font-weight: bold; }
    @media print {
        .soa-pv-screen { display: none !important; }
        .soa-pv-print { display: block; margin-top: 12px; }
        .soa-pv-results { font-size: 8px; }
        .soa-pv-results .soa-pv-name { min-width: 105px; }
        .soa-pv-results thead { display: table-header-group; }
        .soa-pv-results tfoot { display: table-row-group; }
        .soa-pv-results tr { break-inside: avoid; page-break-inside: avoid; }
    }
</style>
<div class="soa-pv-screen">
    @include('eleves.partials.soa_pv_results_table', array('screenTable'=>true, 'tableSubjects'=>$subjectList))
</div>
<div class="soa-pv-print">
    @foreach($subjectPages as $pageNumber=>$pageSubjects)
    <div class="soa-pv-print-page">
        <p><strong>{{ $classe->nom }} — {{ session('year') }} — Tableau {{ $pageNumber + 1 }}/{{ count($subjectPages) }}</strong></p>
        @include('eleves.partials.soa_pv_results_table', array('screenTable'=>false, 'tableSubjects'=>$pageSubjects))
    </div>
    @endforeach
</div>
<div class="soa-pv-statistics">
<p>Classement par moyenne générale décroissante ; les étudiants restent affichés par ordre alphabétique. Les ex æquo partagent le même rang.</p>
<h4><strong>@lang('admin.admis') :</strong> {{ $admis + $echec > 0 ? round(100 * $admis / ($admis + $echec), 2) : 0 }} %</h4>
<h4><strong>@lang('admin.echec') :</strong> {{ $admis + $echec > 0 ? round(100 * $echec / ($admis + $echec), 2) : 0 }} %</h4>
</div>
