@extends('skeleton')
@section('sub-content')
<?php $selectedImport=\Illuminate\Support\Facades\Input::get('id_biometrie_import'); ?>

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

<div class="text-right" style="margin-bottom:12px">
    <a href="{{route('biometrie_permanents')}}" class="btn btn-default">Retour aux permanents</a>
</div>

<div class="panel panel-primary">
    <div class="panel-heading"><strong>Saisie manuelle des presences permanentes</strong></div>
    <div class="panel-body">
        <form method="POST" action="{{route('biometrie_permanents_manual_store')}}">
            <input type="hidden" name="_token" value="{{csrf_token()}}">
            <div class="row">
                <div class="col-md-4">
                    <label>Lot existant</label>
                    <select name="id_biometrie_import" class="form-control">
                        <option value="">Creer un nouveau lot manuel</option>
                        @foreach($imports as $import)
                            <option value="{{$import->id}}" @if($selectedImport==$import->id) selected @endif>#{{$import->id}} - {{$import->libelle}} ({{$import->date_debut}} au {{$import->date_fin}})</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3"><label>Libelle nouveau lot</label><input type="text" name="libelle" class="form-control" value="{{old('libelle')}}"></div>
                <div class="col-md-2"><label>Date debut lot</label><input type="date" name="date_debut_import" class="form-control" value="{{old('date_debut_import')}}"></div>
                <div class="col-md-2"><label>Date fin lot</label><input type="date" name="date_fin_import" class="form-control" value="{{old('date_fin_import')}}"></div>
            </div>
            <hr>
            <div class="row">
                <div class="col-md-5">
                    <label>Rechercher</label>
                    <input type="text" id="emploi-search" class="form-control" placeholder="Nom du personnel">
                </div>
                <div class="col-md-7">
                    <label>Emploi permanent</label>
                    <select name="id_emploi_permanent" id="emploi-select" class="form-control" required>
                        <option value="">-- Choisir --</option>
                        @foreach($emplois as $emploi)
                            <option value="{{$emploi->id}}" @if(old('id_emploi_permanent')==$emploi->id) selected @endif>
                                {{$emploi->personnel?$emploi->personnel->nom:'-'}} / {{isset($jours[$emploi->jour_semaine])?$jours[$emploi->jour_semaine]:'-'}} / {{$emploi->plage?$emploi->plage->libelle:'-'}} @if($emploi->plage) ({{substr($emploi->plage->heure_debut,0,5)}} - {{substr($emploi->plage->heure_fin,0,5)}}) @endif
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="row" style="margin-top:12px">
                <div class="col-md-2"><label>Date</label><input type="date" name="date_presence" class="form-control" value="{{old('date_presence')}}" required></div>
                <div class="col-md-2"><label>Debut reel</label><input type="time" name="heure_debut_reelle" id="debut-reel" class="form-control" value="{{old('heure_debut_reelle')}}" required></div>
                <div class="col-md-2"><label>Fin reelle</label><input type="time" name="heure_fin_reelle" id="fin-reel" class="form-control" value="{{old('heure_fin_reelle')}}" required></div>
                <div class="col-md-4"><label>Observation</label><input type="text" name="observation" class="form-control" value="{{old('observation')}}"></div>
                <div class="col-md-2"><label>&nbsp;</label><button class="btn btn-success form-control">Enregistrer</button></div>
            </div>
        </form>
    </div>
</div>

<div class="panel panel-default">
    <div class="panel-heading"><strong>Presences saisies manuellement</strong></div>
    <div class="table-responsive">
        <table class="table table-bordered table-striped">
            <thead><tr><th>Lot</th><th>Date</th><th>Personnel</th><th>Plage</th><th>Heures prevues</th><th>Heures realisees</th><th>Duree</th><th>Statut</th></tr></thead>
            <tbody>
            @forelse($presencesManuelles as $presence)
                <tr>
                    <td>{{$presence->import?$presence->import->libelle:'-'}}</td>
                    <td>{{$presence->date_presence}}</td>
                    <td>{{$presence->personnel?$presence->personnel->nom:'-'}}</td>
                    <td>{{$presence->plage?$presence->plage->libelle:'-'}}</td>
                    <td>{{substr($presence->heure_debut_prevue,0,5)}} - {{substr($presence->heure_fin_prevue,0,5)}}</td>
                    <td>@if($presence->heure_debut_reelle && $presence->heure_fin_reelle) {{substr($presence->heure_debut_reelle,0,5)}} - {{substr($presence->heure_fin_reelle,0,5)}} @else - @endif</td>
                    <td>{{number_format($presence->duree_realisee,2,',',' ')}} h</td>
                    <td>{{$presence->statut}}</td>
                </tr>
            @empty
                <tr><td colspan="8" class="text-center">Aucune presence manuelle saisie.</td></tr>
            @endforelse
            </tbody>
        </table>
    </div>
</div>

<script>
    (function(){
        var emploisData={!! $emploisDataJson !!};
        var emploiSelect=document.getElementById('emploi-select');
        var search=document.getElementById('emploi-search');
        function fillTimes(){
            var emploi=emploisData[emploiSelect.value];
            if(!emploi) return;
            if(document.getElementById('debut-reel').value==='') document.getElementById('debut-reel').value=emploi.debut;
            if(document.getElementById('fin-reel').value==='') document.getElementById('fin-reel').value=emploi.fin;
        }
        if(emploiSelect){ emploiSelect.onchange=fillTimes; fillTimes(); }
        if(search){
            search.onkeyup=function(){
                var needle=this.value.toLowerCase();
                Array.prototype.forEach.call(emploiSelect.options,function(option){
                    if(option.value==='') return;
                    option.style.display=option.text.toLowerCase().indexOf(needle)>=0?'':'none';
                });
            };
        }
    })();
</script>
@endsection
@section('breadcrumb')
    <li><a href="{{url('emploi-du-temps')}}"><strong>Gestion des ressources humaines</strong></a></li>
    <li><a href="{{route('biometrie_permanents')}}"><strong>Biometrie permanents</strong></a></li>
    <li class="active"><strong>{{$titre}}</strong></li>
@endsection
