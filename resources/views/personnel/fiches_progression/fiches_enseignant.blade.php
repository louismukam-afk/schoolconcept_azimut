@extends('skeleton')
@section('sub-content')
<div class="col-md-12">
    @if(session('success'))<div class="alert alert-success">{{trans('admin.succes')}}</div>@endif
    <div class="panel panel-primary"><div class="panel-heading"><strong>Fiches de progression - {{$enseignant->nom}}</strong></div><div class="panel-body">
        <p><a href="{{route('fiches_progression_enseignants',array('annee_scolaire'=>$selectedYear))}}" class="btn btn-default">Retour</a> <a href="{{route('fiches_progression_enseignant_cours',array($enseignant->id,'annee_scolaire'=>$selectedYear))}}" class="btn btn-success">Ajouter une fiche</a></p>
        <form method="GET" class="well"><div class="row"><div class="col-md-4"><label>Annee academique</label><select name="annee_scolaire" class="form-control">@foreach($years as $year)<option value="{{$year}}" @if($selectedYear==$year) selected @endif>{{$year}}</option>@endforeach</select></div><div class="col-md-2"><label>&nbsp;</label><button class="btn btn-primary form-control">Filtrer</button></div></div></form>
        @include('personnel.fiches_progression.table_fiches',array('fiches'=>$fiches))
    </div></div>
</div>
@endsection
@section('breadcrumb')<li><a href="{{url('emploi-du-temps')}}"><strong>Gestion RH</strong></a></li><li><a href="{{route('fiches_progression_enseignants')}}"><strong>Fiches de progression</strong></a></li><li class="active"><strong>{{$enseignant->nom}}</strong></li>@endsection
