@extends('skeleton')
@section('sub-content')
    @include('perso.functions')
    <div class="row">
        <div class="col-md-12">
            <a href="#" class="btn btn-warning btn-lg" onclick="window.print()"><span class="glyphicon glyphicon-print"></span> {{trans('inscription.imprimer')}}</a>
        </div>
    </div>
    <link rel="stylesheet" media="print" href="{{URL::asset('css/print_table.css')}}">

    @include('perso.header')



    <div class="list-cont">
        <h2 style="font-size: 22px;text-align: center">{{  $titre_liste  }}</h2>

        <table  class="table table-bordered   table-striped table-condensed table-inverse" id="idTableListe" style="margin-top: 5px;">
        <thead>
        <th>#</th>
        <th>@lang('admin.matricule')</th>
        <th>{{  trans('admin.nom')  }}</th>
        <th>{{  trans('inscription.date_naiss')  }}</th>
        <th>{{  trans('inscription.lieu_naiss') }}</th>
        <th  class="sexe">{{  trans('inscription.sexe')  }}</th>
        @if(isset($show_classe))<th class="classe">@lang('admin.classe')</th>@endif
        @if(isset($show_classe))<th>@lang('admin.nniveau')</th>@endif
        @if(isset($type) && $type != 1)<th>{{trans('inscription.reste')}}</th>@endif
        <th class="action"></th>

        </thead>
        <tbody>
        <?php $i=1; $elevesStringArray = ""; $sumReste = 0?>
        @foreach($eleves as $e)
            <?php
                if($i != 1) {
                    $elevesStringArray .= ', ';
                }
            $elevesStringArray .= $e->id;
            ?>
            <tr>
                <td>{{$i++}}</td>
                <td>{{$e->matricule}}</td>
                <td><strong >{{$e->nom.' '.$e->prenom}}</strong></td>
                <td>{{(new DateTime($e->date_naiss))->format('d-m-Y')}}</td>
                <td>{{$e->lieu_naiss}}</td>
                <td style="width: 25px">{{$e->sexe}}</td>
                @if(isset($show_classe))<td>{{$e->code_classe}}</td>@endif
                @if(isset($show_classe))<td>{{$e->nniveau}}</td>@endif
                @if(isset($type) && $type != 1)<td>
                    {{$e->reste}}
                    <?php $sumReste += $e->reste?>
                </td>
                @endif
                <td class="action">
                    <div class="btn-group">
                        <a href="#" class=" btn btn-primary dropdown-toggle" data-toggle="dropdown">Actions <b class="caret"></b></a>
                        <ul class="dropdown-menu">
                            <li><a style="color: white" href="{{ URL::to('/inscriptions/edit/').'/'.$e->id }}"   class="btn-primary"><span class="glyphicon glyphicon-edit"></span> {{  trans('admin.edit_inscription')  }}</a></li>
                            <li><a style="color: white" href="#" onclick="fill_delete_modal({{$e->id}}, '{{ URL::to('/inscriptions/remove/').'/'.$e->id }}', null, null)"   class="btn-danger"><span class="glyphicon glyphicon-trash"></span> {{  trans('admin.desinscrire')  }}</a></li>
                        </ul>
                    </div>

                </td>
            </tr>
        @endforeach
        </tbody>
    </table>


        <div class="col-md-12"><strong>@lang('admin.total'): {{count($eleves)}}</strong></div>
        @if(isset($type) && $type != 1)
            <div class="col-md-12"><strong>@lang('admin.reste') @lang('admin.total'): {{$sumReste}} @lang('inscription.devise')</strong></div>

        @endif
</div>

    <script>
        var elevesArray = [{{$elevesStringArray}}];


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