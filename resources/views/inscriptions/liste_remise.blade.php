@extends('skeleton')
@section('sub-content')
    <div class="row">
        <a href="#" class="btn btn-warning btn-lg" onclick="window.print()"><span class="glyphicon glyphicon-print"></span> {{trans('inscription.imprimer')}}</a>


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
            <th>Date de la remise</th>
            <th> Contacts</th>
            @if(isset($show_classe))<th class="classe">@lang('admin.classe')</th>@endif
            @if(isset($show_classe))<th>@lang('admin.nniveau')</th>@endif
            <th>Montant</th>

            <th class="action"></th>

            </thead>
            <tbody>
            <?php $i=1; ?>
            @foreach($eleves as $e)
                <tr>
                    <td>{{$i++}}</td>
                    <td>{{$e->matricule}}</td>
                    <td><strong >{{$e->nom.' '.$e->prenom}}</strong></td>
                    <td>{{$e->date_remise}}</td>
                    <td>{{$e->tel}}</td>
                    @if(isset($show_classe))<td>{{$e->code_classe}}</td>@endif
                    @if(isset($show_classe))<td>{{$e->nniveau}}</td>@endif
                    <td>{{$e->montant}}</td>
                    <td class="action">
                        <div class="btn-group">
                            <a href="#" class=" btn btn-primary dropdown-toggle" data-toggle="dropdown">Actions <b class="caret"></b></a>
                            <ul class="dropdown-menu">
                                <li><a style="color: white" href="{{ URL::to('/inscriptions/edit/').'/'.$e->id }}"   class="btn-primary"><span class="glyphicon glyphicon-edit"></span> {{  trans('admin.edit_inscription')  }}</a></li>
                                <li><a style="color: white" href="{{ URL::to('/inscriptions/remove/').'/'.$e->id }}"   class="btn-danger"><span class="glyphicon glyphicon-trash"></span> {{  trans('admin.desinscrire')  }}</a></li>
                                <li><a style="color: white" href="{{ URL::to('/inscriptions/remise/formulaire/').'/'.$e->id }}"   class="btn-warning"><span class="glyphicon glyphicon-apple"></span>Accorder une remise</a></li>
                            </ul>
                        </div>

                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>


        <span class="col-md-12"><strong>Total: {{count($eleves)}}</strong></span>
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
                    title: '{{$titre_liste}}',
                    exportOptions: {
                        columns: ':not(:last-child)'
                    }
                }
                ,
                {
                    extend: 'pdfHtml5',
                    text: '<i class="fa fa-file-pdf-o"></i> PDF',
                    titleAttr: 'PDF',
                    title: '{{$titre_liste}}',
                    exportOptions: {
                        columns: ':not(:last-child)'
                    }
                }

            ]
        } );
    </script>
@endsection