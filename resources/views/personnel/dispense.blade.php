@extends('skeleton')
@section('sub-content')
    <div class="col-md-12">
        @if(isset($success))

            <div class="alert alert-success">
                {{  trans('admin.succes')  }}
            </div>

        @endif
            <div class="col-md-12">
                <button class="btn btn-primary" data-toggle="modal" data-backdrop="false" href="#add_cycle"
                ><span class="glyphicon glyphicon-plus"></span> {{  trans('admin.ajouter_dispense')  }}</button>
            </div>

        <table class="table table-striped table-condensed table-inverse" style="margin-top: 15px;">
            <thead>
            <th>{{trans('admin.classe')}}</th>
            <th>@lang('admin.nniveau')</th>
            <th>{{trans('admin.matiere')}}</th>
            <th>{{trans('admin.jour').'/'.trans('admin.salle')}}</th>
            <th>{{trans('admin.horaire')}}</th>
            <th style="width: 50px" > @lang('admin.heure_prevue')</th>
            <th style="width: 50px">@lang('admin.compte_heure_')</th>
            </thead>
            <tbody>
            <?php  $i=1; $id_classe=-1;$id_nniveau=-1;$id_matiere=-1;?>

            @foreach($dispense as $c)
                <?php $i++ ?>
                @if((($id_classe!=$c->id_classe) || ($id_nniveau!=$c->id_nniveau) || ($id_matiere!=$c->id_matiere))  )
                    <tr>
                        @foreach($classes as $n)
                            @if($n->id == $c->id_classe)
                                <td><strong><a href="#show_heure_prevue" data-toggle="modal" data-backdrop="false" onclick="edit_form({{$c->id_personnel}},{{$c->id_classe}},{{$c->id_nniveau}},{{$c->id_matiere}})"><span class="glyphicon glyphicon-time"></span> {{  (App::getLocale()=='en')?$n->nom_anglais:$n->nom  }}</a></strong>  </td>
                                <?php break; ?>
                            @endif

                        @endforeach
                        @foreach($nniveaux as $nn)
                            @if($nn->id == $c->id_nniveau)
                                <td><strong>{{ $nn->nom  }} </strong>  </td>
                                <?php break; ?>
                            @endif

                        @endforeach
                        @foreach($matieres as $e)
                            @if($e->id == $c->id_matiere)
                                <td> <strong>{{  (App::getLocale()=='en')?$e->nom_anglais:$e->nom }}</strong> </td>
                                <?php break; ?>
                            @endif

                        @endforeach
                        <td colspan="2"></td>
                            <td class="text-center">
                                @foreach($heuresP as $h)
                                    @if(($h->id_nniveau == $c->id_nniveau) && ($h->id_classe == $c->id_classe) && ($h->id_matiere == $c->id_matiere))
                                       <strong>{{  $h->total  }}</strong>
                                        <?php break; ?>
                                    @endif

                                @endforeach
                            </td>
                            <?php $sum=0; ?>
                            <td class="text-center">
                            @foreach($heuresR as $h)
                                @if(($h->id_nniveau == $c->id_nniveau) && ($h->id_classe == $c->id_classe) && ($h->id_matiere == $c->id_matiere))
                                    <?php $sum+=$h->nombre; ?>
                                    @endif

                            @endforeach
                                <strong>{{$sum}}</strong>
                        </td>
                        <td></td>
                    </tr>


                @endif
                <?php $id_classe= $c->id_classe ;$id_nniveau=$c->id_nniveau;$id_matiere=$c->id_matiere; ?>

                <tr>




                 <td></td>
                   <td></td>


                    <td></td>
                    <td>
                        <strong><a href="#edit_dispense" data-toggle="modal" data-backdrop="false" onclick="edit_dispense({{$c->id}},{{$c->jour}},{{$c->id_horaire}},{{$c->id_salle}})"><span class="glyphicon glyphicon-pencil"> </span> {{jour($c->jour)}}
                    @foreach($salles as $s)
                        @if($s->id == $c->id_salle)
                              /{{  $s->nom }}
                            <?php break; ?>
                            @endif

                            @endforeach
                            </a>
                        </strong>
                    </td>
                    @foreach($horaires as $h)
                        @if($h->id == $c->id_horaire)
                            <td>  {{  $h->intervalle  }}</td>
                            <?php break; ?>
                            @endif

                            @endforeach
<td></td>
                    <td></td>



                    <td><a href="{{ URL::to('/personnel/dispense/destroy/').'/'.$c->id }}"  class="btn btn-danger btn-xs pull-right"><span class="glyphicon glyphicon-trash"></span> {{trans('admin.del')}}</a></td>
                </tr>
            @endforeach
            </tbody>
        </table>
            @if($i>1)
                <a href="{{url('admin/taux/create/').'/'.$personnel->id}}" class="btn btn-warning btn-xs"><span class="fa fa-euro"></span> @lang('admin.taux_enseignant')</a>
            @endif
        <div class="modal fade" id="add_cycle">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">x</button>
                        <h4 class="modal-title">{{  trans('admin.remplir_champs')  }}</h4>
                    </div>
                    <div class="modal-body">



                        <form accept-charset="UTF-8" id="edit_manger_form" role="form" method="POST" action="{{ url('/personnel/dispense/create/') }}">
                            <input type="hidden" name="_token" value="{{ csrf_token() }}">
                            <input type="hidden" name="id_personnel" value="{{$personnel->id}}">

                            <fieldset>
                                <div class="form-group col-md-6">
                                    <label for="classe">{{  trans('admin.choix_classe')  }} : <span class="required">*</span></label>
                                    <select  name="id_classe" form="edit_manger_form" class="form-control">
                                        <?php $n=0;$i=0; ?>

                                        @foreach($classes as $c)
                                            @if($i=0)
                                                    <optgroup label="{{(App::getLocale()=='en')?$c->niveau->nom_anglais:$c->niveau->nom}}">
                                                        <?php  $i++; ?>
                                                        @endif
                                                        @if($c->id_niveau!=$n)
                                                            <?php $n=$c->id_niveau ?>
                                                    </optgroup>
                                                    <optgroup label="{{(App::getLocale()=='en')?$c->niveau->nom_anglais:$c->niveau->nom}}">
                                                        @endif
                                                        <option value="{{$c->id}}" >{{(App::getLocale()=='en')?$c->nom_anglais:$c->nom}}</option>
                                                        @endforeach
                                                    </optgroup>

                                    </select>
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="nniveau">@lang('admin.nniveau')</label>
                                    <select  id="nniveau" name="nniveau" form="edit_manger_form" class="form-control">
                                        @foreach($nniveaux as $e)
                                            <option value="{{  $e->id  }}" @if($e->id == old('nniveau')) selected @endif>{{ $e->nom }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <input type="hidden" name="id_personnel" value="{{$personnel->id}}">
                                <div class="form-group col-md-6">
                                    <label for="id_matiere">@lang('admin.titre_matiere')</label>
                                    <select  name="id_matiere" id="id_matiere" form="edit_manger_form" class="form-control">
                                        @foreach($matieres as $e)
                                            <option value="{{  $e->id  }}" @if($e->id == old('id_matiere')) selected @endif>{{  (App::getLocale()=='en')?$e->nom_anglais:$e->nom  }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="jour">@lang('admin.jour')</label>

                                    <select  name="jour" id="jour" form="edit_manger_form" class="form-control">
                                        <option value="1" @if(1 == old('jour')) selected @endif>{{ trans('admin.jour1') }}</option>
                                        <option value="2" @if(2 == old('jour')) selected @endif>{{ trans('admin.jour2') }}</option>
                                        <option value="3" @if(3 == old('jour')) selected @endif>{{ trans('admin.jour3') }}</option>
                                        <option value="4" @if(4 == old('jour')) selected @endif>{{ trans('admin.jour4') }}</option>
                                        <option value="5" @if(5 == old('jour')) selected @endif>{{ trans('admin.jour5') }}</option>
                                        <option value="6" @if(6 == old('jour')) selected @endif>{{ trans('admin.jour6') }}</option>
                                        <option value="7" @if(7 == old('jour')) selected @endif>{{ trans('admin.jour7') }}</option>
                                    </select>
                                </div>

                                <div class="form-group col-md-6">
                                    <label for="id_salle">@lang('admin.salle')</label>
                                    <select  name="id_salle" id="id_salle" form="edit_manger_form" class="form-control">
                                        <option value="0">@lang('admin.aucun')</option>
                                        @foreach($salles as $s)
                                            <option value="{{  $s->id  }}" @if($s->id == old('id_salle')) selected @endif>{{ $s->nom  }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                    <div class="row  col-md-12">
                                        <input class="btn  btn-success " type="submit" value="{{  trans('admin.confirmer')  }}">

                                    </div>

                            </fieldset>
                        </form>


                    </div>

                </div>


            </div>
        </div>



            <div class="modal fade" id="edit_dispense">
                <div class="modal-dialog"  >
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal">x</button>
                            <h4 class="modal-title">{{  trans('admin.remplir_champs')  }}</h4>
                        </div>
                        <div class="modal-body">
                            <form  id="edit_dispense_form" method="post" action="{{url('/personnel/dispense/edit')}}">
                                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                <input type="hidden" id="edit-id-dispense" name="id" value="">

                                <div class="form-group">
                                    <label for="edit-jour">@lang('admin.jour')</label>

                                    <select  name="jour" id="edit-jour" form="edit_dispense_form" class="form-control">
                                        <option value="1" >{{ trans('admin.jour1') }}</option>
                                        <option value="2">{{ trans('admin.jour2') }}</option>
                                        <option value="3" >{{ trans('admin.jour3') }}</option>
                                        <option value="4">{{ trans('admin.jour4') }}</option>
                                        <option value="5">{{ trans('admin.jour5') }}</option>
                                        <option value="6">{{ trans('admin.jour6') }}</option>
                                        <option value="7" >{{ trans('admin.jour7') }}</option>
                                    </select>
                                </div>

                                <div class="form-group ">
                                    <label for="edit-horaire">@lang('admin.horaire')</label>
                                    <select  id="edit-horaire" name="id_horaire" form="edit_dispense_form" class="form-control">
                                        @foreach($horaires as $h)
                                            <option value="{{  $h->id  }}">{{ $h->intervalle }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="form-group ">
                                    <label for="edit-salle">@lang('admin.salle')</label>
                                    <select  id="edit-salle" name="id_salle" form="edit_dispense_form" class="form-control">
                                        <option value="0">@lang('admin.aucun')</option>
                                    @foreach($salles as $s)
                                            <option value="{{  $s->id  }}">{{ $s->nom }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <button type="submit" class="btn btn-primary" ><span class="glyphicon glyphicon-check"></span>  @lang('admin.confirmer')</button>

                            </form>
                        </div>

                    </div>
                </div>
            </div>


            <div class="modal fade" id="show_heure_prevue">
                <div class="modal-dialog"  >
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal">x</button>
                            <h4 class="modal-title">{{  trans('admin.remplir_champs')  }}</h4>
                        </div>
                        <div class="modal-body">
                            <form  id="heure_prevue_form">
                                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                <input type="hidden" id="edit-personnel" name="personnel" value="">
                                <input type="hidden" id="edit-classe" name="classe" value="">
                                <input type="hidden" id="edit-nniveau" name="nniveau" value="">
                                <input type="hidden" id="edit-matiere" name="matiere" value="">
                                
                                <div class="form-group">
                                    <label for="heure_prevue">@lang('admin.heure_prevue')</label>
                                    <div class="input-group">
                                        <input type="number" class="form-control" name="heure" id="heure_prevue" disabled>
                                        <div class="input-group-addon">
                                            <strong>@lang('admin.heure')</strong>
                                        </div>
                                    </div>
                                </div>
                                <button type="submit" class="btn btn-primary" ><span class="glyphicon glyphicon-check"></span>  @lang('admin.confirmer')</button>

                            </form>
                        </div>

                    </div>
                </div>
            </div>

<?php
    function jour($n){
        if ($n==1)
            return trans('admin.jour1');
        if ($n==2)
            return trans('admin.jour2');
        if ($n==3)
            return trans('admin.jour3');
        if ($n==4)
            return trans('admin.jour4');
        if ($n==5)
            return trans('admin.jour5');
        if ($n==6)
            return trans('admin.jour6');
        if ($n==7)
            return trans('admin.jour7');
        return 'none';
    }
?>

    <script>
        $('#heure_prevue_form').on('submit', function(e){
            // validation code here
                e.preventDefault();
                updateHp();

        });
        function showHp() {
            startLoading();

            $('#heure_prevue').val('');
            $('#heure_prevue').attr('disabled','disabled');
            $.ajax({
                url:'{{url('/personnel/heures-prevues/show/')}}',
                type:'POST',
                data: new FormData($('#heure_prevue_form')[0]),
                cache: false,
                contentType: false,
                processData: false,
                complete: function (res,stat) {
                    var num=res.responseText;
                    $('#heure_prevue').removeAttr('disabled');
                    $('#heure_prevue').val(num);

                    stopLoading();

                }
            })

        }
        function updateHp() {
            startLoading();

            $.ajax({
                url:'{{url('/personnel/heures-prevues/update/')}}',
                type:'POST',
                data: new FormData($('#heure_prevue_form')[0]),
                cache: false,
                contentType: false,
                processData: false,
                complete: function (res,stat) {

                    var num=res.responseText;
                    //$('#show_heure_prevue').modal('hide');
                    $("#show_heure_prevue .close").click();
                    location.reload();
                   // stopLoading();


                }
            })

        }
        
        function edit_form(personnel,classe,nniveau,matiere) {
            $('#edit-personnel').val(personnel);
            $('#edit-classe').val(classe);
            $('#edit-nniveau').val(nniveau);
            $('#edit-matiere').val(matiere);
            showHp();
        }

        function edit_dispense(id,jour,horaire,salle) {
            $('#edit-id-dispense').val(id);
            $('#edit-jour').val(jour);
            $('#edit-horaire').val(horaire);
            $('#edit-salle').val(salle);
        }
    </script>
@endsection