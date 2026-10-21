@extends('skeleton')
@section('sub-content')
    <div class="row">
        <a href="#" class="btn btn-warning btn-lg" onclick="window.print()"><span class="glyphicon glyphicon-print"></span> {{trans('inscription.imprimer')}}</a>


    </div>
    <link rel="stylesheet" media="print" href="{{URL::asset('css/print_table.css')}}">


    @include('perso.header')

<div class="titre text-center">
    <h3> @lang('admin.rapport_c')
    @if($sequence>8 && $sequence<12)
            @lang('eleve.trimestre') {{$sequence - 8}}
        @else
            {{strtolower(trans('admin.annuel'))}}
        @endif
    </h3>

</div>


    <div class="list-cont">

        <table  class="table  table-bordered  table-striped table-condensed table-inverse" id="idTableListe" style="margin-top: 5px;">
        <thead>
            <tr>
                <th rowspan="2">@lang('admin.classe')</th>
                <th colspan="2">Eff</th>
                <th rowspan="2">Total</th>
                <th colspan="2">Moy>=10</th>
                <th rowspan="2">Total</th>
                <th colspan="2"><p>%Réussite</p><p class="traduction">Success</p></th>
                <th rowspan="2" >Moy/Av</th>

                <th colspan="2"><p>Travail</p><p class="traduction">Work</p></th>
                <th colspan="4"><p>Conduite</p><p class="traduction">Behavior</p></th>
                <th colspan="3"><p>Observations générales</p><p class="traduction">Generals observations</p></th>
            </tr>
        <tr>
            <th>F</th>
            <th>G</th>
            <th>F</th>
            <th>G</th>
            <th>F</th>
            <th>G</th>
            <th>BT</th>
            <th>AT</th>
            <th>AC</th>
            <th>BC</th>
            <th>EXC</th>
            <th>T.ABS</th>
            <th>1e/1st</th>
            <th>Der/Last</th>
            <th>%Reuss/Success</th>



        </tr>

        </thead>
        <tbody>
        @foreach($conseil_classe as $cc)
            <tr>
                <td><strong>{{$cc->classe->nom}}</strong></td>
                <td>{{$cc->efff}}</td>
                <td>{{$cc->effm}}</td>
                <td>{{$cc->eff}}</td>
                <td>{{$cc->nbMoyF}}</td>
                <td>{{$cc->nbMoyG}}</td>
                <td>{{$cc->nbMoy}}</td>
                <td>{{round($cc->reussF,2)}}%</td>
                <td>{{round($cc->reussG,2)}}%</td>
                <td>{{round($cc->moy,2)}}</td>
                <td>{{$cc->nbBT}}</td>
                <td>{{$cc->nbAT}}</td>
                <td>{{$cc->AC}}</td>
                <td>{{$cc->BC}}</td>
                <td>{{$cc->EXC}}</td>
                <td>{{$cc->TABS}}</td>
                <td>{{round($cc->max,2)}}</td>
                <td>{{round($cc->min,2)}}</td>
                <td>{{round($cc->reuss,2)}}%</td>

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
