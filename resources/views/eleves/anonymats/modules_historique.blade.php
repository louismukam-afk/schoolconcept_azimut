@extends('skeleton')
@section('sub-content')
<div class="page-header"><h3><i class="fa fa-history"></i> Historique des PV par modules</h3></div>
@if($errors->any())<div class="alert alert-danger">{{$errors->first()}}</div>@endif

<form method="get" action="{{route('notes_anonymats_modules_historique')}}" class="panel panel-default">
    <div class="panel-heading"><strong>Rechercher les PV par modules generes</strong></div>
    <div class="panel-body">
        <div class="row">
            <div class="form-group col-md-3">
                <label>Annee academique</label>
                <select name="annee_academique" class="form-control">
                    <option value="">Toutes</option>
                    @foreach($annees as $annee)
                        <option value="{{$annee->annee_academique}}" @if($anneeHistorique==$annee->annee_academique) selected @endif>{{$annee->annee_academique}}</option>
                    @endforeach
                    @if(!count($annees))
                        <option value="{{session('year')}}" selected>{{session('year')}}</option>
                    @endif
                </select>
            </div>
        </div>
        @include('eleves.anonymats._selection_fields')
        <button type="submit" class="btn btn-primary"><i class="fa fa-search"></i> Rechercher</button>
        <a href="{{route('notes_anonymats_modules_historique')}}" class="btn btn-default"><i class="fa fa-refresh"></i> Reinitialiser</a>
        <a href="{{route('notes_anonymats_modules')}}" class="btn btn-default"><i class="fa fa-arrow-left"></i> Retour aux modules</a>
    </div>
</form>

<div class="panel panel-success">
    <div class="panel-heading">
        <strong>PV par modules enregistres</strong>
        <span class="badge">{{count($pvs)}}</span>
    </div>
    <div class="panel-body">
        <div class="table-responsive">
            <table class="table table-bordered table-striped table-hover">
                <thead>
                <tr>
                    <th>#</th>
                    <th>Annee</th>
                    <th>Semestre</th>
                    <th>Etudiants</th>
                    <th>Fichier</th>
                    <th>Genere le</th>
                    <th>Statut</th>
                    <th>Action</th>
                </tr>
                </thead>
                <tbody>
                <?php $i = 1; ?>
                @forelse($pvs as $pv)
                    <tr>
                        <td>{{$i++}}</td>
                        <td>{{$pv->annee_academique}}</td>
                        <td><span class="label label-info">{{$pv->semestre}}</span></td>
                        <td>{{$pv->nombre_etudiants}}</td>
                        <td>{{$pv->nom_fichier}}</td>
                        <td>
                            @if($pv->date_generation)
                                {{$pv->date_generation->format('d/m/Y H:i')}}
                            @endif
                        </td>
                        <td><span class="label label-warning">{{ucfirst($pv->statut)}}</span></td>
                        <td>
                            <a href="{{route('notes_anonymats_modules_historique_show', array($pv->id))}}" class="btn btn-xs btn-primary" target="_blank">
                                <i class="fa fa-eye"></i> Visualiser
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="text-center">Aucun PV par module enregistre pour cette selection.</td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
@section('breadcrumb')<li><a href="{{url('eleves')}}">Gestion des notes</a></li><li><a href="{{route('notes_anonymats')}}">Anonymats</a></li><li><a href="{{route('notes_anonymats_modules')}}">Notes par modules</a></li><li class="active">Historique</li>@endsection
