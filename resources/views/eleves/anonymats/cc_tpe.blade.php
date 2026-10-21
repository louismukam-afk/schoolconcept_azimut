@extends('skeleton')
@section('sub-content')
<div class="page-header"><h3><i class="fa fa-pencil"></i> Saisie nominative des notes CC / TPE</h3></div>
@if($errors->any())<div class="alert alert-danger">{{$errors->first()}}</div>@endif
@if(session('success'))<div class="alert alert-success">{{session('success')}}</div>@endif
<form method="get" action="{{route('notes_anonymats_cc_tpe')}}" class="panel panel-default">
    <div class="panel-body">
        <?php $showMatiere=true; $lockSemesterToExam=true; ?>
        @include('eleves.anonymats._selection_fields')
        <div class="row">
            <div class="form-group col-md-4">
                <label>Type de note</label>
                <select name="note_type" class="form-control" required>
                    @foreach($noteTypeLabels as $value=>$label)
                        <option value="{{$value}}" @if($noteType==$value) selected @endif>{{$label}}</option>
                    @endforeach
                </select>
            </div>
        </div>
        <button class="btn btn-primary" type="submit"><i class="fa fa-search"></i> Charger</button>
        <a href="{{route('notes_anonymats')}}" class="btn btn-default"><i class="fa fa-arrow-left"></i> Anonymats</a>
    </div>
</form>
@if(!empty($selectionError))<div class="alert alert-warning">{{$selectionError}}</div>@endif
@if($selectedMatiere)
<form method="post" action="{{route('notes_anonymats_cc_tpe_store')}}">
    <input type="hidden" name="_token" value="{{csrf_token()}}">
    @foreach($selection as $name=>$value)<input type="hidden" name="{{$name}}" value="{{$value}}">@endforeach
    <input type="hidden" name="id_matiere" value="{{$selectedMatiere->id}}">
    <input type="hidden" name="note_type" value="{{$noteType}}">
    <div class="alert alert-info">
        <strong>{{$selectedMatiere->nom}}</strong> -
        {{$noteTypeLabels[$noteType]}} {{$selection['semestre']}}.
        Saisie par noms, sans anonymat.
    </div>
    <div class="table-responsive">
        <table class="table table-bordered table-striped">
            <thead><tr><th>#</th><th>Matricule</th><th>Nom et prenom</th><th style="width:220px">Note /20</th></tr></thead>
            <tbody>
            @forelse($rows as $index=>$row)
                <tr>
                    <td>{{$index+1}}</td>
                    <td>{{$row->matricule}}</td>
                    <td>{{$row->nom}} {{$row->prenom}}</td>
                    <td><input class="form-control" type="number" min="0" max="20" step="0.01" name="notes[{{$row->id}}]" value="{{$row->note_saisie}}"></td>
                </tr>
            @empty
                <tr><td colspan="4" class="text-center">Aucun etudiant inscrit pour cette selection.</td></tr>
            @endforelse
            </tbody>
        </table>
    </div>
    @if(count($rows))<button class="btn btn-success" type="submit"><i class="fa fa-save"></i> Enregistrer les notes</button>@endif
</form>
@endif
@endsection
@section('breadcrumb')<li><a href="{{url('eleves')}}">Gestion des notes</a></li><li><a href="{{route('notes_anonymats')}}">Anonymats</a></li><li class="active">Saisie CC / TPE</li>@endsection
