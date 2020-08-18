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
            <p><strong>Spécialité : {{$classe->nom}} - Niveau  {{$nniveau->nom}}</strong> </p>
            <strong class="traduction">Speciality : {{$classe->nom_anglais}} - Level  {{$nniveau->nom}}</strong>
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
        <th class="note-column">Note /20</th>
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
                            {{round($n->note/1.5,2)}}
                        @endif
                    @endforeach
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>


@endsection

@section('breadcrumb')
    <li><strong><a href="{{url('eleves')}}">@lang('menu.gestion_notes')</a></strong></li>
    <li><strong><a href="{{url('eleves/notes')}}">Notes</a></strong></li>

    <li><a href="{{url('eleves/notes/show')}}"><strong>@lang('admin.choix_classe')</strong></a></li>
    <li><a href="{{url('eleves/notes/matieres/show').'?id='.$classe->id.'&cycle='.$id_cycle.'&nniveau='.$nniveau->id}}"><strong>@lang('admin.titre_matiere')</strong></a></li>

    <li class="active"><strong>{{$titre}}</strong></li>
@endsection