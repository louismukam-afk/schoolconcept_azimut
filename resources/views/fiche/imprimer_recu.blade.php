@extends('index')
@section('content')
    <style media="screen">
        .recu-container
        {
            margin-left: 20px;
        }
        #dupli
        {
            display: none;
        }
        .recu  .panel  .panel-body .row .numero
        {
            -webkit-print-color-adjust:exact;
            color:firebrick !important;
            font-size: 17px;
            margin: 5px;
            margin-right: 10px;
        }
    </style>

    <a href="" onclick="window.print()"  class="btn btn-lg btn-warning">@lang('inscription.imprimer')</a>
    <link href="{{ URL::asset('css/print.css')  }}" rel="stylesheet" media="print"/>

    <div class="recu col-md-12">
        <div class="back">
            <a href="{{URL::to('/inscriptions')}}" style="font-size: 17px"><< {{trans('inscription.retour_ins')}}</a>
        </div>
        @include('perso.header2')
        <div class="panel panel-louis">
        <div class="panel panel-info">

            <div class="panel-heading">
                <h3 class="panel-title">RECU DE PAYEMENT DES FRAIS D'INSCRIPTION</h3>
                <div class="traduction">REGISTRATION FEES PAYMENT RECEIPT</div>
                <div class="herve">
                <div class="etablissement pull-left ">
                    <strong>{{ $nom_etablissement  }}</strong>
                </div>
                <div class="numero pull-right">
                    <strong>N°{{ $payement->numero }}</strong>
                </div>
                    </div>
            </div>
            <div class="panel-body">
               <!-- <div class="row page-header print-head">

                </div>-->
                <div class="recu-container">
                <div class="row mat-ann">
                    <p class="col-md-6 mat"><strong >Matricule : </strong>{{$inscription->eleve->matricule}}</p>
                   <!-- <p class="col-md-6 mod"><strong>Mode de paiement:</strong>{{$inscription->mode_paye}}</p>
                    <p class="col-md-6 ref"><strong>Référence  du paiement:</strong>{{$inscription->ref_versement}}</p>-->

                    <p class="col-md-6 ann" ><strong>Année scolaire </strong><span class="traduction">School year :</span> {{session('year')}}
                    <p class="traduction"></p>
                    </p>

                </div>
                <div class="row nom">
                    <p class="col-md-6" ><strong>Nom :</strong> {{strtoupper($inscription->eleve->nom.' '.$inscription->eleve->prenom)}} <strong class="col-md-6 mode" style="margin-left: 200px;">Mode de paiement :</strong>{{$inscription->mode_paye}}</p>
                    <p class="traduction">Name</p>
                </div>
                <div class="row specialite">
                    <p class="col-md-6"><strong>Cycle : </strong>{{strtoupper($cycle)}} <strong class="col-md-6 Filiere" style="margin-left: 250px;">Filière : </strong>{{strtoupper($niveau->nom)}}</p>
                    <p class="traduction" style="margin-left: 450px">Field of study : {{strtoupper($niveau->nom_anglais)}}</p>
                     <p class="col-md-6 ref"><strong>Référence  du paiement :</strong>{{$inscription->ref_versement}}</p>
                    

                </div>
               <div class="row specialite">
                   <!-- <p class="col-md-12"><strong>Filière : </strong>{{strtoupper($niveau->nom)}}</p>-->

                </div>

                <div class="row specialite">
                    <p class="col-md-12"><strong>Spécialité : </strong>{{strtoupper($classe->nom)}} <strong>Niveau: </strong> {{$nniveau->nom}}</p>
                    <p class="traduction">Spéciality : {{strtoupper($classe->nom_anglais)}} Level: {{$nniveau->nom}}</p>
                </div>
                <div class="row last">
                    <p class="col-md-6 montant"><strong>Montant en chiffre : <span style="font-size: 19px">{{$payement->montant}}</span> </strong>{{trans('inscription.devise')}} <strong class="col-md-6 lettre" >Montant en lettre : <span style="font-size: 19px">{{$payement->lettre}}</span></strong></p>
                    <p class="traduction">Amount</p>

                    <p class="col-md-6 date"><strong>Date : </strong>{{$payement->date_payement}}</p>
                </div>
                    </div>

                <div class="sign pull-right" style="margin-top:30px;">
                    <strong>Signatures</strong>
                </div>


            </div>

        </div>
        </div>
    </div>
    <div style="border-top: dashed 2px; margin-bottom: 10px"></div>

    <div id="dupli" class="panel panel-info duplicata">




    <div class="recu col-md-12">
        @include('perso.header2')
        <div class="panel panel-info">

            <div class="panel-heading">
                <h3 class="panel-title">RECU DE PAYEMENT DES FRAIS D'INSCRIPTION</h3>
                <div class="traduction">REGISTRATION FEES PAYMENT RECEIPT</div>
                <div class="etablissement pull-left ">
                    <strong>{{ $nom_etablissement  }}</strong>
                </div>
                <div class="numero pull-right">
                    <strong>N°{{ $payement->numero }}</strong>
                </div>
            </div>
            <div class="panel-body">
               <!-- <div class="row page-header print-head">

                </div>-->
                <div class="recu-container">
                <div class="row mat-ann">
                    <p class="col-md-6 mat"><strong >Matricule : </strong>{{$inscription->eleve->matricule}}</p>
                <!-- <p class="col-md-6 mod"><strong>Mode de paiement:</strong>{{$inscription->mode_paye}}</p>
                    <p class="col-md-6 ref"><strong>Référence  du paiement:</strong>{{$inscription->ref_versement}}</p>-->

                    <p class="col-md-6 ann" ><strong>Année scolaire </strong><span class="traduction">School year :</span> {{session('year')}}
                    <p class="traduction"></p>
                    </p>

                </div>
                <div class="row nom">
                    <p class="col-md-6" ><strong>Nom :</strong> {{strtoupper($inscription->eleve->nom.' '.$inscription->eleve->prenom)}} <strong class="col-md-6 mode" style="margin-left: 200px;">Mode de paiement :</strong>{{$inscription->mode_paye}}</p>
                    <p class="traduction">Name</p>
                </div>
                <div class="row specialite">
                    <p class="col-md-6"><strong>Cycle : </strong>{{strtoupper($cycle)}} <strong class="col-md-6 Filiere" style="margin-left: 250px;">Filière : </strong>{{strtoupper($niveau->nom)}}</p>
                    <p class="traduction" style="margin-left: 450px">Field of study : {{strtoupper($niveau->nom_anglais)}}</p>
                    <p class="col-md-6 ref"><strong>Référence  du paiement :</strong>{{$inscription->ref_versement}}</p>


                </div>
                <div class="row specialite">
                <!-- <p class="col-md-12"><strong>Filière : </strong>{{strtoupper($niveau->nom)}}</p>-->

                </div>

                <div class="row specialite">
                    <p class="col-md-12"><strong>Spécialité : </strong>{{strtoupper($classe->nom)}} <strong>Niveau: </strong> {{$nniveau->nom}}</p>
                    <p class="traduction">Spéciality : {{strtoupper($classe->nom_anglais)}} Level: {{$nniveau->nom}}</p>
                </div>
                <div class="row last">
                    <p class="col-md-6 montant"><strong>Montant en chiffre : <span style="font-size: 19px">{{$payement->montant}}</span> </strong>{{trans('inscription.devise')}} <strong class="col-md-6 lettre" >Montant en lettre : <span style="font-size: 19px">{{$payement->lettre}}</span></strong></p>
                    <p class="traduction">Amount</p>

                    <p class="col-md-6 date"><strong>Date : </strong>{{$payement->date_payement}}</p>
                </div>

                <div class="sign pull-right" style="margin-top:30px;">
                    <strong>Signatures</strong>
                </div>


            </div>
                </div>

        </div>
    </div>
    </div>

@endsection