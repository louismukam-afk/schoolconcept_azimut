@extends('skeleton')

@section('sub-content')

    <h3>Etat détaillé des absences</h3>
    <h4>Spécialité : {{ $classe->nom }}</h4>

    <form method="POST" action="{{ route('appel.etat.generer.detaille', $classe->id) }}">
        <input type="hidden" name="_token" value="{{ csrf_token() }}"/>
        <input type="hidden" name="id_cycle" value="{{ session('id_cycle') }}">
{{--        <input type="hidden" name="id_niveau" value="{{ session('id_niveau') }}">--}}
        <input type="hidden" name="id_niveau" value="{{ $niveau->id }}">
        <input type="hidden" name="id_n_niveau" value="{{ session('id_n_niveau') }}">
        <div class="row">

            <div class="col-md-3">
                <label>Cycle</label>
                <select name="id_cycle" id="cycle" class="form-control" required>
                    <option value="">-- Choisir cycle --</option>
                    @foreach($cycles as $cycle)
                        <option value="{{ $cycle->id }}">
                            {{ $cycle->nom }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3">
                <label>Niveau</label>
                <select name="id_n_niveau" class="form-control"  id="n_niveau" required>
                    <option value="">-- Choisir niveau --</option>
                </select>
            </div>

            <div class="col-md-3">
                <label>Date début</label>
                <input type="date" name="date_debut" class="form-control" required>
            </div>

            <div class="col-md-3">
                <label>Date fin</label>
                <input type="date" name="date_fin" class="form-control" required>
            </div>

        </div>

        <br>

        <button type="submit" class="btn btn-success">
            Générer état détaillé
        </button>

    </form>
    <script>
        document.getElementById('cycle').addEventListener('change', function() {

            let cycle_id = this.value;
            let nNiveauSelect = document.getElementById('n_niveau');

            nNiveauSelect.innerHTML = '<option value="">Chargement...</option>';

            fetch("{{ url('/eleves/etat/n_niveaux') }}/" + cycle_id)
                .then(response => response.json())
                .then(data => {

                    nNiveauSelect.innerHTML = '<option value="">-- Choisir niveau --</option>';

                    data.forEach(function(niveau) {
                        nNiveauSelect.innerHTML +=
                            '<option value="'+niveau.id+'">'+niveau.nom+'</option>';
                    });
                });
        });
    </script>
@endsection
@section('breadcrumb')

    <li>
        <strong>
            <a href="{{ url('eleves') }}">
                @lang('menu.gestion_notes')
            </a>
        </strong>
    </li>

    <li>
        <a href="{{ route('appel.specialites_etat') }}">
            Choix spécialité
        </a>
    </li>

    <li class="active">
        <strong>Sélection période</strong>
    </li>

@endsection
