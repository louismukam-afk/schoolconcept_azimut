@extends('index')

@section('content')
    <h3 class="big-title">{{  $titre  }}</h3>
    @yield('sub-content')
@endsection