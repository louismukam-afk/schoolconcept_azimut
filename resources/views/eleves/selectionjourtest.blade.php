@extends('skeleton')

@section('sub-content')

    <h3>Sélection du jour - {{ $classe->nom }}</h3>

    <form method="POST" action="{{url('/eleves/appel/formulaire')}}">
        {{--    <form method="POST" action="{{ route('appel.formulaire') }}">--}}
        <input type="hidden" name="_token" value="{{ csrf_token() }}">
        <input type="hidden" name="id_classe" value="{{ $classe->id }}">
        <input type="hidden" name="id_cycle" value="{{ $id_cycle }}">
        <input type="hidden" name="id_n_niveau" value="{{ $id_n_niveau }}">
        <input type="hidden" name="id_niveau" value="{{ $id_niveau }}">


        <div class="row">

            <div class="col-md-3">
                <label>Date</label>
                <input type="date" name="date_discipline" class="form-control" required>
            </div>

            <div class="col-md-3">
                <label>Jour</label>
                <select name="id_jour" class="form-control" required>
                    @foreach($jours as $j)
                        <option value="{{ $j->id }}">{{ $j->nom }}</option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-3">
                <label>Plage horaire</label>
                <select name="id_horaire" class="form-control" required>
                    @foreach($horaires as $h)
                        <option value="{{ $h->id }}">{{ $h->intervalle }}</option>
                    @endforeach
                </select>
            </div>

        </div>

        <br>

        <button type="submit" class="btn btn-primary">
            Continuer vers le formulaire
        </button>

    </form>

@endsection
