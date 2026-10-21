@extends('skeleton')
@section('sub-content')
    <div class="col-md-12">
        @if(isset($success))

            <div class="alert alert-success">
                {{  trans('admin.succes')  }}
            </div>

        @endif



            <div class="col-md-12">
                <div class="col-md-8">
                    <div class="btn-group" >
                        <a href="{{url('/comptabilite/bilan/sorties').'?option=year'}}" class="btn btn-success"> @lang('admin.toute_lannee')</a>
                        <a href="{{url('/comptabilite/bilan/sorties').'?option=month'}}" class="btn btn-info"> @lang('admin.mois')</a>
                        <a href="{{url('/comptabilite/bilan/sorties').'?option=week'}}" class="btn btn-warning"> @lang('admin.semaine')</a>

                    </div>
                </div>

        @include('perso.header3')
<link href="{{URL::asset('css/recu_payement_salaire.css')}}" rel="stylesheet">
<div class="bulletin-paie">
    <div class="titre">
        <div style="font-size: 23px"><strong>ORDRE DE VIREMENT</strong></div>
        <strong class="traduction">PAYSLIP</strong>
    </div>
    <h3><center>Etats de paiement du mois de Janvier 2021</center></h3>


  <!--   <h3 style="text-align: justify;"> <strong> INSTITUT SUPERIEUR AZIMUT(CEF)</strong> autorise <strong>EXPRESS UNION </strong>  agence d'étourdi à effectuer les dépôts eu mobile money  à partir du compte  695 32 01 31  dans le compte de  l'ensemble du personnel dont  les noms figurent dans la liste si dessous concernant le mois de Décembre 2019 au débit du compte 20361501801-11</h3> -->



                        <h4 style="margin-top: 15px;">
                            @if(isset($date_debut))
                                <strong>{{trans('admin.salaire').' : '}}</strong>{{$date_debut.' - '.$date_fin}}
                            @else
                            @endif

                        </h4>

                        <table id="table-salaire" class="table table-bordered table-striped table-condensed table-inverse" >
                            <thead>
                            <th>#</th>
                            <th>@lang('admin.personnel')</th>
                            <!--<th>Banque / microfinance</th>-->
                            <th> N° compte</th>
                            <th>Date</th>
                            <th>@lang('inscription.montant')</th>
                            </thead>
                            <tbody>
                              <?php $i=1; ?>
                            @foreach($payements as $p)
                          
                                <tr>


                                        <td>{{$i++}}</td>
                                    <td>
                                        @foreach($personnels as $pers)
                                            @if($pers->id==$p->id_personnel)
                                                {{$pers->nom}}
                                                <?php break; ?>
                                            @endif
                                        @endforeach
                                    </td>
                                   <!-- <td>
                                        @foreach($personnels as $pers)
                                            @if($pers->id==$p->id_personnel)
                                                {{$pers->banque}}
                                                <?php break; ?>
                                            @endif
                                        @endforeach
                                    </td>-->
                                    <td>
                                        @foreach($personnels as $pers)
                                            @if($pers->id==$p->id_personnel)
                                                {{$pers->numero_compte}}
                                                <?php break; ?>
                                            @endif
                                        @endforeach
                                    </td>
                                    <td>{{(new DateTime($p->date_p))->format('d-m-Y')}}</td>
                                    <td>{{$p->net_a_payer}}</td>
                                   
                                </tr>
                           
                            @endforeach
                            </tbody>

                      </table>
                    </div>


                        <h4 class="montant">
                            Total : <strong>{{$total.' '.trans('inscription.devise')}} </strong>
                        </h4>

                                   <h5 style="text-align: left; margin-top: 20px;"> La GRH</h5>
                <h5 style="text-align: right; margin-top: 20px;"> Le PDG</h5>

                    <div class="col-md-4 manger">
                        <div class="panel panel-primary">
                            <div class="panel-heading">
                                <div class="panel-title">@lang('admin.intervalle')</div>

                            </div>
                            <div class="panel-body">
                                <form accept-charset="UTF-8" role="form" method="POST" action="{{ url('/comptabilite/bilan/sorties') }}">
                                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                    <fieldset>

                                        <div class="form-group ">
                                            <label for="date_debut">{{  trans('admin.date_debut')  }} : </label>
                                            <input placeholder="jj-mm-aaaa" class="form-control" id="date_debut" name="date_debut" type="date"  value="{{ old('date_debut') }}" required>

                                        </div>
                                        <div class="form-group">
                                            <label for="date_fin">{{  trans('admin.date_fin')  }} : </label>
                                            <input placeholder="jj-mm-aaaa" class="form-control" id="date_fin" name="date_fin" type="date"  value="{{ old('date_fin') }}" required >

                                        </div>
                                        <input class="btn  btn-primary pull-right " type="submit" value="{{  trans('admin.confirmer')  }}">


                                    </fieldset>
                                </form>
                            </div>
                        </div>
                    </div>







<?php
function getMois($mois){
    if($mois==1) return 'Janvier';
    if($mois==2) return 'Fevrier';
    if($mois==3) return 'Mars';
    if($mois==4) return 'Avril';
    if($mois==5) return 'Mai';
    if($mois==6) return 'Juin';
    if($mois==7) return 'Juillet';
    if($mois==8) return 'Aout';
    if($mois==9) return 'Septembre';
    if($mois==10) return 'Octobre';
    if($mois==11) return 'Novembre';
    if($mois==12) return 'Décembre';
}
function getMoisEng($mois){
    if($mois==1) return 'January';
    if($mois==2) return 'February';
    if($mois==3) return 'March';
    if($mois==4) return 'April';
    if($mois==5) return 'Mai';
    if($mois==6) return 'June';
    if($mois==7) return 'July';
    if($mois==8) return 'August';
    if($mois==9) return 'September';
    if($mois==10) return 'October';
    if($mois==11) return 'November';
    if($mois==12) return 'Décember';
}
?>

                    <script>
                        $('#table-salaire').dataTable( {
                            dom: 'Bfrtip',
                            buttons: [
                              //  'copy', 'csv', 'excel', 'pdf', 'print'
                                {
                                    extend: 'excelHtml5',
                                    text: '<i class="fa fa-file-excel-o"></i> Excel',
                                    titleAttr: 'Export to Excel',
                                    title: 'Bilan entrées {{$table_titre}}',
                                    exportOptions: {
                                        columns: ':not(:last-child)'
                                    }
                                }
                                ,
                                {
                                    extend: 'pdfHtml5',
                                    text: '<i class="fa fa-file-pdf-o"></i> PDF',
                                    titleAttr: 'PDF',
                                    title: 'Bilan entrées {{$table_titre}}',
                                    exportOptions: {
                                        columns: ':not(:last-child)'
                                    }
                                }
                                ,
                                {
                                    extend: 'print',
                                    text: '<i class="fa fa-print"></i> @lang("inscription.imprimer")',
                                    titleAttr: 'Imprimer',
                                    title: 'Bilan entrées {{$table_titre}}',
                                    exportOptions: {
                                        columns: ':not(:last-child)'
                                    }
                                }
                            ]
                        } );
                        @endsection
