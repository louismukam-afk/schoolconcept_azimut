@extends('skeleton')
@section('sub-content')

    <style>
        .panel-success>.panel-heading {
            background-color: #3c763d;
            color:white;
            border:solid 1px #3c763d;
        }
        .panel-success {

            border:solid 1px #3c763d;
        }
    </style>
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
                        <li class="col-md-4"><a  href={{URL::to('/inscriptions/listes/show?id=0')}}>{{trans('inscription.all_eleves')}}</a></li>
                        @foreach($cycles as $cy)
                            <h3 class="page-header col-md-12">{{$cy->nom}}</h3>

                        @foreach($niveaux as $n)
                            <div class="col-md-6">
                            <h4 class="page-header"> {{(App::getLocale()=='en')?$n->nom_anglais:$n->nom}}</h4>

                                @foreach($classes as $c)
                                    <div class="col-md-12">
                                    @if($c->id_niveau==$n->id)
                                        <li class="col-md-12"><a data-toggle="modal" data-backdrop="false" href="#choose_level" onclick="getNniveau({{$cy->id}});choix_lvl({{$c->id}},{{$cy->id}},'')">{{(App::getLocale()=='en')?$c->nom_anglais:$c->nom}}</a></li>
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
                        <li class="col-md-4"><a href={{URL::to('/inscriptions/listes/show?id=0&option=1')}}>{{trans('inscription.all_eleves')}}</a></li>
                        @foreach($cycles as $cy)
                            <h3 class="page-header col-md-12">{{$cy->nom}}</h3>

                            @foreach($niveaux as $n)
                                <div class="col-md-6">
                                    <h4 class="page-header"> {{(App::getLocale()=='en')?$n->nom_anglais:$n->nom}}</h4>

                                    @foreach($classes as $c)
                                        <div class="col-md-12">
                                            @if($c->id_niveau==$n->id)
                                                <li class="col-md-12"><a data-toggle="modal" data-backdrop="false" href="#choose_level" onclick="getNniveau({{$cy->id}});choix_lvl({{$c->id}},{{$cy->id}},1)">{{(App::getLocale()=='en')?$c->nom_anglais:$c->nom}}</a></li>
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
                        <li class="col-md-4"><a href={{URL::to('/inscriptions/listes/show?id=0&option=2')}}>{{trans('inscription.all_eleves')}}</a></li>

                        @foreach($cycles as $cy)
                            <h3 class="page-header col-md-12">{{$cy->nom}}</h3>

                            @foreach($niveaux as $n)
                                <div class="col-md-6">
                                    <h4 class="page-header"> {{(App::getLocale()=='en')?$n->nom_anglais:$n->nom}}</h4>

                                    @foreach($classes as $c)
                                        <div class="col-md-12">
                                            @if($c->id_niveau==$n->id)
                                                <li class="col-md-12"><a data-toggle="modal" data-backdrop="false" href="#choose_level" onclick="getNniveau({{$cy->id}});choix_lvl({{$c->id}},{{$cy->id}},2)">{{(App::getLocale()=='en')?$c->nom_anglais:$c->nom}}</a></li>
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
                        <li class="col-md-4"><a href={{URL::to('/inscriptions/listes/show?id=0&option=3')}}>{{trans('inscription.all_eleves')}}</a></li>
                        @foreach($cycles as $cy)
                            <h3 class="page-header col-md-12">{{$cy->nom}}</h3>

                            @foreach($niveaux as $n)
                                <div class="col-md-6">
                                    <h4 class="page-header"> {{(App::getLocale()=='en')?$n->nom_anglais:$n->nom}}</h4>

                                    @foreach($classes as $c)
                                        <div class="col-md-12">
                                            @if($c->id_niveau==$n->id)
                                                <li class="col-md-12"><a data-toggle="modal" data-backdrop="false" href="#choose_level" onclick="getNniveau({{$cy->id}});choix_lvl({{$c->id}},{{$cy->id}},3)">{{(App::getLocale()=='en')?$c->nom_anglais:$c->nom}}</a></li>
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
                        <li class="col-md-4"><a href={{URL::to('/inscriptions/listes/show?id=0&option=4')}}>{{trans('inscription.all_eleves')}}</a></li>

                        @foreach($cycles as $cy)
                            <h3 class="page-header col-md-12">{{$cy->nom}}</h3>

                            @foreach($niveaux as $n)
                                <div class="col-md-6">
                                    <h4 class="page-header"> {{(App::getLocale()=='en')?$n->nom_anglais:$n->nom}}</h4>

                                    @foreach($classes as $c)
                                        <div class="col-md-12">
                                            @if($c->id_niveau==$n->id)
                                                <li class="col-md-12"><a data-toggle="modal" data-backdrop="false" href="#choose_level" onclick="getNniveau({{$cy->id}});choix_lvl({{$c->id}},{{$cy->id}},4)">{{(App::getLocale()=='en')?$c->nom_anglais:$c->nom}}</a></li>
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
                        <li class="col-md-4"><a href={{URL::to('/inscriptions/listes/show?id=0&option=5')}}>{{trans('inscription.all_eleves')}}</a></li>


                        @foreach($cycles as $cy)
                            <h3 class="page-header col-md-12">{{$cy->nom}}</h3>

                            @foreach($niveaux as $n)
                                <div class="col-md-6">
                                    <h4 class="page-header"> {{(App::getLocale()=='en')?$n->nom_anglais:$n->nom}}</h4>

                                    @foreach($classes as $c)
                                        <div class="col-md-12">
                                            @if($c->id_niveau==$n->id)
                                                <li class="col-md-12"><a data-toggle="modal" data-backdrop="false" href="#choose_level" onclick="getNniveau({{$cy->id}});choix_lvl({{$c->id}},{{$cy->id}},5)">{{(App::getLocale()=='en')?$c->nom_anglais:$c->nom}}</a></li>
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
                        <li class="col-md-4"><a href={{URL::to('/inscriptions/listes/show?id=0&option=5')}}>{{trans('inscription.all_eleves')}}</a></li>


                        @foreach($cycles as $cy)
                            <h3 class="page-header col-md-12">{{$cy->nom}}</h3>

                            @foreach($niveaux as $n)
                                <div class="col-md-6">
                                    <h4 class="page-header"> {{(App::getLocale()=='en')?$n->nom_anglais:$n->nom}}</h4>

                                    @foreach($classes as $c)
                                        <div class="col-md-12">
                                            @if($c->id_niveau==$n->id)
                                                <li class="col-md-12"><a data-toggle="modal" data-backdrop="false" href="#choose_level" onclick="getNniveau({{$cy->id}});choix_lvl({{$c->id}},{{$cy->id}},5)">{{(App::getLocale()=='en')?$c->nom_anglais:$c->nom}}</a></li>
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

        <div class="panel panel-success">
            <div class="panel-heading">
                <h3 class="panel-title">
                    <a class="accordion-toggle" href="#item8" dataparent="#monaccordeon"
                       data-toggle="collapse">
                        {{trans('admin.frais_pi')}}
                    </a>
                </h3>
            </div>



            <div id="item8"
                 class="panel-collapse collapsing">
                <div
                        class="panel-body">
                    <form class="form-horizontal" id="check_list_form" method="get" action="{{route('show_frais_liste')}}">
                        <div class="">

                           <div class="row">
                                   <div class="col-md-5" >
                                       <div class="form-group" style="padding: 5px 10px">
                                           <label>@lang('admin.frais'): </label>
                                           <select form="check_list_form" id="frais" name="frais" class="form-control" required="required">
                                               <option value="">-- @lang('admin.selectionner') --</option>
                                               @foreach($frais as $f)
                                                   <option value="{{$f->id}}"> {{$f->nom}} </option>
                                               @endforeach
                                           </select>

                                       </div>
                                   </div>

                               <div class="col-md-5" >
                                   <div class="form-group" style="padding: 5px 10px">
                                       <label>Type: </label>
                                       <select form="check_list_form" id="type" name="type" class="form-control" required>
                                           <option value="0">@lang('admin.impaye')</option>
                                           <option value="1">@lang('admin.paye')</option>
                                       </select>

                                   </div>
                               </div>
                           </div>

                            <div class="row">


                                <div class="col-md-5">
                                    <div class="form-group" style="padding: 5px 10px">
                                        <label>@lang('admin.specialites')  : </label>

                                        <select form="check_list_form" id="select_classe_for_frais" name="classe" class="form-control" onchange="classForFraisChange()" required>
                                            <?php $n=0;$i=0; ?>
                                            <option value=""> -- @lang('admin.choix_classe') --</option>
                                                <option value="0"> @lang('admin.tous') </option>
                                            @foreach($classes_list as $c)
                                                @if($i=0)
                                                    <optgroup label="<{{(App::getLocale()=='en')?$c->niveau->nom_anglais:$c->niveau->nom}}">
                                                        <?php  $i++; ?>
                                                        @endif
                                                        @if($c->id_niveau!=$n)
                                                            <?php $n=$c->id_niveau ?>
                                                    </optgroup>
                                                    <optgroup label="{{(App::getLocale()=='en')?$c->niveau->nom_anglais:$c->niveau->nom}}">
                                                        @endif
                                                        <option id="{{$c->niveau->id}}" value="{{$c->id}}" @if(old('classe')==$c->id) selected @endif>{{(App::getLocale()=='en')?$c->nom_anglais:$c->nom}}</option>
                                                        @endforeach
                                                    </optgroup>
                                        </select>

                                    </div>
                                </div>



                            </div>
                            <div class="row">
                                  <div class="col-md-5">
                                      <div class="form-group" style="padding: 5px 10px">
                                          <label>  @lang('admin.nniveau') </label>
                                          <select form="check_list_form"  name="nniveau" class="form-control" id="select_nniveau_for_frais">
                                              @foreach($nniveaux as $nn)
                                                  <option value="{{$nn->id}}"  @if(old('nniveau')==$nn->id) selected @endif>{{$nn->nom}}</option>
                                              @endforeach
                                          </select>

                                      </div>
                                  </div>
                            </div>






                        </div>

                        <div class="row">
                            <div class="col-md-6 col-md-offset-3">
                                <input type="submit" class="btn btn-success form-control" value="{{trans('admin.confirmer')}}" >

                            </div>
                        </div>
                    </form>
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
                                <label for="nniveau">{{  trans('admin.nniveau')  }} : </label>
                                <select  name="nniveau" id="nniveau" form="choix_level_form" class="form-control" required >
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
       function choix_lvl(id,id_cycle,option) 
        { 
            document.getElementById("id").value = id; 
            document.getElementById("id_cycle").value = id_cycle; 
            document.getElementById("option").value = option; 
        }

        function classForFraisChange()
        {
            selectClass = $('#select_classe_for_frais');
            selectNNiveau = $('#select_nniveau_for_frais');

            if(selectClass.val() == 0) {
                selectNNiveau.attr('disabled','disabled');
            } else {
                selectNNiveau.removeAttr('disabled');

            }
        }
    </script>
@endsection

@section('breadcrumb')
    <li><strong><a href="{{url('inscriptions')}}">@lang('menu.gestion_inscriptions')</a></strong></li>
    <li class="active"><strong>{{$titre}}</strong></li>
@endsection