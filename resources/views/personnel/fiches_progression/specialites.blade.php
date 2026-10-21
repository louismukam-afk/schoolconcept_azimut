@extends('skeleton')
@section('sub-content')
<div class="col-md-12"><div class="panel panel-primary"><div class="panel-heading"><strong>{{$titre}}</strong></div><div class="panel-body">
    @include('personnel.fiches_progression.navigation')
    <form method="GET" class="well"><div class="row">
        <div class="col-md-2"><label>Annee</label><select name="annee_scolaire" class="form-control">@foreach($years as $year)<option value="{{$year}}" @if($selectedYear==$year) selected @endif>{{$year}}</option>@endforeach</select></div>
        <div class="col-md-2"><label>Cycle</label><select name="id_cycle" class="form-control"><option value="">Tous</option>@foreach($cycles as $item)<option value="{{$item->id}}" @if(Input::get('id_cycle')==$item->id) selected @endif>{{$item->nom}}</option>@endforeach</select></div>
        <div class="col-md-2"><label>Filiere</label><select name="id_niveau" class="form-control"><option value="">Toutes</option>@foreach($niveaux as $item)<option value="{{$item->id}}" @if(Input::get('id_niveau')==$item->id) selected @endif>{{$item->nom}}</option>@endforeach</select></div>
        <div class="col-md-2"><label>Specialite</label><select name="id_classe" class="form-control"><option value="">Toutes</option>@foreach($classes as $item)<option value="{{$item->id}}" @if(Input::get('id_classe')==$item->id) selected @endif>{{$item->nom}}</option>@endforeach</select></div>
        <div class="col-md-2"><label>Niveau</label><select name="id_nniveau" class="form-control"><option value="">Tous</option>@foreach($nniveaux as $item)<option value="{{$item->id}}" @if(Input::get('id_nniveau')==$item->id) selected @endif>{{$item->nom}}</option>@endforeach</select></div>
        <div class="col-md-2"><label>&nbsp;</label><button class="btn btn-primary form-control">Filtrer</button></div>
    </div></form>
    <div class="table-responsive"><table class="table table-bordered table-striped"><thead><tr><th>Specialite / niveau</th><th>Enseignant</th><th>Matiere</th><th>Semestre</th><th>Etat</th><th>Action</th></tr></thead><tbody>
    @forelse($rows as $cours)<tr><td>{{$cours->classe?$cours->classe->nom:''}} {{$cours->nniveau?' / '.$cours->nniveau->nom:''}}</td><td>{{$cours->personnel?$cours->personnel->nom:''}}</td><td>{{$cours->matiere?$cours->matiere->nom:''}}</td><td>{{$cours->semestre}}</td><td>@if(isset($fichesParCours[$cours->id]))<span class="label label-success">{{count($fichesParCours[$cours->id])}} fiche(s)</span>@else<span class="label label-warning">Manquante</span>@endif</td><td><a class="btn btn-success btn-xs" href="{{route('fiches_progression_enseignant_cours',array($cours->id_personnel,'annee_scolaire'=>$selectedYear))}}">Ajouter</a> <a class="btn btn-info btn-xs" href="{{route('fiches_progression_enseignant_fiches',array($cours->id_personnel,'annee_scolaire'=>$selectedYear))}}">Consulter</a></td></tr>@empty<tr><td colspan="6" class="text-center">Aucun cours trouve.</td></tr>@endforelse
    </tbody></table></div>
</div></div></div>
@endsection
@section('breadcrumb')<li><a href="{{url('emploi-du-temps')}}"><strong>Gestion RH</strong></a></li><li class="active"><strong>{{$titre}}</strong></li>@endsection
