@extends('skeleton')

@section('sub-content')
<?php
    $totalCredits = 0;
    foreach ($matieres as $m) $totalCredits += (float) $m->credit;
    $fmt = function ($value) {
        if ($value === null || $value === '') return '';
        return number_format((float) $value, 2, ',', ' ');
    };
    $codeFor = function ($matiere) {
        if (!empty($matiere->code_matiere)) return $matiere->code_matiere;
        if (!empty($matiere->id_code)) return $matiere->id_code;
        return 'MAT '.$matiere->id_matiere;
    };
    $matiereChunks = count($matieres) > 0 ? array_chunk($matieres, max(1, (int) ceil(count($matieres) / 3))) : array();
?>

<style>
    .pv-bts-wrap { padding: 10px; }
    .pv-bts-toolbar { margin: 8px 0 12px; }
    .pv-bts-title { text-align: center; margin-bottom: 10px; }
    .pv-bts-title h2 { margin: 2px 0; font-size: 18px; font-weight: bold; text-transform: uppercase; }
    .pv-bts-title h3 { margin: 2px 0; font-size: 14px; font-weight: bold; }
    .pv-bts-meta { width: 100%; margin: 8px 0; font-size: 11px; }
    .pv-bts-meta td { padding: 2px 8px; }
    .pv-bts-legend-grid { width: 96%; margin: 8px auto 10px; display: table; table-layout: fixed; border-spacing: 8px 0; }
    .pv-bts-legend-col { display: table-cell; width: 33.33%; vertical-align: top; }
    .pv-bts-legend { width: 100%; border-collapse: collapse; font-size: 8px; }
    .pv-bts-legend th, .pv-bts-legend td { border: 1px solid #222; padding: 2px 4px; line-height: 1.1; }
    .pv-bts-legend th { background: #969696; text-align: center; text-transform: uppercase; }
    .pv-bts-legend td:first-child, .pv-bts-legend td:last-child { width: 42px; text-align: center; white-space: nowrap; }
    .pv-bts-legend td:nth-child(2) { text-align: left; }
    .pv-bts-scroll { overflow-x: auto; width: 100%; }
    .pv-bts-table { border-collapse: collapse; width: 100%; font-size: 7.2px; }
    .pv-bts-table th, .pv-bts-table td { border: 1px solid #222; padding: 2px 3px; text-align: center; vertical-align: middle; }
    .pv-bts-table th { font-weight: bold; background: #bfbfbf; line-height: 1.05; }
    .pv-bts-table .left { text-align: left; white-space: nowrap; }
    .pv-bts-table .name { min-width: 165px; font-size: 13px; }
    .pv-bts-table .code { min-width: 42px; }
    .pv-bts-table .module { background: #969696; }
    .pv-bts-table .summary { font-weight: bold; }
    .pv-bts-table .valide { background: #d8efd8; }
    .pv-bts-table .echec { background: #f5d1d1; }
    .pv-bts-table .vide { background: #efefef; color: #888; }
    .pv-bts-table .small-pct { display: block; font-size: 7px; font-weight: normal; }
    .pv-bts-signatures { margin-top: 22px; width: 100%; font-size: 11px; }
    .pv-bts-signatures td { width: 33%; text-align: center; padding-top: 28px; }
    @media print {
        @page { size: A3 landscape; margin: 8mm; }
        .no-print, .navbar, .sidebar, footer { display: none !important; }
        .pv-bts-wrap { padding: 0; }
        .pv-bts-table { font-size: 6.2px; }
        .pv-bts-legend { font-size: 7px; }
        .content-wrapper, .right-side { margin-left: 0 !important; }
    }
</style>

<div class="pv-bts-wrap">
    <div class="pv-bts-toolbar no-print">
        <button type="button" class="btn btn-primary btn-sm" onclick="window.print()">Imprimer</button>
        <a class="btn btn-default btn-sm" href="{{ url('/eleves/notes/print_note/'.$classe->id.'?sequence='.($semestre == 1 ? 3 : 4).'&cycle='.$cycle->id.'&nniveau='.$nniveau->id.'&pv_type=bts&date_debut='.$date_debut.'&date_fin='.$date_fin) }}">PV standard</a>
    </div>

    @include('eleves.partials.pv_tutelle_header')

    <div class="pv-bts-title">
        <h2>RESULTATS DU SEMESTRE {{ $semestre }} - BTS</h2>
        <h3>{{ isset($titre) ? $titre : 'PV BTS' }} - Annee academique {{ $annee }}</h3>
        <h3>{{ $classe->nom }} / {{ $cycle->nom }} / {{ $nniveau->nom }}</h3>
    </div>

    <table class="pv-bts-meta">
        <tr>
            <td><strong>Specialite :</strong> {{ $classe->nom }}</td>
            <td><strong>Semestre :</strong> {{ $semestre }}</td>
            <td><strong>Credits :</strong> {{ $totalCredits }}</td>
            <td><strong>Etudiants :</strong> {{ count($results) }}</td>
        </tr>
        @if($date_debut || $date_fin)
        <tr>
            <td colspan="4"><strong>Filtre dates :</strong> {{ $date_debut ?: '-' }} au {{ $date_fin ?: '-' }}</td>
        </tr>
        @endif
    </table>

    <div class="pv-bts-legend-grid">
        @foreach($matiereChunks as $chunk)
            <div class="pv-bts-legend-col">
                <table class="pv-bts-legend">
                    <thead>
                        <tr>
                            <th>Code</th>
                            <th>Matiere</th>
                            <th>Credit</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($chunk as $matiere)
                            <tr>
                                <td>{{ $codeFor($matiere) }}</td>
                                <td>{{ $matiere->nom }}</td>
                                <td>{{ number_format((float) $matiere->credit, 0) }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endforeach
    </div>

    <div class="pv-bts-scroll">
        <table class="pv-bts-table">
            <thead>
                <tr>
                    <th rowspan="4">N</th>
                    <th rowspan="4">MAT</th>
                    <th rowspan="4" class="name">NOMS</th>
                    @foreach($matieres as $matiere)
                        <th class="module" colspan="4">{{ $codeFor($matiere) }}</th>
                    @endforeach
                    <th rowspan="4">MOY /20</th>
                    <th rowspan="4">TOTAL CREDIT</th>
                    <th rowspan="4">RANG</th>
                    <th rowspan="4">DECISION</th>
                </tr>
                <tr>
                    @foreach($matieres as $matiere)
                        <th colspan="4">{{ $codeFor($matiere) }}</th>
                    @endforeach
                </tr>
                <tr>
                    @foreach($matieres as $matiere)
                        <th colspan="4">CREDIT : {{ number_format((float) $matiere->credit, 0) }}</th>
                    @endforeach
                </tr>
                <tr>
                    @foreach($matieres as $matiere)
                        <th>CC<span class="small-pct">/{{ number_format((float) $matiere->pct_cc, 0) }}</span></th>
                        <th>EXAM<span class="small-pct">/{{ number_format((float) $matiere->pct_sn, 0) }}</span></th>
                        <th>NOTE /100</th>
                        <th>CREDIT</th>
                    @endforeach
                </tr>
            </thead>
            <tbody>
                @foreach($results as $index => $row)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $row['eleve']->matricule }}</td>
                        <td class="left">{{ trim($row['eleve']->nom.' '.$row['eleve']->prenom) }}</td>
                        @foreach($matieres as $matiere)
                            <?php $key = isset($matiere->bilan_key) ? $matiere->bilan_key : $matiere->id_matiere; $note = isset($row['matieres'][$key]) ? $row['matieres'][$key] : null; ?>
                            <td>{{ $note ? $fmt($note['cc']) : '' }}</td>
                            <td>{{ $note ? $fmt($note['sn']) : '' }}</td>
                            <td class="summary {{ $note ? ($note['total100'] < 50 ? 'echec' : 'valide') : 'vide' }}">{{ $note ? $fmt($note['total100']) : '' }}</td>
                            <td>{{ $note ? number_format((float) $note['credit_valide'], 0) : '0' }}</td>
                        @endforeach
                        <td class="summary">{{ $fmt($row['moyenne20']) }}</td>
                        <td>{{ number_format((float) $row['credits_valides'], 0) }}/{{ number_format((float) $row['credits_prevus'], 0) }}</td>
                        <td>{{ $row['rang'] }}</td>
                        <td class="summary">{{ $row['decision'] }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <table class="pv-bts-signatures">
        <tr>
            <td>Le Chef de departement</td>
            <td>Le Directeur des etudes</td>
            <td>Le Directeur</td>
        </tr>
    </table>
</div>
@endsection
