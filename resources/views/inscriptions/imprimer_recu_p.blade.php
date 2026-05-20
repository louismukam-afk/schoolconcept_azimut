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
                @if($payement->type == 0)
                    <h3 class="panel-title">RECU DE VERSEMENT DES FRAIS DE SCOLARITE</h3>
                    <div class="traduction">SCHOOL FEES PAYMENT RECEIPT</div>
                @else
                    <h3 class="panel-title">RECU DE PAIEMENT</h3>
                    <div class="traduction">PAYMENT RECEIPT</div>
                @endif
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
                <div class="row" style="display: flex; flex-wrap: nowrap">
                    <div class="col-md-6" style="width: 50%">
                        <div class="row mat-ann">
                            <p class="col-md-6 mat"><strong >Matricule : </strong>{{$inscription->eleve->matricule}}</p>
                        </div>
                        <div class="row nom">
                            <p class="col-md-12" ><strong>Nom :</strong> {{strtoupper($inscription->eleve->nom.' '.$inscription->eleve->prenom)}}</p>
                            <div class="col-md-12 traduction">Name</div>
                        </div>
                        <div class="row specialite">
                            <p class="col-md-12"><strong>Cycle : </strong>{{strtoupper($cycle)}} - Niveau : {{$nniveau->nom}}</p>
                        </div>
                        <div class="row specialite">
                            <p class="col-md-12"><strong>Filière : </strong>{{strtoupper($niveau->nom)}}</p>
                            <p class="col-md-12 traduction">Branch : {{strtoupper($niveau->nom_anglais)}}</p>
                        </div>

                        <div class="row">
                            <p class="col-md-12"><strong>Spécialité :</strong>{{strtoupper($inscription->classe->nom)}}</p>
                            <div class="col-md-12 traduction">Speciality</div>

                        </div>
                        <div class="row last">
                            <p class="col-md-6 montant"><strong>Montant : <span style="font-size: 19px"> {{$payement->montant}}</span></strong> {{trans('inscription.devise')}}</p>
                            <div class="col-md-12 traduction">Amount</div>

                        </div>

                        <div class="row">
                            <div class="col-md-6"><strong>Motif :</strong>@if(isset($frais)) {{$frais->nom}} @else {{$payement->motif}} @endif</div>
                            <div class="col-md-12 traduction">Reason for payment</div>
                        </div>
                    </div>

                    <div class="col-md-6" style="text-align: right; width: 50%">
                        <div class="col-md-12 mat-ann"><p ><strong>Année scolaire </strong> {{session('year')}}</div>
                        <div class="traduction col-md-12">School year :</div>
                       <div class="details">
                           <div class="col-md-12"><h4 class="grey-text">Details</h4></div>
                           <div class="col-md-6"><span>@if(isset($frais)) Total à payer @else Total pension @endif :</span><strong><span class="{{$total==0?'text-danger':'' }}">{{$total==0?'Le montant n\'est pas défini':$total.' '.trans('inscription.devise')}}</span></strong></div>
                           
                           <div class="col-md-12 traduction">@if(isset($frais)) Total expected @else Total school fees @endif</div>
                            @if (isset($montant_bourse))
                                <div class="col-md-6"><span>Bourse : </span><strong>{{$montant_bourse.' '.trans('inscription.devise')}}</strong></div>
                                <div class="col-md-12 traduction">Scholarship grant : </div>

                               <div class="col-md-6"><span>Solde en Caisse : </span><strong>{{$solde+$montant_bourse.' '.trans('inscription.devise')}}</strong></div>
                               <div class="col-md-12 traduction">Cash balance : </div>
                               <div class="col-md-6"><span>Reste à payer : </span><strong><span class="{{$total==0?'text-danger':'' }}">{{$total==0?'Le montant n\'est pas défini':($total-$solde)-$montant_bourse.' '.trans('inscription.devise')}}</span></strong></div>
                               <div class="col-md-12 traduction">Left to pay</div>

                           @else

                           <div class="col-md-6"><span>Solde en Caisse : </span><strong>{{$solde.' '.trans('inscription.devise')}}</strong></div>
                           <div class="col-md-12 traduction">Cash balance : </div>
                           <div class="col-md-6"><span>Reste à payer : </span><strong><span class="{{$total==0?'text-danger':'' }}">{{$total==0?'Le montant n\'est pas défini':$total-$solde.' '.trans('inscription.devise')}}</span></strong></div>
                           <div class="col-md-12 traduction">Left to pay</div>
                           @endif
                       </div>
                        <br>
                        <div class="col-md-6">
                            <p> Montant en Lettre: <strong>{{$payement->lettre}}</strong></p>
                            <p>Mode de paiement: <strong>{{$payement->mode_paye}}</strong></p>
                        </div>

                    </div>

                </div>
              <div class="row"  style="padding-top:5mm">
                    <p class="col-md-6"><strong>DATE :</strong> {{$payement->date_payement}}</p>
                </div>
                <div class="row" style="padding-left:20mm">
                    <p class="col-md-4" style="padding-top:5mm"><strong>SIGNATURE</strong></p>
                    <p class="col-md-4 visa"><strong>Caissier <span class="traduction"> Cashier</span></strong></p>
                    <p class="col-md-4 visa"><strong>Nom de la caissier(e)  <span class="traduction"> {{$user}}</strong></p>
                </div>


            </div>

        </div>

        <div style="border-top: dashed 2px; margin-bottom: 10px"></div>


        <div id="dupli" class="panel panel-info duplicata">
            <div class="background-container">
            </div>
            <div class="panel-heading">
                @if($payement->type == 0)
                    <h3 class="panel-title">RECU DE VERSEMENT DES FRAIS DE SCOLARITE</h3>
                    <div class="traduction">SCHOOL FEES PAYMENT RECEIPT</div>
                @else
                    <h3 class="panel-title">RECU DE PAIEMENT</h3>
                    <div class="traduction">PAYMENT RECEIPT</div>
                @endif
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
                <div class="row" style="display: flex; flex-wrap: nowrap">
                    <div class="col-md-6" style="width: 50%">
                        <div class="row mat-ann">
                            <p class="col-md-6 mat"><strong >Matricule : </strong>{{$inscription->eleve->matricule}}</p>
                        </div>
                        <div class="row nom">
                            <p class="col-md-12" ><strong>Nom :</strong> {{strtoupper($inscription->eleve->nom.' '.$inscription->eleve->prenom)}}</p>
                            <div class="col-md-12 traduction">Name</div>
                        </div>
                        <div class="row specialite">
                            <p class="col-md-12"><strong>Cycle : </strong>{{strtoupper($cycle)}} - Niveau : {{$nniveau->nom}}</p>
                        </div>
                        <div class="row specialite">
                            <p class="col-md-12"><strong>Filière : </strong>{{strtoupper($niveau->nom)}}</p>
                            <p class="col-md-12 traduction">Branch : {{strtoupper($niveau->nom_anglais)}}</p>
                        </div>

                        <div class="row">
                            <p class="col-md-12"><strong>Spécialité :</strong>{{strtoupper($inscription->classe->nom)}}</p>
                            <div class="col-md-12 traduction">Speciality</div>

                        </div>
                        <div class="row last">
                            <p class="col-md-6 montant"><strong>Montant : <span style="font-size: 19px"> {{$payement->montant}}</span></strong> {{trans('inscription.devise')}}
                               </p>
                            <div class="col-md-12 traduction">Amount</div>
                        </div>
                        <div class="row">
                            <div class="col-md-6"><strong>Motif :</strong>@if(isset($frais)) {{$frais->nom}} @else {{$payement->motif}} @endif</div>
                            <div class="col-md-12 traduction">Reason for payment</div>
                        </div>
                    </div>

                    <div class="col-md-6" style="text-align: right; width: 50%">
                        <div class="col-md-12 mat-ann"><p ><strong>Année scolaire </strong> {{session('year')}}</div>
                        <div class="traduction col-md-12">School year :</div>
                        <div class="details">
                            <div class="col-md-12"><h4 class="grey-text">Details</h4></div>
                            <div class="col-md-6"><span>@if(isset($frais)) Total à payer @else Total pension @endif :</span><strong><span class="{{$total==0?'text-danger':'' }}">{{$total==0?'Le montant n\'est pas défini':$total.' '.trans('inscription.devise')}}</span></strong></div>

                            <div class="col-md-12 traduction">@if(isset($frais)) Total expected @else Total school fees @endif</div>
                            @if (isset($montant_bourse))
                                <div class="col-md-6"><span>Bourse : </span><strong>{{$montant_bourse.' '.trans('inscription.devise')}}</strong></div>
                                <div class="col-md-12 traduction">Scholarship grant : </div>

                                <div class="col-md-6"><span>Solde en Caisse : </span><strong>{{$solde+$montant_bourse.' '.trans('inscription.devise')}}</strong></div>
                                <div class="col-md-12 traduction">Cash balance : </div>
                                <div class="col-md-6"><span>Reste à payer : </span><strong><span class="{{$total==0?'text-danger':'' }}">{{$total==0?'Le montant n\'est pas défini':($total-$solde)-$montant_bourse.' '.trans('inscription.devise')}}</span></strong></div>
                                <div class="col-md-12 traduction">Left to pay</div>

                            @else

                                <div class="col-md-6"><span>Solde en Caisse : </span><strong>{{$solde.' '.trans('inscription.devise')}}</strong></div>
                                <div class="col-md-12 traduction">Cash balance : </div>
                                <div class="col-md-6"><span>Reste à payer : </span><strong><span class="{{$total==0?'text-danger':'' }}">{{$total==0?'Le montant n\'est pas défini':$total-$solde.' '.trans('inscription.devise')}}</span></strong></div>
                                <div class="col-md-12 traduction">Left to pay</div>
                            @endif
                        </div>
                        <br>
                        <div class="col-md-6">
                            <p> Montant en Lettre: <strong>{{$payement->lettre}}</strong></p>
                            <p>Mode de paiement: <strong>{{$payement->mode_paye}}</strong></p>
                        </div>
                    </div>


                </div>
                <div class="row" style="padding-top:5mm">
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