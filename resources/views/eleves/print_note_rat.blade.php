@extends('skeleton')

@section('sub-content')
    @include('perso.functions')

    <a href="#" class="btn btn-lg btn-warning" onclick="window.print()">
        <span class="glyphicon glyphicon-print"></span> @lang('inscription.imprimer')
    </a>
    @include('perso.header')
    <link rel="stylesheet"  href="{{ URL::asset('css/note_matiere.css') }}">

    @php
        // Pourcentages envoyés par le contrôleur (0 par défaut)
        $pourcentage_cc  = isset($pourcentage_cc)  ? (float)$pourcentage_cc  : 0.0;
        $pourcentage_rat = isset($pourcentage_rat) ? (float)$pourcentage_rat : 0.0;

        $i          = 1;
        $totalNotes = 0;
        $notesQuant = 0;
        $notesCap   = 0;
        $echecs     = 0;
    @endphp

    <div class="titre">
        <p style="font-size: 19px">
            <strong>Examen de rattrapage du semestre {{ $semestre }} en {{ $matiere->nom }}</strong>
        </p>
        <strong class="traduction">
            Semester {{ $semestre }} resit exam of {{ $matiere->nom_anglais }}
        </strong>
    </div>

    <div class="classe">
        <div class="specialite">
            <p>
                <strong>Spécialité : {{ $classe->nom }} - Niveau {{ $nniveau->nom }}</strong>
            </p>
            <strong class="traduction">
                Speciality : {{ $classe->nom_anglais }} - Level {{ $nniveau->nom }}
            </strong>
        </div>

        <div class="filiere">
            <p>
                <strong>Filière: {{ \gesecole\niveau::find($classe->id_niveau)->nom }}</strong>
            </p>
            <strong class="traduction">
                Path : {{ \gesecole\niveau::find($classe->id_niveau)->nom_anglais }}
            </strong>
        </div>
    </div>

    <div class="table">
        <table class="table table-bordered table-striped table-condensed table-inverse" id="note-excel" style="margin-top: 5px;">
            <thead>
            <th class="number-column">#</th>
            <th class="mat-column">Matricule/Matricle</th>
            <th>Nom/Name</th>
            <th class="note-column">CC /20</th>
            <th class="note-column">Note R /20</th>
            <th class="note-column">Note /20</th>
            <th class="note-column">GP</th>
            <th class="note-column">Grade</th>
            </thead>

            <tbody>
            @foreach($eleves as $e)
                @php
                    $totalNotes++;
                    $total    = 0;      // somme des notes stockées (0–100)
                    $noteCc20 = null;   // affichage CC/20
                    $noteR20  = null;   // affichage R/20
                @endphp

                <tr>
                    <td class="number-column">{{ $i++ }}</td>
                    <td class="mat-column">{{ $e->matricule }}</td>
                    <td>{{ $e->nom.' '.$e->prenom }}</td>

                    {{-- CC /20 --}}
                    <td class="note-column">
                        @foreach($notes_cc as $n)
                            @if($n->id_eleve == $e->id)
                                @php
                                    if ($pourcentage_cc > 0) {
                                        // note stockée -> /20
                                        $noteCc20 = round($n->note * 20 / $pourcentage_cc, 2);
                                    } else {
                                        $noteCc20 = round($n->note, 2);
                                    }
                                    // on garde la somme sur base "note stockée"
                                    $total += $n->note;
                                @endphp
                                {{ $noteCc20 }}
                                @break
                            @endif
                        @endforeach
                    </td>

                    {{-- Rattrapage /20 --}}
                    <td class="note-column">
                        @foreach($notes as $n)
                            @if($n->id_eleve == $e->id)
                                @php
                                    if ($pourcentage_rat > 0) {
                                        $noteR20 = round($n->note * 20 / $pourcentage_rat, 2);
                                    } else {
                                        $noteR20 = round($n->note, 2);
                                    }
                                    $total += $n->note;
                                @endphp
                                {{ $noteR20 }}
                                @break
                            @endif
                        @endforeach
                    </td>

                    {{-- Note finale /20 (ancienne logique : total / 5) --}}
                    <td class="note-column">
                        @php
                            // si les % CC + RAT = 100, alors total est sur 0–100
                            $noteFinale20 = round($total / 5, 2);

                            if ($total >= 50) {
                                $notesCap++;
                            } elseif ($total >= 35) {
                                $notesQuant++;
                            } else {
                                $echecs++;
                            }

                            $grade = getGrade($total); // getGrade attend une note sur 100
                        @endphp

                        {{ $noteFinale20 }}
                    </td>

                    {{-- GP & Grade --}}
                    <td class="note-column">{{ $grade[1] }}</td>
                    <td class="note-column">{{ $grade[0] }}</td>
                </tr>
            @endforeach
            </tbody>
        </table>

        @php
            if ($totalNotes == 0) {
                $totalNotes = 1; // éviter division par 0
            }
        @endphp

        <div>
            <p><b>Capitalisation :</b> {{ round(($notesCap   / $totalNotes) * 100, 2) }} %</p>
            <p><b>Quantification :</b> {{ round(($notesQuant / $totalNotes) * 100, 2) }} %</p>
            <p><b>Echec :</b>         {{ round(($echecs    / $totalNotes) * 100, 2) }} %</p>
        </div>
    </div>

    <script>
        $('#note-excel').dataTable({
            dom: 'Bfrtip',
            pageLength: 2000,
            buttons: [
                {
                    extend: 'excelHtml5',
                    text: '<i class="fa fa-file-excel-o"></i> Excel',
                    titleAttr: 'Export to Excel',
                    title: 'Notes {{ $classe->nom }}'
                }
            ]
        });
    </script>

@endsection

@section('breadcrumb')
    <li><strong><a href="{{ url('eleves') }}">@lang('menu.gestion_notes')</a></strong></li>
    <li><strong><a href="{{ url('eleves/notes') }}">Notes</a></strong></li>
    <li><a href="{{ url('eleves/notes/show') }}"><strong>@lang('admin.choix_classe')</strong></a></li>
    <li>
        <a href="{{ url('eleves/notes/matieres/show').'?id='.$classe->id.'&cycle='.$id_cycle.'&nniveau='.$nniveau->id }}">
            <strong>@lang('admin.titre_matiere')</strong>
        </a>
    </li>
    <li class="active">
        <strong>{{ isset($titre) ? $titre : '' }}</strong>
    </li>
@endsection
