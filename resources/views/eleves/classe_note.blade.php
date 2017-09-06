@extends('skeleton')
@section('sub-content')
    <h4>{{trans('eleve.choix_classe')}}</h4>
    <hr>
    <div class="liste-classe col-md-9">
        <ul>
            @foreach($classes as $c)
                <li class="col-md-3"><a href="{{URL::to('/eleves/notes/matieres/'.$c->id)}}">{{$c->nom}}</a></li>
            @endforeach
        </ul>
    </div>


@endsection