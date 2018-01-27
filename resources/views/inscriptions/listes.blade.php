@extends('skeleton')
@section('sub-content')
    <a href="#" class="btn btn-warning btn-lg" onclick="window.print()"><span class="glyphicon glyphicon-print"></span> {{trans('inscription.imprimer')}}</a>
    <link rel="stylesheet" media="print" href="{{URL::asset('css/print_table.css')}}">

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
        @if(isset($show_classe))<th>Classe</th>@endif
        @if(isset($show_classe))<th>@lang('admin.nniveau')</th>@endif
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
                @if(isset($show_classe))<td>{{$e->code_classe}}</td>@endif
                @if(isset($show_classe))<td>{{$e->nniveau}}</td>@endif
                @if(isset($tranche))<td>{{$e->reste}}</td>@endif
                <td class="action">
                    <div class="btn-group">
                        <a href="#" class=" btn btn-primary dropdown-toggle" data-toggle="dropdown">Actions <b class="caret"></b></a>
                        <ul class="dropdown-menu">
                            <li><a style="color: white" href="{{ URL::to('/inscriptions/edit/').'/'.$e->id }}"   class="btn-primary"><span class="glyphicon glyphicon-edit"></span> {{  trans('admin.edit_inscription')  }}</a></li>
                            <li><a style="color: white" href="{{ URL::to('/inscriptions/remove/').'/'.$e->id }}"   class="btn-danger"><span class="glyphicon glyphicon-trash"></span> {{  trans('admin.desinscrire')  }}</a></li>
                        </ul>
                    </div>

                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
        <span class="col-md-12"><strong>Total: {{count($eleves)}}</strong></span>
</div>

@endsection