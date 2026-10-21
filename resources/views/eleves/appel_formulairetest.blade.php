
@extends('skeleton')

@section('sub-content')

    @if(session('success'))
        <div class="alert alert-success">
            Appel enregistré avec succès
        </div>
    @endif

    <h3>
        Fiche d'appel
    </h3>

    <div class="well">
        <strong>Classe :</strong> {{ $classe->nom }} |
        <strong>Cycle :</strong> {{ isset($cycle->nom) ? $cycle->nom : '' }} |
        <strong>Flière :</strong> {{ isset($niveau->nom) ? $niveau->nom : '' }} |
        <strong>Niveau :</strong> {{ isset($n_niveau->nom) ? $n_niveau->nom : '' }}

        <strong>Date :</strong> {{ $date_discipline }}
    </div>

    <form method="POST" action="{{ route('appel.store') }}">
        <input type="hidden" name="_token" value="{{ csrf_token() }}">
        <input type="hidden" name="id_classe" value="{{ $classe->id }}">
        <input type="hidden" name="id_cycle" value="{{ $id_cycle }}">
        <input type="hidden" name="id_jour" value="{{ $id_jour }}">
        <input type="hidden" name="id_niveau" value="{{ $id_niveau }}">
        <input type="hidden" name="id_n_niveau" value="{{ $id_n_niveau }}">
        <input type="hidden" name="date_discipline" value="{{ $date_discipline }}">

        <table class="table table-bordered text-center">
            <thead>
            <tr>
                <th>#</th>
                <th>Nom étudiant</th>

                @foreach($horaires as $horaire)
                    <th>{{ $horaire->intervalle }}</th>
                @endforeach
            </tr>
            </thead>

            <tbody>
            @foreach($eleves as $i => $e)
                <tr>
                    <td>{{ $i+1 }}</td>
                    <td>{{ $e->nom }} {{ $e->prenom }}</td>

                    @foreach($horaires as $horaire)
                        <td>
                            <label>
                                <input type="radio"
                                       name="presence[{{ $e->id }}][{{ $horaire->id }}]"
                                       value="0"
                                       checked> P
                            </label>

                            <label>
                                <input type="radio"
                                       name="presence[{{ $e->id }}][{{ $horaire->id }}]"
                                       value="1"> A
                            </label>
                        </td>
                    @endforeach
                </tr>
            @endforeach
            </tbody>
        </table>

        <button type="submit" class="btn btn-success">
            Valider l'appel
        </button>

    </form>

@endsection
