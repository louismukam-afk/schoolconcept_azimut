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
            @if(App::getLocale()!="en")
            <p><strong>CERTIFICAT DE SCOLARITE</strong> </p>
                <p class="traduction2">@lang('admin.year') {{session('year')}}</p>
            @else
                <p><strong> SCHOOL CERTFICATE</strong></p>
                <p class="traduction2">@lang('admin.year') {{session('year')}}</p>

            @endif

        </div>

        <div class="body" style="font-size: 16px">
            @if(App::getLocale()!="en")
                <div class="ligne">
                    <div class="matricule">
                        @lang('admin.matricule') : {{$eleve->matricule}}
                    </div>

                    <div class="type">
                        @lang('inscription.eleve')
                    </div>
                </div>
                <p>Le Directeur de l’Institut Supérieur des Sciences Biologiques et Appliquées certifie que :</p>
                <br>
            <p>
                <strong>M. / Mme/ Mlle {{$eleve->nom.' '.$eleve->prenom}}</strong><br>
                Né(e) le {{(new DateTime($eleve->date_naiss))->format('d-m-Y')}} à {{$eleve->lieu_naiss}}<br>
                est régulièrement inscrit pour le niveau  <strong>{{$nniveau->nom}}</strong> du cycle
                <strong>{{$cycle->nom}} {{$classe->nom}}</strong>

            </p>
            @else
            <p>
                The manager of {{$nom_etablissement}}, {{$nom_directeur}} certifies that the student <strong> {{$eleve->nom. ' '. $eleve->prenom}} </strong> Born on {{$eleve->date_naiss}} at {{$eleve->lieu_naiss}} with registration number <strong> {{$eleve->matricule}}</Strong>
                is regularly registered for the academic year <strong> {{session ('year')}} </strong> In <strong> {{$niveau->nom}} </strong> speciality <strong> {{$classe->nom}} </strong> at level {{$nniveau->nom}}
                .This certificate is issued to him.
            </p>
            @endif

                @if(App::getLocale()!="en")
            <div class="fait" style="margin-top: 90px">
                <div class="sign" >Yaoundé, le {{date('d/m/Y') }}</div>

                <div class="sign" style="margin-top: 15mm"><strong>Le directeur</strong></div>
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