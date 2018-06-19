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


        <div class="col-md-8">
            <form class="form-horizontal" id="add_inscription_form" method="get" action="{{URL::to('/search/eleve')}}">
                <input type="hidden" name="_token" value="{{ csrf_token() }}"/>
                <input type="hidden" name="view" value="inscriptions/search_edit_eleve">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="search">{{  trans('inscription.recherche')  }} : </label>
                        <div class="input-group">
                            <input type="search" name="search" placeholder="{{trans('inscription.nom')}} ou Matricule" class="form-control" autofocus>
                            <div class="input-group-btn">
                                <button type="submit" class="btn btn-primary" ><span class="fa fa-search"> </span>{{trans('inscription.search')}}</button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>

        </div>

        <div>
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

                                        <a class="btn btn-primary"  data-toggle="modal" data-backdrop="false" href="{{URL::to('/inscriptions/enregistrement/edit/'.$e->id)}}"><span class="glyphicon glyphicon-pencil"  ></span> {{  trans('admin.see_edit')  }}</a>

                        </tr>
                    @endforeach
                    </tbody>
                </table>
                <div class="paginate" style="text-align: center;"> <?php echo(str_replace('/?', '?view=inscriptions/search_edit_eleve&', $eleves->render()) ); ?></div>

                {{Session::forget('search_result')}}
            @endif
        </div>
@endsection