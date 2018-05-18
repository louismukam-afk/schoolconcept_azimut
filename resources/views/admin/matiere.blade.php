@extends('skeleton')
@section('sub-content')
    <div class="col-md-8">
        @if(isset($success))

            <div class="alert alert-success">
                {{  trans('admin.succes')  }}
            </div>

        @endif

        <table class="table table-bordered table-striped table-condensed table-inverse" style="margin-top: 15px;">
            <thead>
            <caption style="font-size: 22px">{{  trans('admin.titre_matiere')  }}</caption>
            <th>#</th>
            <th>{{trans('inscription.nom')}}</th>
            <th>{{trans('admin.nom_anglais')}}</th>
            <th>{{trans('admin.groupe')}}</th>
            </thead>
            <tbody>
            <?php $i=1; ?>
            @foreach($matieres as $c)
                <tr>
                    <td>{{$i++}}</td>
                    <td><strong >{{$c->nom}}</strong></td>
                    <td>{{$c->nom_anglais}}</td>
                    <td>{{$c->groupe}}</td>
                    <td><a class="btn btn-primary btn-xs pull-right" data-toggle="modal" data-backdrop="false" href="#edit_matiere" onclick="editer_matiere({{$c->id}},'{{$c->nom}}','{{$c->nom_anglais}}',{{$c->groupe}})"><span class="glyphicon glyphicon-pencil"  ></span> {{trans('admin.modifier')}}</a></td>
                </tr>
            @endforeach
            </tbody>
        </table>
        <button class="btn btn-success" data-toggle="modal" data-backdrop="false" href="#add_cycle"
        ><span class="glyphicon glyphicon-plus"></span>{{  trans('admin.ajouter_matiere')  }}</button>
        <div class="modal fade" id="add_cycle">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">x</button>
                        <h4 class="modal-title">{{  trans('admin.remplir_champs')  }}</h4>
                    </div>
                    <div class="modal-body">



                        <form accept-charset="UTF-8" role="form" method="POST" action="{{ url('/admin/matieres/store/') }}">
                            <input type="hidden" name="_token" value="{{ csrf_token() }}">
                            <fieldset>

                                <div class="form-group">
                                    <label for="nom">{{  trans('admin.nom')  }} : <span class="required">*</span></label>
                                    <input class="form-control" id="nom" name="nom" type="text"  value="{{ old('nom') }}" required autofocus>

                                </div>
                                <div class="form-group">
                                    <label for="nom_anglais">{{  trans('admin.nom_anglais')  }} : <span class="required">*</span></label>
                                    <input class="form-control" id="nom_anglais" name="nom_anglais" type="text"  value="{{ old('nom_anglais') }}" >

                                </div>
                                <div class="form-group">
                                    <label for="groupe">{{  trans('admin.groupe')  }} :({{trans('admin.optional')}})</label>
                                    <input class="form-control" id="groupe" name="groupe" type="text"  value="{{ old('groupe') }}" >

                                </div>
                                <input class="btn  btn-success " type="submit" value="{{  trans('admin.confirmer')  }}">


                            </fieldset>
                        </form>


                    </div>





                </div>


            </div>
        </div>


            <div class="modal fade" id="edit_matiere">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal">x</button>
                            <h4 class="modal-title">{{  trans('admin.remplir_champs')  }}</h4>
                        </div>
                        <div class="modal-body">



                            <form accept-charset="UTF-8" role="form" method="POST" action="{{ url('/admin/matieres/update/') }}">
                                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                <input type="hidden" name="id" id="id">
                                <fieldset>

                                    <div class="form-group">
                                        <label for="edit_nom">{{  trans('admin.nom')  }} : <span class="required">*</span></label>
                                        <input class="form-control" id="edit_nom" name="nom" type="text"  value="{{ old('nom') }}" required autofocus>

                                    </div>
                                    <div class="form-group">
                                        <label for="edit_nom_anglais">{{  trans('admin.nom_anglais')  }} : <span class="required">*</span></label>
                                        <input class="form-control" id="edit_nom_anglais" name="nom_anglais" type="text"  value="{{ old('nom_anglais') }}" required autofocus>

                                    </div>
                                    <div class="form-group">
                                        <label for="groupe">{{  trans('admin.groupe')  }} :({{trans('admin.optional')}})</label>
                                        <input class="form-control" id="edit_groupe" name="groupe" type="text"  value="{{ old('groupe') }}" >

                                    </div>
                                    <input class="btn  btn-success " type="submit" value="{{  trans('admin.confirmer')  }}">


                                </fieldset>
                            </form>


                        </div>





                    </div>


                </div>
            </div>



        <div class="paginate" style="text-align: center;"> <?php echo(str_replace('/?', '?', $matieres->render()) ); ?></div>
    </div>
    <script>
        function editer_matiere(id,nom,nom_anglais,groupe)
        {
            document.getElementById("id").value = id;
            document.getElementById("edit_nom").value = nom;
            document.getElementById("edit_nom_anglais").value = nom_anglais;
            document.getElementById("edit_groupe").value = groupe;

        }
    </script>

@endsection