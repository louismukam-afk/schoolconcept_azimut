@extends('skeleton')
@section('sub-content')
    <link rel="stylesheet" media="print" href="{{URL::asset('css/print_table.css')}}">
    <a href="#" class="btn btn-warning btn-lg" onclick="window.print()"><span class="glyphicon glyphicon-print"></span> {{trans('inscription.imprimer')}}</a>

    @include('perso.header')
    <div class="list-cont">
    <table class="table table-bordered table-striped table-condensed table-inverse" style="margin-top: 5px;">
        <thead>
        <caption style="font-size: 22px">{{  $titre_liste  }}</caption>
        <th>#</th>
        <th>@lang('admin.matricule')</th>
        <th>{{  trans('admin.nom')  }}</th>
        <th>{{  trans('inscription.date_naiss')  }}</th>
        <th>{{  trans('inscription.lieu_naiss') }}</th>
        <th>{{  trans('inscription.sexe')  }}</th>
        <th>Classe</th>
        @if(isset($tranche))<th>{{trans('inscription.reste')}}</th>@endif

        </thead>
        <tbody>
        <?php $i=1; ?>
        @foreach($eleves as $e)
            <tr>
                <td>{{$i++}}</td>
                <td>{{$e->matricule}}</td>
                <td><strong >{{$e->nom.' '.$e->prenom}}</strong></td>
                <td>{{$e->date_naiss}}</td>
                <td>{{$e->lieu_naiss}}</td>
                <td>{{$e->sexe}}</td>
                <td>{{$e->nom_classe}}</td>
                @if(isset($tranche))<td>{{-$e->reste}}</td>@endif
                <td class="action">
                    <div class="btn-group">
                        <a href="#" class=" btn btn-primary dropdown-toggle" data-toggle="dropdown">Actions <b class="caret"></b></a>
                        <ul class="dropdown-menu">
                            <li><a style="color: white" title="Modifier les informations" class="btn-primary"  data-toggle="modal" data-backdrop="false" href="{{URL::to('/inscriptions/enregistrement/edit/'.$e->id)}}"><span class="glyphicon glyphicon-pencil"  ></span> {{  trans('admin.edit')  }}</a></li>
                            <li><a style="color: white" href="{{ URL::to('/inscriptions/create/').'/'.$e->id }}"   class="btn-success"><span class="glyphicon glyphicon-cloud-upload"></span> {{  trans('inscription.inscrire')  }}</a></li>
                        </ul>
                    </div>
            </tr>
        @endforeach
        </tbody>
    </table>
        <span class="col-md-12"><strong>Total: {{count($eleves)}}</strong></span>
</div>

@endsection