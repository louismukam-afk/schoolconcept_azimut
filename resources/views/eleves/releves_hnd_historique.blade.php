@extends('skeleton')
@section('sub-content')
<div class="btn-toolbar" style="margin-bottom:20px">
    <a class="btn btn-default" href="{{ url('/eleves') }}">Accueil élèves</a>
    <a class="btn btn-primary" href="{{ url('/eleves/releves_hnd_annuel') }}">Générer un relevé HND</a>
</div>
<div class="panel panel-primary">
    <div class="panel-heading">Historique des relevés HND</div>
    <div class="panel-body">
    @if(count($generations))
    <div class="table-responsive">
        <table class="table table-bordered table-striped">
            <thead><tr><th>Date de génération</th><th>Spécialité</th><th>Cycle / Niveau</th><th>Année académique</th><th>Période</th><th>Relevés</th><th>Actions</th></tr></thead>
            <tbody>
            @foreach($generations as $generation)
                <tr>
                    <td>{{ $generation->created_at }}</td>
                    <td>{{ $generation->classe ? $generation->classe->nom : '—' }}</td>
                    <td>{{ $generation->cycle ? $generation->cycle->nom : 'HND' }} / {{ $generation->nniveau ? $generation->nniveau->nom : '—' }}</td>
                    <td>{{ $generation->annee_academique }}</td>
                    <td>{{ $generation->periode_debut }} au {{ $generation->periode_fin }}</td>
                    <td>{{ $generation->nb_releves }}</td>
                    <td>
                        <a class="btn btn-primary btn-sm" href="{{ url('/eleves/releves_hnd/generation/'.$generation->id.'/print') }}">Ouvrir / Imprimer</a>
                        <form method="POST" style="display:inline" action="{{ url('/eleves/releves_hnd/generation/'.$generation->id.'/destroy') }}" onsubmit="return confirm('Supprimer cet historique HND ?')">
                            <input type="hidden" name="_token" value="{{ csrf_token() }}">
                            <button class="btn btn-danger btn-sm" type="submit">Supprimer</button>
                        </form>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
    @else
        <p class="alert alert-info">Aucun relevé HND sauvegardé.</p>
    @endif
    </div>
</div>
@endsection
@section('breadcrumb')
<li><a href="{{ url('/eleves') }}">Gestion des notes</a></li><li class="active">Historique HND</li>
@endsection
