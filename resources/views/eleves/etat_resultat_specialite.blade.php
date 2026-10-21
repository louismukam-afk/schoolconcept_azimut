@extends('skeleton')

@section('sub-content')
    <a href="#" class="btn btn-lg btn-warning" onclick="window.print()">
        <span class="glyphicon glyphicon-print"></span> @lang('inscription.imprimer')
    </a>
    @include('perso.header2')
    <div class="well">
        <h3>
            État des absences – {{ $classe->nom }}
        </h3>

        <strong>Période :</strong>
        {{ $date_debut }} au {{ $date_fin }} <br>

        <strong>Année académique :</strong>
        {{ session('year') }}
    </div>

    <div class="text-right" style="margin-bottom:15px;">

        <a href="{{ route('appel.etat.periode', $classe->id) }}"
           class="btn btn-default">
            <span class="fa fa-arrow-left"></span>
            Retour
        </a>



    </div>

    @if(count($data) > 0)

        <table class="table table-bordered table-striped table-condensed">
            <thead>
            <tr>
                <th>#</th>
                <th>Nom étudiant</th>
                <th>Cycle</th>
                <th>Filière</th>
                <th>Niveau</th>
                <th>Total heures d'absence</th>
            </tr>
            </thead>
            <tbody>

            <?php
                $i = 1;
                $totalGeneral = 0;
            ?>

            @foreach($data as $row)

                <?php $totalGeneral += $row->total_absence; ?>

                <tr>
                    <td>{{ $i++ }}</td>
                    <td><strong>{{ $row->nom }} {{ $row->prenom }}</strong></td>
                    <td>{{ $row->cycle }}</td>
                    <td>{{ $row->filiere }}</td>
                    <td>{{ $row->sous_niveau }}</td>
                    <td class="text-center">
                        <strong>{{ $row->total_absence }}</strong>
                    </td>
                </tr>

            @endforeach

            </tbody>

            <tfoot>
            <tr class="info">
                <th colspan="5" class="text-right">
                    TOTAL GÉNÉRAL DES HEURES
                </th>
                <th class="text-center">
                    {{ $totalGeneral }}
                </th>
            </tr>
            </tfoot>

        </table>

    @else

        <div class="alert alert-warning">
            Aucun résultat trouvé pour cette période.
        </div>

    @endif

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
        <strong>État des absences</strong>
    </li>

@endsection
