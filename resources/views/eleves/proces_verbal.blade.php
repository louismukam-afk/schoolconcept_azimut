@extends('skeleton')
@section('sub-content')

    <style>
        .table-bordered,.table-bordered thead tr,.table-bordered thead tr td,.table-bordered tbody tr,.table-bordered tbody tr td,.table-bordered thead
        {
            border-top: solid 1px black !important;
            border-bottom: solid 1px black !important;
            border-left: solid 1px black !important;
            border-right: solid 1px black !important;
        }

    </style>
    <div class="row">
        <a href="#" class="btn btn-warning btn-lg" onclick="window.print()"><span class="glyphicon glyphicon-print"></span> {{trans('inscription.imprimer')}}</a>


    </div>
    <link rel="stylesheet" media="print" href="{{URL::asset('css/print_table.css')}}">


    @include('perso.header')

<div class="titre text-center">
    <h2> <u>@lang('admin.proces_v')</u></h2>

</div>

    <div class="fiche-head" style="position: relative;padding: 5px 10px;margin-top: 25px;">
        <p style="font-size: 18px"> <strong>@lang('admin.classe'):</strong> {{$classe->nom}}</p>
    </div>

    <div class="list-cont" style="-webkit-print-color-adjust: exact">

        <table  class="table  table-bordered  table-striped table-condensed table-inverse" id="idTableListe" style="margin-top: 5px;">
        <thead >
            <tr>
                <th style="background: lightgrey!important;">Nom/<strong class="traduction">Name</strong>   </th>
                <th style="background: lightgrey!important;">Moyenne/<strong class="traduction">Average</strong>   </th>
                <th style="background: lightgrey!important;">Rang/<strong class="traduction">Rank</strong>   </th>
                <th>Décision du conseil de classe/<strong class="traduction">Class council decision</strong>   </th>
            </tr>


        </thead>
        <tbody>
            @foreach($eleves as $e)
                <tr>
                    <td><strong>{{$e->nom.' '.$e->prenom}}</strong></td>
                    <td style="background: lightgrey!important;">
                        <?php $r=1; ?>
                        @foreach($moyennes as $m)
                            @if($m->id_eleve==$e->id)
                                <strong>{{round($m->moyenne,2)}}</strong>
                                <?php break; ?>
                                @endif
                                <?php $r++; ?>

                            @endforeach
                    </td>
                    <td style="background: lightgrey!important;"><strong>{{$r}}e</strong></td>
                    <td></td>
                </tr>
            @endforeach

        </tbody>
    </table>


</div>

    <?php
    function isIn($id,$array)
    {
        $i=0;
        $trouve=false;
        while ($i<count($array) && !$trouve)
        {
            if ($array[$i]==$id){
                $trouve=true;
            }
            $i++;
        }
        return $trouve;
    }
    ?>
@endsection

@section('breadcrumb')
    <li><strong><a href="{{url('eleves')}}">@lang('menu.gestion_notes')</a></strong></li>
    <li><strong><a href="{{url('/eleves/autres')}}">@lang('admin.autre_doc')</a></strong></li>

    <li class="active"><strong>{{$titre}}</strong></li>
@endsection

