@extends('skeleton')
@section('sub-content')
    <div class="col-md-6">
        @if(isset($success))

            <div class="alert alert-success">
                {{  trans('admin.succes')  }}
            </div>

        @endif

            <table class="table table-bordered table-striped table-condensed table-inverse" style="margin-top: 15px;">
                <thead>
                <caption style="font-size: 22px">{{  trans('admin.titre_nniveau')  }}</caption>
                <th>#</th>
                <th>{{trans('inscription.nom')}}</th>
                </thead>
                <tbody>
                <?php $i=1; ?>
                @foreach($nniveaux as $nn)
                    <tr>
                        <td>{{$i++}}</td>
                        <td><strong ><a data-toggle="modal" data-backdrop="false" href="#edit_niveau" onclick="editer_nniveau({{$nn->id}},'{{$nn->nom}}')" > <span class="glyphicon glyphicon-edit"></span> {{$nn->nom}}</a> </strong></td>
                        <td><a href="{{ URL::to('/admin/nniveaux/destroy/').'/'.$nn->id }}"  title="Supprimer" class="btn btn-danger btn-xs pull-right"><span class="glyphicon glyphicon-trash"></span> Supprimer</a></td>
                    </tr>
                @endforeach
                </tbody>
            </table>

            <button class="btn btn-success" data-toggle="modal" data-backdrop="false" href="#add_nniveau"
            ><span class="glyphicon glyphicon-plus"></span>{{  trans('admin.nouveau_nniveau')  }}</button>
            <div class="modal fade" id="add_nniveau">
                <div class="modal-dialog" style="width: 30%">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal">x</button>
                            <h4 class="modal-title">{{  trans('admin.remplir_champs')  }}</h4>
                        </div>
                        <div
                                class="modal-body">



                            <form accept-charset="UTF-8" role="form" method="POST" id="add_level_form" action="{{ url('/admin/nniveaux/store/') }}">
                                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                <fieldset>


                                    <div class="form-group">
                                        <label for="nom">{{  trans('admin.nom')  }} : <span class="required">*</span></label>
                                        <input class="form-control" id="nom" name="nom" type="text"  value="{{ old('nom') }}" required autofocus>

                                    </div>


                                    <input class="btn  btn-success " type="submit" value="{{  trans('admin.confirmer')  }}">


                                </fieldset>
                            </form>


                        </div>





                    </div>


                </div>
            </div>























            <div class="modal fade" id="edit_niveau">
                <div class="modal-dialog" style="width: 30%">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal">x</button>
                            <h4 class="modal-title">{{  trans('admin.remplir_champs')  }}</h4>
                        </div>
                        <div
                                class="modal-body">



                            <form accept-charset="UTF-8" role="form" method="POST" id="edit_level_form" action="{{ url('/admin/nniveaux/update/') }}">
                                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                <input type="hidden" name="id" id="edit_id_niveau" value=""/>
                                <fieldset>
                                    <div class="form-group">
                                        <label for="nom">{{  trans('admin.nom')  }} : <span class="required">*</span></label>
                                        <input class="form-control" id="edit_nom" name="nom" type="text"  value="{{ old('nom') }}" required autofocus>

                                    </div>


                                    <input class="btn  btn-success " type="submit" value="{{  trans('admin.confirmer')  }}">


                                </fieldset>
                            </form>



                        </div>





                    </div>


                </div>
            </div>

            <div class="paginate" style="text-align: center;"> <?php echo(str_replace('/?', '?', $nniveaux->render()) ); ?></div>
    </div>

    <script>
        function editer_nniveau(id,nom)
        {

            document.getElementById("edit_id_niveau").value = id;
            document.getElementById("edit_nom").value = nom;


        }
    </script>
    </div>


@endsection