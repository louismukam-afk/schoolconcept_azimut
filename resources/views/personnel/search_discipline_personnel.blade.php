

@extends('skeleton')
@section('sub-content')
    @if(isset($success))

        <div class="alert alert-success">
            {{  trans('admin.succes')  }}
        </div>


    @endif

    @if(isset($custom_error))

        <div class="alert alert-danger">
            {{  $custom_error  }}
        </div>

    @endif
    <div id="monaccordeon" class="panel-group col-md-12">
        <h3>{{trans('inscription.choix_liste')}}</h3>
        <div class="panel panel-primary">
            <div class="panel-heading">
                <h3 class="panel-title">
                    <a class="accordion-toggle" href="#item1" dataparent="#monaccordeon"
                       data-toggle="collapse">
                        {{trans('admin.etat_disc')}}
                    </a>
                </h3>
            </div>

            <div id="item1"
                 class="panel-collapse collapsing">
                <div
                        class="panel-body">
                    <ul>
                        <li><strong><a href="{{route('etat_disciplinaire_personnel')}}">@lang('admin.tout_personnel')</a></strong></li>
                    </ul>




                </div>

            </div>
        </div>
    </div>




    <div class="col-md-8">
        <form class="form-horizontal" id="add_inscription_form" method="get" action="{{URL::to('/search/personnel/')}}">
            <input type="hidden" name="_token" value="{{ csrf_token() }}"/>
            <input type="hidden" name="view" value="personnel/search_discipline_personnel">
            <input type="hidden" name="titre" value="{{trans('admin.search_personnel')}}">
            <div class="col-md-6">
                <div class="form-group">
                    <label for="search">{{  trans('inscription.recherche')  }} : </label>
                    <div class="input-group">
                        <input type="search" name="search" placeholder="{{trans('inscription.nom')}}" class="form-control" autofocus>
                        <div class="input-group-btn">
                            <button type="submit" class="btn btn-primary" ><span class="fa fa-search"> </span>{{trans('inscription.search')}}</button>
                        </div>
                    </div>
                </div>
            </div>
        </form>

    </div>

    <div class="col-md-8">
        @if(session('search_result'))
            <table class="table table-bordered table-striped table-condensed table-inverse" style="margin-top: 15px;">
                <thead>
                <caption style="font-size: 22px">{{  trans('inscription.search_result')  }}</caption>
                <th>#</th>
                <th>{{  trans('admin.nom')  }}</th>

                <th>{{  trans('inscription.sexe')  }}</th>

                </thead>
                <tbody>
                <?php $i=1; ?>
                @foreach($personnel as $p)
                    <tr>
                        <td>{{$i++}}</td>
                        <td><strong >{{$p->nom}}</strong></td>
                        <td>{{$p->sexe}}</td>
                        <td>
                                    <a href="{{ URL::to('/personnel/discipline/show').'/'.$p->id }}"   class="btn btn-warning"><span class="fa fa-bell-o"></span> {{  trans('eleve.titre_discipline')  }}</a>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
            <div class="paginate" style="text-align: center;"> <?php echo(str_replace('/?', '?view=personnel/search_discipline_personnel&', $personnel->render()) ); ?></div>

            {{Session::forget('search_result')}}
        @endif
    </div>
@endsection

@section('breadcrumb')
    <li><strong><a href="{{url('personnel')}}">@lang('menu.gestion_personnel')</a></strong></li>

    <li class="active"><strong>{{$titre}}</strong></li>
@endsection