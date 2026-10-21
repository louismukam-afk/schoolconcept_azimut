@extends('skeleton')
@section('sub-content')
<div class="anonymat-print">
<?php $documentTitle="FICHES DE PRESENCE AUX EXAMENS PAR MATIERE"; ?>@include('eleves.anonymats._print_header')
@forelse($sheets as $sheet)
<section class="anonymat-subject-sheet">
@include('eleves.anonymats._print_sheet_header')
<p><strong>Date de l'examen :</strong> .......... / .......... / ....................</p>
<table><thead><tr><th style="width:55px">#</th><th>Nom et prenom de l'etudiant</th><th style="width:310px">Signature</th></tr></thead><tbody>
@foreach($sheet->rows as $index=>$row)<tr><td>{{$index+1}}</td><td><strong>{{$row->nom}} {{$row->prenom}}</strong></td><td style="height:32px"></td></tr>@endforeach
</tbody></table>
<table style="margin-top:18px"><tbody><tr><th>Total des presents</th><td style="height:32px"></td><th>Total des absents</th><td></td><th>Effectif total</th><td>{{count($sheet->rows)}}</td></tr></tbody></table>
<table style="margin-top:28px"><tbody><tr><th style="width:50%;height:75px;vertical-align:top">Signature du surveillant de salle</th><th style="width:50%;vertical-align:top">Signature du responsable des affaires academiques</th></tr></tbody></table>
</section>
@empty<div class="alert alert-warning">Aucune matiere ou aucun anonymat disponible pour cette selection.</div>@endforelse
</div>
@endsection
