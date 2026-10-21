@extends('index')
@section('content')
    @if($inscription==null)
        <div class="alert alert-danger">
            @lang('inscription.pas_inscrit')
        </div>
    @else
        <a href="" onclick="window.print()" class="btn btn-warning btn-lg">@lang('inscription.imprimer')</a>
        <link rel="stylesheet" href="{{URL::asset('css/certificat.css')}}">
        @include('inscriptions.partials.certificate_scolarite')
    @endif
@endsection

@section('breadcrumb')
    <li><strong><a href="{{url('inscriptions')}}">@lang('menu.gestion_inscriptions')</a></strong></li>
    <li><strong><a href="{{url('inscriptions/new')}}">@lang('inscription.title_search_register')</a></strong></li>
@endsection
