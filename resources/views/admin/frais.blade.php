@extends('skeleton')
@section('sub-content')
    @include('perso.functions')
    <div class="col-md-6">
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

            <button class="btn btn-primary" data-toggle="modal" data-backdrop="false" href="#add_frais"
            ><span class="glyphicon glyphicon-plus"></span>{{  trans('admin.frais_n')  }}</button>

        <table class="table table-bordered table-striped table-condensed table-inverse" style="margin-top: 15px;">
            <thead>
            <caption style="font-size: 22px">{{  trans('admin.frais_l')  }}</caption>
            <th>#</th>
            <th>@lang('admin.nom')</th>
            <th>@lang('admin.montant')</th>
            </thead>
            <tbody>
            <?php $i=1; ?>
            @foreach($frais as $n)
                <tr>
                    <td>{{$i++}}</td>
                    <td>{{$n->nom}} </td>
                    <td>
                        <strong>{{$n->montant}}</strong>
                    </td>
                    <td>
                        <div class="btn-group">
                            <a href="#" class=" btn btn-primary dropdown-toggle" data-toggle="dropdown">Actions <b class="caret"></b></a>
                            <ul class="dropdown-menu">
                                <li><a style="color: white" class="btn-primary"  data-toggle="modal" data-backdrop="false" href="#edit_frais" onclick="editer_frais({{$n->id}},'{{sanitize($n->nom)}}',{{$n->montant}})"><span class="glyphicon glyphicon-pencil"  ></span> {{  trans('admin.edit')  }}</a></li>
                                <li>
                                    <a style="color: white" href="{{ URL::to('/admin/frais/destroy/').'/'.$n->id }}"   class="btn-danger"><span class="glyphicon glyphicon-trash"></span> @lang('admin.del')</a>
                                </li>
                            </ul>
                        </div>


                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>


        <div class="modal fade" id="add_frais">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">x</button>
                        <h4 class="modal-title">{{  trans('admin.remplir_champs')  }}</h4>
                    </div>
                    <div
                            class="modal-body">



                        <form accept-charset="UTF-8" role="form" method="POST" id="add_level_form" action="{{ url('/admin/frais/store/') }}">
                            <input type="hidden" name="_token" value="{{ csrf_token() }}">
                            <fieldset>


                                <div class="form-group">
                                    <label for="nom">{{  trans('admin.nom')  }} : <span class="required">*</span></label>
                                    <input class="form-control" id="nom" name="nom" type="text"  value="{{ old('nom') }}" required autofocus>

                                </div>

                                <div class="form-group">
                                    <label>{{  trans('admin.montant')  }} : </label>
                                    <input class="form-control"  name="montant" type="number"  value="{{ old('montant') }}" required >

                                </div>
                                <input class="btn  btn-success " type="submit" value="{{  trans('admin.confirmer')  }}">


                            </fieldset>
                        </form>


                    </div>





                </div>


            </div>
        </div>



            <div class="modal fade" id="edit_frais">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal">x</button>
                            <h4 class="modal-title">{{  trans('admin.remplir_champs')  }}</h4>
                        </div>
                        <div
                                class="modal-body">



                            <form accept-charset="UTF-8" role="form" method="POST" id="edit_level_form" action="{{ url('/admin/frais/update/') }}">
                                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                <input type="hidden" name="id" id="edit_id_frais" value=""/>
                                <fieldset>
                                    <div class="form-group">
                                        <label for="nom">{{  trans('admin.nom')  }} : <span class="required">*</span></label>
                                        <input class="form-control" id="edit_nom" name="nom" type="text"   required >

                                    </div>

                                    <div class="form-group">
                                        <label>{{  trans('admin.montant')  }} : </label>
                                        <input class="form-control" id="edit_montant" name="montant" type="number"  required >

                                    </div>
                                    <input class="btn  btn-success " type="submit" value="{{  trans('admin.confirmer')  }}">


                                </fieldset>
                            </form>



                        </div>





                    </div>


                </div>
            </div>

        <div class="paginate" style="text-align: center;"> <?php echo(str_replace('/?', '?', $frais->render()) ); ?></div>
    </div>

    <script>
        function editer_frais(id,nom,montant)
        {

            document.getElementById("edit_id_frais").value = id;
            document.getElementById("edit_nom").value = nom;
            document.getElementById("edit_montant").value = montant;

        }
    </script>

@endsection

@section('breadcrumb')
    <li><strong><a href="{{url('admin')}}">@lang('admin.dashboard')</a></strong></li>
    <li class="active"><strong>{{$titre}}</strong></li>
@endsection