@extends('index')
@section('content')

    <a href="" onclick="window.print()"  class="btn btn-lg btn-warning">@lang('inscription.imprimer')</a>
        <link href="{{ URL::asset('css/print2.css')  }}" rel="stylesheet" media="print"/>

    <div class="recu col-md-12">
        <div class="back">
            <a href="{{URL::to('/inscriptions')}}" style="font-size: 17px"><< {{trans('inscription.retour_ins')}}</a>
        </div>
        @include('perso.header')
        <div class="panel panel-info">
            <div class="panel-heading">
                <h3 class="panel-title">RECU DE VERSEMENT DES FRAIS DE SCOLARITE</h3>
                <div class="traduction">SCHOOL FEES PAYMENT RECEIPT</div>
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
                    <p class="col-md-6 ann" ><strong>Année scolaire/School year : </strong>{{session('year')}}</p>

                </div>
                <div class="row nom">
                    <p class="col-md-12" ><strong>Nom :</strong> {{strtoupper($inscription->eleve->nom.' '.$inscription->eleve->prenom)}}</p>
                    <div class="traduction">Name</div>
                </div>

                <div class="row specialite">
                    <p class="col-md-12"><strong>Niveau : </strong>{{strtoupper($niveau->nom)}}</p>
                    <p class="traduction">Level : {{strtoupper($niveau->nom_anglais)}}</p>
                </div>

                <div class="row">
                    <p class="col-md-12"><strong>Classe :</strong>{{strtoupper($inscription->classe->nom)}}</p>
                    <div class="traduction">Class</div>

                </div>
                <div class="row last">
                    <p class="col-md-6 montant"><strong>Montant : <span style="font-size: 19px"> {{$payement->montant}}</span></strong> {{trans('inscription.devise')}}</p>
                    <div class="traduction">Amount</div>
                    <p class="col-md-6 date"><strong>Date : </strong>{{$payement->date_payement}}</p>
                </div>
                <div class="row" style="margin-top: 50px">
                    <div class="col-md-6"><strong>Motif :</strong>{{$payement->motif}}</div>
                    <div class="traduction">Reason for payment</div>
                    <div class="col-md-6"><strong>Total pension :</strong><span class="{{$total==0?'text-danger':'' }}">{{$total==0?'Le montant n\'est pas défini':$total}}</span></div>
                    <div class="traduction">Total school fees</div>
                    <div class="col-md-6"><strong>Solde en Caisse : </strong>{{$solde.' '.trans('inscription.devise')}}</div>
                    <div class="traduction">Cash balance : </div>
                    <div class="col-md-6"><strong>Reste à payer : </strong><span class="{{$total==0?'text-danger':'' }}">{{$total==0?'Le montant n\'est pas défini':$total-$solde.' '.trans('inscription.devise')}}</span></div>
                    <div class="traduction">Left to pay</div>

                </div>

                <div class="sign pull-right">
                    <strong>Signatures</strong>
                </div>

            </div>

        </div>
    </div>
@endsection