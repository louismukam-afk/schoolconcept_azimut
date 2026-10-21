@extends('skeleton')
@section('sub-content')
<div class="col-md-12">
    <div class="panel panel-primary">
        <div class="panel-heading"><strong>Fiches de progression - Enseignants</strong></div>
        <div class="panel-body">
            @include('personnel.fiches_progression.navigation')
            <form method="GET" class="well">
                <div class="row">
                    <div class="col-md-3"><label>Annee academique</label><select name="annee_scolaire" class="form-control">@foreach($years as $year)<option value="{{$year}}" @if($selectedYear==$year) selected @endif>{{$year}}</option>@endforeach</select></div>
                    <div class="col-md-6"><label>Rechercher</label><input type="text" name="q" value="{{$search}}" class="form-control" placeholder="Nom ou matricule"></div>
                    <div class="col-md-3"><label>&nbsp;</label><button class="btn btn-primary form-control"><span class="glyphicon glyphicon-search"></span> Rechercher</button></div>
                </div>
            </form>
            <div class="table-responsive"><table class="table table-bordered table-striped">
                <thead><tr><th>Matricule</th><th>Nom</th><th>Date de naissance</th><th>Lieu de naissance</th><th>Actions</th></tr></thead>
                <tbody>@forelse($enseignants as $enseignant)<tr>
                    <td>{{$enseignant->matricule}}</td><td>{{$enseignant->nom}}</td><td>{{$enseignant->date_naiss}}</td><td>{{$enseignant->lieu_naiss}}</td>
                    <td><a class="btn btn-success btn-xs" href="{{route('fiches_progression_enseignant_cours',array($enseignant->id,'annee_scolaire'=>$selectedYear))}}"><span class="glyphicon glyphicon-plus"></span> Ajouter</a> <a class="btn btn-info btn-xs" href="{{route('fiches_progression_enseignant_fiches',array($enseignant->id,'annee_scolaire'=>$selectedYear))}}"><span class="glyphicon glyphicon-list"></span> Ses fiches</a></td>
                </tr>@empty<tr><td colspan="5" class="text-center">Aucun enseignant trouve.</td></tr>@endforelse</tbody>
            </table></div>
            <div class="text-center"><?php echo $enseignants->appends(array('q'=>$search,'annee_scolaire'=>$selectedYear))->render(); ?></div>
        </div>
    </div>
</div>
@endsection
@section('breadcrumb')<li><a href="{{url('emploi-du-temps')}}"><strong>Gestion des ressources humaines</strong></a></li><li class="active"><strong>Fiches de progression</strong></li>@endsection
