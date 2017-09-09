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
            <caption style="font-size: 22px">{{  trans('admin.titre_table_fonction') .'   '.$personnel->nom }}</caption>
            <th>#</th>
            <th>Fonction</th>
            </thead>
            <tbody>
            <?php  $i=1; ?>
            @foreach($fonction_personnel as $c)

                <tr>
                    <td>{{$i++}}</td>
                    @foreach($fonction as $n)
                        @if($n->id == $c->id_fonction)
                            <td>  {{  $n->nom  }}</td>
                            <?php break; ?>
                        @endif
                            <td><strong >{{$c->fonction}}</strong></td>
                    @endforeach





                    <td><a href="{{ URL::to('/personnel/fonction_personnel/destroy/').'/'.$c->id }}"  title="Supprimer" class="btn btn-danger btn-xs pull-right"><span class="glyphicon glyphicon-trash"></span> Supprimer</a></td>
                </tr>
            @endforeach
            </tbody>
        </table>
        <button class="btn btn-primary" data-toggle="modal" data-backdrop="false" href="#add_cycle"
        ><span class="glyphicon glyphicon-plus"></span>{{  trans('admin.fonction_personnel_ajouter')  }}</button>
        <div class="modal fade" id="add_cycle">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">x</button>
                        <h4 class="modal-title">{{  trans('admin.remplir_champs')  }}</h4>
                    </div>
                    <div class="modal-body">



                        <form accept-charset="UTF-8" id="edit_fonction_form " role="form" method="POST" action="{{ url('/personnel/fonction_personnel/store/') }}">
                            <input type="hidden" name="_token" value="{{ csrf_token() }}">
                            <fieldset>
                                <div class="form-group">
                                    <label for="personnel">{{  trans('admin.choose_level')  }} : <span class="required">*</span></label>
                                    <select  name="id_fonction" form="edit_fonction_form" class="form-control">
                                        @foreach($fonction as $n)
                                            <option value="{{  $n->id  }}" @if($n->id == old('id_personnel')) selected @endif>{{ $n->nom }}</option>
                                        @endforeach
                                    </select>
                                    </div>
                                <input type="hidden" name="id_personnel" value="{{$personnel->id}}">




                                <input class="btn  btn-success " type="submit" value="{{  trans('admin.confirmer')  }}">

                            </fieldset>
                        </form>


                    </div>

                </div>


            </div>
        </div>

    </div>

@endsection