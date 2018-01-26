@extends('skeleton')
@section('sub-content')
    <div class="col-md-8">
        @foreach($horaires as $h)
            <?php $i=0; ?>
            <ul>
                @foreach($occupied_horaire as $oh)
                    @if($h->id==$oh->id_horaire)

                        <li><strong><a href="#">{{$h->intervalle}}</a> - </strong> {{trans('admin.occupe')}}  <?php $mat=\gesecole\matiere::find($oh->id_matiere);$pers=\gesecole\personnel::find($oh->id_personnel) ?> {{  (session('locale')=='en')?$mat->nom_anglais:$mat->nom }} - {{$pers->nom}} </li>
                    <?php $i++;break; ?>
                    @endif
                @endforeach
                @if($i==0)
                    <li><strong><a href="{{url('/personnel/dispense/store'.'?id_matiere='.$id_matiere.'&id_personnel='.$id_personnel.'&id_classe='.$id_classe.'&jour='.$jour.'&id_horaire='.$h->id)}}">{{$h->intervalle}}</a></strong></li>
                    @endif
            </ul>

        @endforeach
    </div>
@endsection