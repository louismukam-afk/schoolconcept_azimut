@extends('index')

@section('content')

    <h3 class="big-title">{{  isset($titre)? $titre : 'School-concept'  }}</h3>
    @yield('sub-content')
@endsection