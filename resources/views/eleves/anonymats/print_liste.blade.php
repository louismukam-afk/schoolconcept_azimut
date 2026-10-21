@extends('skeleton')
@section('sub-content')
<div class="anonymat-print">
<?php $documentTitle="LISTES DE CORRESPONDANCE PAR MATIERE"; ?>@include('eleves.anonymats._print_header')
@forelse($sheets as $sheet)
<section class="anonymat-subject-sheet">
@include('eleves.anonymats._print_sheet_header')
<table><thead><tr><th>#</th><th>Numero d'anonymat</th><th>Matricule</th><th>Nom et prenom</th></tr></thead><tbody>
@foreach($sheet->rows as $index=>$row)<tr><td>{{$index+1}}</td><td><strong>{{$row->code}}</strong></td><td>{{$row->matricule}}</td><td>{{$row->nom}} {{$row->prenom}}</td></tr>@endforeach
</tbody></table><p><strong>Total : {{count($sheet->rows)}}</strong></p>
</section>
@empty<div class="alert alert-warning">Aucune matiere ou aucun anonymat disponible pour cette selection.</div>@endforelse
</div>
@endsection
