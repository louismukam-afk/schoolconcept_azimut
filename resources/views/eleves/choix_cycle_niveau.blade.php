@extends('skeleton')

@section('sub-content')

    <h3>
        Spécialité : {{ $classe->nom }}
    </h3>

    <form method="POST" action="{{ route('appel.selection.jour') }}">
        <input type="hidden" name="_token" value="{{ csrf_token() }}">
        <input type="hidden" name="id_classe" value="{{ $classe->id }}">
     {{--  <input type="hidden" name="id_cycle" value="{{ $id_cycle->id }}">
        <input type="hidden" name="id_niveau" value="{{ $id_niveau->id }}">
        <input type="hidden" name="id_n_niveau" value="{{ $id_n_niveau->id }}">--}}

        <div class="row">

            <div class="col-md-4">
                <label>Cycle</label>
                <select name="id_cycle" id="cycle" class="form-control" required>
                    <option value="">-- Choisir Cycle --</option>
                    @foreach($cycles as $c)
                        <option value="{{ $c->id }}">{{ $c->nom }}</option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-4">
                <label>Niveau</label>
                <select name="id_n_niveau" id="niveau" class="form-control" required>
                    <option value="">-- Choisir Niveau --</option>
                </select>
            </div>

        </div>

        <br>

        <button type="submit" class="btn btn-success">
            Continuer
        </button>

    </form>
    <script>
        document.getElementById('cycle').addEventListener('change', function() {

            var cycle_id = this.value;

            fetch("{{ url('/eleves/appel/niveaux') }}/" + cycle_id)
                .then(response => response.json())
                .then(data => {

                    var niveauSelect = document.getElementById('niveau');
                    niveauSelect.innerHTML = '<option value="">-- Choisir Niveau --</option>';

                    data.forEach(function(niveau) {
                        niveauSelect.innerHTML +=
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

   {{-- --}}{{-- Liste des classes --}}{{--
    <li>
        <a href="{{ route('appel.classesok.get', [$id_cycle, $id_niveau, $id_n_niveau]) }}">
            choisir autres specialites de la filière
        </a>
    </li>
--}}
    {{-- Choix cycle / niveau --}}
 {{--   <li>
        <a href="{{ route('appel.specialite', $classe->id) }}">
            Choix cycle / niveau
        </a>
    </li>--}}
    {{-- Page choix spécialité --}}
    <li>
        <a href="{{ route('appel.specialitesok') }}">
            Choisir autres spécialités
        </a>
    </li>
    {{-- Sélection jour --}}
   {{-- <li>
        <a href="{{ route('appel.selection', $classe->id) }}">
            Sélection jour
        </a>
    </li>
--}}
    {{-- Page actuelle --}}
    <li class="active">
        <strong>Choix | Cycle | Niveau</strong>
    </li>

@endsection