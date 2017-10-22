@extends('skeleton')
@section('sub-content')
    <ul class="col-md-12">

            @foreach($cycles as $cy)
                <h3 class="page-header col-md-12">{{$cy->nom}}</h3>

                @foreach($niveaux as $n)
                    <div class="col-md-6">
                        <h4 class="page-header"> {{(session('locale')=='en')?$n->nom_anglais:$n->nom}}</h4>

                        @foreach($classes as $c)
                            <div class="col-md-12">
                                @if($c->id_cycle==$cy->id && $c->id_niveau==$n->id)
                                    <li class="col-md-12"><a href="{{URL::to('/admin/programmes/selection-matieres/'.$c->id.'?cycle='.$cy->id)}}">{{(session('locale')=='en')?$c->nom_anglais:$c->nom}}</a></li>
                                @endif
                            </div>
                        @endforeach
                    </div>
                @endforeach

            @endforeach
    </ul>
@endsection