@extends('skeleton')
@section('sub-content')
<div class="page-header"><h3><i class="fa fa-pencil"></i> Saisie des notes par anonymat</h3></div>
@if($errors->any())<div class="alert alert-danger">{{$errors->first()}}</div>@endif
@if(session('success'))<div class="alert alert-success">{{session('success')}}</div>@endif
<form method="get" action="{{route('notes_anonymats_saisie')}}" class="panel panel-default"><div class="panel-body"><?php $showMatiere=true; $lockSemesterToExam=true; ?>@include('eleves.anonymats._selection_fields')<button class="btn btn-primary" type="submit"><i class="fa fa-search"></i> Charger</button></div></form>
@if(!empty($selectionError))<div class="alert alert-warning">{{$selectionError}}</div>@endif
@if($selectedMatiere)
<form method="post" action="{{route('notes_anonymats_notes_store')}}"><input type="hidden" name="_token" value="{{csrf_token()}}">@foreach($selection as $name=>$value)<input type="hidden" name="{{$name}}" value="{{$value}}">@endforeach<input type="hidden" name="id_matiere" value="{{$selectedMatiere->id}}">
<div class="alert alert-info"><strong>{{$selectedMatiere->nom}}</strong> - {{$examLabels[$selection['type_examen']]}}. Les identites sont masquees.</div>
<?php $printQuery=http_build_query(array_merge($selection,array('id_matiere'=>$selectedMatiere->id))); ?>
<p><a class="btn btn-default" target="_blank" href="{{route('notes_anonymats_fiche_matiere')}}?{{$printQuery}}"><i class="fa fa-print"></i> Fiche de report PDF</a></p>
<div class="table-responsive"><table class="table table-bordered"><thead><tr><th>#</th><th>Numero d'anonymat</th><th style="width:220px">Note /20</th></tr></thead><tbody>
@forelse($rows as $index=>$row)<tr><td>{{$index+1}}</td><td><strong>{{$row->code}}</strong></td><td><input class="form-control" type="number" min="0" max="20" step="0.01" name="notes[{{$row->id}}]" value="{{$row->note_saisie}}"></td></tr>
@empty<tr><td colspan="3" class="text-center">Aucun anonymat genere pour cette selection.</td></tr>@endforelse
</tbody></table></div>@if(count($rows))<button class="btn btn-success" type="submit"><i class="fa fa-save"></i> Enregistrer les notes</button>@endif</form>
@endif
@endsection
