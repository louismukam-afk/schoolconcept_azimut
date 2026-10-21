@extends('skeleton')
@section('sub-content')
    <h4>{{trans('eleve.sequence')}}</h4>
    <hr>
    <div class="col-md-12">
        <ul>


            <li><h4><a href="{{url('/eleves/tableau-honneur/create/').'?id='.$classe.'&sequence=7'}}">@lang('eleve.trimestre') 1</a></h4></li>
            <li><h4><a href="{{url('/eleves/tableau-honneur/create/').'?id='.$classe.'&sequence=8'}}">@lang('eleve.trimestre') 2</a></h4></li>
            <li><h4><a href="{{url('/eleves/tableau-honneur/create/').'?id='.$classe.'&sequence=9'}}">@lang('eleve.trimestre') 3</a></h4></li>
            <li><h4><a href="{{url('/eleves/tableau-honneur/create/').'?id='.$classe.'&sequence=10'}}">@lang('admin.annuel')</a></h4></li>



        </ul>
    </div>


@endsection

@section('breadcrumb')
    <li><strong><a href="{{url('inscriptions')}}">@lang('menu.gestion_inscriptions')</a></strong></li>
    <li><strong><a href="{{url('select/classe').'?url='.url('/eleves/tableau-honneur/session')}}">@lang('admin.selectionner_classe')</a></strong></li>
    <li class="active"><strong>{{$titre}}</strong></li>
@endsection