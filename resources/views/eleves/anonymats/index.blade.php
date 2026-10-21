@extends('skeleton')
@section('sub-content')
<div class="page-header"><h3><i class="fa fa-barcode"></i> Anonymats des examens</h3></div>
@if($migrationRequired)<div class="alert alert-warning">Le module d’anonymat doit être initialisé par l’administrateur avant utilisation.</div>@endif
@if($errors->any())<div class="alert alert-danger">{{$errors->first()}}</div>@endif
@if(session('success'))<div class="alert alert-success">{{session('success')}}</div>@endif
<form method="post" action="{{route('notes_anonymats_generer')}}" class="panel panel-default">
    <input type="hidden" name="_token" value="{{csrf_token()}}"><div class="panel-heading"><strong>Nouvelle generation</strong></div>
    <div class="panel-body"><?php $showSubjectCheckboxes=true; ?>@include('eleves.anonymats._selection_fields')
        <button type="submit" class="btn btn-primary" @if($migrationRequired) disabled @endif><i class="fa fa-cogs"></i> Generer</button>
        <button type="submit" formmethod="get" formaction="{{route('notes_anonymats_show')}}" class="btn btn-default" @if($migrationRequired) disabled @endif><i class="fa fa-list"></i> Afficher</button>
        <button type="submit" formmethod="get" formaction="{{route('notes_anonymats_saisie')}}" class="btn btn-success" @if($migrationRequired) disabled @endif><i class="fa fa-pencil"></i> Saisir les notes</button>
        <button type="submit" formmethod="get" formaction="{{route('notes_anonymats_cc_tpe')}}" class="btn btn-warning" @if($migrationRequired) disabled @endif><i class="fa fa-pencil-square-o"></i> Saisie CC / TPE</button>
        <button type="submit" formmethod="get" formaction="{{route('notes_anonymats_modules')}}" class="btn btn-info" @if($migrationRequired) disabled @endif><i class="fa fa-table"></i> Notes par modules</button>
    </div>
</form>
<h4>Generations de l'annee {{session('year')}}</h4>
<div class="table-responsive"><table class="table table-bordered table-striped"><thead><tr><th>Specialite</th><th>Campus</th><th>Session</th><th>Mode</th><th>Nombre</th><th></th></tr></thead><tbody>
@forelse($generations as $generation)
<?php $q=http_build_query(array('id_classe'=>$generation->id_classe,'id_cycle'=>$generation->id_cycle,'id_niveau'=>$generation->id_niveau,'id_nniveau'=>$generation->id_nniveau,'id_campus'=>$generation->id_campus,'id_tutelle'=>$generation->id_tutelle,'semestre'=>$generation->semestre,'type_examen'=>$generation->type_examen,'mode_generation'=>$generation->mode_generation)); ?>
<tr><td>{{$generation->classe_nom}}</td><td>{{$generation->campus_nom}}</td><td>{{$examLabels[$generation->type_examen]}} ({{$generation->semestre}})</td><td>{{$generation->mode_generation=='matiere'?'Par matiere':'Par examen'}}</td><td>{{$generation->total}}</td><td><a class="btn btn-xs btn-default" title="Afficher" href="{{route('notes_anonymats_show')}}?{{$q}}"><i class="fa fa-eye"></i></a> <a class="btn btn-xs btn-primary" title="Telecharger toutes les fiches de report" href="{{route('notes_anonymats_pdf_fiches')}}?{{$q}}"><i class="fa fa-file-pdf-o"></i></a> <a class="btn btn-xs btn-info" title="Telecharger toutes les listes d'anonymats" href="{{route('notes_anonymats_pdf_listes')}}?{{$q}}"><i class="fa fa-download"></i></a> <a class="btn btn-xs btn-warning" title="Telecharger toutes les fiches de presence" href="{{route('notes_anonymats_pdf_presences')}}?{{$q}}"><i class="fa fa-check-square-o"></i></a> <a class="btn btn-xs btn-success" title="Visualiser les notes par modules" href="{{route('notes_anonymats_modules')}}?{{$q}}"><i class="fa fa-table"></i></a></td></tr>
@empty<tr><td colspan="6" class="text-center">Aucune generation pour cette annee.</td></tr>@endforelse
</tbody></table></div>
@endsection
@section('breadcrumb')<li><a href="{{url('eleves')}}">Gestion des notes</a></li><li class="active">Anonymats</li>@endsection
