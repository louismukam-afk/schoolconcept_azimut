@extends('skeleton')
@section('sub-content')
    <div id="monaccordeon" class="panel-group col-lg-6">
        <h3>{{trans('inscription.choix_liste')}}</h3>
        <div class="panel panel-primary">
            <div class="panel-heading">
                <h3 class="panel-title">
                    <a class="accordion-toggle" href="#item1" dataparent="#monaccordeon"
                       data-toggle="collapse">
                        {{trans('inscription.eleves_inscrits')}}
                    </a>
                </h3>
            </div>

            <div id="item1"
                    class="panel-collapse collapsing">
                <div
                        class="panel-body">
                    <ul >
                        <li class="col-md-4"><a href={{URL::to('/inscriptions/listes/show/'.'0')}}>{{trans('inscription.all_eleves')}}</a></li>
                        @foreach($classes as $c)
                            <li class="col-md-4"><a href="{{URL::to('/inscriptions/listes/show/'.$c->id)}}">{{$c->nom}}</a></li>
                        @endforeach
                    </ul>
                </div>

            </div>
        </div>
        <div
                class="panel panel-primary">
            <div class="panel-heading">
                <h3 class="panel-title">
                    <a class="accordion-toggle" href="#item2" dataparent="#monaccordeon"
                       data-toggle="collapse">
                        {{trans('inscription.no_tranche1')}}
                    </a>
                </h3>
            </div>
            <div id="item2"
                 class="panel-collapse collapsing">
                <div
                        class="panel-body">
                    <ul >
                        <li class="col-md-4"><a href={{URL::to('/inscriptions/listes/show/'.'0'.'?option=1')}}>{{trans('inscription.all_eleves')}}</a></li>
                        @foreach($classes as $c)
                            <li class="col-md-4"><a href="{{URL::to('/inscriptions/listes/show/'.$c->id.'?option=1')}}">{{$c->nom}}</a></li>
                        @endforeach
                    </ul>
                </div>

            </div>
        </div>
        <div
                class="panel panel-primary">
            <div class="panel-heading">
                <h3 class="panel-title">
                    <a class="accordion-toggle" href="#item3" dataparent="#monaccordeon"
                       data-toggle="collapse">
                        {{trans('inscription.no_tranche2')}}
                    </a>
                </h3>
            </div>
            <div id="item3"
                 class="panel-collapse collapsing">
                <div
                        class="panel-body">
                    <ul >
                        <li class="col-md-4"><a href={{URL::to('/inscriptions/listes/show/'.'0'.'?option=2')}}>{{trans('inscription.all_eleves')}}</a></li>
                        @foreach($classes as $c)
                            <li class="col-md-4"><a href="{{URL::to('/inscriptions/listes/show/'.$c->id.'?option=2')}}">{{$c->nom}}</a></li>
                        @endforeach
                    </ul>
                </div>

            </div>
        </div>
        <div
                class="panel panel-primary">
            <div class="panel-heading">
                <h3 class="panel-title">
                    <a class="accordion-toggle" href="#item4" dataparent="#monaccordeon"
                       data-toggle="collapse">
                        {{trans('inscription.no_tranche3')}}
                    </a>
                </h3>
            </div>
            <div id="item4"
                 class="panel-collapse collapsing">
                <div
                        class="panel-body">
                    <ul >
                        <li class="col-md-4"><a href={{URL::to('/inscriptions/listes/show/'.'0'.'?option=3')}}>{{trans('inscription.all_eleves')}}</a></li>
                        @foreach($classes as $c)
                            <li class="col-md-4"><a href="{{URL::to('/inscriptions/listes/show/'.$c->id.'?option=3')}}">{{$c->nom}}</a></li>
                        @endforeach
                    </ul>
                </div>

            </div>
        </div>
        <div
                class="panel panel-primary">
            <div class="panel-heading">
                <h3 class="panel-title">
                    <a class="accordion-toggle" href="#item5" dataparent="#monaccordeon"
                       data-toggle="collapse">
                        {{trans('inscription.no_tranche4')}}
                    </a>
                </h3>
            </div>
            <div id="item5"
                 class="panel-collapse collapsing">
                <div
                        class="panel-body">
                    <ul >
                        <li class="col-md-4"><a href={{URL::to('/inscriptions/listes/show/'.'0'.'?option=4')}}>{{trans('inscription.all_eleves')}}</a></li>
                        @foreach($classes as $c)
                            <li class="col-md-4"><a href="{{URL::to('/inscriptions/listes/show/'.$c->id.'?option=4')}}">{{$c->nom}}</a></li>
                        @endforeach
                    </ul>
                </div>

            </div>
        </div>
    </div>
@endsection