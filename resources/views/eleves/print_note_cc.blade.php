@extends('skeleton')
@section('sub-content')
    <a href="#" class="btn btn-lg btn-warning" onclick="window.print()"><span class="glyphicon glyphicon-print"></span> @lang('inscription.imprimer')</a>
    @include('perso.header')
    <link rel="stylesheet"  href="{{URL::asset('css/note_matiere.css')}}">
    <div class="titre">
        <p style="font-size: 19px"><strong>Controle continu de {{$matiere->nom}}</strong></p>
        <strong class="traduction">Continuous assessment of {{$matiere->nom_anglais}}</strong>
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
        <th class="note-column">Note /30</th>
        </thead>
        <tbody>
        <?php $i=1; ?>
        @foreach($eleves as $e)
            <tr>
                <td class="number-column">{{$i++}}</td>
                <td class="mat-column">{{$e->matricule}}</td>
                <td>{{$e->nom.' '.$e->prenom}}</td>
                <td class="note-column">
                    @foreach($notes as $n)
                        @if($n->id_eleve==$e->id)
                            {{$n->note}}
                        @endif
                    @endforeach
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>


@endsection