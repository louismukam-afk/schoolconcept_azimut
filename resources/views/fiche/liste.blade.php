@extends('skeleton')
@section('sub-content')
    <div class="row">
        <a href="#" class="btn btn-warning btn-lg" onclick="window.print()"><span class="glyphicon glyphicon-print"></span> {{trans('inscription.imprimer')}}</a>


    </div>
    <link rel="stylesheet" media="print" href="{{URL::asset('css/print_table.css')}}">

    @include('perso.header2')



    <div class="list-cont">
        <h2 style="font-size: 22px;text-align: center"> <strong>{{  $titre_liste  }} .................................................</strong></h2>
        <h4><strong> Heure de passage: ............../...............</h4></strong>
        <h4><strong> Date du jour: ............................   Surveiller par: ................................................</h4></strong>
        <h5><p>Semestre. .....................................................................................                        <strong>Année académique.......................................................</strong> </p>  </h5>






        <table  class="table table-bordered   table-condensed table-inverse" id="idTableListe" style="margin-top: 5px;">
        <thead>
  {{--      <caption style="text-align: center;"><strong>NB:</strong> Cette fiche est unique à chaque spécialité/option et par module/ue</caption>--}}
        <th>#</th>
        <th>@lang('admin.matricule')</th>
        <th>{{  trans('admin.nom')  }}</th>
        <th>Signature de l'étudiant</th>
  <th>Observations</th>
        </thead>
        <tbody>
         
        <?php $i=1; ?>
        @foreach($eleves as $e)
        <tr>
      
                <td>{{$i++}}</td>
                <td>{{$e->matricule}}</td>
                <td><strong >{{$e->nom.' '.$e->prenom}}</strong></td>
                <td></td>
            <td></td>
            </tr>
        @endforeach
        </tbody>
    </table>

<br>
<br>

        <span class="col-md-12"><strong>Total: {{count($eleves)}}</strong></span>
        <br>
        <br>
      {{--   <strong class="col-md-12">Conformément au LMD, les notes d'examen sont reportés sur 100 par étudiant</strong>
          <strong  class="col-md-12">(Exemple: note sur 100= note sur 20*5)</strong>--}}

</div>

<div class="raac pull-left" style="text-align: left; margin-top: 20%;    font-family: "Times New Roman" !important;"> <strong> Nombre de copie(s)</strong></div>

<div class="enseignants" style="text-align: right; margin-top: 20%;    font-family: "Times New Roman" !important;"  ><strong> Noms et signature des surveillants de salle
    
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