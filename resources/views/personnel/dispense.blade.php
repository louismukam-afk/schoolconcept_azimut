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
            <th>#</th>
            <th>{{trans('admin.classe')}}</th>
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
                            <td>  {{  $n->nom  }}</td>
                            <?php break; ?>
                        @endif

                    @endforeach

                    @foreach($matieres as $e)
                        @if($e->id == $c->id_matiere)
                            <td>  {{  $e->nom  }}</td>
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
        <button class="btn btn-primary" data-toggle="modal" data-backdrop="false" href="#add_cycle"
        ><span class="glyphicon glyphicon-plus"></span> {{  trans('admin.ajouter_dispense')  }}</button>
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
                                    <label for="classe">{{  trans('admin.choose_level')  }} : <span class="required">*</span></label>
                                    <select  name="id_classe" form="edit_manger_form" class="form-control">
                                        @foreach($classes as $n)
                                            <option value="{{  $n->id  }}" @if($n->id == old('id_classe')) selected @endif>{{ $n->nom }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <input type="hidden" name="id_personnel" value="{{$personnel->id}}">
                                <div class="form-group">
                                    <select  name="id_matiere" form="edit_manger_form" class="form-control">
                                        @foreach($matieres as $e)
                                            <option value="{{  $e->id  }}" @if($e->id == old('id_matiere')) selected @endif>{{  $e->nom  }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group">
                                    <select  name="jour" form="edit_manger_form" class="form-control">
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