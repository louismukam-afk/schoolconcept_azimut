@extends('skeleton')
@section('sub-content')
    <ul class="col-md-12">
        @foreach($cycles as $cy)
            <h4 class="page-header col-md-12">{{$cy->nom}}</h4>
            @foreach($classes as $c)
                <li class="col-md-4"><a href="{{URL::to('/admin/programmes/selection-matieres/'.$c->id.'?cycle='.$cy->id)}}">{{$c->nom}}</a></li>
            @endforeach
        @endforeach

    </ul>
@endsection