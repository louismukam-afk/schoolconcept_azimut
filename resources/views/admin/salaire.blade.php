@extends('skeleton')
@section('sub-content')
    <div class="col-md-10">
        @include('perso.functions')
        @if(isset($success))

            <div class="alert alert-success">
                {{  trans('admin.succes')  }}
            </div>

        @endif

        <table class="table table-bordered table-striped table-condensed table-inverse" style="margin-top: 15px;">
            <thead>
            <caption style="font-size: 22px">{{  trans('admin.salaire')  }}</caption>
            <th>#</th>
            <th>{{trans('admin.nom')}}</th>
            <th>Type</th>
            <th>{{trans('inscription.montant')}}</th>
            </thead>
            <tbody>
            <?php $i=1; ?>
            @foreach($salaire as $c)
                <tr>
                    <td>{{$i++}}</td>


                    @foreach($personnel as $p)
                        @if($p->id == $c->id_personnel)
                          <td>  <strong ><a data-toggle="modal" data-backdrop="false" href="#edit_salaire" onclick="edit_item({{$c->id}},'{{sanitize($p->nom)}}',{{$c->type}},{{$c->montant}})" > <span class="glyphicon glyphicon-edit"></span> {{$p->nom}}</a> </strong></td>
                            <td>  @if($c->type==0){{trans('admin.salaire0') }}@else {{trans('admin.salaire1')}} @endif</td>
                            <td>  {{  $c->montant.' '.trans('inscription.devise')  }}</td>

                        <?php break; ?>
                        @endif
                    @endforeach

                    <td><a href="{{ URL::to('/admin/salaire/destroy/').'/'.$c->id }}"  title="Supprimer" class="btn btn-danger btn-xs pull-right"><span class="glyphicon glyphicon-trash"></span> @lang('admin.delete')</a></td>
                </tr>
            @endforeach
            </tbody>
        </table>
        <button class="btn btn-primary" data-toggle="modal" data-backdrop="false" href="#add_salaire"
        ><span class="glyphicon glyphicon-plus"></span>{{  trans('admin.ajouter_salaire')  }}</button>

    </div>

    <div class="modal fade" id="add_salaire">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">x</button>
                    <h4 class="modal-title">{{  trans('admin.remplir_champs')  }}</h4>
                </div>
                <div class="modal-body">

                    <form accept-charset="UTF-8" id="add_salaire_form" role="form" method="POST" action="{{ url('/admin/salaire/store/') }}">
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                        <fieldset>
                            <div class="form-group">
                                <label for="niveau">{{  trans('admin.choix_personnel')  }} : </label>
                                <select  name="id_personnel" form="add_salaire_form" class="form-control" required>
                                    @foreach($personnel1 as $p)
                                        <option value="{{  $p->id  }}" @if($p->id == old('id_personnel')) selected @endif>{{  $p->nom  }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group">
                                <label>Type: </label>
                                <select  name="type" form="add_salaire_form" class="form-control" required>
                                    <option value="0" >{{trans('admin.salaire0')}}</option>
                                <!-- <option value="1" >{{trans('admin.salaire1')}}</option>-->
                                </select>
                            </div>

                            <div class="form-group">
                                <label for="montant">{{  trans('inscription.montant')  }} :  </label>
                                <div class="input-group">
                                    <input class="form-control" id="montant" name="montant" type="number"  value="{{ old('montant') }}" required>
                                    <span class="input-group-addon"> {{trans('inscription.devise')}}</span>
                                </div>

                            </div>
                            <input class="btn  btn-success " type="submit" value="{{  trans('admin.confirmer')  }}">

                        </fieldset>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="edit_salaire">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">x</button>
                    <h4 class="modal-title">{{  trans('admin.remplir_champs')  }}</h4>
                </div>
                <div class="modal-body">

                    <form accept-charset="UTF-8" id="edit_salaire_form" role="form" method="POST" action="{{ url('/admin/salaire/update/') }}">
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                        <input type="hidden" name="id" id="edit_id" value="">
                        <fieldset>
                            <div class="form-group">
                                <h2 id="edit_personnel"></h2>
                            </div>
                            <div class="form-group">
                                <label for="edit_type">Type: </label>
                                <select  name="type" id="edit_type" form="edit_salaire_form" class="form-control" required>
                                    <option value="0" >{{trans('admin.salaire0')}}</option>
                                <!-- <option value="1" >{{trans('admin.salaire1')}}</option>-->
                                </select>
                            </div>

                            <div class="form-group">
                                <label for="montant">{{  trans('inscription.montant')  }} :  </label>
                                <div class="input-group">
                                    <input class="form-control" id="edit_montant" name="montant" type="number"  required>
                                    <span class="input-group-addon"> {{trans('inscription.devise')}}</span>
                                </div>

                            </div>
                            <input class="btn  btn-success " type="submit" value="{{  trans('admin.confirmer')  }}">

                        </fieldset>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        function edit_item(id,personnel,type, montant)
        {

            document.getElementById("edit_id").value = id;
            document.getElementById("edit_personnel").innerHTML = personnel;
            document.getElementById("edit_type").value = type;
            document.getElementById("edit_montant").value = montant;



        }
    </script>
@endsection

@section('breadcrumb')
    <li><strong><a href="{{url('admin')}}">@lang('admin.dashboard')</a></strong></li>
    <li class="active"><strong>{{$titre}}</strong></li>
@endsection