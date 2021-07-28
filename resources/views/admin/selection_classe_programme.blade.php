@extends('skeleton')
@section('sub-content')
    <ul class="col-md-12">



                @foreach($niveaux as $n)
                    <div class="col-md-6">
                        <h4 class="page-header"> {{(App::getLocale()=='en')?$n->nom_anglais:$n->nom}}</h4>

                        @foreach($classes as $c)
                            <div class="col-md-12">
                                @if($c->id_niveau==$n->id)
                                    <li class="col-md-12"><a class="special" data-toggle="modal" data-backdrop="false" href="#choose_level" onclick="choose_level({{$c->id}}, '{{URL::to('/admin/programmes/selection-matieres/')}}', '')">{{(App::getLocale()=='en')?$c->nom_anglais:$c->nom}}</a></li>
                                @endif
                            </div>
                        @endforeach
                    </div>
                @endforeach

    </ul>



@endsection

@section('breadcrumb')
    <li><strong><a href="{{url('admin')}}">@lang('admin.dashboard')</a></strong></li>
    <li class="active"><strong>{{$titre}}</strong></li>
@endsection