@extends('skeleton')
@section('sub-content')

    <h3>Choisir une Classe</h3>

    @foreach($classes as $c)
        <div class="col-md-4">
            <a href="{{ route('appel.selection', $c->id) }}"
               class="btn btn-info btn-block">
                {{ $c->nom }}
            </a>




        </div>
    @endforeach

@endsection
@section('breadcrumb')

    <li>
        <strong>
            <a href="{{ url('eleves') }}">
                @lang('menu.gestion_notes')
            </a>
        </strong>
    </li>

    <li class="active">
        <strong>Choix de la classe</strong>
    </li>

@endsection
