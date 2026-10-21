<div class="anonymat-sheet-header">
@include('perso.header')
<?php $headerMatiere=isset($sheet)?$sheet->matiere:(isset($selectedMatiere)?$selectedMatiere:null); ?>
<div style="text-align:center;margin:18px 0 12px">
    <h3 style="margin:0">{{$documentTitle}}</h3>
    @if($headerMatiere)<h4 style="margin:7px 0 3px">Matiere : {{$headerMatiere->nom}}</h4>@endif
    <p style="margin:5px 0">{{$examLabel}} | {{isset($selection)?$selection['semestre']:$anonymat->semestre}} | {{session('year')}}</p>
    @if(isset($campus) && $campus)<p style="margin:0">Campus : {{$campus->nom}}</p>@endif
</div>
<table class="anonymat-context" style="margin-bottom:14px"><tbody>
<tr><th>Cycle</th><td>{{isset($cycle) && $cycle?$cycle->nom:''}}</td><th>Niveau</th><td>{{isset($nniveau) && $nniveau?$nniveau->nom:''}}</td></tr>
<tr><th>Filiere</th><td>{{isset($filiere) && $filiere?$filiere->nom:''}}</td><th>Specialite</th><td>{{$classe?$classe->nom:''}}</td></tr>
</tbody></table>
</div>
