@extends('skeleton')
@section('sub-content')
    <div class="row">
        <a href="#" class="btn btn-warning btn-lg" onclick="window.print()"><span class="glyphicon glyphicon-print"></span> {{trans('inscription.imprimer')}}</a>


    </div>
    <link rel="stylesheet" media="print" href="{{URL::asset('css/print_table.css')}}">

    @include('perso.header3')



    <div class="list-cont">
        <h2 style="font-size: 22px;text-align: center">{{  $titre_liste  }}</h2>
        <h4><strong>Intitulé de l'unité d'enseignement:..........................................................................</h4></strong>
        <h4><strong>Nom de l'enseignant: ......................................................................................</h4></strong>
 <h5><p>Semestre. ............................. ..............................                       <strong>Année académique.........................................................................................</strong> </p>  </h5>





        <table  class="table table-bordered   table-condensed table-inverse" id="idTableListe" style="margin-top: 5px;">
        <thead>
        <th rowspan="1">#</th>
        <th rowspan="1">@lang('admin.matricule')</th>
        <th rowspan="1">{{  trans('admin.nom')  }}</th>
        <th colspan="4">Notes</th>
        </thead>
         <tr>

            <td></td>
            <td></td>
            <td></td>
            <td>CC/</td>
            <td>TP/</td>
            <td>TPE/</td>
            <td>TOTAL /30</td>

        </tr>
        <tbody
         
        <?php $i=1; ?>
        @foreach($eleves as $e)
      
            <tr>
                <td>{{$i++}}</td>
                <td>{{$e->matricule}}</td>
                <td><strong >{{$e->nom.' '.$e->prenom}}</strong></td>
                <td></td>
                <td></td>
                <td style="width: 25px"></td>
                <td></td>
                @if(isset($show_classe))<td>{{$e->code_classe}}</td>@endif
                @if(isset($show_classe))<td>{{$e->nniveau}}</td>@endif
                @if(isset($tranche))<td>{{$e->reste}}</td>@endif

            
            </tr>
        @endforeach
        </tbody>
    </table>


        <span class="col-md-12"><strong>Total: {{count($eleves)}}</strong></span>
</div>

<div class="raac pull-left" style="text-align: left; margin-top: 20%;    font-family: "Times New Roman" !important;"> <strong>Responsable des Affaires Académiques </strong></div>
<div class="enseignants" style="text-align: right; margin-top: 20%;    font-family: "Times New Roman" !important;"  ><strong> Enseignants
    
</strong></div>

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