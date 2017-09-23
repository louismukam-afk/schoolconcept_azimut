@extends('skeleton')
@section('sub-content')
    <h4>{{trans('eleve.choix_classe')}}</h4>
    <hr>
    <div class="liste-classe col-md-12">
        <ul>
            @foreach($cycles as $cy)
                <h4 class="page-header col-md-12">{{$cy->nom}}</h4>
                @foreach($classes as $c)
                    <li class="col-md-4"><a href="{{URL::to('/eleves/notes/matieres/'.$c->id.'?cycle='.$cy->id)}}">{{$c->nom}}</a></li>
                @endforeach
            @endforeach

        </ul>
    </div>


@endsection