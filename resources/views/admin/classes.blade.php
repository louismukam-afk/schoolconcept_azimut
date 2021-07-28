@extends('skeleton')
@section('sub-content')
    <div class="col-md-10">
        @include('perso.functions')
    @if(isset($success))

            <div class="alert alert-success">
                {{  trans('admin.succes')  }}
            </div>

        @endif

        <table class="table table-striped table-condensed table-inverse" style="margin-top: 15px;">
            <thead>
            <caption style="font-size: 22px">{{  trans('admin.titre_table_classe')  }}</caption>
            <th>#</th>
            <th>Code</th>
            <th>{{  trans('admin.nom')  }}</th>
            <th>{{  trans('admin.nom_anglais')  }}</th>
            <th>{{  trans('admin.level')  }}</th>
            <th>{{  trans('admin.size')  }}</th>
            <th></th>
            </thead>
            <tbody>
            <?php $i=1; ?>
            @foreach($classes as $c)
                <tr>
                    <td>{{$i++}}</td>
                    <td>{{$c->code}}</td>
                    <td><strong >{{$c->nom}}</strong></td>
                    <td>{{$c->nom_anglais}}</td>

                    <td>
                        @foreach($niveaux as $n)
                            @if($n->id == $c->id_niveau)
                                {{  (App::getLocale()=='en')?$n->getNom_anglais():$n->getNom()  }}
                                <?php break; ?>
                            @endif
                        @endforeach
                    </td>
                    <td>{{$c->capacite}}</td>
                    <td>
                        <div class="btn-group">
                            <a href="#" class=" btn btn-primary dropdown-toggle" data-toggle="dropdown">Actions <b class="caret"></b></a>
                            <ul class="dropdown-menu">
                                <li><a style="color: white" title="Modifier les informations" class="btn-primary"  data-toggle="modal" data-backdrop="false" href="#edit_classe" onclick="editer_classe({{$c->id}},'{{sanitize($c->code)}}',{{$c->id_niveau}},{{$c->capacite}},'{{sanitize($c->nom)}}','{{sanitize($c->nom_anglais)}}')"><span class="glyphicon glyphicon-pencil"  ></span> {{  trans('admin.edit')  }}</a></li>
                                <li><a style="color: white" href="{{ URL::to('/admin/classes/destroy/').'/'.$c->id }}"   class="btn-danger"><span class="glyphicon glyphicon-trash"></span> {{  trans('admin.del')  }}</a></li>
                            </ul>
                        </div>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
        <button class="btn btn-primary" data-toggle="modal" data-backdrop="false" href="#add_cycle"
        ><span class="glyphicon glyphicon-plus"></span>{{  trans('admin.nouvelle_classe')  }}</button>
        <div class="modal fade" id="add_cycle">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">x</button>
                        <h4 class="modal-title">{{  trans('admin.remplir_champs')  }}</h4>
                    </div>
                    <div
                            class="modal-body">



                        <form accept-charset="UTF-8" role="form" method="POST" id="add_level_form" action="{{ url('/admin/classes/store/') }}">
                            <input type="hidden" name="_token" value="{{ csrf_token() }}">
                            <fieldset>

                                <div class="form-group">
                                    <label for="niveau">{{  trans('admin.choose_level')  }} : <span class="required">*</span></label>
                                    <select id="niveau" name="niveau" form="add_level_form" class="form-control">

                                    @foreach($niveaux as $n)
                                                <option value="{{  $n->id  }}" @if($n->id == old('niveau')) selected @endif>{{  (App::getLocale()=='en')?$n->nom_anglais:$n->nom  }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label>Code : <span class="required">*</span></label>
                                    <input class="form-control"  name="code" type="text"  value="{{ old('code') }}"  autofocus>

                                </div>
                                <div class="form-group">
                                    <label for="nom">{{  trans('admin.nom')  }} : <span class="required">*</span></label>
                                    <input class="form-control" id="nom" name="nom" type="text"  value="{{ old('nom') }}"  >

                                </div>
                                <div class="form-group">
                                    <label>{{  trans('admin.nom_anglais')  }} : </label>
                                    <input class="form-control" name="nom_anglais" type="text"  value="{{ old('nom_anglais') }}"  >

                                </div>
                                <div class="form-group">
                                    <label for="add_capacite">{{  trans('admin.size')  }} : <span class="required">*</span></label>
                                    <input class="form-control" id="add_capacite" name="capacite" type="number"  value="{{ old('capacite') }}">

                                </div>
                                <input class="btn  btn-success " type="submit" value="{{  trans('admin.confirmer')  }}">


                            </fieldset>
                        </form>


                    </div>





                </div>


            </div>
        </div>

            <div class="modal fade" id="edit_classe">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal">x</button>
                            <h4 class="modal-title">{{  trans('admin.remplir_champs')  }}</h4>
                        </div>
                        <div
                                class="modal-body">



                            <form accept-charset="UTF-8" role="form" method="POST" id="edit_classes_form" action="{{ url('/admin/classes/update/') }}">
                                <input type="hidden" name="_token" value="{{ csrf_token() }}"/>
                                <input type="hidden" name="id" id="edit_id_classe" value=""/>
                                <fieldset>

                                    <div class="form-group">
                                        <label for="niveau">{{  trans('admin.choose_level')  }} : <span class="required">*</span></label>
                                        <select id="edit_niveau" name="niveau" form="edit_classes_form" class="form-control">
                                            @foreach($niveaux as $n)
                                                <option value="{{  $n->id  }}" @if($n->id == old('niveau')) selected @endif>{{  $n->nom  }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label>Code : <span class="required">*</span></label>
                                        <input class="form-control" id="edit_code" name="code" type="text"  value="{{ old('code') }}"  autofocus>

                                    </div>
                                    <div class="form-group">
                                        <label for="nom">{{  trans('admin.nom')  }} : <span class="required">*</span></label>
                                        <input class="form-control" id="edit_nom" name="nom" type="text"  value="{{ old('nom') }}"  required>

                                    </div>

                                    <div class="form-group">
                                        <label>{{  trans('admin.nom_anglais')  }} : </label>
                                        <input class="form-control" id="edit_nom_anglais" name="nom_anglais" type="text"  value="{{ old('nom_anglais') }}"  >

                                    </div>
                                    <div class="form-group">
                                        <label for="edit_capacite">{{  trans('admin.size')  }} : <span class="required">*</span></label>
                                        <input class="form-control" id="edit_capacite" name="capacite" type="number"  value="{{ old('capacite') }}">

                                    </div>
                                    <input class="btn  btn-success " type="submit" value="{{  trans('admin.confirmer')  }}">


                                </fieldset>
                            </form>


                        </div>





                    </div>


                </div>
            </div>

        <div class="paginate" style="text-align: center;"> <?php echo(str_replace('/?', '?', $classes->render()) ); ?></div>
    </div>

    <script>
        function editer_classe(id,code,niveau,capacite,nom,nom_anglais)
        {

            document.getElementById("edit_id_classe").value = id;
            document.getElementById("edit_code").value = code;
            document.getElementById("edit_nom").value = nom;
            document.getElementById("edit_capacite").value = capacite;
            document.getElementById("edit_nom_anglais").value = nom_anglais;
            var sel= document.getElementById("edit_niveau");
            sel.value = niveau;
        }
    </script>
    <!--    -->
@endsection

@section('breadcrumb')
    <li><strong><a href="{{url('admin')}}">@lang('admin.dashboard')</a></strong></li>
    <li class="active"><strong>{{$titre}}</strong></li>
@endsection