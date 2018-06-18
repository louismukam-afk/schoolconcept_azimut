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
            @if(session('locale')=="fr")
            <p><strong>CERTIFICAT DE SCOLARITE</strong> </p>
            @else
                <p><strong >SCHOOL CERTFICATE</strong></p>
                @endif

        </div>

        <div class="body">
            @if(session('locale')=="fr")
            <p>Le directeur de {{$nom_etablissement}}, {{$nom_directeur}} atteste que l'étudiant <strong>{{$eleve->nom.' '.$eleve->prenom}}</strong>
                Né(e) le {{$eleve->date_naiss}} à {{$eleve->lieu_naiss}} de matricule <strong>{{$eleve->matricule}}
                </strong>
                est régulièrement inscrit pour l'année académique <strong>{{session('year')}}</strong> En  <strong>{{$classe->nom}}</strong> Filière  <strong>{{$niveau->nom}}</strong>
                en voie de quoi le présent certificat lui est délivré.
            </p>
            @else
            <p>
                The manager of {{$nom_etablissement}}, {{$nom_directeur}} certifies that the student <strong> {{$eleve->nom. ' '. $eleve->prenom}} </strong> Born on {{$eleve->date_naiss}} at {{$eleve->lieu_naiss}} with registration number <strong> {{$eleve->matricule}}</Strong>
                is regularly registered for the academic year <strong> {{session ('year')}} </strong> In <strong> {{$classe->nom}} </strong> in <strong> {{$niveau ->nom}} </strong>
                this certificate is issued to him.
            </p>
            @endif

                @if(session('locale')=="fr")
            <div class="fait" style="margin-top: 90px">
                <div>Fait le {{date('d/m/Y') }}</div>

                <div class="sign">Signature</div>
            </div>
                @else
            <div class="fait" style="margin-top: 90px">
                <div>Made on {{date('d/m/Y') }}</div>

                <div class="sign">Signature</div>
            </div>
                    @endif
        </div>


    @endif
@endsection