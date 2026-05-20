@extends('skeleton')

@section('sub-content')
    @include('perso.functions')

    <?php
    // Indexer les PV par élève
    $PVIndex = array();
    if (!empty($PV)) {
        foreach ($PV as $p) {
            if (isset($p->id_eleve)) {
                $PVIndex[$p->id_eleve] = $p;
            }
        }
    }
    ?>

    <style>
        .note-column { text-align:center; }
        .gris { background:#e0e0e0; color:#666; }
    </style>

    <style media="print">
        #note-excel, .dt-buttons,
        #note-excel_filter,
        #note-excel_info,
        #note-excel_paginate { display:none; }
    </style>
    <style>
        .table-ecran { display:block; }
        .table-print { display:block; }
        .note-column { text-align:center; }
        .gris { background:#e0e0e0; color:#777; }
    </style>

    <style media="print">
        body * { visibility:hidden; }

        .table-print,
        .table-print * {
            visibility:visible;
        }

        .table-print {
            position:absolute;
            top:0;
            left:0;
            width:100%;
        }

        .table-ecran { display:none; }
        .btn, .dt-buttons { display:none !important; }
    </style>


    <a href="#" class="btn btn-warning" onclick="window.print()">
        <i class="glyphicon glyphicon-print"></i> Imprimer
    </a>

    <div class="titre">
        <strong>PROCÈS VERBAL – SEMESTRE {{ $semestre }}</strong><br>
        Année académique : {{ $annee }}
    </div>

    <div class="classe">
        Spécialité : {{ $classe->nom }} — Niveau {{ $nniveau->nom }}
    </div>

    <div class="table">
        <table id="note-excel" class="table table-bordered table-striped table-condensed">
            <thead>
            <tr>
                <th>#</th>
                <th>Matricule</th>
                <th>Nom</th>

                @foreach($matieres as $m)
                    <th>{{ $m->nom }}</th>
                @endforeach

                <th>Cr prévus</th>
                <th>Cr validés</th>
                <th>Matières non validées</th>
                <th>Moy /20</th>
                <th>Rang</th>
                <th>MGP</th>
            </tr>
            </thead>

            <tbody>
            <?php
            // ===============================
            // CALCUL DES MOYENNES POUR LE RANG
            // ===============================
            $moyennes = [];

            foreach ($eleves as $e) {
                $pv = isset($PVIndex[$e->id]) ? $PVIndex[$e->id] : null;

                $sum = 0;
                $count = 0;
                $allValidated = true;

                if ($pv && !empty($pv->matieres)) {
                    foreach ($pv->matieres as $mm) {
                        $parts = [];
                        if (isset($mm->cc))  $parts[] = $mm->cc;
                        if (isset($mm->sn))  $parts[] = $mm->sn;
                        if (isset($mm->tpe)) $parts[] = $mm->tpe;

                        if (count($parts) === 0) {
                            $allValidated = false;
                            break;
                        }

                        $total = array_sum($parts) / count($parts);
                        if ($total < 10) {
                            $allValidated = false;
                            break;
                        }

                        $sum += $total;
                        $count++;
                    }
                } else {
                    $allValidated = false;
                }

                $moyennes[$e->id] = ($allValidated && $count > 0)
                    ? round($sum / $count, 2)
                    : null;
            }

            // ===============================
            // CLASSEMENT
            // ===============================
            arsort($moyennes, SORT_NUMERIC);

            $rangs = [];
            $rang = 0;
            $position = 0;
            $previous = null;

            foreach ($moyennes as $id_eleve => $moy) {
                $position++;

                if ($moy === null) {
                    $rangs[$id_eleve] = null;
                    continue;
                }

                if ($previous === null || $moy < $previous) {
                    $rang = $position;
                }

                $rangs[$id_eleve] = $rang;
                $previous = $moy;
            }
            ?>

            <?php $i = 1; ?>
            @foreach($eleves as $e)

                <?php

                $pv = isset($PVIndex[$e->id]) ? $PVIndex[$e->id] : null;

                $sumSemestre = 0;
                $nbMatieres = 0;
                $creditsPrevu = 0;
                $creditsValide = 0;
                $allValidated = true;
                $matieresNonValidees = array();
                ?>

                <tr>
                    <td>{{ $i++ }}</td>
                    <td>{{ $e->matricule }}</td>
                    <td>{{ $e->nom }} {{ $e->prenom }}</td>

                    @foreach($matieres as $m)
                        <?php
                        $creditsPrevu += isset($m->credit) ? $m->credit : 0;
                        $nbMatieres++;

                        $cc = $sn = $tpe = null;
                        $total20 = null;

                        if ($pv && !empty($pv->matieres)) {
                            foreach ($pv->matieres as $mm) {
                                if ($mm->id_matiere == $m->id) {
                                    $cc  = isset($mm->cc)  ? $mm->cc  : null;
                                    $sn  = isset($mm->sn)  ? $mm->sn  : null;
                                    $tpe = isset($mm->tpe) ? $mm->tpe : null;
                                    break;
                                }
                            }
                        }

                        // CAS : aucune note
                        if ($cc === null && $sn === null && $tpe === null) {
                            $allValidated = false;
                            $matieresNonValidees[] = $m->nom;
                        }
// CAS : calcul du total
                        else {
                            $parts = array();
                            if ($cc !== null)  $parts[] = $cc;
                            if ($sn !== null)  $parts[] = $sn;
                            if ($tpe !== null) $parts[] = $tpe;

                            $total20 = round(array_sum($parts) / count($parts), 2);
                            $sumSemestre += $total20;

                            if ($total20 >= 10) {
                                $creditsValide += isset($m->credit) ? $m->credit : 0;
                            } else {
                                $allValidated = false;
                                $matieresNonValidees[] = $m->nom;
                            }
                        }
                        ?>

                        <td class="note-column">
                            @if($total20 !== null)
                                @if($total20 < 10)
                                    <span style="background:red;color:#fff;padding:2px 6px">
            {{ number_format($total20,2) }}
        </span>
                                @else
                                    <span style="background:green;color:#fff;padding:2px 6px">
            {{ number_format($total20,2) }}
        </span>
                                @endif
                            @else
                                -
                            @endif
                        </td>
                    @endforeach

                    <td class="note-column">{{ $creditsPrevu }}</td>
                    <td class="note-column">{{ $creditsValide }}</td>

                    <td>
                        @if(count($matieresNonValidees) > 0)
                            {{ implode(', ', $matieresNonValidees) }}
                        @else
                            -
                        @endif
                    </td>

                    <?php
                    $moy = ($allValidated && $nbMatieres > 0)
                        ? round($sumSemestre / $nbMatieres, 2)
                        : null;
                    ?>

                    <td class="note-column {{ $moy === null ? 'gris' : '' }}">
                        {{ $moy !== null ? number_format($moy,2) : '-' }}
                    </td>
                    <td class="note-column {{ ($moy === null) ? 'gris' : '' }}">
                        @if ($moy !== null)
                            {{ isset($rangs[$e->id]) ? $rangs[$e->id] : '-' }}
                        @else
                            -
                        @endif
                    </td>


                    <td class="note-column {{ $moy === null ? 'gris' : '' }}">
                        {{ $moy !== null ? number_format($moy/5,2) : '-' }}
                    </td>

                </tr>
            @endforeach
            </tbody>
        </table>
    </div>

    <div class="table table-print">
        <center>
        <div class="titre">
            <h2>INSTITUT SUPERIEUR HINTEL</h2>
            <strong>PROCÈS VERBAL – SEMESTRE {{ $semestre }}</strong><br>
            Année académique : {{ $annee }}
        </div>

        <div class="classe">
            Spécialité : {{ $classe->nom }} — Niveau {{ $nniveau->nom }}
        </div>
        </center>
        <table class="table table-bordered table-condensed">
            <thead>
            <tr>
                <th>#</th>
                <th>Matricule</th>
                <th>Nom</th>

                @foreach($matieres as $m)
                    <th colspan="4">
                        {{ $m->nom }} <br>
                        <small>Cr : {{ $m->credit }}</small>
                    </th>
                @endforeach

                <th>Cr prévus</th>
                <th>Cr validés</th>
                <th>Matières non validées</th>
                <th>Moy /20</th>
                <th>Rang</th>
            </tr>

            <tr>
                <th></th><th></th><th></th>
                @foreach($matieres as $m)
                    <th>CC</th>
                    <th>SN</th>
                    <th>TPE</th>
                    <th>Total /20</th>
                @endforeach
                <th></th><th></th><th></th><th></th>
            </tr>
            </thead>

            <tbody>
            <?php $i = 1; ?>
            @foreach($eleves as $e)

                <?php
                $pv = isset($PVIndex[$e->id]) ? $PVIndex[$e->id] : null;

                $creditPrevu   = 0;
                $creditValide  = 0;
                $sommeTotaux   = 0;
                $nbMatieres    = 0;
                $toutesValidees = true;
                $matieresNonValidees = array();
                ?>

                <tr>
                    <td>{{ $i++ }}</td>
                    <td>{{ $e->matricule }}</td>
                    <td>{{ $e->nom }} {{ $e->prenom }}</td>

                    @foreach($matieres as $m)
                        <?php
                        $creditPrevu += $m->credit;
                        $matNote = null;

                        if ($pv && !empty($pv->matieres)) {
                            foreach ($pv->matieres as $mm) {
                                if ($mm->id_matiere == $m->id) {
                                    $matNote = $mm;
                                    break;
                                }
                            }
                        }

                        $cc  = ($matNote && isset($matNote->cc))  ? $matNote->cc  : null;
                        $sn  = ($matNote && isset($matNote->sn))  ? $matNote->sn  : null;
                        $tpe = ($matNote && isset($matNote->tpe)) ? $matNote->tpe : null;

                        $sum = 0;
                        $parts = 0;
                        if ($cc !== null)  { $sum += $cc;  $parts++; }
                        if ($sn !== null)  { $sum += $sn;  $parts++; }
                        if ($tpe !== null) { $sum += $tpe; $parts++; }

                        $total20 = ($parts > 0) ? round($sum / $parts, 2) : null;

                        if ($total20 === null || $total20 < 10) {
                            $toutesValidees = false;
                            $matieresNonValidees[] = $m->nom;
                        } else {
                            $creditValide += $m->credit;
                        }

                        if ($total20 !== null) {
                            $sommeTotaux += $total20;
                            $nbMatieres++;
                        }
                        ?>

                        <td class="note-column">{{ $cc !== null ? number_format($cc,2) : '-' }}</td>
                        <td class="note-column">{{ $sn !== null ? number_format($sn,2) : '-' }}</td>
                        <td class="note-column">{{ $tpe !== null ? number_format($tpe,2) : '-' }}</td>

                        <td class="note-column">
                            @if($total20 !== null)
                                @if($total20 < 10)
                                    <span style="background:red;color:#fff;padding:2px 6px">
                                    {{ number_format($total20,2) }}
                                </span>
                                @else
                                    <span style="background:green;color:#fff;padding:2px 6px">
                                    {{ number_format($total20,2) }}
                                </span>
                                @endif
                            @else
                                -
                            @endif
                        </td>

                    @endforeach

                    <?php
                    $moyGenerale = null;
                    if ($toutesValidees && $nbMatieres > 0) {
                        $moyGenerale = round($sommeTotaux / $nbMatieres, 2);
                    }
                    ?>

                    <td class="note-column">{{ $creditPrevu }}</td>
                    <td class="note-column">{{ $creditValide }}</td>

                    <td style="font-size:11px">
                        {{ count($matieresNonValidees) ? implode(', ', $matieresNonValidees) : '-' }}
                    </td>

                    <td class="note-column {{ $moyGenerale === null ? 'gris' : '' }}">
                        {{ $moyGenerale !== null ? number_format($moyGenerale,2) : '-' }}
                    </td>
                    <td class="note-column {{ ($moyGenerale === null) ? 'gris' : '' }}">
                        @if ($moyGenerale !== null)
                            {{ isset($rangs[$e->id]) ? $rangs[$e->id] : '-' }}
                        @else
                            -
                        @endif
                    </td>

                </tr>
            @endforeach
            </tbody>
        </table>
    </div>

    <script>
        $('#note-excel').dataTable({
            dom: 'Bfrtip',
            pageLength: 2000,
            buttons: [{
                extend: 'excelHtml5',
                title: 'PV Semestre {{ $semestre }} - {{ $classe->nom }} {{ $nniveau->nom }}'
            }]
        });
    </script>

@endsection
@section('breadcrumb')
    <li><strong><a href="{{url('eleves')}}">@lang('menu.gestion_notes')</a></strong></li>
    <li><strong><a href="{{url('eleves/notes')}}">Notes</a></strong></li>

    <li><a href="{{url('eleves/notes/show')}}"><strong>@lang('admin.choix_classe')</strong></a></li>
    <li><a href="{{url('eleves/notes/matieres/show').'?id='.$classe->id.'&cycle='.$id_cycle.'&nniveau='.$nniveau->id}}"><strong>@lang('admin.titre_matiere')</strong></a></li>

    <li class="active"><strong>{{$titre}}</strong></li>
@endsection