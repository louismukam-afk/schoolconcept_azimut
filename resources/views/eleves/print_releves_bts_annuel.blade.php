@extends('skeleton')
@section('sub-content')
<?php $titre = 'Relevé de notes BTS — '.$annee; ?>
<link rel="stylesheet" href="{{ asset('css/releve_bts_annuel.css') }}?v=20261020-3">
<div class="bts-report">
<nav class="bts-toolbar">
    <a class="btn btn-default" href="{{ url('/eleves/releves_bts_annuel') }}"><span class="glyphicon glyphicon-plus"></span> Nouveau relevé</a>
    <a class="btn btn-warning" href="{{ url('/eleves/releves_bts/historique') }}"><span class="glyphicon glyphicon-folder-open"></span> Historique BTS</a>
    <button type="button" class="btn btn-primary" onclick="window.print()"><span class="glyphicon glyphicon-print"></span> Imprimer / Enregistrer en PDF (A4)</button>
    <span>Période : {{ $periode_debut ? date('d/m/Y', strtotime($periode_debut)) : '—' }} au {{ $periode_fin ? date('d/m/Y', strtotime($periode_fin)) : '—' }}</span>
    @if(isset($generation))
    <form method="GET" class="bts-signature-form">
        <label for="print_signature">Date de signature</label>
        <input class="form-control" type="date" id="print_signature" name="date_signature" value="{{ $date_signature }}" required>
        <button class="btn btn-success" type="submit">Appliquer</button>
    </form>
    @endif
</nav>
<div class="bts-preview">
@foreach($PV as $releve)
<article class="bts-page">
    <header class="bts-header">
        <div class="bts-brand"><img src="{{ asset('img/releve_soa_azimut.png') }}" alt="Logo Azimut"><div><strong>INSTITUT SUPERIEUR</strong><br><span>AZIMUT</span></div></div>
        <div>
            <p>Arrêté de création n° 14/0027/MINESUP du 11 Mars 2014</p>
            <p>Autorisation d’ouverture n° 14/01316/MINESUP du 11 Mars 2014</p>
            <hr>
            <p>Authorization No14/06890/MINESUP 01<sup>st</sup> October 2014</p>
            <p>Tel: (+237)696 459 283/672 280 944 P.O box 4441</p>
        </div>
    </header>
    <h1>RELEVE DE NOTE ANNUEL</h1>
    <div class="bts-identity">
        <div>
            <p>Nom et Prénom &nbsp; : &nbsp; {{ $releve->eleve->nom }} {{ $releve->eleve->prenom }}</p>
            <p>Né(e) le &nbsp; : {{ $releve->eleve->date_naiss ? date('d-m-Y', strtotime($releve->eleve->date_naiss)) : '—' }} <span class="birthplace">Lieu De Naissance : {{ $releve->eleve->lieu_naiss }}</span></p>
            <p>Filière : {{ $niveau ? \gesecole\Services\BtsAnnualTranscript::displayText($niveau->nom) : '' }}</p>
            <p>Spécialité : {{ \gesecole\Services\BtsAnnualTranscript::displayText($classe->nom) }}</p>
        </div>
        <div>
            <p>Matricule : {{ $releve->eleve->matricule }}</p>
            <p>Sexe : {{ $releve->eleve->sexe }}</p>
            <p>Année Académique : {{ $annee }}</p>
            <p>Cycle : {{ $cycle->nom }} <span class="level">Niveau : {{ \gesecole\Services\BtsAnnualTranscript::levelLabel($nniveau->nom) }}</span></p>
        </div>
    </div>
    @foreach($releve->semestres as $numero => $semestre)
    <table class="bts-results">
        <colgroup><col style="width:8%"><col style="width:33%"><col style="width:7%"><col style="width:8%"><col style="width:9%"><col style="width:13%"><col style="width:15%"><col style="width:7%"></colgroup>
        <thead>
            <tr class="bts-grey"><th colspan="8">SEMESTRE {{ isset($semestre->numero) ? $semestre->numero : $numero }}</th></tr>
            <tr><th>Code UE</th><th>Intitulé des Enseignements</th><th>Crédits</th><th>Note/100</th><th>GRADE</th><th>GRADE POINT</th><th>NOTE PONDEREE</th><th>ANNEE</th></tr>
        </thead>
        <tbody>
        @foreach(array(0 => 'UE Fondamentales', 2 => 'UE Professionnelles', 1 => 'UE Transversales') as $type => $label)
            <?php $groupCredits = 0; foreach ($semestre->rows as $row) { if ((int) $row->type === $type) $groupCredits += $row->credit; } ?>
            <tr class="bts-group"><th colspan="2">{{ $label }}</th><th>{{ $groupCredits }}</th><td colspan="5"></td></tr>
            @foreach($semestre->rows as $row)
                @if((int) $row->type === $type)
                <tr>
                    <td class="bts-code">{{ $row->code }}</td><td class="bts-course">{{ \gesecole\Services\BtsAnnualTranscript::displayText($row->nom) }}</td>
                    <td>{{ $row->credit }}</td><td>{{ $row->note === null ? '—' : number_format($row->note, 2, '.', '') }}</td>
                    <td>{{ $row->grade }}</td><td>{{ $row->point === null ? '—' : number_format($row->point, 2, '.', '') }}</td>
                    <td>{{ $row->weighted === null ? '—' : number_format($row->weighted, 2, '.', '') }}</td>
                    <td>{{ $periode_fin ? date('Y', strtotime($periode_fin)) : substr($annee, -4) }}</td>
                </tr>
                @endif
            @endforeach
        @endforeach
            <tr class="bts-total"><th colspan="2">TOTAL</th><th>{{ $semestre->credits }}</th><td colspan="2"></td><th>{{ number_format($semestre->points, 2, '.', '') }}</th><th>{{ number_format($semestre->weighted, 2, '.', '') }}</th><td></td></tr>
        </tbody>
        @if($numero == 1)
        <tfoot><tr class="bts-grey"><td colspan="2"></td><td colspan="4">PREMIER SEMESTRE MGP :</td><th>{{ $semestre->mgp === null ? '—' : number_format($semestre->mgp, 2, '.', '') }}</th><td></td></tr></tfoot>
        @endif
    </table>
    @endforeach
    <?php $second = null; foreach ($releve->semestres as $position => $semesterValue) { if ((int) $position === 2) $second = $semesterValue; } ?>
    <div class="bts-bottom">
        <div class="bts-legend">
            <table>
                <thead><tr><th colspan="3">Système de notation</th></tr><tr><th>Grade</th><th>Marks</th><th>GP</th></tr></thead>
                <tbody>
                @foreach(array(array('A','80–100','4.0'),array('B+','70–79','3.5'),array('B','60–69','3.0'),array('C+','55–59','2.5'),array('C','50–54','2.0'),array('C-','45–49','1.5'),array('D+','40–44','1.0'),array('F','0–39','0.0')) as $g)
                    <tr><td>{{ $g[0] }}</td><td>{{ $g[1] }}</td><td>{{ $g[2] }}</td></tr>
                @endforeach
                </tbody>
            </table>
        </div>
        <div class="bts-classification">
            <strong>Classification du diplôme</strong><br>
            MOYENNE PONDÉRÉE / CLASSIFICATION DE DIPLÔME<br>
            3.60–4.00 : PREMIÈRE CLASSE<br>
            3.00–3.59 : DEUXIÈME CLASSE SUP.<br>
            2.50–2.99 : DEUXIÈME CLASSE INF.<br>
            2.00–2.49 : TROISIÈME CLASSE<br>
            0.00–1.99 : NON ADMIS<br>
            D : DÉLIVRÉ DISPENSATOIRE<br>
            F : DÉCISION INFÉRIEURE
        </div>
        <div class="bts-abbreviations">
            X : ABSENT<br>** : TITRE OBTENU APRÈS RATTRAPAGE<br>
            MGP : MOYENNE PONDÉRÉE<br>MGPC : MOYENNE GÉNÉRALE PONDÉRÉE CUMULÉE<br>
            GP : GRADE POINT<br>NOTE PONDÉRÉE : CRÉDITS × GP
        </div>
        <div class="bts-summary">
            <p>SECOND SEMESTRE MGP : &nbsp; {{ $second->mgp === null ? '—' : number_format($second->mgp, 2, '.', '') }}</p>
            <p>CRÉDITS TOTAL : &nbsp; {{ $releve->annuel->credits }}</p>
            <p class="bts-cumulative">MOYENNE GÉNÉRALE PONDÉRÉE CUMULATIVE : &nbsp; {{ $releve->annuel->mgp === null ? '—' : number_format($releve->annuel->mgp, 2, '.', '') }}</p>
            <p class="bts-diploma">CLASSE DE DIPLÔME :<br>{{ $releve->annuel->classification }}</p>
        </div>
    </div>
    <div class="bts-signature">Yaoundé. Le {{ $date_signature ? date('d/m/Y', strtotime($date_signature)) : '...........................' }}<p><strong>Le Directeur</strong></p></div>
    <footer class="bts-footer"><em>Yaoundé Cameroun/Nkolfoulou face GMI Police. Email : <a href="mailto:azimut@yahoo.com">azimut@yahoo.com</a></em><br><strong>« Le Choix des petits grands »</strong></footer>
</article>
@endforeach
</div>
</div>
@endsection

@section('breadcrumb')
    <li><strong><a href="{{ url('/eleves') }}">Gestion des notes</a></strong></li>
    <li><strong><a href="{{ url('/eleves/releves_bts/historique') }}">Relevés BTS</a></strong></li>
    <li class="active"><strong>Impression du relevé</strong></li>
@endsection
