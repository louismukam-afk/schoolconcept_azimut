<table @if($screenTable) id="note-excel" @endif class="table table-bordered table-striped table-condensed soa-pv-results">
    <thead>
        <tr>
            <th rowspan="2">N°</th><th rowspan="2">Matricule</th><th class="soa-pv-name" rowspan="2">Nom et prénoms</th>
            @foreach($tableSubjects as $subject)<th colspan="3">{{ $subject->nom }}</th>@endforeach
            <th rowspan="2">Moyenne générale /20</th><th rowspan="2">MGP</th><th rowspan="2">Total crédits validés</th><th rowspan="2">Décision d’admission</th><th rowspan="2">Rang</th>
        </tr>
        <tr>@foreach($tableSubjects as $subject)<th>CC</th><th>SN</th><th>Total /100</th>@endforeach</tr>
    </thead>
    <tbody>
    <?php $rowNumber = 0; ?>
    @foreach($eleves as $student)
        <?php $rowNumber++; $summary = isset($soaSummaries[$student->id]) ? $soaSummaries[$student->id] : null; ?>
        <tr data-student-id="{{ $student->id }}">
            <td>{{ $rowNumber }}</td><td>{{ $student->matricule }}</td><td class="soa-pv-name">{{ $student->nom }} {{ $student->prenom }}</td>
            @foreach($tableSubjects as $subject)
                <?php $mark = $summary && isset($summary->subjects[$subject->id]) ? $summary->subjects[$subject->id] : null; ?>
                <td>{{ $mark ? $mark->controle : '—' }}</td><td>{{ $mark ? $mark->normal : '—' }}</td><td>{{ $mark ? $mark->note : '—' }}</td>
            @endforeach
            <td class="soa-pv-summary">{{ $summary && $summary->average !== null ? number_format($summary->average, 2, '.', '') : '—' }}</td>
            <td>{{ $summary && $summary->mgp !== null ? number_format($summary->mgp, 2, '.', '') : '—' }}</td>
            <td class="soa-pv-summary">{{ $summary ? $summary->validated : 0 }}</td>
            <td class="soa-pv-summary">{{ !$summary || $summary->average === null ? 'Non évalué' : ($summary->admitted ? trans('admin.admis') : trans('admin.echec')) }}</td>
            <td class="soa-pv-summary">{{ $summary && $summary->rank !== null ? $summary->rank : '—' }}</td>
        </tr>
    @endforeach
    </tbody>
    <tfoot>
        <tr><th colspan="3">% validation</th>
        @foreach($tableSubjects as $subject)
            <?php $totals = array(0, 0, 0); $counts = array(0, 0, 0);
                foreach ($soaSummaries as $summary) {
                    if (!isset($summary->subjects[$subject->id])) continue;
                    $mark = $summary->subjects[$subject->id];
                    foreach (array('controle'=>15, 'normal'=>35, 'note'=>50) as $field=>$threshold) {
                        $index = $field === 'controle' ? 0 : ($field === 'normal' ? 1 : 2);
                        if (!is_numeric($mark->$field)) continue;
                        $counts[$index]++; if ($mark->$field >= $threshold) $totals[$index]++;
                    }
                }
            ?>
            @foreach(array(0, 1, 2) as $index)<td>{{ $counts[$index] ? round(100 * $totals[$index] / $counts[$index], 2).' %' : '—' }}</td>@endforeach
        @endforeach
        <td colspan="5"></td></tr>
    </tfoot>
</table>
