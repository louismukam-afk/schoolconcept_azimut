@extends('skeleton')

@section('sub-content')

    <h3>Générer état d'absence</h3>
    <h4>Spécialité : <strong>{{ $classe->nom }}</strong></h4>

    <form method="POST" action="{{ route('appel.etat.generer.specialite', $classe->id) }}">
        <input type="hidden" name="_token" value="{{ csrf_token() }}"/>
        <div class="row">
            <div class="col-md-3">
            {{-- Cycle --}}
            <select name="id_cycle" id="cycle" class="form-control" required>
                <option value="">-- Choisir cycle --</option>
                @foreach($cycles as $cycle)
                    <option value="{{ $cycle->id }}">
                        {{ $cycle->nom }}
                    </option>
                @endforeach
            </select>
            </div>

            {{-- Filière / Niveau --}}
            <div class="col-md-3">
                <label>Niveau</label>
                <select name="id_n_niveau" id="n_niveau" class="form-control" required>
                    <option value="">-- Choisir Niveau --</option>
                </select>
              {{--  <select name="id_n_niveau" class="form-control" required>
                    <option value="">-- Choisir niveau --</option>
                    @foreach($n_niveaus as $nn)
                        <option value="{{ $nn->id }}">
                            {{ $nn->nom }}
                        </option>
                    @endforeach
                </select>--}}
            </div>


            {{-- Date début --}}
            <div class="col-md-3">
                <label>Date début</label>
                <input type="date" name="date_debut" class="form-control" required>
            </div>

            {{-- Date fin --}}
            <div class="col-md-3">
                <label>Date fin</label>
                <input type="date" name="date_fin" class="form-control" required>
            </div>

        </div>

        <br>

        <button type="submit" class="btn btn-success">
            <span class="fa fa-search"></span>
            Générer l'état
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
