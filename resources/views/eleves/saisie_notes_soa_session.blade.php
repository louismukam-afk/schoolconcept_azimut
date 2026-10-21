@extends('skeleton')
@section('sub-content')
<a class="btn btn-default" href="{{ url('/eleves/notes/matieres_soa?id='.$id_classe.'&cycle='.$id_cycle.'&nniveau='.$id_nniveau) }}">Retour aux matières et sessions</a>
<h4>{{ \gesecole\Services\BtsAnnualTranscript::displayText(App::getLocale() === 'en' && $matiere->nom_anglais ? $matiere->nom_anglais : $matiere->nom) }}</h4>
<p><strong>{{ $phase->label }} — {{ $phase->weight }} %</strong> · Session #{{ $config->id }} · Année {{ session('year') }}</p>
@if($phase->usesNormalWeight)<p class="text-muted">La pondération de la session normale est utilisée pour ce rattrapage.</p>@endif
<p>Les notes sont saisies sur 20 et pondérées automatiquement selon cette session. Décochez les étudiants dont la note ne doit pas être enregistrée.</p>
<form method="POST" action="{{ url('/eleves/notes/store_soa') }}">
    <input type="hidden" name="_token" value="{{ csrf_token() }}">
    <input type="hidden" name="id_matiere" value="{{ $matiere->id }}">
    <input type="hidden" name="id_classe" value="{{ $id_classe }}">
    <input type="hidden" name="cycle" value="{{ $id_cycle }}">
    <input type="hidden" name="nniveau" value="{{ $id_nniveau }}">
    <input type="hidden" name="id_session" value="{{ $config->id }}">
    <input type="hidden" name="sequence" value="{{ $phase->sequence }}">
    <div class="table-responsive"><table class="table table-bordered table-striped">
        <thead><tr><th>Sélection</th><th>Matricule</th><th>Nom et prénoms</th><th>Note /20</th></tr></thead><tbody>
        @foreach($eleves as $student)
            <?php $selected = !in_array($phase->sequence,array(5,6)) || isset($noteValues[$student->id]); ?>
            <tr>
                <td><input type="checkbox" aria-label="Sélectionner {{ $student->nom }}" name="eleve[]" value="{{ $student->id }}" @if($selected) checked @endif onchange="document.getElementById('soa-mark-{{ $student->id }}').disabled=!this.checked"></td>
                <td>{{ $student->matricule }}</td><td><label for="soa-mark-{{ $student->id }}">{{ $student->nom }} {{ $student->prenom }}</label></td>
                <td><input id="soa-mark-{{ $student->id }}" class="form-control" type="number" name="note[{{ $student->id }}]" min="0" max="20" step="0.0001" required @if(!$selected) disabled @endif value="{{ old('note.'.$student->id,isset($noteValues[$student->id])?$noteValues[$student->id]:'') }}"></td>
            </tr>
        @endforeach
        </tbody></table></div>
    @if(count($eleves))<button class="btn btn-success" type="submit">Enregistrer les notes de cette session</button>@else<p class="alert alert-info">Aucun étudiant inscrit pour cette sélection.</p>@endif
</form>
@endsection
@section('breadcrumb')
<li><a href="{{ url('/eleves') }}">Gestion des notes</a></li><li><a href="{{ url('/eleves/soa') }}">Notes de Soa</a></li><li class="active">Saisie par session</li>
@endsection
