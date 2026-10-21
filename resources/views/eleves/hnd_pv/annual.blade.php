<!doctype html>
<html lang="en">
<head><meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1"><title>HND — {{ $pv['exam']['title'] }} — {{ $annee }}</title><link rel="stylesheet" href="{{ asset('css/hnd_pv.css') }}?v=20261021-10">
    <style>
        @page { size: {{ $layout['paper'] }} landscape; margin: 0; }
        .hnd-sheet { --paper-width: {{ $layout['width'] }}mm; --note-font: {{ $layout['font'] }}px; --name-font: {{ $layout['name_font'] }}px; --legend-font: {{ $layout['legend_font'] }}px; --code-font: {{ $layout['code_font'] }}px; --registration-width: {{ $layout['registration_width'] }}px; --name-width: {{ $layout['name_width'] }}px; --summary-width: {{ $layout['summary_width'] }}px; }
    </style>
</head>
<body>
    <nav class="hnd-toolbar">
        <a href="{{ url('/eleves/pv_hnd/sessions').'?'.http_build_query(array('id'=>$classe->id, 'cycle'=>$cycle->id, 'nniveau'=>$nniveau->id)) }}">Retour aux examens</a>
        <a href="{{ url('/eleves/pv_hnd') }}">Nouvelle sélection</a>
        <button type="button" onclick="window.print();">Imprimer / Enregistrer en PDF</button>
        <label for="pv-paper">Mise en page <select id="pv-paper" onchange="var target = new URL(window.location.href); target.searchParams.set('papier', this.value); window.location.href = target.toString();">
            @foreach($papers as $key => $label)<option value="{{ $key }}" {{ $papier == $key ? 'selected' : '' }}>{{ $label }}</option>@endforeach
        </select></label>
        <span>{{ count($pv['rows']) }} étudiant(s) — {{ !empty($pv['annual']) ? 'procès-verbal annuel' : 'procès-verbal synthétique' }}</span>
    </nav>
    <?php $english = function ($object) { return \gesecole\Services\BtsAnnualTranscript::displayText(!empty($object->nom_anglais) ? $object->nom_anglais : $object->nom); }; $mark = function ($value) { return $value === null ? '—' : rtrim(rtrim(number_format($value, 2, '.', ''), '0'), '.'); }; ?>
    <?php
        $campusName = isset($campus) && trim((string)$campus->nom) !== '' ? $campus->nom : 'Non renseigné';
        $tutelleName = isset($tutelle) ? (!empty($tutelle->tutelle_anglophone) ? $tutelle->tutelle_anglophone : $tutelle->nom) : 'THE UNIVERSITY OF BAMENDA';
    ?>
    <main class="hnd-sheet hnd-annual-sheet">
        <header class="hnd-header">
            <img src="{{ asset('img/azimut.png') }}" alt="Azimut Higher Institute">
            <div><strong>AZIMUT HIGHER INSTITUTE OF<br>COMMERCE, TECHNOLOGY AND HEALTH</strong><br>Authorization:<br>N° 14/0027/MINESUP of 11<sup>th</sup> March 2014<br>N° 14/06890/MINESUP of 01<sup>st</sup> October 2014<br>Tel: (+237)696 459 283/672 280 944 P.O box 4441</div>
            <img src="{{ asset('img/bamenda.webp') }}" alt="The University of Bamenda">
        </header>
        <div class="hnd-affiliations"><span><strong>CAMPUS:</strong> {{ $campusName }}</span><span><strong>ACADEMIC SUPERVISION:</strong> {{ $tutelleName }}</span></div>
        <h1>{{ $pv['exam']['title'] }} &nbsp; {{ $annee }}</h1>
        <div class="hnd-meta"><strong>CYCLE: {{ $english($cycle) }}</strong><strong>SPECIALITY: {{ $english($classe) }}</strong><strong>LEVEL: {{ $nniveau->nom }}</strong></div>
        <p class="hnd-period">FIELD OF STUDY: {{ $filiere ? $english($filiere) : '—' }} @if($date_debut || $date_fin) · EXAM MARK PERIOD: {{ $date_debut ?: 'Start of academic year' }} — {{ $date_fin ?: 'End of academic year' }} @endif</p>
        @if(count($pv['subjects']) && count($pv['rows']))
            <?php
                $isAnnual = !empty($pv['annual']);
                $courseCodes = array(); $semesterCounts = array(1 => 0, 2 => 0);
                foreach ($pv['subjects'] as $position => $subject) {
                    $courseCodes[$position] = trim((string)$subject->code) !== '' ? $subject->code : 'UV-'.$subject->id_classe_matiere;
                    $semesterCounts[$isAnnual ? $subject->annual_semester : $pv['exam']['semester']]++;
                }
            ?>
            @if($isAnnual && !$pv['complete_year'])<p class="hnd-empty">Annual results are incomplete: no recorded courses were found for one of the two semesters. No annual average or admission is awarded.</p>@endif
            <table class="hnd-course-legend">
                <caption>{{ $isAnnual ? 'ANNUAL COURSE LEGEND — S1: SEMESTER 1 / S2: SEMESTER 2' : 'COURSE LEGEND — SEMESTER '.$pv['exam']['semester'] }}</caption>
                <colgroup>@for($compartment = 0; $compartment < 3; $compartment++)<col class="legend-code"><col><col class="legend-credit">@endfor</colgroup>
                <thead><tr>@for($compartment = 0; $compartment < 3; $compartment++)<th>CODE</th><th>COURSE TITLE</th><th>CR.</th>@endfor</tr></thead>
                <tbody>
                @foreach(array_chunk(array_keys($pv['subjects']), 3) as $positions)
                    <tr>
                        @foreach($positions as $position)
                            <?php $subject = $pv['subjects'][$position]; ?>
                            <td><strong>{{ $courseCodes[$position] }}</strong></td><td>S{{ $isAnnual ? $subject->annual_semester : $pv['exam']['semester'] }} — {{ $english($subject) }}</td><td class="legend-credit">{{ $subject->credit }}</td>
                        @endforeach
                        @for($empty = count($positions); $empty < 3; $empty++)<td></td><td></td><td></td>@endfor
                    </tr>
                @endforeach
                </tbody>
            </table>
            <div class="hnd-grid-scroll"><table class="hnd-grid hnd-annual-grid">
                <thead>
                    <tr><th rowspan="2" class="serial">S/N</th><th rowspan="2" class="registration">MATRICULE</th><th rowspan="2" class="student-name">NAMES</th>
                        @foreach($semesterCounts as $semester => $count)
                            @if($count)<th colspan="{{ $count }}">SEMESTER {{ $semester }} — MARKS /100</th>@endif
                        @endforeach
                        <th rowspan="2" class="average-total">{{ $isAnnual ? 'ANNUAL' : 'SEMESTER' }}<br>AVG /20</th><th rowspan="2" class="credit-total">{{ $isAnnual ? 'ANNUAL' : 'SEMESTER' }}<br>CREDITS /{{ $pv['credits'] }}</th><th rowspan="2" class="rank">RANK</th><th rowspan="2" class="decision">DECISION</th>
                    </tr>
                    <tr>@foreach($courseCodes as $code)<th class="course-code">{{ $code }}</th>@endforeach</tr>
                </thead>
                <tbody>
                @foreach($pv['rows'] as $index => $row)
                    <tr><td>{{ $index + 1 }}</td><td>{{ $row['student']->matricule }}</td><th class="student-name">{{ $row['student']->nom }} {{ $row['student']->prenom }}</th>
                        @foreach($row['marks'] as $value)<td class="{{ \gesecole\Services\HndPv::markClass($value['retained']) }}">{{ $mark($value['retained']) }}</td>@endforeach
                        <td><strong>{{ $row['average20'] === null ? '' : number_format($row['average20'], 2, '.', '') }}</strong></td><td><strong>{{ $row['credits'] }}</strong></td><td><strong>{{ $row['rank'] === null ? '—' : $row['rank'] }}</strong></td><td><strong>{{ $isAnnual ? $row['decision'] : ($row['decision'] === 'V' ? 'ADMIS' : 'ECHOUE') }}</strong></td>
                    </tr>
                @endforeach
                </tbody>
            </table></div>
            <p class="hnd-legend hnd-color-key">MARKS /100: <span class="mark-green">≥ 50</span> <span class="mark-yellow">40–49.99</span> <span class="mark-red">&lt; 40</span></p>
            @if($isAnnual)
                <p class="hnd-legend">Each course retains the higher normal/resit result, including continuous assessment and practical work. Annual average: weighted by course credits across both semesters, divided by 5 for /20; displayed only after all courses are validated. Annual credits: sum of course credits validated at ≥ 50/100. ADMIS: all courses validated. ECHOUE: at least one course failed or missing. Missing course marks prevent calculation of the annual average and rank. Ties share the same rank.</p>
            @else
                <p class="hnd-legend">Final course marks /100 include continuous assessment and practical work. For resits, the higher normal/resit result is retained. Average /20: credit-weighted semester average, displayed only after all course credits are validated. ADMIS: all course credits validated. ECHOUE: credits not fully validated. Incomplete marks prevent ranking; ties share a rank.</p>
            @endif
        @else
            <p class="hnd-empty">No results available for the selected examination, class and period.</p>
        @endif
    </main>
</body></html>
