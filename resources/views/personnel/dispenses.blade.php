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
            <form class="form-horizontal" id="add_inscription_form" method="get" action="{{URL::to('/personnel/find/')}}">
                <input type="hidden" name="_token" value="{{ csrf_token() }}"/>
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
                    <th>@lang('admin.titre_matiere')</th>

                    </thead>
                    <tbody>
                    <?php $i=1; ?>
                    @foreach($personnel as $p)
                        @if($p->type==0)
                            <tr>
                                <td>{{$i++}}</td>
                                <td><strong >{{$p->nom}}</strong></td>
                                <td>{{$p->sexe}}</td>
                                <td>{{$p->matieres}}</td>
                                <td>
                                    <a  href="{{url('/personnel/dispense/'.$p->id)}}"   class="btn btn-warning btn-xs"> <span class="glyphicon glyphicon-time"></span> {{  trans('admin.emploi_temps')  }}</a>
                                </td>
                            </tr>
                        @endif

                    @endforeach
                    </tbody>
                </table>
                <div class="paginate" style="text-align: center;"> <?php echo(str_replace('/?', '?', $personnel->render()) ); ?></div>

                {{Session::forget('search_result')}}
            @endif





        </div>
    <div class="col-md-12">
        <div id="monaccordeon" class="panel-group col-md-12">
            <h3>{{trans('admin.ou')}}</h3>
            <div class="panel panel-primary">
                <div class="panel-heading">
                    <h3 class="panel-title">
                        <a class="accordion-toggle" href="#item1" dataparent="#monaccordeon"
                           data-toggle="collapse">
                            {{trans('admin.emploi_temps_classe')}}
                        </a>
                    </h3>
                </div>

                <div id="item1"
                     class="panel-collapse collapsing">
                    <div
                            class="panel-body">
                        @foreach($cycles as $cy)
                            <h3 class="page-header col-md-12">{{$cy->nom}}</h3>

                            @foreach($niveaux as $n)
                                <div class="col-md-6">
                                    <h4 class="page-header"> {{ (session('locale')=='en')?$n->nom_anglais:$n->nom}}</h4>

                                    @foreach($classes as $c)
                                        <div class="col-md-12">
                                            @if($c->id_niveau==$n->id)
                                                <li class="col-md-12"><a data-toggle="modal" data-backdrop="false" href="#choose_level" onclick="getNniveau({{$cy->id}});choix_lvl({{$c->id}},'')">{{(session('locale')=='en')?$c->nom_anglais:$c->nom}}</a></li>
                                            @endif
                                        </div>
                                    @endforeach
                                </div>
                            @endforeach

                        @endforeach

                    </div>

                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="choose_level">
        <div class="modal-dialog" style="width: 20%">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">x</button>
                    <h4 class="modal-title">{{  trans('admin.remplir_champs')  }}</h4>
                </div>
                <div
                        class="modal-body">



                    <form accept-charset="UTF-8" role="form" method="GET" id="choix_level_form" action="{{URL::to('/personnel/emploi-temps/show/')}}">
                        <input type="hidden" name="_token" value="{{ csrf_token() }}"/>
                        <input type="hidden" name="id" id="id" value=""/>
                        <input type="hidden" name="cycle" id="id_cycle" value=""/>

                        <fieldset>
                            <div class="form-group">
                                <label for="niveau">{{  trans('admin.nniveau')  }} : </label>
                                <select  name="nniveau" id="nniveau" form="choix_level_form" class="form-control" >
                                    @foreach($nniveaux as $nn)
                                        <option value="{{$nn->id}}">{{$nn->nom}}</option>
                                    @endforeach
                                </select>
                            </div>


                            <input class="btn  btn-success " type="submit" value="{{  trans('admin.confirmer')  }}" autofocus>


                        </fieldset>
                    </form>


                </div>





            </div>


        </div>
    </div>

    <script>
        function getNniveau(cycle) {
            $.ajax({
                type: 'GET',
                url: '{{url('/admin/nniveaux/get/')}}/'+cycle,
                timeout: 3000,
                success: function (data) {
                    $('#nniveau').empty()
                    $.each(data,function (key,val) {
                        $('#nniveau').append('<option value="'+val.id+'">'+val.nom+'</option>');
                    })
                },
                error: function (data) {
                    alert('La requete a échoué');
                }
            });
        }
        function choix_lvl(id,id_cycle)
        {
            document.getElementById("id").value = id;
            document.getElementById("id_cycle").value = id_cycle;
        }
    </script>
@endsection