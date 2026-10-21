@extends('skeleton')
@section('sub-content')
<style>
    table.dataTable thead .nb
    {
        width: 50px;
    }
</style>

    <div class="col-md-12">  <div class="col-md-12" >
            <div class="btn-group" >
               {{-- @foreach($eleves as $e)
                    @foreach($disciplines as $d)
                        @if($e->id==$d->id_eleve)--}}
                    <a href="{{url('/eleves/discipline/state/').'?option=year'}}" class="btn btn-success"> @lang('admin.toute_lannee')</a>
                    <a href="{{url('/eleves/discipline/state/').'?option=month'}}" class="btn btn-info"> @lang('admin.mois')</a>
                    <a href="{{url('/eleves/discipline/state/').'?option=week'}}" class="btn btn-warning"> @lang('admin.semaine')</a>
                    <a href="{{url('/eleves/discipline/state/').'?option=today'}}" class="btn btn-primary"> @lang('admin.today')</a>
                    <a href="#date_range_modal" data-toggle="modal" class="btn btn-default"> @lang('admin.intervalle')</a>
                      {{--  @endif
                    @endforeach
                @endforeach--}}
            </div>


            <h4 style="margin-top: 15px;">
                @if(isset($date_debut))
                    {{$date_debut.' - '.$date_fin}}
                @else
                    {{$table_titre}}
                @endif

            </h4>


            <h3 style="font-size: 22px">{{  ($classe==null)?trans('inscription.all_eleves'):((App::getLocale()=='en'?$classe->nom_anglais:$classe->nom).' '.$nniveau->nom) }}</h3>

        <table id="table-discipline" class="table table-striped table-condensed table-inverse" style="margin-top: 15px;">
                <thead>

                <th>@lang('admin.matricule')</th>
                <th>@lang('admin.nom')</th>
                <th class="nb">{{  trans('eleve.retard')  }}</th>
                <th class="nb">{{  trans('eleve.abscence_i')  }}</th>
                <th class="nb">{{  trans('eleve.abscence_j')  }}</th>
                <th class="nb">{{  trans('eleve.avertissement')  }}</th>
                <th class="nb">@lang('eleve.blame')</th>
                <th class="nb">@lang('eleve.exclusion')</th>

                </thead>
                <tbody>
                @foreach($eleves as $e)
                    @foreach($disciplines as $d)
                        @if($e->id==$d->id_eleve)
                            <tr>
                                <td>{{$e->matricule}}</td>
                                <td><strong >{{$e->nom.' '.$e->prenom}}</strong></td>
                                <td>{{$d->retard}}</td>
                                <td>{{$d->abscence_inj}}</td>
                                <td>{{$d->abscence_just}}</td>
                                <td>{{$d->avertissement}}</td>
                                <td>{{$d->blame}}</td>
                                <td>{{$d->exclusion}}</td>

                            </tr>
                        @endif
                    @endforeach

                @endforeach
                </tbody>
            </table>


    </div>


            <div id="date_range_modal" class="modal fade">
                <div class="modal-dialog">
                    <div class="modal-content" style="width: 300px; box-shadow: none; background-color: unset">
                        <div class="panel panel-primary">
                            <div class="panel-heading">
                                <div class="panel-title">@lang('admin.intervalle')
                                    <a href="#" class="pull-right" data-dismiss="modal">X</a></div>

                            </div>
                            <div class="panel-body">
                                <form accept-charset="UTF-8" role="form" method="POST" action="{{ url('/eleves/discipline/state/')}}">
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
                title: '{{trans('admin.etat_disc')}}. {{$table_titre}}',
                {{--title: '{{trans('admin.etat_disc')}}',--}}
                exportOptions: {
                    columns: ':not(:last-child)'
                }
            }
            ,
            {
                extend: 'pdfHtml5',
                text: '<i class="fa fa-file-pdf-o"></i> PDF',
                titleAttr: 'PDF',
                title: '{{trans('admin.etat_disc')}}. {{$table_titre}}',
                {{--title: '{{trans('admin.etat_disc')}}',--}}
                exportOptions: {
                    columns: ':not(:last-child)'
                }
            }
            ,
            {
                extend: 'print',
                text: '<i class="fa fa-print"></i> @lang("inscription.imprimer")',
                titleAttr: 'Imprimer',
                title: '{{trans('admin.etat_disc')}}. {{$table_titre}}',
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