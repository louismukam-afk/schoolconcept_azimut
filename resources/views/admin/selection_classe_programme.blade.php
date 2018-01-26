@extends('skeleton')
@section('sub-content')
    <ul class="col-md-12">



                @foreach($niveaux as $n)
                    <div class="col-md-6">
                        <h4 class="page-header"> {{(session('locale')=='en')?$n->nom_anglais:$n->nom}}</h4>

                        @foreach($classes as $c)
                            <div class="col-md-12">
                                @if( $c->id_niveau==$n->id)
                                    <li class="col-md-12"><a href="{{URL::to('/admin/programmes/selection-matieres/'.$c->id)}}">{{$c->nom}}</a></li>
                                @endif
                            </div>
                        @endforeach
                    </div>
                @endforeach

    </ul>
@endsection