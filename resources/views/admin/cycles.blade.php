@extends('skeleton')
@section('sub-content')
    <div class="col-md-6">
        @include('perso.functions')
    @if(isset($success))

            <div class="alert alert-success">
                {{  trans('admin.succes')  }}
            </div>

            @endif

        <table class="table table-bordered table-striped table-condensed table-inverse" style="margin-top: 15px;">
            <thead>
            <caption style="font-size: 22px">{{  trans('admin.titre_table_cycle')  }}</caption>
            <th>#</th>
            <th>{{trans('inscription.nom')}}</th>
            <th>{{trans('admin.numero_ordre')}}</th>
            </thead>
            <tbody>
            <?php $i=1; ?>
            @foreach($cycles as $c)
                <tr>
                    <td>{{$i++}}</td>
                    <td><strong ><a data-toggle="modal" data-backdrop="false" href="#edit_cycle" onclick="edit_item({{$c->id}},'{{sanitize($c->nom)}}', {{$c->ordre}})" > <span class="glyphicon glyphicon-edit"></span> {{$c->nom}}</a> </strong></td>
                    <td><strong>{{$c->ordre}}</strong></td>
                    <td><a href="{{ URL::to('/admin/cycles/destroy/').'/'.$c->id }}"  title="Supprimer" class="btn btn-danger btn-xs pull-right"><span class="glyphicon glyphicon-trash"></span> @lang('admin.delete')</a></td>
                </tr>
            @endforeach
            </tbody>
        </table>
        <button class="btn btn-primary" data-toggle="modal" data-backdrop="false" href="#add_cycle"
        ><span class="glyphicon glyphicon-plus"></span>{{  trans('admin.nouveau_cycle')  }}</button>
        <div class="modal fade" id="add_cycle">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">x</button>
                        <h4 class="modal-title">{{  trans('admin.remplir_champs')  }}</h4>
                    </div>
                    <div
                            class="modal-body">



                        <form accept-charset="UTF-8" role="form" method="POST" action="{{ url('/admin/cycles/store/') }}">
                            <input type="hidden" name="_token" value="{{ csrf_token() }}">
                            <fieldset>

                                <div class="form-group">
                                    <label for="nom">{{  trans('admin.nom')  }} : <span class="required">*</span></label>
                                    <input class="form-control" id="nom" name="nom" type="text"  value="{{ old('nom') }}" required autofocus>

                                </div>
                                <div class="form-group">
                                    <label for="ordre">{{  trans('admin.numero_ordre')  }} : <span class="required">*</span></label>
                                    <input class="form-control" id="ordre" name="ordre" type="number"   required >

                                </div>
                                <input class="btn  btn-success " type="submit" value="{{  trans('admin.confirmer')  }}">


                            </fieldset>
                        </form>


                    </div>





                </div>


            </div>
        </div>


        <div class="modal fade" id="edit_cycle">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">x</button>
                        <h4 class="modal-title">{{  trans('admin.remplir_champs')  }}</h4>
                    </div>
                    <div class="modal-body">
                        <form accept-charset="UTF-8" role="form" method="POST" action="{{ url('/admin/cycles/update/') }}">
                            <input type="hidden" name="_token" value="{{ csrf_token() }}">
                            <input type="hidden" name="id" id="edit_id_cycle" value=""/>

                            <fieldset>

                                <div class="form-group">
                                    <label for="edit_nom">{{  trans('admin.nom')  }} : <span class="required">*</span></label>
                                    <input class="form-control" id="edit_nom" name="nom" type="text"  value="{{ old('nom') }}" required >

                                </div>

                                <div class="form-group">
                                    <label for="edit_ordre">{{  trans('admin.numero_ordre')  }} : <span class="required">*</span></label>
                                    <input class="form-control" id="edit_ordre" name="ordre" type="number"   required >

                                </div>
                                <input class="btn  btn-success " type="submit" value="{{  trans('admin.confirmer')  }}">


                            </fieldset>
                        </form>
                    </div>

                </div>


            </div>
        </div>

        <div class="paginate" style="text-align: center;"> <?php echo(str_replace('/?', '?', $cycles->render()) ); ?></div>
    </div>
    <script>
        function edit_item(id, nom, ordre)
        {

            document.getElementById("edit_id_cycle").value = id;
            document.getElementById("edit_nom").value = nom;
            document.getElementById("edit_ordre").value = ordre;


        }
    </script>
@endsection


@section('breadcrumb')
        <li><strong><a href="{{url('admin')}}">@lang('admin.dashboard')</a></strong></li>
        <li class="active"><strong>{{$titre}}</strong></li>
@endsection