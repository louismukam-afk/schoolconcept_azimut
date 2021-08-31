@extends('skeleton')
@section('sub-content')
        @if(isset($success))

        <div class="alert alert-success">
            {{  trans('admin.succes')  }}
        </div>
        <a  class="btn btn-warning btn-lg" href="{{URL::to('/inscriptions/payement/print/'.$success)}}"><span class="glyphicon glyphicon-print"></span> {{trans('inscription.print_receipe')}}</a>

    @endif


    <div class="col-md-8">
        <form class="form-horizontal" id="add_inscription_form" method="get" action="{{URL::to('/inscriptions/payement/search')}}">
            <input type="hidden" name="_token" value="{{ csrf_token() }}"/>
            <div class="col-md-8">
                <div class="form-group">
                    <label for="search">{{  trans('inscription.recherche')  }} : </label>
                    <div class="input-group">
                        <input type="search" name="search" placeholder="{{trans('inscription.nom')}} ou Matricule ou {{trans('inscription.numero_recu')}}" class="form-control" autofocus>
                        <div class="input-group-btn">
                            <button type="submit" class="btn btn-primary" ><span class="fa fa-search"> </span>{{trans('inscription.search')}}</button>
                        </div>
                    </div>
                </div>
            </div>
        </form>

    </div>

    <div class="col-md-10">
        @if(session('search_result'))
            <table class="table table-bordered table-striped table-condensed table-inverse" style="margin-top: 15px;">
                <thead>
                <caption style="font-size: 22px">{{  trans('inscription.search_result')  }}</caption>
                <th>#</th>
                <th>Matricule</th>
                <th>{{  trans('admin.nom')  }}</th>
                <th>{{  trans('inscription.date_naiss')  }}</th>
                <th>{{  trans('inscription.lieu_naiss')  }}</th>
                <th>{{  trans('inscription.sexe')  }}</th>

                </thead>
                <tbody>
                <?php $i=1; ?>
                @foreach($eleves as $e)
                    <tr>
                        <td>{{$i++}}</td>
                        <td>{{$e->matricule}}</td>
                        <td><strong >{{$e->nom.' '.$e->prenom}}</strong></td>
                        <td>{{$e->date_naiss}}</td>
                        <td>{{$e->lieu_naiss}}</td>
                        <td>{{$e->sexe}}</td>
                        <td>
                            <div class="btn-group">
                                <a href="#" class=" btn btn-primary dropdown-toggle" data-toggle="dropdown">Actions <b class="caret"></b></a>
                                <ul class="dropdown-menu">
                                    <li><a href="{{URL::to('/inscriptions/payement/create/'.$e->id)}}" style="color: white" class=" btn btn-success" ><span class="fa fa-money"></span> {{trans('inscription.add_payement')}}</a></li>
                                    <li><a href="{{ URL::to('/inscriptions/payement/list/'.$e->id)}}"  style="color: white" class="btn btn-warning"><span class="glyphicon glyphicon-list"></span> {{  trans('inscription.liste_paiement')  }}</a></li>
                                </ul>
                            </div>

                    </tr>
                @endforeach
                </tbody>
            </table>
            <div class="paginate" style="text-align: center;"> <?php echo(str_replace('/?', '?', $eleves->render()) ); ?></div>

            {{Session::forget('search_result')}}
         @elseif(session('info_payement'))

                <div class="panel panel-success" style="margin-top: 30px">
                    <div class="panel-heading">
                        <h3 class="panel-title">{{trans('inscription.numero_recu')}}: {{$payement->numero}}</h3>
                    </div>
                    <div class="panel-body">
                        <p ><strong>{{trans('inscription.nom')}}: </strong> {{$eleve->nom.' '.$eleve->prenom}}</p>
                        <p><strong>{{trans('inscription.montant')}}: </strong> {{$payement->montant.' '.trans('inscription.devise') }}</p>
                        <p ><strong>{{trans('inscription.motif')}}: </strong> {{$payement->motif}}</p>
                        <p ><strong>Date : </strong> {{$payement->date_payement}}</p>
                    </div>
                    <div class="panel-footer">ScoolConcept</div>
                    {{Session::forget('info_payement')}}
        @endif
    </div>
    </div>
@endsection
