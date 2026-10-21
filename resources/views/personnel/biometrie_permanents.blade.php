@extends('skeleton')
@section('sub-content')
<?php
    $idPersonnel=\Illuminate\Support\Facades\Input::get('id_personnel');
    $idImport=\Illuminate\Support\Facades\Input::get('id_biometrie_import');
    $dateDebut=\Illuminate\Support\Facades\Input::get('date_debut');
    $dateFin=\Illuminate\Support\Facades\Input::get('date_fin');
?>

@if(session('success') || isset($success))
    <div class="alert alert-success">{{trans('admin.succes')}}</div>
@endif

@if(count($errors)>0)
    <div class="alert alert-danger">
        @foreach($errors->all() as $error)
            <div>{{$error}}</div>
        @endforeach
    </div>
@endif

<style>
    .permanent-anomalie{color:#a94442;font-weight:bold}
    .permanent-row-anomalie td{background:#fff5f5}
</style>

<div class="row">
    <div class="col-md-8">
        <div class="panel panel-primary">
            <div class="panel-heading"><strong>Importer un fichier biometrie permanent</strong></div>
            <div class="panel-body">
                <form method="POST" action="{{route('biometrie_permanents_store')}}" enctype="multipart/form-data">
                    <input type="hidden" name="_token" value="{{csrf_token()}}">
                    <div class="row">
                        <div class="col-md-4"><label>Libelle</label><input type="text" name="libelle" class="form-control" placeholder="Pointage permanents"></div>
                        <div class="col-md-3"><label>Date debut</label><input type="date" name="date_debut" class="form-control" required></div>
                        <div class="col-md-3"><label>Date fin</label><input type="date" name="date_fin" class="form-control" required></div>
                        <div class="col-md-2"><label>Fichier</label><input type="file" name="fichier" class="form-control" accept=".xls,.csv,.txt" required><small class="help-block">XLS biometrie, CSV ou TXT</small></div>
                    </div>
                    <div class="text-right" style="margin-top:12px">
                        <button class="btn btn-success">Importer</button>
                        <a href="{{route('biometrie_permanents_manual_create')}}" class="btn btn-default">Saisie manuelle</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="panel panel-info">
            <div class="panel-heading"><strong>Exports</strong></div>
            <div class="panel-body">
                <button type="button" class="btn btn-default btn-block" onclick="window.print()">Imprimer / PDF</button>
                <a href="{{route('decompte_heures')}}" class="btn btn-primary btn-block">Decompte enseignants</a>
            </div>
        </div>
    </div>
</div>

<form method="GET" class="well">
    <div class="row">
        <div class="col-md-3">
            <label>Personnel permanent</label>
            <select name="id_personnel" class="form-control">
                <option value="">Tous</option>
                @foreach($personnels as $p)
                    <option value="{{$p->id}}" @if($idPersonnel==$p->id) selected @endif>{{$p->nom}}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-3">
            <label>Import</label>
            <select name="id_biometrie_import" class="form-control">
                <option value="">Tous</option>
                @foreach($imports as $import)
                    <option value="{{$import->id}}" @if($idImport==$import->id) selected @endif>#{{$import->id}} - {{$import->libelle}}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-2"><label>Recherche</label><input type="text" name="search_personnel" value="{{\Illuminate\Support\Facades\Input::get('search_personnel')}}" class="form-control"></div>
        <div class="col-md-2"><label>Date debut</label><input type="date" name="date_debut" value="{{$dateDebut}}" class="form-control"></div>
        <div class="col-md-2"><label>Date fin</label><input type="date" name="date_fin" value="{{$dateFin}}" class="form-control"></div>
        <div class="col-md-2"><label>&nbsp;</label><button class="btn btn-primary form-control">Filtrer</button></div>
    </div>
</form>

<div class="row">
    <div class="col-md-7">
        <div class="panel panel-default">
            <div class="panel-heading"><strong>Imports biometrie permanents</strong></div>
            <div class="table-responsive">
                <table class="table table-bordered table-striped">
                    <thead><tr><th>#</th><th>Libelle</th><th>Periode</th><th>Statut</th><th>Lignes</th><th>Consolidees</th><th>Actions</th></tr></thead>
                    <tbody>
                    @forelse($imports as $import)
                        <tr>
                            <td>{{$import->id}}</td>
                            <td>{{$import->libelle}}</td>
                            <td>{{$import->date_debut}}<br>{{$import->date_fin}}</td>
                            <td>{{$import->statut}}</td>
                            <td>{{$import->total_lignes}}</td>
                            <td>{{$import->total_consolidees}}</td>
                            <td>
                                @if($import->type_import!='permanent_manuel')
                                    <form method="POST" action="{{route('biometrie_permanents_consolider',$import->id)}}" style="display:inline">
                                        <input type="hidden" name="_token" value="{{csrf_token()}}">
                                        <button class="btn btn-xs btn-success">Consolider</button>
                                    </form>
                                @else
                                    <a href="{{route('biometrie_permanents_manual_create',['id_biometrie_import'=>$import->id])}}" class="btn btn-xs btn-primary">Ajouter</a>
                                @endif
                                <form method="POST" action="{{route('biometrie_permanents_clear',$import->id)}}" style="display:inline">
                                    <input type="hidden" name="_token" value="{{csrf_token()}}">
                                    <button class="btn btn-xs btn-warning">Vider</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="7" class="text-center">Aucun import permanent.</td></tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="col-md-5">
        <div class="panel panel-default">
            <div class="panel-heading"><strong>Associations biometrie permanents</strong></div>
            <div class="panel-body">
                <form method="POST" action="{{route('biometrie_heures_mapping')}}">
                    <input type="hidden" name="_token" value="{{csrf_token()}}">
                    <div class="row">
                        <div class="col-md-5">
                            <label>Identite biometrie</label>
                            <select name="nom_biometrie" class="form-control">
                                @foreach($biometrieIdentites as $identite)
                                    <option value="{{$identite->nom_biometrie}}" data-numero="{{$identite->numero_biometrie}}">{{$identite->nom_biometrie}} @if($identite->numero_biometrie) ({{$identite->numero_biometrie}}) @endif</option>
                                @endforeach
                            </select>
                            <input type="hidden" name="numero_biometrie" id="numero-biometrie-permanent">
                        </div>
                        <div class="col-md-5">
                            <label>Personnel</label>
                            <select name="id_personnel" class="form-control" required>
                                <option value="">-- Choisir --</option>
                                @foreach($personnels as $p)
                                    <option value="{{$p->id}}">{{$p->nom}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-2"><label>&nbsp;</label><button class="btn btn-primary form-control">Lier</button></div>
                    </div>
                </form>
                @if(count($unmatchedPointages)>0)
                    <hr>
                    <strong>Pointages non associes</strong>
                    <ul>
                        @foreach($unmatchedPointages as $item)
                            <li>{{$item->nom_biometrie}} @if($item->numero_biometrie) ({{$item->numero_biometrie}}) @endif</li>
                        @endforeach
                    </ul>
                @endif
            </div>
        </div>
    </div>
</div>

<div class="panel panel-default">
    <div class="panel-heading"><strong>Totaux par personnel</strong></div>
    <div class="table-responsive">
        <table class="table table-bordered table-striped">
            <thead><tr><th>Personnel</th><th>Jours</th><th>Heures prevues</th><th>Heures realisees</th><th>Non comptabilisees</th><th>Salaire theorique</th><th>Montant du</th><th>Penalite</th></tr></thead>
            <tbody>
            @forelse($totaux as $row)
                <tr>
                    <td>{{$row['personnel']}}</td>
                    <td>{{$row['jours']}}</td>
                    <td>{{number_format($row['heures_prevues'],2,',',' ')}}</td>
                    <td>{{number_format($row['heures_realisees'],2,',',' ')}}</td>
                    <td class="@if($row['heures_non_comptabilisees']>0) permanent-anomalie @endif">{{number_format($row['heures_non_comptabilisees'],2,',',' ')}}</td>
                    <td>{{number_format($row['salaire_theorique'],0,',',' ')}}</td>
                    <td>{{number_format($row['montant_du'],0,',',' ')}}</td>
                    <td class="@if($row['penalite_montant']>0) permanent-anomalie @endif">{{number_format($row['penalite_montant'],0,',',' ')}}</td>
                </tr>
            @empty
                <tr><td colspan="8" class="text-center">Aucun total disponible.</td></tr>
            @endforelse
            </tbody>
        </table>
    </div>
</div>

<div class="panel panel-default">
    <div class="panel-heading"><strong>Detail des presences permanentes</strong></div>
    <div class="table-responsive">
        <table class="table table-bordered table-striped">
            <thead><tr><th>Date</th><th>Personnel</th><th>Plage</th><th>Prevu</th><th>Realise</th><th>Duree prevue</th><th>Duree realisee</th><th>Non comptabilisee</th><th>Montant du</th><th>Penalite</th><th>Statut</th><th>Observation</th></tr></thead>
            <tbody>
            @forelse($presences as $presence)
                <?php $non=max($presence->duree_prevue-$presence->duree_realisee,0); $anomalie=$non>0 || $presence->penalite_montant>0 || $presence->observation; ?>
                <tr class="@if($anomalie) permanent-row-anomalie @endif">
                    <td>{{$presence->date_presence}}</td>
                    <td>{{$presence->personnel?$presence->personnel->nom:'-'}}</td>
                    <td>{{$presence->plage?$presence->plage->libelle:'-'}}</td>
                    <td>{{substr($presence->heure_debut_prevue,0,5)}} - {{substr($presence->heure_fin_prevue,0,5)}}</td>
                    <td>@if($presence->heure_debut_reelle && $presence->heure_fin_reelle) {{substr($presence->heure_debut_reelle,0,5)}} - {{substr($presence->heure_fin_reelle,0,5)}} @else - @endif</td>
                    <td>{{number_format($presence->duree_prevue,2,',',' ')}} h</td>
                    <td>{{number_format($presence->duree_realisee,2,',',' ')}} h</td>
                    <td class="@if($non>0) permanent-anomalie @endif">{{number_format($non,2,',',' ')}} h</td>
                    <td>{{number_format($presence->montant_du,0,',',' ')}}</td>
                    <td class="@if($presence->penalite_montant>0) permanent-anomalie @endif">{{number_format($presence->penalite_montant,0,',',' ')}}</td>
                    <td>{{$presence->statut}}</td>
                    <td>{{$presence->observation}}</td>
                </tr>
            @empty
                <tr><td colspan="12" class="text-center">Aucune presence permanente consolidee.</td></tr>
            @endforelse
            </tbody>
        </table>
    </div>
</div>

<script>
    (function(){
        var select=document.querySelector('select[name="nom_biometrie"]');
        var input=document.getElementById('numero-biometrie-permanent');
        function syncNumero(){
            if(!select || !input) return;
            var option=select.options[select.selectedIndex];
            input.value=option ? option.getAttribute('data-numero') : '';
        }
        if(select){ select.onchange=syncNumero; syncNumero(); }
    })();
</script>
@endsection
@section('breadcrumb')
    <li><a href="{{url('emploi-du-temps')}}"><strong>Gestion des ressources humaines</strong></a></li>
    <li class="active"><strong>{{$titre}}</strong></li>
@endsection
