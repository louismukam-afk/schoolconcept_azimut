@extends('skeleton')
@section('sub-content')
    <a  class="btn btn-warning col-md-12" onclick="window.print()"><span class="glyphicon glyphicon-print"></span> Imprimer</a>
    <div class="recu col-md-10">
        <div class="panel panel-info">
            <div class="panel-heading">
                <h3 class="panel-title">{{trans('inscription.titre_recu_inscription')}}</h3>
            </div>
            <div class="panel-body">
                <div class="row page-header">
                    <div class="etablissement pull-left">
                        <strong>Collège Francois Xavier Vogt</strong>
                    </div>
                    <div class="numero pull-right">
                        <strong>N°000234504</strong>
                    </div>
                </div>

                <div class="row">
                    <p class="col-md-6"><strong >Matricule : </strong>17DT00034</p>
                    <p class="col-md-6"><strong>Année scolaire/School year : </strong>2017-2018</p>
                </div>
                <div class="row">
                   <p class="col-md-12"><strong>Nom/Name :</strong> CHINDA MELONG</p>
                </div>

                <div class="row">
                    <p class="col-md-12"><strong>Prénom/Forname :</strong> CYRILLE JOEL</p>
                </div>
                <div class="row">
                    <p class="col-md-6"><strong>Montant/Amount :</strong> 50000 {{trans('inscription.devise')}}</p>
                    <p class="col-md-6"><strong>Date : </strong>{{date('d/m/Y')}}</p>
                </div>

            </div>

        </div>
    </div>


@endsection