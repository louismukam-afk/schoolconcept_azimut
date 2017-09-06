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
            <caption style="font-size: 22px">{{  trans('admin.titre_table_niveau')  }}</caption>
            <th>#</th>
            <th>Nom</th>
            <th>Cycle</th>
            </thead>
            <tbody>
            <?php $i=1; ?>
            @foreach($niveaux as $n)
                <tr>
                    <td>{{$i++}}</td>
                    <td><strong >{{$n->nom}}</strong></td>
                    <td>
                        @foreach($cycles as $c)
                            @if($c->id == $n->id_cycle)
                                {{  $c->nom  }}
                                <?php break; ?>
                            @endif
                        @endforeach
                    </td>
                    <td><a href="{{ URL::to('/admin/niveaux/destroy/').'/'.$n->id }}"  title="Supprimer" class="btn btn-danger btn-xs pull-right"><span class="glyphicon glyphicon-trash"></span> Supprimer</a></td>
                </tr>
            @endforeach
            </tbody>
        </table>
        <button class="btn btn-primary" data-toggle="modal" data-backdrop="false" href="#add_cycle"
        ><span class="glyphicon glyphicon-plus"></span>{{  trans('admin.nouveau_niveau')  }}</button>
        <div class="modal fade" id="add_cycle">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">x</button>
                        <h4 class="modal-title">{{  trans('admin.remplir_champs')  }}</h4>
                    </div>
                    <div
                            class="modal-body">



                        <form accept-charset="UTF-8" role="form" method="POST" id="add_level_form" action="{{ url('/admin/niveaux/store/') }}">
                            <input type="hidden" name="_token" value="{{ csrf_token() }}">
                            <fieldset>
                                <div class="form-group">
                                    <label for="cycle">{{  trans('admin.choose_cycle')  }} : <span class="required">*</span></label>
                                    <select id="cycle" name="cycle" form="add_level_form" class="form-control">
                                        @foreach($cycles as $c)
                                            <option value="{{  $c->id  }}">{{  $c->nom  }}</option>
                                        @endforeach
                                    </select>
                                </div>

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

        <div class="paginate" style="text-align: center;"> <?php echo(str_replace('/?', '?', $niveaux->render()) ); ?></div>
    </div>

@endsection