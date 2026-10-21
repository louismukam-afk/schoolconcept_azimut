@extends('skeleton')
@section('sub-content')
<?php $titre = 'Annual Transcript HND — '.$annee; ?>
<link rel="stylesheet" href="{{ asset('css/releve_bts_annuel.css') }}?v=20261020-4">
<link rel="stylesheet" href="{{ asset('css/releve_hnd.css') }}?v=20261021-2">
<div class="bts-report">
    <nav class="bts-toolbar">
        <a class="btn btn-default" href="{{ url('/eleves/releves_hnd_annuel') }}"><span class="glyphicon glyphicon-plus"></span> Nouveau relevé HND</a>
        <a class="btn btn-warning" href="{{ url('/eleves/releves_hnd/historique') }}">Historique HND</a>
        <button type="button" class="btn btn-primary" onclick="window.print()"><span class="glyphicon glyphicon-print"></span> Imprimer / PDF (A4)</button>
        <span>Période : {{ date('d/m/Y', strtotime($periode_debut)) }} au {{ date('d/m/Y', strtotime($periode_fin)) }}</span>
        @if(isset($generation))
        <form method="GET" class="bts-signature-form">
            <label for="hnd_signature">Date de signature</label>
            <input class="form-control" id="hnd_signature" name="date_signature" type="date" value="{{ $date_signature }}" required>
            <button type="submit" class="btn btn-success">Appliquer</button>
        </form>
        @endif
    </nav>
    <div class="bts-preview">
    @foreach($PV as $releve)
    <article class="bts-page hnd-page">
        <header class="bts-header">
            <div class="bts-brand"><div class="hnd-brand-panel"><img src="{{ asset('img/releve_soa_azimut.png') }}" alt="Azimut"><strong><span>AZIMUT</span> HIGHER<br>INSTITUTE</strong></div></div>
            <div>
                <p>Arrêté de création n° 14/0027/MINESUP du 11 Mars 2014</p>
                <p>Autorisation d’ouverture n° 14/01316/MINESUP du 11 Mars 2014</p>
                <hr>
                <p>Authorization No14/06890/MINESUP 01<sup>st</sup> October 2014</p>
                <p>Tel: (+237)696 459 283/672 280 944 P.O box 4441</p>
            </div>
        </header>
        <h1>ANNUAL TRANSCRIPT</h1>
        <div class="bts-identity">
            <div>
                <p>Student’s Name &nbsp; : {{ $releve->eleve->nom }} {{ $releve->eleve->prenom }}</p>
                <p>Date of Birth &nbsp; : {{ $releve->eleve->date_naiss ? date('d-m-Y', strtotime($releve->eleve->date_naiss)) : '—' }} <span class="birthplace">Place of Birth : {{ $releve->eleve->lieu_naiss }}</span></p>
                <p>Field of study &nbsp; : {{ $niveau ? \gesecole\Services\BtsAnnualTranscript::displayText(!empty($niveau->nom_anglais) ? $niveau->nom_anglais : $niveau->nom) : '' }}</p>
                <p>Department &nbsp; : {{ \gesecole\Services\BtsAnnualTranscript::displayText(!empty($classe->nom_anglais) ? $classe->nom_anglais : $classe->nom) }}</p>
            </div>
            <div>
                <p>Registration Number: {{ $releve->eleve->matricule }}</p>
                <p>Sex &nbsp; : {{ $releve->eleve->sexe }}</p>
                <p>Academic Year &nbsp; : {{ $annee }}</p>
                <p>Cycle: HND <span class="level">Level: {{ \gesecole\Services\BtsAnnualTranscript::levelLabel($nniveau->nom) }}</span></p>
            </div>
        </div>
        @foreach($releve->semestres as $position => $semester)
        <table class="bts-results">
            <colgroup><col style="width:8%"><col style="width:39%"><col style="width:8%"><col style="width:8%"><col style="width:8%"><col style="width:10%"><col style="width:11%"><col style="width:8%"></colgroup>
            <thead>
                <tr class="bts-grey"><th colspan="8">@if($position == 1 && (!isset($semester->numero) || $semester->numero == 1))1<sup>st</sup> SEMESTER @else SEMESTER {{ isset($semester->numero) ? $semester->numero : $position }} @endif</th></tr>
                <tr><th>CODE</th><th>TITLE</th><th>CREDIT</th><th>MARK</th><th>GRADE</th><th>GRADE<br>POINT</th><th>WEIGHTED<br>POINT</th><th>YEAR</th></tr>
            </thead>
            <tbody>
            @foreach(array(0=>'Fundamental Courses', 2=>'Professional Courses', 1=>'Common Courses') as $type => $label)
                <?php $groupCredits = 0; foreach ($semester->rows as $row) { if ((int) $row->type === $type) $groupCredits += $row->credit; } ?>
                <tr class="bts-group"><td></td><th>{{ $label }}</th><th>{{ $groupCredits }}</th><td colspan="5"></td></tr>
                @foreach($semester->rows as $row)
                    @if((int) $row->type === $type)
                    <tr><td class="bts-code">{{ $row->code }}</td><td class="bts-course">{{ \gesecole\Services\BtsAnnualTranscript::displayText($row->nom) }}</td>
                        <td>{{ $row->credit }}</td><td>{{ $row->note === null ? '—' : number_format($row->note, 2, '.', '') }}</td>
                        <td>{{ $row->grade }}</td><td>{{ $row->point === null ? '—' : number_format($row->point, 1, '.', '') }}</td>
                        <td>{{ $row->weighted === null ? '—' : number_format($row->weighted, 1, '.', '') }}</td><td>{{ date('Y', strtotime($periode_fin)) }}</td></tr>
                    @endif
                @endforeach
            @endforeach
                <tr class="bts-total"><th colspan="2">TOTAL</th><th>{{ $semester->credits }}</th><td colspan="2"></td><th>{{ number_format($semester->points, 1, '.', '') }}</th><th>{{ number_format($semester->weighted, 1, '.', '') }}</th><td></td></tr>
            </tbody>
            @if($position == 1)
            <tfoot><tr><td colspan="3"></td><th colspan="3">FIRST SEMESTER GPA :</th><th colspan="2">{{ $semester->mgp === null ? '—' : number_format($semester->mgp, 2, '.', '') }}</th></tr></tfoot>
            @endif
        </table>
        @endforeach
        <?php $second = null; foreach ($releve->semestres as $key=>$semester) { if ((int) $key === 2) $second = $semester; } ?>
        <div class="bts-bottom">
            <div class="bts-legend">
                <table><thead><tr><th colspan="3">Grade System</th></tr><tr><th>Grade</th><th>Mark</th><th>GP</th></tr></thead><tbody>
                    @foreach(array(array('A','80–100','4.0'),array('B+','70–79','3.5'),array('B','60–69','3.0'),array('C+','55–59','2.5'),array('C','50–54','2.0'),array('D+','45–49','1.5'),array('D','40–44','1.0'),array('F','0–39','0.0')) as $grade)
                    <tr><td>{{ $grade[0] }}</td><td>{{ $grade[1] }}</td><td>{{ $grade[2] }}</td></tr>
                    @endforeach
                </tbody></table>
            </div>
            <div class="bts-classification"><strong>Degree Classification</strong>
                GPA / CLASS OF DEGREE<br>3.60–4.00 / FIRST CLASS<br>3.00–3.59 / SECOND CLASS-UD<br>2.50–2.99 / SECOND CLASS-LD<br>2.25–2.49 / THIRD CLASS<br>2.00–2.24 / PASS<br>UD (UPPER DIVISION);<br>LD (LOWER DIVISION)
            </div>
            <div class="bts-abbreviations">X=ABSENT<br>**=MARK OBTAINED AFTER RESIT<br>GP=GRADE POINT<br>GPA=GRADE POINT AVERAGE<br>CGPA=CUMULATIVE GRADE POINT AVERAGE<br>WP=WEIGHTED POINT</div>
            <div class="bts-summary hnd-summary">
                <p class="hnd-semester-gpa"><span>SECOND SEMESTER GPA :</span><strong>{{ !$second || $second->mgp === null ? '—' : number_format($second->mgp, 2, '.', '') }}</strong></p>
                <div class="hnd-cumulative">Overall Credits Earned : &nbsp; <strong>{{ $releve->annuel->validated }}</strong><br>Cumulative GPA : &nbsp; <strong>{{ $releve->annuel->mgp === null ? '—' : number_format($releve->annuel->mgp, 2, '.', '') }}</strong></div>
                <p class="bts-diploma">Class of Degree :<br><strong>{{ $releve->annuel->classification }}</strong></p>
            </div>
        </div>
        <div class="bts-signature">Yaounde. The {{ $date_signature ? date('d/m/Y', strtotime($date_signature)) : '...........................' }}<p><strong>The Director</strong></p></div>
        <footer class="bts-footer"><em>Yaoundé Cameroun / Nkolfoulou face GMI Police</em><br><strong>« Le Choix des petits grands »</strong></footer>
    </article>
    @endforeach
    </div>
</div>
@endsection
@section('breadcrumb')
<li><a href="{{ url('/eleves') }}">Gestion des notes</a></li><li><a href="{{ url('/eleves/releves_hnd/historique') }}">Relevés HND</a></li><li class="active">Annual Transcript</li>
@endsection
