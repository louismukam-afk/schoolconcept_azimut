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
            <caption style="font-size: 22px">{{  trans('admin.salaire')  }}</caption>
            <th>#</th>
            <th>Montant</th>
            <th>Nom</th>
            </thead>
            <tbody>
            <?php $i=1; ?>
            @foreach($salaire as $c)
                <tr>
                    <td>{{$i++}}</td>


                    @foreach($personnel as $p)
                        @if($p->id == $c->id_personnel)
                          <td>  {{  $p->nom  }}</td>
                            <?php break; ?>
                        @endif
                    @endforeach

                    <td><a href="{{ URL::to('/personnel/salaire/destroy/').'/'.$c->id }}"  title="Supprimer" class="btn btn-danger btn-xs pull-right"><span class="glyphicon glyphicon-trash"></span> Supprimer</a></td>
                </tr>
            @endforeach
            </tbody>
        </table>
        <button class="btn btn-primary" data-toggle="modal" data-backdrop="false" href="#add_cycle"
        ><span class="glyphicon glyphicon-plus"></span>{{  trans('admin.ajouter_salaire')  }}</button>
        <div class="modal fade" id="add_cycle">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">x</button>
                        <h4 class="modal-title">{{  trans('admin.remplir_champs')  }}</h4>
                    </div>
                    <div class="modal-body">



                        <form accept-charset="UTF-8" id="edit_salaire_form" role="form" method="POST" action="{{ url('/personnel/salaire/store/') }}">
                            <input type="hidden" name="_token" value="{{ csrf_token() }}">
                            <fieldset>
                                <div class="form-group">
                                    <label for="niveau">{{  trans('admin.choose_level')  }} : <span class="required">*</span></label>
                                    <select  name="id_personnel" form="edit_salaire_form" class="form-control">
                                        @foreach($personnel as $p)
                                            <option value="{{  $p->id  }}" @if($p->id == old('id_personnel')) selected @endif>{{  $p->nom  }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="form-group">
                                    <label for="montant">{{  trans('admin.montant')  }} : <span class="required">*</span></label>
                                    <input class="form-control" id="nom" name="montant" type="text"  value="{{ old('montant') }}" required autofocus>

                                </div>
                                <input class="btn  btn-success " type="submit" value="{{  trans('admin.confirmer')  }}">


                            </fieldset>
                        </form>


                    </div>





                </div>


            </div>
        </div>

        <div class="paginate" style="text-align: center;"> <?php echo(str_replace('/?', '?', $salaire->render()) ); ?></div>
    </div>

@endsection