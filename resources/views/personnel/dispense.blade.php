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
            <caption style="font-size: 22px">{{ trans('admin.titre_table_dispense').' '.$personnel->nom }}</caption>
            <th>#</th>
            <th>classe</th>
            <th>matiere</th>
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





                    <td><a href="{{ URL::to('/personnel/dispense/destroy/').'/'.$c->id }}"  title="Supprimer" class="btn btn-danger btn-xs pull-right"><span class="glyphicon glyphicon-trash"></span> Supprimer</a></td>
                </tr>
            @endforeach
            </tbody>
        </table>
        <button class="btn btn-primary" data-toggle="modal" data-backdrop="false" href="#add_cycle"
        ><span class="glyphicon glyphicon-plus"></span>{{  trans('admin.ajouter_dispense')  }}</button>
        <div class="modal fade" id="add_cycle">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">x</button>
                        <h4 class="modal-title">{{  trans('admin.remplir_champs')  }}</h4>
                    </div>
                    <div class="modal-body">



                        <form accept-charset="UTF-8" id="edit_manger_form" role="form" method="POST" action="{{ url('/personnels/dispense/store/') }}">
                            <input type="hidden" name="_token" value="{{ csrf_token() }}">
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


                                <input class="btn  btn-success " type="submit" value="{{  trans('admin.confirmer')  }}">

                            </fieldset>
                        </form>


                    </div>

                </div>


            </div>
        </div>


    </div>

@endsection