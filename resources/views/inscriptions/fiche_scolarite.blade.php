@extends('skeleton')
@section('sub-content')
    @include('perso.functions')
    <style >

        .table  tr,.table  tr td, .table  th,.table  th td{
            padding-top: 0!important;
            padding-bottom: 0!important;
            height: 12px!important;
            font-size: 10px!important;
            line-height: 11px!important;

        }

        .table  tr.center-cell,.table  tr td.center-cell, .table  th.center-cell,.table  th td.center-cell{
            text-align: center;
            vertical-align: middle;
        }

        table td.back-green, table th.back-green {
            color-adjust: exact!important;
            -webkit-print-color-adjust: exact!important;
            background-color: greenyellow!important;
        }

        table td.back-yellow, table th.back-yellow {
            color-adjust: exact!important;
            -webkit-print-color-adjust: exact!important;
            background-color: yellow!important;
        }

        table td.back-black, table th.back-black {
            color-adjust: exact!important;
            -webkit-print-color-adjust: exact!important;
            background-color: black!important;
        }

        table tr td.border-left-bold, table tr th.border-left-bold {
            border-left-width: 10px!important;
        }
    </style>
    <div class="row">
        <div class="col-md-12">
            <a href="#" class="btn btn-warning btn-lg" onclick="window.print()"><span class="glyphicon glyphicon-print"></span> {{trans('inscription.imprimer')}}</a>
        </div>
    </div>
    <link rel="stylesheet" media="print" href="{{URL::asset('css/print_scolarite_state.css')}}">

    @include('perso.header')


    <?php $maxNbVersements = 9 ; $sumFrais = []; $sumPayments = []; $sumIns = 0;?>
    <div class="list-cont">
        <h2 style="font-size: 22px;text-align: center">{{  $titre_liste  }}</h2>

        <table  class="table table-bordered   table-striped table-condensed table-inverse" style="margin-top: 5px;">
            <tr>
                <th class="center-cell back-green" rowspan="3">#</th>
                <th class="center-cell back-green" rowspan="3">{{  trans('admin.nom_prenom')  }}</th>
                <th class="center-cell back-green text-uppercase" colspan="{{count($frais) + 1}}">{{trans('admin.modalite_inscription')}}</th>
                <th class="center-cell back-green text-uppercase border-left-bold   " colspan="{{$maxNbVersements + 1}}">{{trans('admin.schoolfees')}}</th>
                <th class="center-cell back-green" rowspan="3">@lang('admin.observations')</th>
            </tr>
            <tr>
                <th class="center-cell">INS</th>
                @foreach($frais as $f)
                    <th class="center-cell"> {{$f->getPrintedNom()}} </th>
                @endforeach
                <th class="center-cell border-left-bold" rowspan="2"><div>@lang('admin.montant')</div><div>(@lang('inscription.devise'))</div></th>

                    @for ($i = 0; $i < $maxNbVersements; $i++)
                        <th class="center-cell" rowspan="2"> <div>{{($i + 1).getIeme(($i + 1))}}</div><div>@lang('admin.versement')</div></th>

                    @endfor

            </tr>
            <tr>
                <th class="center-cell">{{$scolarite->inscription}}</th>
                @foreach($frais as $f)
                    <th class="center-cell"> {{$f->montant}} </th>
                @endforeach
            </tr>


        <tbody>
        <?php $i=1?>
        @foreach($eleves as $e)
            <?php $m= 0?>
            <tr>
                <td class="center-cell" rowspan="2">{{$i++}}</td>
                <td class="center-vertical-cell" rowspan="2"><strong >{{$e->nom.' '.$e->prenom}}</strong></td>
                <td class="center-cell" rowspan="2">
                    {{$e->montant_inscription}}
                    <?php $sumIns += $e->montant_inscription ?>
                </td>
                @foreach($frais as $f)
                    <td class="center-cell" rowspan="2">
                    @foreach($e->payements_frais as $p)
                        @if($p->type == $f->id)

                             {{$p->montant}}
                            @if(isset($sumFrais[$m]))
                                    <?php $sumFrais[$m] += $p->montant ?>
                            @else
                                    <?php $sumFrais[$m] = $p->montant ?>
                            @endif
                            <?php break ?>
                        @endif
                    @endforeach
                    </td>
                    <?php $m++ ?>
                @endforeach
                <td class="center-cell border-left-bold" rowspan="2">{{$scolarite->total}}</td>
                @for($j = 0; $j < $maxNbVersements; $j++)
                    <td class="center-cell" >
                            @if(isset($e->payements[$j]))
                                {{$e->payements[$j]->montant}}
                                @if(isset($sumPayments[$j]))
                                    <?php $sumPayments[$j] += $e->payements[$j]->montant ?>
                                @else
                                    <?php $sumPayments[$j] = $e->payements[$j]->montant ?>
                                @endif
                            @endif
                    </td>
                @endfor
                <td rowspan="2"></td>
            </tr>
            <tr>
                @for($k = 0; $k < $maxNbVersements; $k++)
                    <td class="center-cell" >
                        @if(isset($e->payements[$k]))
                            {{(new DateTime($e->payements[$k]->date_payement))->format('d/m/Y')}}
                        @endif
                    </td>
                @endfor
            </tr>
        @endforeach
        <tr>
            <td class="center-cell back-yellow text-uppercase" colspan="2">@lang('admin.total')</td>
            <td class="center-cell"> {{$sumIns}}</td>
            @for($n=0; $n < count($frais); $n++)
                <td class="center-cell">
                    @if(isset($sumFrais[$n]))
                        {{$sumFrais[$n]}}
                    @endif
                </td>
            @endfor
            <td class="border-left-bold back-black"></td>
            @for($n=0; $n < $maxNbVersements; $n++)
                <td class="center-cell">
                    @if(isset($sumPayments[$n]))
                        {{$sumPayments[$n]}}
                    @endif
                </td>
            @endfor
                <td class="back-black"></td>
        </tr>
        </tbody>
    </table>
        
</div>

    <script>
        $('#idTableListe').dataTable( {
            dom: 'Bfrtip',
            pageLength:5000,
            buttons: [
                //'copy', 'csv', 'excel', 'pdf', 'print'
                {
                    extend: 'excelHtml5',
                    text: '<i class="fa fa-file-excel-o"></i> Excel',
                    titleAttr: 'Export to Excel',
                    title: '{!! sanitize($titre_liste) !!}',
                    exportOptions: {
                        columns: ':not(:last-child)'
                    }
                }
                ,
                {
                    extend: 'pdfHtml5',
                    text: '<i class="fa fa-file-pdf-o"></i> PDF',
                    titleAttr: 'PDF',
                    title: '{!! sanitize($titre_liste) !!}',
                    exportOptions: {
                        columns: ':not(:last-child)'
                    }
                }

            ]
        } );
    </script>
@endsection
@section('breadcrumb')
    <li><strong><a href="{{url('inscriptions')}}">@lang('menu.gestion_inscriptions')</a></strong></li>
    <li><strong><a href="{{url('inscriptions/selection-listes')}}">@lang('inscription.listes')</a></strong></li>
@endsection