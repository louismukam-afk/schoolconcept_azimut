@extends('skeleton')

@section('sub-content')
    <a href="#" class="btn btn-lg btn-warning" onclick="window.print()">
        <span class="glyphicon glyphicon-print"></span> @lang('inscription.imprimer')
    </a>
    @include('perso.header2')
    <div class="well">
        <h3 style="text-align:center;">
            LISTE DES ABSENCES
            <br>
            Du {{ $date_debut }} au {{ $date_fin }}
        </h3>
    </div>

    <table class="table table-bordered table-striped">
        <thead>
        <tr>
            <th>N°</th>
            <th>Nom & Prénom</th>
            <th>Spécialité |Cycle| Filiere |Niveau</th>
            <th>Type de manquement</th>
            <th>Nombre d'heure absence</th>
            <th>Décision</th>
            <th>Observation</th>
        </tr>
        </thead>

        <tbody>
        @foreach($etat as $index => $e)
            <tr>
                <td>{{ $index + 1 }}</td>

                <td>
                    <strong>{{ $e->nom }} {{ $e->prenom }}</strong>
                </td>

                <td>
                    {{ $e->classe }} <br>
                    {{ $e->cycle }} - {{ $e->niveau }} - {{ $e->n_niveaus }}
                </td>

                <td>ABSENCE</td>

                <td>{{ $e->total_absence }} H</td>

                <td>RAS</td>

                <td>//</td>
            </tr>
        @endforeach
        </tbody>


    </table>
    <p style="text-align: right; right: 0;">
    <h4>Yaounde le </h4>
    <strong>La Direction</strong>
    </p>
@endsection
@section('breadcrumb')

    {{-- Accueil discipline --}}
    <li>
        <strong>
            <a href="{{ url('eleves') }}">
                @lang('menu.gestion_notes')
            </a>
        </strong>
    </li>

    <li>
        <a href="{{ route('appel.specialites_etat') }}">
            GENERER UN NOUVEAU ETATS
        </a>
    </li>
    {{-- Choix cycle / niveau --}}
    @if(isset($classe))
        <li>
            <a href="{{ route('appel.specialite', $classe->id) }}">
                Choix cycle / niveau
            </a>
        </li>
    @endif

    {{-- Sélection jour --}}
    @if(isset($classe))
        <li>
            <a href="{{ route('appel.selection', $classe->id) }}">
                Sélection jour
            </a>
        </li>
    @endif

    {{-- Page actuelle --}}
    <li class="active">
        <strong>Etats de discipline</strong>
    </li>

@endsection
