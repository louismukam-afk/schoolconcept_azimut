@extends('skeleton')
@section('sub-content')
    <div id="monaccordeon" class="panel-group col-md-12">
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
                        <li class="col-md-4"><a  href={{URL::to('/inscriptions/listes/show/?id=0')}}>{{trans('inscription.all_eleves')}}</a></li>
                        @foreach($cycles as $cy)
                            <h3 class="page-header col-md-12">{{$cy->nom}}</h3>

                        @foreach($niveaux as $n)
                            <div class="col-md-6">
                            <h4 class="page-header"> {{(session('locale')=='en')?$n->nom_anglais:$n->nom}}</h4>

                                @foreach($classes as $c)
                                    <div class="col-md-12">
                                    @if($c->id_niveau==$n->id)
                                        <li class="col-md-12"><a data-toggle="modal" data-backdrop="false" href="#choose_level" onclick="getNniveau({{$cy->id}});choix_lvl({{$c->id}},{{$cy->id}},'')">{{(session('locale')=='en')?$c->nom_anglais:$c->nom}}</a></li>
                                    @endif
                                    </div>
                                @endforeach
                            </div>
                            @endforeach

                        @endforeach

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
                        <li class="col-md-4"><a href={{URL::to('/inscriptions/listes/show/?id=0&option=1')}}>{{trans('inscription.all_eleves')}}</a></li>
                        @foreach($cycles as $cy)
                            <h3 class="page-header col-md-12">{{$cy->nom}}</h3>

                            @foreach($niveaux as $n)
                                <div class="col-md-6">
                                    <h4 class="page-header"> {{(session('locale')=='en')?$n->nom_anglais:$n->nom}}</h4>

                                    @foreach($classes as $c)
                                        <div class="col-md-12">
                                            @if($c->id_niveau==$n->id)
                                                <li class="col-md-12"><a data-toggle="modal" data-backdrop="false" href="#choose_level" onclick="getNniveau({{$cy->id}});choix_lvl({{$c->id}},{{$cy->id}},1)">{{(session('locale')=='en')?$c->nom_anglais:$c->nom}}</a></li>
                                            @endif
                                        </div>
                                    @endforeach
                                </div>
                            @endforeach

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
                        <li class="col-md-4"><a href={{URL::to('/inscriptions/listes/show/?id=0&option=2')}}>{{trans('inscription.all_eleves')}}</a></li>

                        @foreach($cycles as $cy)
                            <h3 class="page-header col-md-12">{{$cy->nom}}</h3>

                            @foreach($niveaux as $n)
                                <div class="col-md-6">
                                    <h4 class="page-header"> {{(session('locale')=='en')?$n->nom_anglais:$n->nom}}</h4>

                                    @foreach($classes as $c)
                                        <div class="col-md-12">
                                            @if($c->id_niveau==$n->id)
                                                <li class="col-md-12"><a data-toggle="modal" data-backdrop="false" href="#choose_level" onclick="getNniveau({{$cy->id}});choix_lvl({{$c->id}},{{$cy->id}},2)">{{(session('locale')=='en')?$c->nom_anglais:$c->nom}}</a></li>
                                            @endif
                                        </div>
                                    @endforeach
                                </div>
                            @endforeach

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
                        <li class="col-md-4"><a href={{URL::to('/inscriptions/listes/show/?id=0&option=3')}}>{{trans('inscription.all_eleves')}}</a></li>
                        @foreach($cycles as $cy)
                            <h3 class="page-header col-md-12">{{$cy->nom}}</h3>

                            @foreach($niveaux as $n)
                                <div class="col-md-6">
                                    <h4 class="page-header"> {{(session('locale')=='en')?$n->nom_anglais:$n->nom}}</h4>

                                    @foreach($classes as $c)
                                        <div class="col-md-12">
                                            @if($c->id_niveau==$n->id)
                                                <li class="col-md-12"><a data-toggle="modal" data-backdrop="false" href="#choose_level" onclick="getNniveau({{$cy->id}});choix_lvl({{$c->id}},{{$cy->id}},3)">{{(session('locale')=='en')?$c->nom_anglais:$c->nom}}</a></li>
                                            @endif
                                        </div>
                                    @endforeach
                                </div>
                            @endforeach

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
                        <li class="col-md-4"><a href={{URL::to('/inscriptions/listes/show/?id=0&option=4')}}>{{trans('inscription.all_eleves')}}</a></li>

                        @foreach($cycles as $cy)
                            <h3 class="page-header col-md-12">{{$cy->nom}}</h3>

                            @foreach($niveaux as $n)
                                <div class="col-md-6">
                                    <h4 class="page-header"> {{(session('locale')=='en')?$n->nom_anglais:$n->nom}}</h4>

                                    @foreach($classes as $c)
                                        <div class="col-md-12">
                                            @if($c->id_niveau==$n->id)
                                                <li class="col-md-12"><a data-toggle="modal" data-backdrop="false" href="#choose_level" onclick="getNniveau({{$cy->id}});choix_lvl({{$c->id}},{{$cy->id}},4)">{{(session('locale')=='en')?$c->nom_anglais:$c->nom}}</a></li>
                                            @endif
                                        </div>
                                    @endforeach
                                </div>
                            @endforeach

                        @endforeach

                    </ul>
                </div>

            </div>
        </div>

        <div
                class="panel panel-primary">
            <div class="panel-heading">
                <h3 class="panel-title">
                    <a class="accordion-toggle" href="#item6" dataparent="#monaccordeon"
                       data-toggle="collapse">
                        {{trans('inscription.no_tranche5')}}
                    </a>
                </h3>
            </div>
            <div id="item6"
                 class="panel-collapse collapsing">
                <div
                        class="panel-body">
                    <ul >
                        <li class="col-md-4"><a href={{URL::to('/inscriptions/listes/show/?id=0&option=5')}}>{{trans('inscription.all_eleves')}}</a></li>


                        @foreach($cycles as $cy)
                            <h3 class="page-header col-md-12">{{$cy->nom}}</h3>

                            @foreach($niveaux as $n)
                                <div class="col-md-6">
                                    <h4 class="page-header"> {{(session('locale')=='en')?$n->nom_anglais:$n->nom}}</h4>

                                    @foreach($classes as $c)
                                        <div class="col-md-12">
                                            @if($c->id_niveau==$n->id)
                                                <li class="col-md-12"><a data-toggle="modal" data-backdrop="false" href="#choose_level" onclick="getNniveau({{$cy->id}});choix_lvl({{$c->id}},{{$cy->id}},5)">{{(session('locale')=='en')?$c->nom_anglais:$c->nom}}</a></li>
                                            @endif
                                        </div>
                                    @endforeach
                                </div>
                            @endforeach

                        @endforeach
                    </ul>
                </div>

            </div>
        </div>




        <div
                class="panel panel-primary">
            <div class="panel-heading">
                <h3 class="panel-title">
                    <a class="accordion-toggle" href="#item7" dataparent="#monaccordeon"
                       data-toggle="collapse">
                        {{trans('admin.no_scolarite')}}
                    </a>
                </h3>
            </div>
            <div id="item7"
                 class="panel-collapse collapsing">
                <div
                        class="panel-body">
                    <ul >
                        <li class="col-md-4"><a href={{URL::to('/inscriptions/listes/show/?id=0&option=5')}}>{{trans('inscription.all_eleves')}}</a></li>


                        @foreach($cycles as $cy)
                            <h3 class="page-header col-md-12">{{$cy->nom}}</h3>

                            @foreach($niveaux as $n)
                                <div class="col-md-6">
                                    <h4 class="page-header"> {{(session('locale')=='en')?$n->nom_anglais:$n->nom}}</h4>

                                    @foreach($classes as $c)
                                        <div class="col-md-12">
                                            @if($c->id_niveau==$n->id)
                                                <li class="col-md-12"><a data-toggle="modal" data-backdrop="false" href="#choose_level" onclick="getNniveau({{$cy->id}});choix_lvl({{$c->id}},{{$cy->id}},5)">{{(session('locale')=='en')?$c->nom_anglais:$c->nom}}</a></li>
                                            @endif
                                        </div>
                                    @endforeach
                                </div>
                            @endforeach

                        @endforeach
                    </ul>
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



                    <form accept-charset="UTF-8" role="form" method="GET" id="choix_level_form" action="{{URL::to('/inscriptions/listes/show/')}}">
                        <input type="hidden" name="_token" value="{{ csrf_token() }}"/>
                        <input type="hidden" name="id" id="id" value=""/>
                        <input type="hidden" name="cycle" id="id_cycle" value=""/>
                        <input type="hidden" name="option" id="option" value=""/>


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
        function choix_lvl(id,id_cycle,option)
        {
            document.getElementById("id").value = id;
            document.getElementById("id_cycle").value = id_cycle;
            document.getElementById("option").value = option;
        }
    </script>
@endsection