@extends('skeleton')

@section('sub-content')

    <h3>
        Sélection du jour - {{ $classe->nom }}
    </h3>

    <div class="well">
        <strong>Cycle :</strong> {{ $cycle->nom }} |
        <strong>Filière :</strong> {{ $niveau->nom }} |
        <strong>Niveau :</strong> {{ $n_niveau->nom }}
    </div>

    <form method="POST" action="{{ route('appel.formulaire') }}">
        <input type="hidden" name="_token" value="{{ csrf_token() }}">
        <input type="hidden" name="id_classe" value="{{ $classe->id }}">
        <input type="hidden" name="id_cycle" value="{{ $id_cycle }}">
        <input type="hidden" name="id_niveau" value="{{ $id_niveau }}">
        <input type="hidden" name="id_n_niveau" value="{{ $id_n_niveau }}">

        <div class="row">

            <div class="col-md-4">
                <label>Date</label>
                <input type="date" name="date_discipline" class="form-control" required>
            </div>

            <div class="col-md-4">
                <label>Jour</label>
                <select name="id_jour" class="form-control" required>
                    @foreach($jours as $j)
                        <option value="{{ $j->id }}">{{ $j->nom }}</option>
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
@section('breadcrumb')

    <li>
        <strong>
            <a href="{{ url('eleves') }}">
                @lang('menu.gestion_notes')
            </a>
        </strong>
    </li>
    <li>
        <a href="{{ route('appel.specialite', $classe->id) }}">
            Choix cycle / niveau
        </a>
    </li>
    {{-- Page choix spécialité --}}
    <li>
        <a href="{{ route('appel.specialitesok') }}">
            Choisir autres  spécialités
        </a>
    </li>
   {{-- <li>
        <a href="{{ route('appel.classesok', [$id_cycle, $id_niveau]) }}">

            Classes
        </a>
    </li>--}}
    {{--<li>
        <a href="{{ route('appel.classes') }}">
            Classes
        </a>
    </li>--}}

    <li class="active">
        <strong>Sélection du jour</strong>
    </li>

@endsection
