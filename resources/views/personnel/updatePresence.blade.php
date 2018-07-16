@extends('skeleton')
@section('sub-content')
    @include('perso.functions')
    @if(isset($success))

        <div class="alert alert-success">
            {{  trans('admin.succes')  }}
        </div>

    @endif
    <div class="col-md-12">
        <button class="btn btn-primary" data-toggle="modal" data-backdrop="false" href="#add_presence"
        ><span class="glyphicon glyphicon-plus"></span>{{  trans('admin.ajouter')  }}</button>
    </div>

    <div class="col-md-10 ">

        <table class="table table-bordered table-striped table-condensed table-inverse" style="margin-top: 15px;">
            <thead>
            <th>#</th>
            <th>Date</th>
            <th>@lang('admin.jour')</th>
            <th>@lang('admin.intervalle')</th>
            <th>{{trans('admin.quantite')}}</th>
            <th>@lang('admin.date_enr')</th>

            </thead>
            <tbody>
            <?php $i=1; ?>
            @foreach($realise as $h)
                <tr>
                    <td>{{$i++}}</td>
                    <td>{{(new DateTime($h->date_heure_realisee))->format('d-m-Y')}}</td>
                    <td>{{jour($h->jour)}}</td>
                    <td>{{$h->intervalle}}</td>
                    <td><strong >{{$h->nombre}}</strong></td>
                    <td>{{(new DateTime($h->date_enregistrement))->format('d-m-Y')}}</td>
                    <td><a href="{{ URL::to('/personnel/heures-effectuees/destroy/').'/'.$h->id.'?personnel='.$h->id_personnel}}"  class="btn btn-danger btn-xs pull-right"><span class="glyphicon glyphicon-trash"></span> Supprimer</a></td>
                </tr>
            @endforeach
            </tbody>
        </table>

        <div class="paginate" style="text-align: center;"> <?php echo(str_replace('/?', '?', $realise->render()) ); ?></div>


    </div>


    <div class="modal fade" id="add_presence">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">x</button>
                    <h4 class="modal-title">{{  trans('admin.remplir_champs')  }}</h4>
                </div>
                <div
                        class="modal-body">

                    <form accept-charset="UTF-8" role="form" method="POST" id="presence-form"  action="{{ url('/personnel/heures-effectuees/update/'.$personnel->id) }}">
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">

                        <fieldset>


                            <div class="form-group col-md-6">
                                <label >{{  trans('admin.horaire')  }} : </label>
                                <select class="form-control" form="presence-form" name="dispense">
                                    <option value="0">@lang('admin.aucun')</option>
                                    <optgroup label="@lang('admin.jour1')">
                                        @foreach($dispenses as $d)
                                            @if($d->jour==1)
                                                @foreach($horaires as $h)
                                                    @if($d->id_horaire==$h->id)
                                                        <option value="{{$d->id}}">{{$h->intervalle}}</option>

                                                        <?php break; ?>
                                                    @endif
                                                @endforeach
                                            @endif
                                        @endforeach
                                    </optgroup>
                                    <optgroup label="@lang('admin.jour2')">
                                        @foreach($dispenses as $d)
                                            @if($d->jour==2)
                                                @foreach($horaires as $h)
                                                    @if($d->id_horaire==$h->id)
                                                        <option value="{{$d->id}}">{{$h->intervalle}}</option>

                                                        <?php break; ?>
                                                    @endif
                                                @endforeach
                                            @endif
                                        @endforeach
                                    </optgroup>

                                    <optgroup label="@lang('admin.jour3')">
                                        @foreach($dispenses as $d)
                                            @if($d->jour==3)
                                                @foreach($horaires as $h)
                                                    @if($d->id_horaire==$h->id)
                                                        <option value="{{$d->id}}">{{$h->intervalle}}</option>

                                                        <?php break; ?>
                                                    @endif
                                                @endforeach
                                            @endif
                                        @endforeach
                                    </optgroup>
                                    <optgroup label="@lang('admin.jour4')">
                                        @foreach($dispenses as $d)
                                            @if($d->jour==4)
                                                @foreach($horaires as $h)
                                                    @if($d->id_horaire==$h->id)
                                                        <option value="{{$d->id}}">{{$h->intervalle}}</option>

                                                        <?php break; ?>
                                                    @endif
                                                @endforeach
                                            @endif
                                        @endforeach
                                    </optgroup>
                                    <optgroup label="@lang('admin.jour5')">
                                        @foreach($dispenses as $d)
                                            @if($d->jour==5)
                                                @foreach($horaires as $h)
                                                    @if($d->id_horaire==$h->id)
                                                        <option value="{{$d->id}}">{{$h->intervalle}}</option>

                                                        <?php break; ?>
                                                    @endif
                                                @endforeach
                                            @endif
                                        @endforeach
                                    </optgroup>
                                    <optgroup label="@lang('admin.jour6')">
                                        @foreach($dispenses as $d)
                                            @if($d->jour==6)
                                                @foreach($horaires as $h)
                                                    @if($d->id_horaire==$h->id)
                                                        <option value="{{$d->id}}">{{$h->intervalle}}</option>

                                                        <?php break; ?>
                                                    @endif
                                                @endforeach
                                            @endif
                                        @endforeach
                                    </optgroup>
                                    <optgroup label="@lang('admin.jour7')">
                                        @foreach($dispenses as $d)
                                            @if($d->jour==7)
                                                @foreach($horaires as $h)
                                                    @if($d->id_horaire==$h->id)
                                                        <option value="{{$d->id}}">{{$h->intervalle}}</option>

                                                        <?php break; ?>
                                                    @endif
                                                @endforeach
                                            @endif
                                        @endforeach
                                    </optgroup>
                                </select>

                            </div>

                            <div class="form-group col-md-6">
                                <label >{{  trans('admin.compte_heure_')  }} : </label>
                                <div class="input-group">
                                    <input class="form-control"  name="nombre" type="number"  value="{{ old('nombre') }}"  required>
                                    <span class="input-group-addon">{{ trans('admin.heure') }}</span>

                                </div>

                            </div>
                            <div class="form-group col-md-6">
                                <label > Date : </label>
                                <input class="form-control"  name="date" type="date"  value="{{ old('date') }}" >

                            </div>
                            <div class="col-md-12">
                                <input class="btn  btn-success " type="submit" value="{{  trans('admin.confirmer')  }}">

                            </div>


                        </fieldset>
                    </form>




                </div>





            </div>


        </div>
    </div>
<div class="col-md-">

</div>

@endsection