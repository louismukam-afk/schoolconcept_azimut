@extends('skeleton')

@section('sub-content')

    <a href="#" class="btn btn-lg btn-warning" onclick="window.print()">
        <span class="glyphicon glyphicon-print"></span> @lang('inscription.imprimer')
    </a>

    @include('perso.header2')

    <h3 style="text-align: center;">Etat détaillé des absences</h3>

    <div class="well">
        <strong>Cycle :</strong>
        {{ isset($cycle) ? $cycle->nom : '' }} |

        <strong>Filière :</strong>
        {{ isset($niveau) ? $niveau->nom : '' }} |

        <strong>Niveau :</strong>
        {{ isset($n_niveau) ? $n_niveau->nom : '' }} |

        <strong>Spécialité :</strong> {{ $classe->nom }} |
        <strong>Période :</strong> {{ $date_debut }} au {{ $date_fin }}
    </div>

    @if(count($tableau) > 0)

        <table class="table table-bordered table-condensed">

            <thead>
            <tr>
                <th width="40">#</th>
                <th>Nom étudiant</th>

                @foreach($horaires as $h)
                    <th>{{ $h->intervalle }}</th>
                @endforeach

                <th>Total</th>
            </tr>
            </thead>

            <tbody>

                <?php
                $i = 1;
                $totalGeneral = 0;

                // Initialiser totaux par plage
                $totalHeuresParPlage = [];
                $totalEtudiantsParPlage = [];

                foreach($horaires as $h){
                    $totalHeuresParPlage[$h->id] = 0;
                    $totalEtudiantsParPlage[$h->id] = 0;
                }
                ?>

            @foreach($tableau as $id => $data)

                <tr>

                    <td align="center">{{ $i++ }}</td>

                    <td>{{ $data['nom'] }} {{ $data['prenom'] }}</td>

                    @foreach($horaires as $h)

                            <?php
                            $val = isset($data['heures'][$h->id]) ? $data['heures'][$h->id] : 0;

                            // Addition heures
                            $totalHeuresParPlage[$h->id] += $val;

                            // Compter étudiant absent si > 0
                            if($val > 0){
                                $totalEtudiantsParPlage[$h->id] += 1;
                            }
                            ?>

                        <td align="center">
                            {{ $val }}
                        </td>

                    @endforeach

                        <?php
                        $totalGeneral += $totauxEleve[$id];
                        ?>

                    <td align="center">
                        <strong>{{ $totauxEleve[$id] }}</strong>
                    </td>

                </tr>

            @endforeach

            </tbody>

            {{-- LIGNE TOTAL ETUDIANTS ABSENTS --}}
            <tfoot>

            <tr style="background:#f5f5f5; font-weight:bold;">
                <td colspan="2" align="right">
                    Nombre d'étudiants absents par plage :
                </td>

                @foreach($horaires as $h)
                    <td align="center">
                        {{ $totalEtudiantsParPlage[$h->id] }}
                    </td>
                @endforeach

                <td></td>
            </tr>

            {{-- LIGNE TOTAL HEURES --}}
            <tr style="background:#e9ecef; font-weight:bold;">
                <td colspan="2" align="right">
                    Total heures d'absence par plage :
                </td>

                @foreach($horaires as $h)
                    <td align="center">
                        {{ $totalHeuresParPlage[$h->id] }}
                    </td>
                @endforeach

                <td align="center">
                    {{ $totalGeneral }}
                </td>
            </tr>

            </tfoot>

        </table>

    @else

        <div class="alert alert-warning">
            Aucun étudiant absent sur cette période.
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

    {{-- Retour vers choix spécialité --}}
    <li>
        <a href="{{ route('appel.specialites_etat') }}">
            Choix spécialité
        </a>
    </li>

    {{-- Retour vers sélection période détaillée --}}
    <li>
        <a href="{{ route('appel.etat.periode.detaille', $classe->id) }}">
            Sélection période
        </a>
    </li>

    <li class="active">
        <strong>État détaillé</strong>
    </li>

@endsection
