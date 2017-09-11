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
            <th>#</th>
            <th>{{trans('admin.classe')}}</th>
            <th>{{trans('admin.personnel')}}</th>
            </thead>
            <tbody>
            <?php  $i=1; ?>
            @foreach($titulaire as $c)
                <tr>
                    <td>{{$i++}}</td>
                    @foreach($classes as $classe)
                        @if($classe->id == $c->id_classe)
                            <td>  {{  $classe->nom  }}</td>
                            <?php break; ?>
                        @endif

                    @endforeach
                    @foreach($personnel as $p)
                        @if($p->id == $c->id_personnel)
                            <td>  {{  $p->nom  }}</td>
                            <?php break; ?>
                        @endif

                    @endforeach





                    <td><a href="{{ URL::to('/personnel/titulaire/destroy/').'/'.$c->id }}"  title="Supprimer" class="btn btn-danger btn-xs pull-right"><span class="glyphicon glyphicon-trash"></span> Supprimer</a></td>
                </tr>
            @endforeach
            </tbody>
        </table>
        <button class="btn btn-primary" data-toggle="modal" data-backdrop="false" href="#add_cycle"
        ><span class="glyphicon glyphicon-plus"></span>{{  trans('admin.ajouter_titulaire')  }}</button>
        <div class="modal fade" id="add_cycle">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">x</button>
                        <h4 class="modal-title">{{  trans('admin.remplir_champs')  }}</h4>
                    </div>
                    <div class="modal-body">



                        <form accept-charset="UTF-8" id="edit_eco_form" role="form" method="POST" action="{{ url('/personnel/titulaire/store/') }}">
                            <input type="hidden" name="_token" value="{{ csrf_token() }}">
                            <fieldset>
                                <div class="form-group">
                                    <label for="classe">{{  trans('admin.choix_classe')  }} : <span class="required">*</span></label>
                                    <select  name="id_classe" form="edit_eco_form" class="form-control">
                                        @foreach($st as $classe)
                                            <option value="{{  $classe->id  }}" @if($classe->id == old('id_classe')) selected @endif>{{ $classe->nom }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="personnel">{{  trans('admin.choix_personnel')  }} : <span class="required">*</span></label>
                                    <select  name="id_personnel" form="edit_eco_form" class="form-control">
                                        @foreach($sc as $p)
                                            <option value="{{  $p->id  }}" @if($p->id == old('id_classe')) selected @endif>{{ $p->nom }}</option>
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