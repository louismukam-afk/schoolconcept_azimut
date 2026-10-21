<!doctype html>
<html lang="en">
<head><meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1"><title>HND — {{ $pv['exam']['title'] }} — {{ $annee }}</title><link rel="stylesheet" href="{{ asset('css/hnd_pv.css') }}?v=20261021-9">
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
        <span>{{ count($pv['rows']) }} étudiant(s) — modèle CA / EXAM</span>
    </nav>
    <?php $english = function ($object) { return \gesecole\Services\BtsAnnualTranscript::displayText(!empty($object->nom_anglais) ? $object->nom_anglais : $object->nom); }; $mark = function ($value) { return $value === null ? '—' : rtrim(rtrim(number_format($value, 2, '.', ''), '0'), '.'); }; ?>
    <?php
        $campusName = isset($campus) && trim((string)$campus->nom) !== '' ? $campus->nom : 'Non renseigné';
        $tutelleName = isset($tutelle) ? (!empty($tutelle->tutelle_anglophone) ? $tutelle->tutelle_anglophone : $tutelle->nom) : 'THE UNIVERSITY OF BAMENDA';
    ?>
    <main class="hnd-sheet">
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
            <?php $courseCodes = array(); foreach ($pv['subjects'] as $subject) { $courseCodes[$subject->id_classe_matiere] = trim((string)$subject->code) !== '' ? $subject->code : 'UV-'.$subject->id_classe_matiere; } ?>
            <table class="hnd-course-legend">
                <caption>COURSE LEGEND</caption>
                <colgroup>@for($compartment = 0; $compartment < 3; $compartment++)<col class="legend-code"><col><col class="legend-credit">@endfor</colgroup>
                <thead><tr>@for($compartment = 0; $compartment < 3; $compartment++)<th>CODE</th><th>COURSE TITLE</th><th>CR.</th>@endfor</tr></thead>
                <tbody>
                @foreach(array_chunk($pv['subjects'], 3) as $pair)
                    <tr>
                        @foreach($pair as $subject)
                            <td><strong>{{ $courseCodes[$subject->id_classe_matiere] }}</strong></td><td>{{ $english($subject) }}</td><td class="legend-credit">{{ $subject->credit }}</td>
                        @endforeach
                        @for($empty = count($pair); $empty < 3; $empty++)<td></td><td></td><td></td>@endfor
                    </tr>
                @endforeach
                </tbody>
            </table>
            <?php $blockSize = $layout['subjects_per_block']; $subjectBlocks = array_chunk($pv['subjects'], $blockSize); ?>
            @foreach($subjectBlocks as $blockIndex => $blockSubjects)
            <section class="hnd-marks-block">
            @if(count($subjectBlocks) > 1)
                <h2 class="hnd-block-caption">{{ $pv['exam']['title'] }} — {{ $annee }} — {{ $english($classe) }} — LEVEL {{ $nniveau->nom }}</h2>
                <p class="hnd-block-note">COURSES {{ $blockIndex * $blockSize + 1 }}–{{ $blockIndex * $blockSize + count($blockSubjects) }} / {{ count($pv['subjects']) }} — BLOCK {{ $blockIndex + 1 }} / {{ count($subjectBlocks) }}. Credit totals and decisions cover all courses.</p>
                <p class="hnd-block-note">CAMPUS: {{ $campusName }} · ACADEMIC SUPERVISION: {{ $tutelleName }}</p>
            @endif
            <div class="hnd-grid-scroll"><table class="hnd-grid">
                <thead>
                    <tr><th rowspan="2" class="serial">S/N</th><th rowspan="2" class="registration">MATRICULE</th><th rowspan="2" class="student-name">NAMES</th><th rowspan="2" class="phase-heading">PART</th>
                        @foreach($blockSubjects as $subject)<th colspan="4" class="course-code">{{ $courseCodes[$subject->id_classe_matiere] }}</th>@endforeach
                        <th rowspan="2" class="credit-total">CREDIT<br>TOTAL /{{ $pv['credits'] }}</th><th rowspan="2" class="average-total">AVERAGE<br>/20</th><th rowspan="2" class="rank">RANK</th><th rowspan="2" class="decision">DECISION</th>
                    </tr>
                    <tr>@foreach($blockSubjects as $subject)<th>CA /<br>EXAM</th><th>Mark<br>/100</th><th>Mark UE<br>/100</th><th>Credit</th>@endforeach</tr>
                </thead>
                @foreach($pv['rows'] as $index => $row)
                <?php $blockMarks = array_slice($row['marks'], $blockIndex * $blockSize, count($blockSubjects)); ?>
                <tbody class="hnd-student">
                    <tr><td rowspan="2">{{ $index + 1 }}</td><td rowspan="2">{{ $row['student']->matricule }}</td><th rowspan="2" class="student-name">{{ $row['student']->nom }} {{ $row['student']->prenom }}</th><td class="phase">CA</td>
                        @foreach($blockMarks as $value)<td>{{ $mark($value['ca']) }}</td><td rowspan="2" class="{{ \gesecole\Services\HndPv::markClass($value['total']) }}">{{ $mark($value['total']) }}</td><td rowspan="2" class="{{ \gesecole\Services\HndPv::markClass($value['retained']) }}">{{ $mark($value['retained']) }}</td><td rowspan="2">{{ $value['credit'] }}</td>@endforeach
                        <td rowspan="2"><strong>{{ $row['credits'] }}</strong></td><td rowspan="2"><strong>{{ $row['average20'] === null ? '' : number_format($row['average20'], 2, '.', '') }}</strong></td><td rowspan="2"><strong>{{ $row['rank'] === null ? '—' : $row['rank'] }}</strong></td><td rowspan="2"><strong>{{ $row['decision'] }}</strong></td>
                    </tr>
                    <tr><td class="phase">EXAM</td>@foreach($blockMarks as $value)<td>{{ $mark($value['exam']) }}</td>@endforeach</tr>
                </tbody>
                @endforeach
            </table></div>
            </section>
            @endforeach
            <p class="hnd-legend hnd-color-key">MARKS /100: <span class="mark-green">≥ 50</span> <span class="mark-yellow">40–49.99</span> <span class="mark-red">&lt; 40</span></p>
            <p class="hnd-legend">CA: Continuous Assessment (including practical work where configured). EXAM: selected examination contribution. Mark/100: CA + EXAM. Mark UE/100: retained mark (best normal / resit result for resits). V: all credits validated. NV: credits not fully validated. —: no mark recorded.</p>
            <p class="hnd-legend">AVERAGE /20: credit-weighted average displayed only when all course credits are validated. RANK: descending weighted average among students with complete marks; equal averages share a rank. Students with incomplete marks are not ranked.</p>
        @else
            <p class="hnd-empty">No students or courses available for the selected examination and period.</p>
        @endif
    </main>
</body></html>
