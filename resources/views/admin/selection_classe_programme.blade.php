@extends('skeleton')
@section('sub-content')
    <ul >
            @foreach($classes as $c)
                <li class="col-md-4"><a href="{{URL::to('/admin/programmes/selection-matieres/'.$c->id)}}">{{$c->nom}}</a></li>
            @endforeach
    </ul>
@endsection