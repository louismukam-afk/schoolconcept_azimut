@extends('skeleton')
@section('sub-content')
    <div class="btn-toolbar" style="margin-bottom:15px;"><a href="{{ url('/eleves/pv_hnd') }}" class="btn btn-default">Changer de spécialité</a><a href="{{ url('/eleves') }}" class="btn btn-default">Accueil élèves</a></div>
    <div class="alert alert-info"><strong>{{ $cycle->nom }} / {{ $nniveau->nom }}</strong> — {{ $filiere ? $filiere->nom : '' }} / {{ $classe->nom }}</div>
    @if($errors->any())
        <div class="alert alert-danger"><ul>@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul></div>
    @endif
    <form method="GET" action="{{ url('/eleves/pv_hnd/print') }}">
        <input type="hidden" name="id" value="{{ $classe->id }}"><input type="hidden" name="cycle" value="{{ $cycle->id }}"><input type="hidden" name="nniveau" value="{{ $nniveau->id }}">
        <div class="panel panel-default"><div class="panel-heading"><strong>Période du procès-verbal</strong></div><div class="panel-body">
            <div class="row">
                <div class="col-md-4 form-group"><label for="hnd_year">Année académique</label><select id="hnd_year" name="annee" class="form-control" required>
                    @foreach($annees as $year)<option value="{{ $year }}" {{ $annee == $year ? 'selected' : '' }}>{{ $year }}</option>@endforeach
                </select></div>
                <div class="col-md-4 form-group"><label for="hnd_start">Date de début</label><input type="date" name="date_debut" id="hnd_start" class="form-control" value="{{ old('date_debut') }}"></div>
                <div class="col-md-4 form-group"><label for="hnd_end">Date de fin</label><input type="date" name="date_fin" id="hnd_end" class="form-control" value="{{ old('date_fin') }}"></div>
            </div>
            <div class="form-group"><label for="hnd_paper">Mise en page</label><select id="hnd_paper" name="papier" class="form-control" style="max-width:300px;">
                @foreach($papers as $key => $label)<option value="{{ $key }}" {{ $key == 'a3' ? 'selected' : '' }}>{{ $label }}</option>@endforeach
            </select><p class="help-block">Modèle d'origine conservé : lignes CA / EXAM, note sur 100, note retenue et crédit par matière, puis total des crédits et décision. Le format choisi adapte le nombre de matières par bloc pour conserver des colonnes lisibles.</p></div>
            <p class="help-block">Dates facultatives : laissez les deux champs vides pour toute l'année. La période filtre les notes de l'examen choisi selon leur date d'enregistrement ou de dernière modification ; les notes de contrôle continu restent prises en compte.</p>
        </div></div>
        <div class="row">
            @foreach($exams as $key => $exam)
                <div class="{{ $key == 'annual' ? 'col-md-12' : 'col-md-6' }}" style="margin-bottom:15px;"><button type="submit" name="examen" value="{{ $key }}" class="btn {{ $key == 'annual' ? 'btn-success' : ($exam['sequence'] >= 5 ? 'btn-warning' : 'btn-primary') }} btn-lg btn-block"><span class="glyphicon glyphicon-list-alt"></span> {{ $exam['label'] }}</button></div>
            @endforeach
        </div>
        <p class="help-block">Le PV annuel regroupe les deux semestres, retient la meilleure note entre session normale et rattrapage et affiche uniquement les résultats sur 100. La moyenne annuelle sur 20 est affichée après validation de toutes les matières.</p>
        <div class="panel panel-success">
            <div class="panel-heading"><strong>Deuxième version — modèle du PV annuel</strong></div>
            <div class="panel-body">
                <div class="form-group"><label for="compact_session">Session à produire</label><select id="compact_session" name="session_synthetique" class="form-control" style="max-width:350px;">
                    <option value="normale">Examen de fin de semestre</option><option value="rattrapage">Rattrapage</option>
                </select></div>
                <p>Une ligne par étudiant : notes finales sur 100, moyenne sur 20 après validation totale, crédits, rang et décision. Les notes CC, SN et rattrapage ne sont pas détaillées.</p>
                <div class="row">
                    <div class="col-md-6" style="margin-bottom:10px;"><button type="submit" name="semestre_synthetique" value="1" class="btn btn-success btn-lg btn-block">PV synthétique — Semestre 1</button></div>
                    <div class="col-md-6" style="margin-bottom:10px;"><button type="submit" name="semestre_synthetique" value="2" class="btn btn-success btn-lg btn-block">PV synthétique — Semestre 2</button></div>
                </div>
                <p class="help-block">Les paramètres d'année, de dates et de format A4/A3 ci-dessus s'appliquent aussi à cette version. En rattrapage, la meilleure note entre session normale et rattrapage est retenue.</p>
            </div>
        </div>
    </form>
    <script>
        document.getElementById('hnd_start').addEventListener('change', function () { document.getElementById('hnd_end').min = this.value; });
    </script>
@endsection
@section('breadcrumb')
    <li><a href="{{ url('/eleves') }}">Élèves</a></li><li><a href="{{ url('/eleves/pv_hnd') }}">PV HND</a></li><li class="active">Choix de l'examen</li>
@endsection
