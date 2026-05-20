@extends('index')
@section('content')

    <a href="" onclick="window.print()"  class="btn btn-lg btn-warning">@lang('inscription.imprimer')</a>
    <link href="{{ URL::asset('css/print.css')  }}" rel="stylesheet" media="print"/>

    <style media="screen">

        #dupli
        {
            display: none;
        }
        .recu  .panel  .panel-body .row .numero
        {
            -webkit-print-color-adjust:exact;
            color-adjust: exact;
            color:firebrick !important;
            font-size: 17px;
            margin: 5px;
            margin-right: 10px;
        }
    </style>

    <div class="recu col-md-12">
        <div class="back">
            <a href="{{URL::to('/inscriptions')}}" style="font-size: 17px"><< {{trans('inscription.retour_ins')}}</a>
        </div>
        <div class="panel panel-info duplicata">
            <div class="background-container">
            </div>
            <div class="panel-heading">
                <h3 class="panel-title">RECU DE PAIEMENT DES FRAIS D'INSCRIPTION</h3>
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
                    <p class="col-md-12 traduction">Name</p>
                </div>
                <div class="row specialite">
                    <p class="col-md-12"><strong>Cycle : </strong>{{strtoupper($cycle)}}</p>
                </div>
                <div class="row specialite">
                    <p class="col-md-12"><strong>Filière : </strong>{{strtoupper($niveau->nom)}}</p>
                    <p class="col-md-12 traduction">Path : {{strtoupper($niveau->nom_anglais)}}</p>
                </div>

                <div class="row specialite">
                    <p class="col-md-12"><strong>Spécialité : </strong>{{strtoupper($classe->nom)}} <strong>Niveau: </strong> {{$nniveau->nom}}</p>
                    <p class="col-md-12 traduction">Speciality : {{strtoupper($classe->nom_anglais)}} Level: {{$nniveau->nom}}</p>
                </div>
                <div class="row last">
                    <p class="col-md-6 montant"><strong>Montant : <span style="font-size: 17px">{{$payement->montant}}</span> </strong>{{trans('inscription.devise')}}
                        <strong style="margin-left: 5%"> Montant en Lettre: {{$payement->lettre}}</strong></p>
                    <p class="col-md-6 lettre">  Motif:<strong> {{$payement->motif}}</strong></p>
                    <p class="col-md-12 traduction">Amount</p>
                </div>
                <div class="row">
                    <p class="col-md-6"><strong>DATE :</strong> {{$payement->date_payement}}</p>
                </div>
                <div class="row" style="padding-left:20mm">
                    <p class="col-md-4" style="padding-top:5mm"><strong>SIGNATURE</strong></p>
                    <p class="col-md-4 visa"><strong>Caissier <span class="traduction"> Cashier</span></strong></p>
                    <p class="col-md-4 visa"><strong>Nom de la caissier(e) <span class="traduction"> {{$user}}</strong></p>
                </div>





            </div>

        </div>

        <div style="border-top: dashed 2px; margin-bottom: 10px"></div>

        <div id="dupli" class="panel panel-info duplicata">
            <div class="background-container">
            </div>
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
                    <p class="col-md-12 traduction">Name</p>
                </div>
                <div class="row specialite">
                    <p class="col-md-12"><strong>Cycle : </strong>{{strtoupper($cycle)}}</p>
                </div>
                <div class="row specialite">
                    <p class="col-md-12"><strong>Filière : </strong>{{strtoupper($niveau->nom)}}</p>
                    <p class="col-md-12 traduction">Path : {{strtoupper($niveau->nom_anglais)}}</p>
                </div>

                <div class="row specialite">
                    <p class="col-md-12"><strong>Spécialité : </strong>{{strtoupper($classe->nom)}} <strong>Niveau: </strong> {{$nniveau->nom}}</p>
                    <p class="col-md-12 traduction">Spéciality : {{strtoupper($classe->nom_anglais)}} Level: {{$nniveau->nom}}</p>
                </div>
                <div class="row last">
                    <p class="col-md-6 montant"><strong>Montant : <span style="font-size: 17px">{{$payement->montant}}</span> </strong>{{trans('inscription.devise')}}    <strong style="margin-left: 5%"> Montant en Lettre: {{$payement->lettre}}</strong></p>
                    <p class="col-md-6 lettre">  Motif:<strong> {{$payement->motif}}</strong></p>

                    <p class="col-md-12 traduction">Amount</p>
                </div>
                <div class="row">
                    <p class="col-md-6"><strong>DATE :</strong> {{$payement->date_payement}}</p>
                </div>
                <div class="row" style="padding-left:20mm">
                    <p class="col-md-6" style="padding-top:5mm"><strong>SIGNATURE</strong></p>
                    <p class="col-md-6 visa"><strong>Caissier <span class="traduction"> Cashier</span></strong></p>
                </div>




            </div>

        </div>
    </div>

@endsection