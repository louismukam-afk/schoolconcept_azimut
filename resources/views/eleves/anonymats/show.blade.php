@extends('skeleton')
@section('sub-content')
<div class="page-header"><h3>Liste de controle des anonymats</h3></div>
@if(session('success'))<div class="alert alert-success">{{session('success')}}</div>@endif
<p><strong>Cycle :</strong> {{$cycle?$cycle->nom:''}} | <strong>Niveau :</strong> {{$nniveau?$nniveau->nom:''}} | <strong>Filiere :</strong> {{$filiere?$filiere->nom:''}} | <strong>Specialite :</strong> {{$classe?$classe->nom:''}}</p>
<p>{{$examLabel}} | {{$selection['semestre']}} | {{session('year')}}</p>
<?php $q=http_build_query($selection); ?>
<p><a class="btn btn-default" href="{{route('notes_anonymats')}}"><i class="fa fa-arrow-left"></i> Retour</a> <a class="btn btn-primary" href="{{route('notes_anonymats_pdf_fiches')}}?{{$q}}"><i class="fa fa-download"></i> PDF global des fiches de report</a> <a class="btn btn-info" href="{{route('notes_anonymats_pdf_listes')}}?{{$q}}"><i class="fa fa-download"></i> PDF global des listes d'anonymats</a> <a class="btn btn-warning" href="{{route('notes_anonymats_pdf_presences')}}?{{$q}}"><i class="fa fa-download"></i> PDF global des fiches de presence</a> <a class="btn btn-success" href="{{route('notes_anonymats_saisie')}}?{{$q}}"><i class="fa fa-pencil"></i> Saisie par anonymat</a> <a class="btn btn-warning" href="{{route('notes_anonymats_cc_tpe')}}?{{$q}}"><i class="fa fa-pencil-square-o"></i> Saisie CC / TPE</a> <a class="btn btn-default" href="{{route('notes_anonymats_modules')}}?{{$q}}"><i class="fa fa-table"></i> Notes par modules</a></p>
<h4>Documents par matiere</h4>
<div class="table-responsive"><table class="table table-bordered"><thead><tr><th>Matiere</th><th style="width:480px">Documents</th></tr></thead><tbody>
@forelse($subjects as $subject)<?php $subjectQuery=http_build_query(array_merge($selection,array('id_matiere'=>$subject->id))); ?><tr><td>{{$subject->nom}}</td><td><a class="btn btn-sm btn-default" target="_blank" href="{{route('notes_anonymats_fiche_matiere')}}?{{$subjectQuery}}"><i class="fa fa-file-text-o"></i> Fiche de report</a> <a class="btn btn-sm btn-default" target="_blank" href="{{route('notes_anonymats_print')}}?{{$subjectQuery}}"><i class="fa fa-list"></i> Liste des anonymats</a> <a class="btn btn-sm btn-default" target="_blank" href="{{route('notes_anonymats_fiche_presence')}}?{{$subjectQuery}}"><i class="fa fa-check-square-o"></i> Fiche de presence</a></td></tr>
@empty<tr><td colspan="2" class="text-center">Aucune matiere configuree pour ce semestre.</td></tr>@endforelse
</tbody></table></div>
<h4>Liste de controle</h4>
<div class="table-responsive"><table class="table table-bordered table-striped"><thead><tr><th>#</th><th>Anonymat</th><th>Matricule</th><th>Etudiant (controle)</th><th>Matiere</th></tr></thead><tbody>
@forelse($rows as $row)<tr><td>{{$row->rang}}</td><td><strong>{{$row->code}}</strong></td><td>{{$row->matricule}}</td><td>{{$row->nom}} {{$row->prenom}}</td><td>{{$row->matiere_nom?:'Toutes les matieres'}}</td></tr>
@empty<tr><td colspan="5" class="text-center">Aucun anonymat trouve.</td></tr>@endforelse
</tbody></table></div>
@endsection
