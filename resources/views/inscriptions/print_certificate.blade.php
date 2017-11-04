@extends('index')
@section('content')

    @if($inscription==null)
        <div class="alert alert-danger">
            @lang('inscription.pas_inscrit')
        </div>
    @else
        <a href="" onclick="window.print()" class="btn btn-warning btn-lg">@lang('inscription.imprimer')</a>
        @include('perso.header')
        <link rel="stylesheet" href="{{URL::asset('css/certificat.css')}}">
        <div class="titre">
            <p><strong>CERTIFICAT DE SCOLARITE</strong> </p>
            <p><strong class="traduction">SCHOOL CERTFICATE</strong></p>

        </div>

        <div class="body">
            <p>Le directeur de {{$nom_etablissement}}, {{$nom_directeur}} certifie que l'étudiant</p>
            <p class="traduction">The director of  {{$nom_etablissement}}, {{$nom_directeur}} certifies that the student</p>
            <div style="font-size: 17px;margin-top: 10px"> <strong>{{$eleve->nom.' '.$eleve->prenom}}</strong> </div>

            <div class="born-status" style="margin-top: 20px">
                <div class="the">Né(e) {{$eleve->date_naiss}}
                <div class="traduction">Born the</div>
                </div>
                <div class="at">à{{$eleve->lieu_naiss}}
                <div class="traduction">At</div>
                </div>
            </div>

            <div  class="s-text" style="margin-top: 20px">
                <div> Matricule : <strong>{{$eleve->matricule}}</strong></div>
                <div class="traduction">Registration number</div>
            </div>

            <div class="s-text" style="margin-top: 20px">
                <p>Est régulièrement inscrit pour l'année académic <strong>{{session('year')}}</strong> </p>
                <p class="traduction">Is regularly registered for the academic year {{session('year')}}</p>

            </div>
            <div class="s-text"style="margin-top: 20px">
                <p>En : <strong>{{$classe->nom}}</strong></p>
                <div class="traduction">In {{$classe->nom_anglais}} </div>
            </div>

            <div class="s-text" style="margin-top: 20px">
                <p>Filière : <strong>{{$niveau->nom}}</strong></p>
                <div class="traduction">Path : {{$niveau->nom_anglais}}</div>
            </div>

            <div class="fait" style="margin-top: 40px">
                <div>Fait le {{date('d/m/Y') }}</div>
                <div class="traduction">Made on </div>

                <div class="sign">Signature</div>
            </div>
        </div>


    @endif
@endsection