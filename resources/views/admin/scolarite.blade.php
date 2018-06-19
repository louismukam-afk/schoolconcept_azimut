@extends('skeleton')
@section('sub-content')
    <div class="col-md-10">
        @if(isset($success))

            <div class="alert alert-success">
                {{  trans('admin.succes')  }}
            </div>

        @endif

        <table class="table table-bordered table-striped table-condensed table-inverse" style="margin-top: 15px;">
            <caption style="font-size: 22px">{{  trans('admin.titre_table_scolarite')  }}</caption>

            <thead>
            <th>#</th>
            <th>Cycle</th>
            <th>{{trans('admin.level')}}</th>
            <th>{{  trans('admin.tranche1')  }}</th>
            <th>{{  trans('admin.tranche2')  }}</th>
            <th>{{  trans('admin.tranche3')  }}</th>
            <th>{{  trans('admin.tranche4')  }}</th>
            <th>{{  trans('admin.tranche5')  }}</th>
            <th>Total</th>
            </thead>
            <tbody>
            <?php $i=1; ?>
            @foreach($scolarite as $s)
                <tr>
                    <td>{{$i++}}</td>
                    <td><strong >
                            @foreach($cycles as $c)

                                @if($c->id==$s->id_cycle)
                                    {{$c->nom}}
                                    <?php break; ?>
                                @endif
                            @endforeach
                        </strong>
                    </td>
                    <td><strong >
                            @foreach($niveaux as $c)

                                    @if($c->id==$s->id_niveau)
                                        {{(App::getLocale()=='en')?$c->nom_anglais:$c->nom}}
                                        <?php break; ?>
                                    @endif
                            @endforeach
                        </strong></td>
                    <td>{{$s->tranche1}}</td>
                    <td>{{$s->tranche2}}</td>
                    <td>{{$s->tranche3}}</td>
                    <td>{{$s->tranche4}}</td>
                    <td>{{$s->tranche5}}</td>
                    <td>{{$s->total}}</td>
                    <td>
                        <div class="btn-group">
                            <a href="#" class=" btn btn-primary dropdown-toggle" data-toggle="dropdown">Actions <b class="caret"></b></a>
                            <ul class="dropdown-menu">
                                <li><a style="color: white" title="Modifier les informations" class="btn-primary"  data-toggle="modal" data-backdrop="false" href="#edit-classe" onclick="editer_scolarite({{$s->id}},{{$s->id_niveau}},{{$s->id_cycle}},{{$s->tranche1}},{{$s->tranche2}},{{$s->tranche3}},{{$s->tranche4}},{{$s->tranche5}})"><span class="glyphicon glyphicon-pencil"  ></span> {{  trans('admin.edit')  }}</a></li>
                                <li><a style="color: white" href="{{ URL::to('/admin/scolarite/destroy/').'/'.$s->id }}"   class="btn-danger"><span class="glyphicon glyphicon-trash"></span> {{  trans('admin.del')  }}</a></li>
                            </ul>
                        </div>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
        <button class="btn btn-primary" data-toggle="modal" data-backdrop="false" href="#add_cycle"
        ><span class="glyphicon glyphicon-plus"></span>{{  trans('admin.nouvelle_scolarite')  }}</button>
        <div class="modal fade" id="add_cycle">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">x</button>
                        <h4 class="modal-title">{{  trans('admin.remplir_champs')  }}</h4>
                    </div>
                    <div
                            class="modal-body">



                        <form accept-charset="UTF-8" role="form" method="POST" id="add_level_form" action="{{ url('/admin/scolarite/store/') }}">
                            <input type="hidden" name="_token" value="{{ csrf_token() }}">
                            <fieldset>
                                <div class="form-group">
                                    <label for="cycle">Cycle : </label>
                                    <select id="cycle" name="cycle" form="add_level_form" class="form-control">
                                        @foreach($cycles as $c)
                                            <option value="{{  $c->id  }}" @if($c->id == old('cycle')) selected @endif>{{  $c->nom  }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="classe">{{trans('admin.level')}} : <span class="required">*</span></label>
                                    <select id="classe" name="classe" form="add_level_form" class="form-control">
                                        @foreach($niveaux as $c)
                                            <option value="{{  $c->id  }}" @if($c->id == old('classe')) selected @endif>{{  (App::getLocale()=='en')?$c->nom_anglais:$c->nom  }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="row">
                                    <div class="form-group col-md-6">
                                        <label for="tranche1">{{  trans('admin.tranche1')  }} : <span class="required">*</span></label>
                                        <input class="form-control" id="tranche1" name="tranche1" type="text"  value="{{ old('tranche1') }}"  autofocus>

                                    </div>
                                    <div class="form-group col-md-6">
                                        <label for="tranche2">{{  trans('admin.tranche2')  }} : <span class="required">*</span></label>
                                        <input class="form-control" id="tranche2" name="tranche2" type="text"  value="{{ old('tranche2') }}">

                                    </div>
                                </div>
                               <div class="row">
                                   <div class="form-group col-md-6">
                                       <label for="tranche3">{{  trans('admin.tranche3')  }} : <span class="required">*</span></label>
                                       <input class="form-control" id="tranche3" name="tranche3" type="text"  value="{{ old('tranche3') }}">

                                   </div>
                                   <div class="form-group col-md-6">
                                       <label for="tranche4">{{  trans('admin.tranche4')  }} :</label>
                                       <input class="form-control" id="tranche4" name="tranche4" type="text"  value="{{ old('tranche4') }}">


                                   </div>
                               </div>
                                <div class="row">
                                    <div class="form-group col-md-6">
                                        <label for="tranche5">{{  trans('admin.tranche5')  }} : </label>
                                        <input class="form-control" id="tranche5" name="tranche5" type="text"  value="{{ old('tranche5') }}">

                                    </div>
                                </div>

                                <input class="btn  btn-success " type="submit" value="{{  trans('admin.confirmer')  }}">


                            </fieldset>
                        </form>


                    </div>





                </div>


            </div>
        </div>

        <div class="modal fade" id="edit-classe">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">x</button>
                        <h4 class="modal-title">{{  trans('admin.remplir_champs')  }}</h4>
                    </div>
                    <div
                            class="modal-body">



                        <form accept-charset="UTF-8" role="form" method="POST" id="edit_scolarite_form" action="{{ url('/admin/scolarite/update/') }}">
                            <input type="hidden" name="_token" value="{{ csrf_token() }}"/>
                            <input type="hidden" name="id" id="id_scolarite" value=""/>
                            <fieldset>
                                <div class="form-group">
                                    <label for="cycle">Cycle : </label>
                                    <select id="edit_cycle" name="cycle" form="edit_scolarite_form" class="form-control">
                                        @foreach($cycles as $c)
                                            <option value="{{  $c->id  }}" @if($c->id == old('cycle')) selected @endif>{{  $c->nom  }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="edit_classes">{{trans('admin.level')}} : <span class="required">*</span></label>
                                    <select id="edit_classes" name="classes" form="edit_scolarite_form" class="form-control">
                                        @foreach($niveaux as $c)
                                            <option value="{{  $c->id  }}" @if($c->id == old('classes')) selected @endif>{{  ((App::getLocale()=='en')?$c->nom_anglais:$c->nom)  }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="row">
                                    <div class="form-group col-md-6">
                                        <label for="edit_tranche1">{{  trans('admin.tranche1')  }} : <span class="required">*</span></label>
                                        <input class="form-control" id="edit_tranche1" name="tranche1" type="text"  value="{{ old('tranche1') }}"  autofocus>

                                    </div>
                                    <div class="form-group col-md-6">
                                        <label for="edit_tranche2">{{  trans('admin.tranche2')  }} : <span class="required">*</span></label>
                                        <input class="form-control" id="edit_tranche2" name="tranche2" type="text"  value="{{ old('tranche2') }}">

                                    </div>
                                </div>
                               <div class="row">
                                   <div class="form-group col-md-6">
                                       <label for="edit_tranche3">{{  trans('admin.tranche3')  }} : <span class="required">*</span></label>
                                       <input class="form-control" id="edit_tranche3" name="tranche3" type="text"  value="{{ old('tranche3') }}">

                                   </div>
                                   <div class="form-group col-md-6">
                                       <label for="edit_tranche4">{{  trans('admin.tranche4')  }} :</label>
                                       <input class="form-control" id="edit_tranche4" name="tranche4" type="text"  value="{{ old('tranche4') }}">

                                   </div>
                               </div>
                                <div class="row">
                                    <div class="form-group col-md-6">
                                        <label for="edit_tranche5">{{  trans('admin.tranche5')  }} : </label>
                                        <input class="form-control" id="edit_tranche5" name="tranche5" type="text"  value="{{ old('tranche5') }}">

                                    </div>
                                </div>

                                <input class="btn  btn-success " type="submit" value="{{  trans('admin.confirmer')  }}">


                            </fieldset>
                        </form>


                    </div>





                </div>


            </div>
        </div>

        <div class="paginate" style="text-align: center;"> <?php echo(str_replace('/?', '?', $scolarite->render()) ); ?></div>
    </div>

    <script>
        function editer_scolarite(id,id_classe,id_cycle,tranche1,tranche2,tranche3,tranche4,tranche5)
        {
            document.getElementById("id_scolarite").value = id;
            document.getElementById("edit_classes").value = id_classe;
            document.getElementById("edit_cycle").value = id_cycle;
            document.getElementById("edit_tranche1").value = tranche1;
            document.getElementById("edit_tranche2").value = tranche2;
            document.getElementById("edit_tranche3").value = tranche3;
            document.getElementById("edit_tranche4").value = tranche4;
            document.getElementById("edit_tranche5").value = tranche5;

        }
    </script>
    <!--    -->
@endsection