<html>
    <head>
        <title>Printing</title>
        <link href="{{ URL::asset('css/bootstrap.css')  }}" rel="stylesheet" media="screen"/>
        <!-- FONTAWESOME STYLES-->
        <link href="{{ URL::asset('css/font-awesome.css')  }}" rel="stylesheet" />
        <!-- CUSTOM STYLES-->
        <link href="{{ URL::asset('css/custom.css')  }}" rel="stylesheet" media="screen"/>
        <link href="{{ URL::asset('css/print2.css')  }}" rel="stylesheet" media="print"/>
    </head>

    <body onload="window.print()">


    <div class="recu col-md-8">
        <div class="back">
            <a href="{{URL::to('/inscriptions')}}" style="font-size: 17px"><< {{trans('inscription.retour_ins')}}</a>
        </div>
        <div class="panel panel-info">
            <div class="panel-heading">
                <h3 class="panel-title">{{trans('inscription.titre_recu_payement')}}</h3>
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
                    <p class="col-md-12" ><strong>Nom/Name :</strong> {{strtoupper($inscription->eleve->nom)}}</p>
                </div>

                <div class="row">
                    <p class="col-md-12"><strong>Prénom/Forname :</strong> {{strtoupper($inscription->eleve->prenom)}}</p>
                </div>
                <div class="row">
                    <p class="col-md-12"><strong>Classe :</strong>{{strtoupper($inscription->classe->nom)}}</p>
                </div>
                <div class="row last">
                    <p class="col-md-6 montant"><strong>Montant/Amount :</strong> {{$payement->montant}} {{trans('inscription.devise')}}</p>
                    <p class="col-md-6 date"><strong>Date : </strong>{{$payement->date_payement}}</p>
                </div>
                <div class="row" style="margin-top: 50px">
                    <div class="col-md-6"><strong>Motif/Reason for payment :</strong>{{$payement->motif}}</div>
                    <div class="col-md-6"><strong>Total pension/Total schoolfees :</strong>{{$total.' '.trans('inscription.devise')}}</div>
                    <div class="col-md-6"><strong>Solde en Caisse/Cash Balance :</strong>{{$solde.' '.trans('inscription.devise')}}</div>
                    <div class="col-md-6"><strong>Reste à payer/Left to pay :</strong>{{$total-$solde.' '.trans('inscription.devise')}}</div>
                </div>

                <div class="sign pull-right">
                    <strong>Signatures</strong>
                </div>

            </div>

        </div>
    </div>
    </body>
</html>