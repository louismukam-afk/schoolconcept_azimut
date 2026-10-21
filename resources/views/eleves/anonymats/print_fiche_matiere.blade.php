@extends('skeleton')
@section('sub-content')
<div class="anonymat-print">
<?php $documentTitle="FICHE DE REPORT DE NOTES"; ?>@include('eleves.anonymats._print_header')
<section class="anonymat-subject-sheet">
@include('eleves.anonymats._print_sheet_header')
<p><strong>Enseignant :</strong> ...................................................................................................</p>
<table><thead><tr><th style="width:55px">#</th><th>Numero d'anonymat</th><th style="width:130px">Note /20</th><th style="width:220px">Observation</th></tr></thead><tbody>
@foreach($rows as $index=>$row)<tr><td>{{$index+1}}</td><td><strong>{{$row->code}}</strong></td><td style="height:28px"></td><td></td></tr>@endforeach
</tbody></table><p><strong>Total : {{count($rows)}}</strong></p>
</section>
</div>
@endsection
