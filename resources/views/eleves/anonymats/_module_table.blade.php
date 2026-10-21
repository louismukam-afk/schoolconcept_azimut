<section class="module-sheet">
    <h4 class="module-title">Module : {{$module->code?$module->code.' - ':''}}{{$module->nom}}</h4>
    <div class="table-responsive">
        <table class="table table-bordered table-condensed module-table">
            <thead>
            <tr>
                <th rowspan="2">#</th>
                <th rowspan="2">Matricule</th>
                <th rowspan="2">Nom et prenom</th>
                @foreach($module->uvs as $uv)
                    <th colspan="{{!empty($uv->has_tpe_session)?5:4}}">{{$uv->code_matiere}}</th>
                @endforeach
                <th rowspan="2">Moyenne<br>/20</th>
                <th rowspan="2">MGP<br>/4</th>
                <th rowspan="2">Decision</th>
            </tr>
            <tr>
                @foreach($module->uvs as $uv)
                    <th>Anonymat</th><th>CC</th><th>{{$examColumnLabel}}</th>@if(!empty($uv->has_tpe_session))<th>TPE</th>@endif<th>Total /20</th>
                @endforeach
            </tr>
            </thead>
            <tbody>
            @foreach($module->rows as $index=>$row)
            <tr>
                <td class="grade-cell">{{$index+1}}</td>
                <td class="student-number">{{$row->student->matricule}}</td>
                <td class="student-name">{{$row->student->nom}} {{$row->student->prenom}}</td>
                @foreach($module->uvs as $uv)
                    <?php $result=$row->uvs[$uv->id_classe_matiere]; ?>
                    <td class="anonymat-cell">{{$result->anonymat?:'-'}}</td>
                    <td class="grade-cell">{{is_null($result->cc)?'-':number_format($result->cc,2,',','')}}</td>
                    <td class="grade-cell">{{is_null($result->exam)?'-':number_format($result->exam,2,',','')}}</td>
                    @if(!empty($uv->has_tpe_session))
                        <td class="grade-cell">{{is_null($result->tpe)?'-':number_format($result->tpe,2,',','')}}</td>
                    @endif
                    <td class="grade-cell"><strong>{{is_null($result->total)?'-':number_format($result->total,2,',','')}}</strong></td>
                @endforeach
                <td class="grade-cell"><strong>{{is_null($row->moyenne)?'-':number_format($row->moyenne,2,',','')}}</strong></td>
                <td class="grade-cell">{{is_null($row->mgp)?'-':number_format($row->mgp,2,',','')}}</td>
                <td class="grade-cell {{$row->decision==='V'?'decision-v':'decision-nv'}}">{{$row->decision}}</td>
            </tr>
            @endforeach
            </tbody>
        </table>
    </div>
    <div class="module-legend">
        <strong>Legende :</strong>
        @foreach($module->uvs as $uv)<span><strong>{{$uv->code_matiere}}</strong> : {{$uv->matiere_nom}}</span>@endforeach
        <span><strong>V</strong> : Valide</span><span><strong>NV</strong> : Non valide</span>
    </div>
</section>
