@extends('skeleton')
@section('sub-content')
    @include('perso.functions')
    <a href="#" class="btn btn-lg btn-warning" onclick="window.print()"><span class="glyphicon glyphicon-print"></span> @lang('inscription.imprimer')</a>
    @include('perso.header')
    <link rel="stylesheet"  href="{{URL::asset('css/note_matiere.css')}}">
    <div class="titre">
        <p style="font-size: 19px"><strong>Examen de rattrapage du semestre {{$semestre}} en {{$matiere->nom}}</strong></p>
        <strong class="traduction">Semester {{$semestre}} recit exam of {{$matiere->nom_anglais}}</strong>
    </div>
    <div class="classe">
        <div class="specialite">
            <p><strong>Spécialité : {{$classe->nom}}</strong></p>
            <strong class="traduction">Speciality : {{$classe->nom_anglais}}</strong>
        </div>

        <div class="filiere">
            <p ><strong>Filière: {{\gesecole\niveau::find($classe->id_niveau)->nom}}</strong></p>
            <strong class="traduction">Path : {{\gesecole\niveau::find($classe->id_niveau)->nom_anglais}}</strong>
        </div>

    </div>
<div class="table">
    <table class="table table-bordered table-striped table-condensed table-inverse" style="margin-top: 5px;">
        <thead>
        <th class="number-column">#</th>
        <th class="mat-column">Matricule/Matricle</th>
        <th>Nom/Name</th>
        <th class="note-column">Note /100</th>
        <th class="note-column">GP</th>
        <th class="note-column">Grade</th>
        </thead>
        <tbody>
        <?php $i=1; ?>
        <?php $totalNotes=0;
        $notesQuant=0;
        $notesCap=0;
        $echecs=0;
        ?>
        @foreach($eleves as $e)
            <?php $total=0; ?>
                                        <?php $totalNotes++; ?>

            <tr>
                <td class="number-column">{{$i++}}</td>
                <td class="mat-column">{{$e->matricule}}</td>
                <td>{{$e->nom.' '.$e->prenom}}</td>

                <td class="note-column">
                    @foreach($notes as $n)
                        @if($n->id_eleve==$e->id)
                            {{$n->note}}

                            @if($n->note>=50)
                                <?php $notesCap++; ?>
                            @elseif($n->note>=35)
                                <?php $notesQuant++; ?>
                            @else
                                <?php $echecs++; ?>
                            @endif
                            <?php $total=$n->note; ?>
                        @endif
                    @endforeach
                </td>

                <td class="note-column">{{getGrade($total)[1]}}</td>
                <td class="note-column">{{getGrade($total)[0]}}</td>
            </tr>
        @endforeach
        </tbody>
    </table>
@if($totalNotes==0)
    <?php $totalNotes=1; ?>
    @endif
    <div>
        <p><b>Capitalisation :</b> {{round(($notesCap/$totalNotes)*100,2)}} %</p>
        <p><b>Quantification :</b> {{round(($notesQuant/$totalNotes)*100,2)}} %</p>
        <p><b>Echec :</b> {{round(($echecs/$totalNotes)*100,2)}} %</p>
    </div>
</div>


@endsection