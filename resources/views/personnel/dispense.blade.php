@extends('skeleton')
@section('sub-content')
    <div class="col-md-10">
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
            <th>#</th>
            <th>{{trans('admin.classe')}}</th>
            <th>@lang('admin.nniveau')</th>
            <th>{{trans('admin.matiere')}}</th>
            <th>{{trans('admin.jour')}}</th>
            <th>{{trans('admin.horaire')}}</th>
            </thead>
            <tbody>
            <?php  $i=1; ?>
            @foreach($dispense as $c)

                <tr>
                    <td>{{$i++}}</td>




                    @foreach($classes as $n)
                        @if($n->id == $c->id_classe)
                            <td>  {{  (App::getLocale()=='en')?$n->nom_anglais:$n->nom  }}</td>
                            <?php break; ?>
                        @endif

                    @endforeach
                    @foreach($nniveaux as $nn)
                        @if($nn->id == $c->id_nniveau)
                            <td>  {{ $nn->nom  }}</td>
                            <?php break; ?>
                        @endif

                    @endforeach


                    @foreach($matieres as $e)
                        @if($e->id == $c->id_matiere)
                            <td>  {{  (App::getLocale()=='en')?$e->nom_anglais:$e->nom }}</td>
                            <?php break; ?>
                        @endif

                    @endforeach
                    <td>{{jour($c->jour)}}</td>
                    @foreach($horaires as $h)
                        @if($h->id == $c->id_horaire)
                            <td>  {{  $h->intervalle  }}</td>
                            <?php break; ?>
                            @endif

                            @endforeach




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
                                <div class="form-group">
                                    <label for="classe">{{  trans('admin.choix_classe')  }} : <span class="required">*</span></label>
                                    <select  name="id_classe" form="edit_manger_form" class="form-control">
                                        <?php $n=0;$i=0; ?>

                                        @foreach($classes as $c)
                                            @if($i=0)
                                                <optgroup label="{{(App::getLocale()=='en')?\gesecole\niveau::find($c->id_niveau)->nom_anglais:\gesecole\niveau::find($c->id_niveau)->nom}}">
                                                    <?php  $i++; ?>
                                                    @endif
                                                    @if($c->id_niveau!=$n)
                                                        <?php $n=$c->id_niveau ?>
                                                </optgroup>
                                                <optgroup label="{{(App::getLocale()=='en')?\gesecole\niveau::find($c->id_niveau)->nom_anglais:\gesecole\niveau::find($c->id_niveau)->nom}}">
                                                    @endif
                                                    <option value="{{$c->id}}" @if($c->id == old('id_classe')) selected @endif>{{(App::getLocale()=='en')?$c->nom_anglais:$c->nom}}</option>
                                                    @endforeach
                                                </optgroup>

                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="nniveau">@lang('admin.nniveau')</label>
                                    <select  id="nniveau" name="nniveau" form="edit_manger_form" class="form-control">
                                        @foreach($nniveaux as $e)
                                            <option value="{{  $e->id  }}" @if($e->id == old('nniveau')) selected @endif>{{ $e->nom }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <input type="hidden" name="id_personnel" value="{{$personnel->id}}">
                                <div class="form-group">
                                    <label for="id_matiere">@lang('admin.titre_matiere')</label>
                                    <select  name="id_matiere" id="id_matiere" form="edit_manger_form" class="form-control">
                                        @foreach($matieres as $e)
                                            <option value="{{  $e->id  }}" @if($e->id == old('id_matiere')) selected @endif>{{  (App::getLocale()=='en')?$e->nom_anglais:$e->nom  }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group">
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


                                <input class="btn  btn-success " type="submit" value="{{  trans('admin.confirmer')  }}">

                            </fieldset>
                        </form>


                    </div>

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
@endsection