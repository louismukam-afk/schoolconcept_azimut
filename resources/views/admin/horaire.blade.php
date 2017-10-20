@extends('skeleton')
@section('sub-content')
    <div class="col-md-6">
        @if(isset($success))

            <div class="alert alert-success">
                {{  trans('admin.succes')  }}
            </div>

        @endif
            @if(isset($error))

                <div class="alert alert-danger">
                    {{  $error  }}
                </div>

            @endif

        <table class="table table-bordered table-striped table-condensed table-inverse" style="margin-top: 15px;">
            <thead>
            <th>#</th>
            <th>{{trans('admin.intervalle')}}</th>
            <th>Type</th>
            <th>@lang('admin.numero_ordre')</th>
            </thead>
            <tbody>
            <?php $i=1; ?>
            @foreach($horaire as $h)
                <tr>
                    <td>{{$i++}}</td>

                    <td>{{$h->intervalle}}</td>

                    <td>@if($h->type==0){{trans('admin.cours')}}@else Pause @endif</td>
                    <td>{{$h->ordre}}</td>
                    <td>
                        <div class="btn-group">
                            <a data-toggle="modal" data-backdrop="false" href="#edit_horaire"  onclick="editer_horaire({{$h->id}},'{{$h->intervalle}}',{{$h->type}},{{$h->ordre}})" class="btn btn-primary btn-xs "><span class="glyphicon glyphicon-edit"></span> </a>
                            <a  href="{{url('/admin/horaires/destroy/'.$h->id)}}"   class="btn btn-danger btn-xs "><span class="glyphicon glyphicon-trash"></span> </a>

                        </div>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
        <button class="btn btn-primary" data-toggle="modal" data-backdrop="false" href="#add_cycle"
        ><span class="glyphicon glyphicon-plus"></span>{{  trans('admin.ajouter_horaire')  }}</button>
        <div class="modal fade" id="add_cycle">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">x</button>
                        <h4 class="modal-title">{{  trans('admin.remplir_champs')  }}</h4>
                    </div>
                    <div class="modal-body">



                        <form accept-charset="UTF-8" id="edit_salaire_form" role="form" method="POST" action="{{ url('/admin/horaires/store/') }}">
                            <input type="hidden" name="_token" value="{{ csrf_token() }}">
                            <fieldset>
                                <div class="form-group">
                                    <label for="horaire">{{  trans('admin.horaire')  }} : <span class="required" >*</span> </label>
                                    <input class="form-control" id="horaire" name="intervalle" type="text"  value="{{ old('intervalle') }}" autofocus>

                                </div>
                                <div class="form-group">
                                    <label for="type">Type:  </label>
                                    <select name="type" id="type" form="edit_salaire_form" class="form-control">
                                        <option value="0" @if(old('type')==0) selected @endif>{{trans('admin.cours')}}</option>
                                        <option value="1" @if(old('type')==1) selected @endif>Pause</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="ordre">@lang('admin.numero_ordre') :</label>
                                        <input type="text" class="form-control" name="ordre" id="ordre">
                                </div>
                                <input class="btn  btn-success " type="submit" value="{{  trans('admin.confirmer')  }}">


                            </fieldset>
                        </form>


                    </div>





                </div>


            </div>
        </div>


            <div class="modal fade" id="edit_horaire">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal">x</button>
                            <h4 class="modal-title">{{  trans('admin.remplir_champs')  }}</h4>
                        </div>
                        <div class="modal-body">



                            <form accept-charset="UTF-8" id="edit_horaire_form" role="form" method="POST" action="{{ url('/admin/horaires/update/') }}">
                                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                <input type="hidden" id="edit_id_horaire" name="id">
                                <fieldset>
                                    <div class="form-group">
                                        <label for="edit_int">{{  trans('admin.horaire')  }} : <span class="required">*</span> </label>
                                        <input class="form-control" id="edit_int" name="intervalle" type="text" autofocus>

                                    </div>
                                    <div class="form-group">
                                        <label for="type">Type:  </label>
                                        <select name="type" id="edit_type" form="edit_horaire_form" class="form-control">
                                            <option value="0">{{trans('admin.cours')}}</option>
                                            <option value="1">Pause</option>
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label for="edit_ordre">@lang('admin.numero_ordre')  :</label>
                                            <input type="text" class="form-control" name="ordre" id="edit_ordre">

                                    </div>
                                    <input class="btn  btn-success " type="submit" value="{{  trans('admin.confirmer')  }}">


                                </fieldset>
                            </form>


                        </div>





                    </div>


                </div>
            </div>

    </div>
    <script>
        function editer_horaire(id,intervalle,type,ordre)
        {
            document.getElementById("edit_id_horaire").value = id;
            document.getElementById("edit_int").value = intervalle;
            document.getElementById("edit_type").value = type;
            document.getElementById("edit_ordre").value = ordre;

        }
    </script>

@endsection