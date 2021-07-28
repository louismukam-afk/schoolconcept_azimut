@extends('skeleton')
@section('sub-content')
    <h4>{{trans('eleve.choix_classe')}}</h4>
    <hr>
    <div class="liste-classe col-md-12">
        <ul>



                    @foreach($niveaux as $n)
                        <div class="col-md-6">
                            <h4 class="page-header"> {{(App::getLocale()=='en')?$n->nom_anglais:$n->nom}}</h4>

                            @foreach($classes as $c)
                                <div class="col-md-12">
                                    @if($c->id_niveau==$n->id)
                                        <li class="col-md-12"><a class="special" data-toggle="modal" data-backdrop="false" href="#choose_level" onclick="choose_level({{$c->id}}, '{{URL::to('/eleves/notes/matieres/')}}', '')">{{(App::getLocale()=='en')?$c->nom_anglais:$c->nom}}</a></li>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    @endforeach



        </ul>
    </div>



@endsection

@section('breadcrumb')
    <li><strong><a href="{{url('eleves')}}">@lang('menu.gestion_notes')</a></strong></li>
    <li><strong><a href="{{url('eleves/notes')}}">Notes</a></strong></li>

    <li class="active"><strong>@lang('admin.choix_classe')</strong></li>
@endsection