@extends('skeleton')
@section('sub-content')
    <h4>{{trans('eleve.choix_classe')}}</h4>
    <hr>
    <div class="liste-classe col-md-12">
        <ul>
           

                @foreach($cycles as $cy)
                    <h3 class="page-header col-md-12">{{$cy->nom}}</h3>

                    @foreach($niveaux as $n)
                        <div class="col-md-6">
                            <h4 class="page-header"> {{$n->nom.'/'.$n->nom_anglais}}</h4>

                            @foreach($classes as $c)
                                <div class="col-md-12">
                                    @if($c->id_cycle==$cy->id && $c->id_niveau==$n->id)
                                        <li class="col-md-12"><a href="{{URL::to('/eleves/notes/matieres/show/'.$c->id.'?cycle='.$cy->id)}}">{{$c->nom}}</a></li>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    @endforeach

                @endforeach


        </ul>
    </div>


@endsection