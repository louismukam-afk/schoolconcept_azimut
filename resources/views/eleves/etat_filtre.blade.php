<form method="GET" action="{{ route('etat.discipline.generate') }}">

    <div class="row">

        <div class="col-md-2">
            <label>Cycle</label>
            <select name="id_cycle" class="form-control" required>
                @foreach($cycles as $c)
                    <option value="{{ $c->id }}">{{ $c->nom }}</option>
                @endforeach
            </select>
        </div>

        <div class="col-md-2">
            <label>Filière</label>
            <select name="id_niveau" class="form-control" required>
                @foreach($niveaux as $n)
                    <option value="{{ $n->id }}">{{ $n->nom }}</option>
                @endforeach
            </select>
        </div>

        <div class="col-md-2">
            <label>Niveau</label>
            <select name="id_n_niveau" class="form-control" required>
                @foreach($n_niveaux as $nn)
                    <option value="{{ $nn->id }}">{{ $nn->nom }}</option>
                @endforeach
            </select>
        </div>

        <div class="col-md-2">
            <label>Spécialité</label>
            <select name="id_classe" class="form-control" required>
                @foreach($classes as $cl)
                    <option value="{{ $cl->id }}">{{ $cl->nom }}</option>
                @endforeach
            </select>
        </div>

        <div class="col-md-2">
            <label>Date début</label>
            <input type="date" name="date_debut" class="form-control" required>
        </div>

        <div class="col-md-2">
            <label>Date fin</label>
            <input type="date" name="date_fin" class="form-control" required>
        </div>

        <div class="col-md-2">
            <label>Année académique</label>
            <input type="text" name="annee_scolaire"
                   class="form-control"
                   value="{{ session('year') }}" required>
        </div>

    </div>

    <br>
    <button class="btn btn-primary">
        Générer l'état
    </button>

</form>
