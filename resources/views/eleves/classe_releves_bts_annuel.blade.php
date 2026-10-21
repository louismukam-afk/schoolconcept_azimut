@extends('skeleton')
@section('sub-content')
<div class="btn-toolbar" style="margin-bottom:20px">
    <a class="btn btn-default" href="{{ url('/eleves') }}">Accueil élèves</a>
    <a class="btn btn-warning" href="{{ url($historyBase.'/historique') }}">Historique des relevés {{ $documentLabel }}</a>
</div>
@if(session('error'))<div class="alert alert-warning">{{ session('error') }}</div>@endif
@if($errors->any())
    <div class="alert alert-danger"><ul>@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul></div>
@endif
<div class="panel panel-primary">
    <div class="panel-heading">Relevé {{ $documentLabel }} annuel — modèle à deux semestres</div>
    <div class="panel-body">
        <p>Année académique : <strong>{{ session('year') }}</strong>. Un relevé sera créé pour chaque étudiant de la sélection.</p>
        <form method="POST" action="{{ url($formBase.'/generate') }}">
            <input type="hidden" name="_token" value="{{ csrf_token() }}">
            @include('eleves.partials.cycle_niveau_filiere_filter', array(
                'selectionSubmitFilters' => true,
                'selectionCycleValue' => old('cycle'),
                'selectionLevelValue' => old('nniveau'),
                'selectionFiliereValue' => old('filiere'),
                'selectionSpecialtySelectId' => 'annual_class',
                'selectionSpecialtyValue' => old('id')
            ))
            <div class="row">
                <div class="col-md-12 form-group">
                    <label for="annual_class">Spécialité</label>
                    <select class="form-control" name="id" id="annual_class" required disabled>
                        <option value="">Sélectionner une spécialité</option>
                        @foreach($classes as $cl)<option value="{{ $cl->id }}" data-filiere="{{ $cl->id_niveau }}">{{ $cl->getNom() }}</option>@endforeach
                    </select>
                </div>
                <div class="col-md-4 form-group"><label for="annual_start">Date de début</label><input class="form-control" type="date" id="annual_start" name="periode_debut" value="{{ old('periode_debut') }}" required></div>
                <div class="col-md-4 form-group"><label for="annual_end">Date de fin</label><input class="form-control" type="date" id="annual_end" name="periode_fin" value="{{ old('periode_fin') }}" required></div>
                <div class="col-md-4 form-group"><label for="annual_signature">Date de signature</label><input class="form-control" type="date" id="annual_signature" name="date_signature" value="{{ old('date_signature') }}" required></div>
            </div>
            <p class="help-block">La période inclut les notes enregistrées entre la date de début et la date de fin incluse. Les notes sans date d’enregistrement ne sont pas retenues. La date de signature apparaîtra sous les tableaux.</p>
            <button type="submit" class="btn btn-success"><span class="glyphicon glyphicon-print"></span> Générer et sauvegarder les relevés {{ $documentLabel }}</button>
        </form>
    </div>
</div>
<script>
(function () {
    var start = document.getElementById('annual_start'), end = document.getElementById('annual_end');
    start.onchange = function () { end.min = start.value; }; end.min = start.value;
})();
</script>
@endsection
