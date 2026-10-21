@extends('skeleton')
@section('sub-content')
    @include('perso.functions')
    <?php $standartLimite=7;
    $standartLimite1=7;
    $current_matiere=0;
    $current_matiere1=0;
    $current_note=0;
    $admis=0;
    $echec=0;

    ?>
    <style media="screen">
        .table-note
        {
            display: none;
        }
        div.table
        {
            overflow-x: scroll;
        }

    </style>
    <style media="print">
        #note-excel,.dt-buttons,#note-excel_filter,#note-excel_info,#note-excel_paginate
        {
            display: none;
        }

    </style>
    <a href="#" class="btn btn-lg btn-warning" onclick="exportSoaPvPdf('A4', true); return false;"><span class="glyphicon glyphicon-print"></span> Aperçu / imprimer A4</a>
    <button type="button" class="btn btn-lg btn-primary" onclick="exportSoaPvPdf('A4')"><span class="fa fa-file-pdf-o"></span> Exporter PDF A4</button>
    <button type="button" class="btn btn-lg btn-primary" onclick="exportSoaPvPdf('A3')"><span class="fa fa-file-pdf-o"></span> Exporter PDF A3</button>
    <span id="soa-pdf-status" role="status" aria-live="polite"></span>
    <link rel="stylesheet"  href="{{URL::asset('css/pv.css')}}">

    <?php $pv_type = 'licence_soa'; $pv_title_mode = 'semestre'; ?>
    @include('eleves.partials.pv_tutelle_header')

    <div class="titre">
        <p style="font-size: 19px"><strong>Résultats  des Examens du semestre</strong></p>
        <strong class="traduction">Semester exam results</strong>
    </div>

    <?php $soaLevel = \gesecole\Services\BtsAnnualTranscript::levelLabel($nniveau->nom); ?>
    <style>
        .soa-pv-context { display: grid; grid-template-columns: 1fr 1fr; gap: 16px; }
        .soa-pv-context .specialite, .soa-pv-context .filiere { position: static; min-width: 0; }
    </style>
    <div class="classe soa-pv-context">
        <div class="specialite">
            <p><strong>Spécialité : {{$classe->nom}} - Niveau {{ $soaLevel }}</strong></p>
            <strong class="traduction">Speciality : {{$classe->nom_anglais}} - Level {{ $soaLevel }}</strong>
        </div>

        <div class="filiere">
            <p ><strong>Filière: {{\gesecole\niveau::find($classe->id_niveau)->nom}}</strong></p>
            <strong class="traduction">Path : {{\gesecole\niveau::find($classe->id_niveau)->nom_anglais}}</strong>
        </div>

    </div>
    @include('eleves.partials.soa_pv_results')

    <h3 class="soa-pv-signature" style="text-align: right;">Responsable des Affaires Academiques </h3>


    <script src="{{ asset('js/datatables/pdfmake-0.1.36/pdfmake.min.js') }}"></script>
    <script src="{{ asset('js/datatables/pdfmake-0.1.36/vfs_fonts.js') }}"></script>
    <script src="{{ asset('js/soa_pv_pdf.js') }}?v={{ sha1_file(public_path('js/soa_pv_pdf.js')) }}"></script>
    <script>

        $('#note-excel').dataTable( {
            dom: 'Bfrtip',
            pageLength:2000,
            order: [],
            buttons: [
                //'copy', 'csv', 'excel', 'pdf', 'print'
                {
                    extend: 'excelHtml5',
                    text: '<i class="fa fa-file-excel-o"></i> Excel',
                    titleAttr: 'Export to Excel',
                    title: 'Notes {{$classe->nom}}'

                }

            ]
        } );


    </script>
@endsection
@section('breadcrumb')
    <li><strong><a href="{{url('eleves')}}">@lang('menu.gestion_notes')</a></strong></li>
    <li><strong><a href="{{url('eleves/notes')}}">Notes</a></strong></li>

    <li><a href="{{url('eleves/notes/show')}}"><strong>@lang('admin.choix_classe')</strong></a></li>
    <li><a href="{{url('eleves/notes/matieres/show').'?id='.$classe->id.'&cycle='.$id_cycle.'&nniveau='.$nniveau->id}}"><strong>@lang('admin.titre_matiere')</strong></a></li>

    <li class="active"><strong>{{$titre}}</strong></li>
@endsection
