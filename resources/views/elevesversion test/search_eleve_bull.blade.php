@extends('skeleton')
@section('sub-content')
    @if(isset($success))

        <div class="alert alert-success">
            {{  trans('admin.succes')  }}
        </div>

    @endif
    @if(isset($error))

        <div class="alert alert-danger">
            {{  $error  }}
        </div>

    @endif


    <div class="col-md-8">
        <form class="form-horizontal" id="add_inscription_form" method="get" action="{{URL::to('/eleves/bulletins/find/')}}">
            <input type="hidden" name="_token" value="{{ csrf_token() }}"/>
            <div class="col-md-8">
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

                                    <a data-toggle="modal" data-backdrop="false" href="#choose_seq" onclick="choix_seq({{$e->id}})" class=" btn btn-success" ><span class="fa fa-mortar-board"></span> {{trans('eleve.bulletins')}}</a>


                    </tr>
                @endforeach
                </tbody>
            </table>

            <div class="modal fade" id="choose_seq">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal">x</button>
                            <h4 class="modal-title">{{  trans('admin.remplir_champs')  }}</h4>
                        </div>
                        <div
                                class="modal-body">



                            <form accept-charset="UTF-8" role="form" method="POST" id="choix_seq_form" action="{{ url('/eleves/bulletins/print-single-seq') }}">
                                <input type="hidden" name="_token" value="{{ csrf_token() }}"/>
                                <input type="hidden" name="id" id="id" value=""/>
                                <fieldset>
                                    <div class="form-group">
                                        <label for="niveau">{{  trans('eleve.choix_seq')  }} : </label>
                                        <select  name="sequence" form="choix_seq_form" class="form-control" >
                                            <option value="1">Séquence 1</option>
                                            <option value="2">Séquence 2</option>
                                            <option value="3">Séquence 3</option>
                                            <option value="4">Séquence 4</option>
                                            <option value="5">Séquence 5</option>
                                            <option value="6">Séquence 6</option>
                                            <option value="7">{{trans('eleve.trimestre')}} 1</option>
                                            <option value="8">{{trans('eleve.trimestre')}} 2</option>
                                            <option value="9">{{trans('eleve.trimestre')}} 3</option>
                                        </select>
                                    </div>


                                    <input class="btn  btn-success " type="submit" value="{{  trans('admin.confirmer')  }}" autofocus>


                                </fieldset>
                            </form>


                        </div>





                    </div>


                </div>
            </div>
    </div>
    <script>
        function choix_seq(id)
        {
            document.getElementById("id").value = id;
        }
    </script>

            {{Session::forget('search_result')}}

    @endif
    </div>

@endsection