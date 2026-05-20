@extends('skeleton')
@section('sub-content')
    @include('perso.functions')
    <div class="col-md-12">
        @if(isset($success))

            <div class="alert alert-success">
                {{  trans('admin.succes')  }}
            </div>

        @endif
        <style>
            table.dataTable thead .nb
            {
                width: 50px;
            }
        </style>
        <h4>Liste des absences justifiées de {{$pers->nom.' '.$pers->prenom}}</h4>


        <div class="col-md-12" >
            <div class="btn-group" >
                @foreach($discipline as $disciplines)
                    <a href="{{url('/eleves/discipline/index_justifier/').'/'.$disciplines->id_eleve.'?option=year'}}" class="btn btn-success"> @lang('admin.toute_lannee')</a>
                    <a href="{{url('/eleves/discipline/index_justifier/').'/'.$disciplines->id_eleve.'?option=month'}}" class="btn btn-info"> @lang('admin.mois')</a>
                    <a href="{{url('/eleves/discipline/index_justifier/').'/'.$disciplines->id_eleve.'?option=week'}}" class="btn btn-warning"> @lang('admin.semaine')</a>
                    <a href="{{url('/eleves/discipline/index_justifier/').'/'.$disciplines->id_eleve.'?option=today'}}" class="btn btn-primary"> @lang('admin.today')</a>
                    <a href="#date_range_modal" data-toggle="modal" class="btn btn-default"> @lang('admin.intervalle')</a>
                @endforeach
            </div>


            <h4 style="margin-top: 15px;">
                @if(isset($date_debut))
                    {{$date_debut.' - '.$date_fin}}
                @else
                    {{$table_titre}}
                @endif

            </h4>


            <form id="justify_form" >
                <input type="hidden" name="_token" value="{{ csrf_token() }}"/>

                <table id="table-discipline" class=" table  table-striped table-condensed table-inverse" style="margin-top: 15px;">
                    <thead>
                    <th>#</th>
                    <th style="width:150px">@lang('admin.nombre_heure_abscence')</th>
                    <th style="width:150px">Heure(s) de début</th>
                    <th style="width:150px">heure(s) de fin</th>
                    <th style="width:150px">Motifs</th>
                    <th >Details</th>
                    <th style="width: 100px">Date</th>



                    </thead>
                    <?php $i=1; ?>
                    <tbody id="abscence_inj_pa">
                    @foreach($discipline as $d)
                        {{--@if($d->type==0 and $d->just==0)--}}
                        <tr>
                            <td>{{$i++}}</td>
                            <td>{{$d->nb_heures}}</td>
                            <td>{{$d->heure_debut}}</td>
                            <td>{{$d->heure_fin}}</td>
                            <td>{{$d->motif_justification}}</td>
                            <td>{{$d->description}}</td>
                            <td>{{$d->ddate}}</td>

                        </tr>
                        {{--@endif--}}
                    @endforeach
                    </tbody>
                </table>
            </form>
        </div>
        @foreach($discipline as $d)
            <div id="date_range_modal" class="modal fade">
                <div class="modal-dialog">
                    <div class="modal-content" style="width: 300px; box-shadow: none; background-color: unset">
                        <div class="panel panel-primary">
                            <div class="panel-heading">
                                <div class="panel-title">@lang('admin.intervalle')
                                    <a href="#" class="pull-right" data-dismiss="modal">X</a></div>

                            </div>
                            <div class="panel-body">
                                <form accept-charset="UTF-8" role="form" method="POST" action="{{ url('/eleves/discipline/index_injustifier/').'/'.$d->id_eleve }}">
                                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                    <input type="hidden" name="date_range" value="1">
                                    <fieldset>

                                        <div class="form-group">
                                            <label for="date_debut">{{  trans('admin.date_debut')  }} : </label>
                                            <input readonly placeholder="yyyy/mm/dd" class="form-control datepicker" id="date_debut" name="date_debut" type="text"  value="{{ old('date_debut') }}" required>

                                        </div>
                                        <div class="form-group ">
                                            <label for="date_fin">{{  trans('admin.date_fin')  }} : </label>
                                            <input readonly placeholder="yyyy/mm/dd" class="form-control datepicker" id="date_fin" name="date_fin" type="text"  value="{{ old('date_fin') }}" required >

                                        </div>
                                        <input  class="btn  btn-primary pull-right " type="submit" value="{{  trans('admin.confirmer')  }}">


                                    </fieldset>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        @endforeach








        <script>

            $('#table-discipline').dataTable( {
                pageLength : 50,
                dom: 'Bfrtip',
                buttons: [
                    //'copy', 'csv', 'excel', 'pdf', 'print'
                    {
                        extend: 'excelHtml5',
                        text: '<i class="fa fa-file-excel-o"></i> Excel',
                        titleAttr: 'Export to Excel',
                        title: '{{trans('admin.etat_disc')}}',
                        exportOptions: {
                            columns: ':not(:last-child)'
                        }
                    }
                    ,
                    {
                        extend: 'pdfHtml5',
                        text: '<i class="fa fa-file-pdf-o"></i> PDF',
                        titleAttr: 'PDF',
                        title: '{{trans('admin.etat_inj')}}',
                        exportOptions: {
                            columns: ':not(:last-child)'
                        }
                    }
                    ,
                    {
                        extend: 'print',
                        text: '<i class="fa fa-print"></i> @lang("inscription.imprimer")',
                        titleAttr: 'Imprimer',
                        title: '{{trans('admin.etat_inj')}}',
                        exportOptions: {
                            columns: ':not(:last-child)'
                        }
                    }
                ]
            } );
            $(function () {
                //$('#date_debut').datepicker({ dateFormat: 'dd-mm-yy' });
                //$('#date_fin').datepicker({ dateFormat: 'dd-mm-yy' });
            });



        </script>
        @endsection
        @section('breadcrumb')
            <li><strong><a href="{{url('eleves')}}">@lang('menu.gestion_notes')</a></strong></li>
            <li><strong><a href="{{url('eleves/discipline')}}">@lang('inscription.title_search')</a></strong></li>

            <li class="active"><strong>{{$titre}}</strong></li>
@endsection