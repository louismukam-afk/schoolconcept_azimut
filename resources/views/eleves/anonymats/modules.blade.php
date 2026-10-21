@extends('skeleton')
@section('sub-content')
<style>
    .module-toolbar{margin-bottom:16px}
    .module-context{margin:0 0 18px;padding:10px 0;border-top:1px solid #ddd;border-bottom:1px solid #ddd}
    .module-sheet{margin:0 0 30px}
    .module-title{font-size:17px;margin:0;padding:10px 12px;background:#263238;color:#fff}
    .module-table{margin:0;min-width:980px;font-size:12px}
    .module-table th{background:#eef1f2;text-align:center;vertical-align:middle!important;white-space:nowrap}
    .module-table td{vertical-align:middle!important}
    .module-table .student-name{min-width:190px}
    .module-table .student-number{min-width:105px}
    .module-table .anonymat-cell{min-width:118px;font-family:monospace;font-weight:600}
    .module-table .grade-cell{min-width:58px;text-align:center}
    .module-table .decision-v{color:#1b5e20;font-weight:700}
    .module-table .decision-nv{color:#b71c1c;font-weight:700}
    .module-legend{padding:9px 12px;border:1px solid #ddd;border-top:0;background:#fafafa}
    .module-legend span{display:inline-block;margin-right:18px}
</style>
<div class="page-header"><h3><i class="fa fa-table"></i> Visualisation des notes par modules</h3></div>
@if($errors->any())<div class="alert alert-danger">{{$errors->first()}}</div>@endif
<form method="get" action="{{route('notes_anonymats_modules')}}" class="panel panel-default">
    <div class="panel-heading"><strong>Selection de l'examen</strong></div>
    <div class="panel-body">
        @include('eleves.anonymats._selection_fields')
        <div class="row">
            <div class="form-group col-md-4">
                <label>Mode du PV module</label>
                <select name="mode_note" class="form-control">
                    <option value="normal" @if($selection['mode_note']=='normal') selected @endif>Session normale : CC + SN + TPE</option>
                    <option value="rattrapage" @if($selection['mode_note']=='rattrapage') selected @endif>Rattrapage : CC + RAT + TPE</option>
                </select>
            </div>
        </div>
        <button type="submit" class="btn btn-primary"><i class="fa fa-search"></i> Afficher les modules</button>
        <a href="{{route('notes_anonymats_modules_historique')}}" class="btn btn-info"><i class="fa fa-history"></i> Historique des PV par modules</a>
        <a href="{{route('notes_anonymats')}}" class="btn btn-default"><i class="fa fa-arrow-left"></i> Anonymats</a>
    </div>
</form>

@if($resultsLoaded)
<?php $moduleQuery=http_build_query($selection); ?>
<div class="module-toolbar">
    <a class="btn btn-danger" href="{{route('notes_anonymats_modules_pdf')}}?{{$moduleQuery}}"><i class="fa fa-file-pdf-o"></i> Telecharger le PDF</a>
    <a class="btn btn-info" href="{{route('notes_anonymats_modules_historique')}}?{{$moduleQuery}}"><i class="fa fa-history"></i> Historique de cette selection</a>
</div>
<div class="module-context">
    <strong>Cycle :</strong> {{$cycle?$cycle->nom:''}} &nbsp;|&nbsp;
    <strong>Niveau :</strong> {{$nniveau?$nniveau->nom:''}} &nbsp;|&nbsp;
    <strong>Filiere :</strong> {{$filiere?$filiere->nom:''}} &nbsp;|&nbsp;
    <strong>Specialite :</strong> {{$classe?$classe->nom:''}}<br>
    <strong>Examen :</strong> {{$examLabel}} &nbsp;|&nbsp;
    <strong>Mode note :</strong> {{$moduleNoteModeLabel}} &nbsp;|&nbsp;
    <strong>Semestre :</strong> {{$selection['semestre']}} (programme S{{$programmeSemester}}) &nbsp;|&nbsp;
    <strong>Annee :</strong> {{session('year')}}
</div>

@forelse($modules as $module)
    @include('eleves.anonymats._module_table', array('printMode' => false))
@empty
    <div class="alert alert-warning">Aucun groupe de matieres configure pour cette specialite et ce semestre.</div>
@endforelse
@endif
@endsection
@section('breadcrumb')<li><a href="{{url('eleves')}}">Gestion des notes</a></li><li><a href="{{route('notes_anonymats')}}">Anonymats</a></li><li class="active">Notes par modules</li>@endsection
