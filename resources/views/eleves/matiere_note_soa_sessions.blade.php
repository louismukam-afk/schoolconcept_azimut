@extends('skeleton')
@section('sub-content')
<div class="btn-toolbar" style="margin-bottom:15px"><a class="btn btn-default" href="{{ url('/eleves/notes/edit_soa') }}">Changer de spécialité</a></div>
<h4>{{ $classe->nom }} — Sessions d’examen {{ session('year') }}</h4>
<p>Choisissez une épreuve pour saisir ses notes sur 20. Si la pondération du rattrapage n’est pas renseignée, celle de la session normale est utilisée.</p>
<div class="table-responsive">
<table class="table table-bordered table-striped">
    <thead><tr><th>Code UE</th><th>Matière</th><th>Semestre</th><th>Session configurée</th><th>Épreuves disponibles</th></tr></thead>
    <tbody>
    @forelse($matieres as $m)
        <tr>
            <td>{{ $m->id_code }}</td>
            <td>{{ \gesecole\Services\BtsAnnualTranscript::displayText(App::getLocale() === 'en' && $m->nom_anglais ? $m->nom_anglais : $m->nom) }}</td>
            <td>{{ $m->semestre == 0 ? '1 et 2' : $m->semestre }}</td>
            <td>@if($m->id_session)<strong>Session #{{ $m->id_session }}</strong><br>{{ $m->tutelle ?: 'Configuration de la spécialité' }}<br>{{ session('year') }}@else<span class="text-warning">Aucune session configurée pour cette année</span>@endif</td>
            <td>
            @if($m->id_session && count($m->phases))
                @foreach($m->phases as $phase)
                <form method="GET" action="{{ url('/eleves/notes/create_soa/'.$id) }}" style="display:inline-block;margin:3px">
                    <input type="hidden" name="id" value="{{ $m->id_matiere }}">
                    <input type="hidden" name="cycle" value="{{ $id_cycle }}">
                    <input type="hidden" name="nniveau" value="{{ $id_nniveau }}">
                    <input type="hidden" name="id_session" value="{{ $m->id_session }}">
                    <button class="btn btn-primary btn-sm" type="submit" name="sequence" value="{{ $phase->sequence }}">{{ $phase->label }} ({{ $phase->weight }} %)</button>
                </form>
                @endforeach
            @elseif($m->id_session)
                <span class="text-muted">Aucune épreuve activée pour ce semestre.</span>
            @else
                <span class="text-muted">La configuration de la session est nécessaire avant la saisie.</span>
            @endif
            </td>
        </tr>
    @empty
        <tr><td colspan="5">Aucune matière pour cette sélection.</td></tr>
    @endforelse
    </tbody>
</table>
</div>
@endsection
@section('breadcrumb')
<li><a href="{{ url('/eleves') }}">Gestion des notes</a></li><li><a href="{{ url('/eleves/soa') }}">Notes de Soa</a></li><li class="active">Matières et sessions d’examen</li>
@endsection
