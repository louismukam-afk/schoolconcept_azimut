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
            <div class="row">
                <div class="col-md-6 form-group">
                    <label for="annual_cycle">Cycle</label>
                    <select class="form-control" name="cycle" id="annual_cycle" required>
                        <option value="">Sélectionner un cycle</option>
                        @foreach($cycles as $cy)<option value="{{ $cy->id }}" @if(old('cycle') == $cy->id) selected @endif>{{ $cy->nom }}</option>@endforeach
                    </select>
                </div>
                <div class="col-md-6 form-group">
                    <label for="annual_level">Niveau</label>
                    <select class="form-control" name="nniveau" id="annual_level" required>
                        <option value="">Sélectionner un niveau</option>
                        @foreach($nniveaux as $nn)<option data-cycle="{{ $nn->id_cycle }}" value="{{ $nn->id }}" @if(old('nniveau') == $nn->id) selected @endif>{{ $nn->nom }}</option>@endforeach
                    </select>
                </div>
                <div class="col-md-12 form-group">
                    <label for="annual_class">Spécialité</label>
                    <select class="form-control" name="id" id="annual_class" required>
                        <option value="">Sélectionner une spécialité</option>
                        @foreach($classes as $cl)<option value="{{ $cl->id }}" @if(old('id') == $cl->id) selected @endif>{{ $cl->nom }}</option>@endforeach
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
    var cycle = document.getElementById('annual_cycle'), level = document.getElementById('annual_level');
    function filterLevels() {
        for (var i = 1; i < level.options.length; i++) {
            var allowed = level.options[i].getAttribute('data-cycle') === cycle.value;
            level.options[i].disabled = !allowed;
            level.options[i].hidden = !allowed;
            if (!allowed && level.options[i].selected) level.value = '';
        }
    }
    cycle.onchange = filterLevels; filterLevels();
    var start = document.getElementById('annual_start'), end = document.getElementById('annual_end');
    start.onchange = function () { end.min = start.value; }; end.min = start.value;
})();
</script>
@endsection
