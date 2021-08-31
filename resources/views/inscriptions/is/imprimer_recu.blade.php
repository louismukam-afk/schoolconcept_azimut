@extends('index')
@section('content')

    <a href="" onclick="window.print()"  class="btn btn-lg btn-warning">@lang('inscription.imprimer')</a>
    <link href="{{ URL::asset('css/print.css')  }}" rel="stylesheet" media="print"/>

    <div class="recu col-md-12">
        <div class="back">
            <a href="{{URL::to('/inscriptions')}}" style="font-size: 17px"><< {{trans('inscription.retour_ins')}}</a>
        </div>
        @include('perso.header')
        <div class="panel panel-info">

            <div class="panel-heading">
                <h3 class="panel-title">RECU DE PAYEMENT DES FRAIS D'INSCRIPTION</h3>
                <div class="traduction">REGISTRATION FEES PAYMENT RECEIPT</div>
            </div>
            <div class="panel-body">
                <div class="row page-header print-head">
                    <div class="etablissement pull-left">
                        <strong>{{ $nom_etablissement  }}</strong>
                    </div>
                    <div class="numero pull-right">
                        <strong>N°{{ $payement->numero }}</strong>
                    </div>
                </div>

                <div class="row mat-ann">
                    <p class="col-md-6 mat"><strong >Matricule : </strong>{{$inscription->eleve->matricule}}</p>
                    <p class="col-md-6 ann" ><strong>Année scolaire </strong><span class="traduction">School year :</span> {{session('year')}}
                    <p class="traduction"></p>
                    </p>

                </div>
                <div class="row nom">
                    <p class="col-md-12" ><strong>Nom :</strong> {{strtoupper($inscription->eleve->nom.' '.$inscription->eleve->prenom)}}</p>
                    <p class="traduction">Name</p>
                </div>
                <div class="row specialite">
                    <p class="col-md-12"><strong>Cycle : </strong>{{strtoupper($cycle)}}</p>
                </div>
                <div class="row specialite">
                    <p class="col-md-12"><strong>Filière : </strong>{{strtoupper($niveau->nom)}}</p>
                    <p class="traduction">Path : {{strtoupper($niveau->nom_anglais)}}</p>
                </div>

                <div class="row specialite">
                    <p class="col-md-12"><strong>Spécialité : </strong>{{strtoupper($classe->nom)}} <strong>Niveau: </strong> {{$nniveau->nom}}</p>
                    <p class="traduction">Spéciality : {{strtoupper($classe->nom_anglais)}} Level: {{$nniveau->nom}}</p>
                </div>
                <div class="row last">
                    <p class="col-md-6 montant"><strong>Montant : <span style="font-size: 19px">{{$payement->montant}}</span> </strong>{{trans('inscription.devise')}}</p>
                    <p class="traduction">Amount</p>
                    <p class="col-md-6 date"><strong>Date : </strong>{{$payement->date_payement}}</p>
                </div>

                <div class="sign pull-right">
                    <strong>Signatures</strong>
                </div>


            </div>

        </div>
    </div>

@endsection