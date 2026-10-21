@extends('skeleton')
@section('sub-content')
    <div class="row">
        <a href="#" class="btn btn-warning btn-lg" onclick="window.print()"><span class="glyphicon glyphicon-print"></span> {{trans('inscription.imprimer')}}</a>


    </div>
    <link rel="stylesheet" media="print" href="{{URL::asset('css/print_table.css')}}">


<div class="titre text-center">
    <h3 class="text-uppercase"> {{$titre_liste}}</h3>

</div>



    <div class="list-cont" style="padding-top: 50px">

        <table  class="table  table-bordered  table-striped table-condensed table-inverse" id="idTableListe" style="margin-top: 5px;">
        <thead>
        <th>#</th>
        <th>{{  trans('admin.nom')  }}</th>
        <th>{{  trans('admin.moyenne')  }}</th>
        <th>{{  trans('admin.rang')  }}</th>


        </thead>
        <tbody>
        <?php $i=1; ?>
        @foreach($eleves as $e)
            <tr>
                <td>{{$i++}}</td>
                <td><strong >{{$e->nom.' '.$e->prenom}}</strong></td>
                <td><strong class="text-primary">{{round($e->moyenne,2)}}</strong></td>
                <td><strong>{{$e->rang}}</strong></td>

               
            </tr>
        @endforeach
        </tbody>
    </table>


        <span class="col-md-12"><strong>Total: {{count($eleves)}}</strong></span>
</div>


@endsection

@section('breadcrumb')
    <li><strong><a href="{{url('eleves')}}">@lang('menu.gestion_notes')</a></strong></li>
    <li><strong><a href="{{url('/eleves/autres')}}">@lang('admin.autre_doc')</a></strong></li>

    <li class="active"><strong>{{$titre}}</strong></li>
@endsection