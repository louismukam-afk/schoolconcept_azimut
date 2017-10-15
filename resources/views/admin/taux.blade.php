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
            <th>{{  trans('admin.personnel')  }}</th>
            <th>{{  trans('admin.taux')  }}</th>
            </thead>
            <tbody>
            <?php $i=0; ?>
            @foreach($taux as $t)
                <tr>
                    <td>{{++$i}}</td>
                    <td>
                        @foreach($personnels as $p)
                            @if($p->id == $t->id_personnel)
                                {{  $p->nom  }}
                                <?php break; ?>
                            @endif
                        @endforeach
                    </td>

                    <td>{{$t->taux.' '.trans('inscription.devise').'/ H' }}</td>
                    <td>

                        <a style="color: white" href="{{ URL::to('/admin/taux/destroy/').'/'.$t->id }}"   class="btn btn-danger btn-xs"><span class="glyphicon glyphicon-trash"></span> {{  trans('admin.del')  }}</a>

                </tr>
            @endforeach
            </tbody>
        </table>
        <button class="btn btn-primary" data-toggle="modal" data-backdrop="false" href="#add_cycle"
        ><span class="glyphicon glyphicon-plus"></span>{{  trans('admin.ajouter_taux')  }}</button>

        <div class="modal fade" id="add_cycle">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">x</button>
                        <h4 class="modal-title">{{  trans('admin.remplir_champs')  }}</h4>
                    </div>
                    <div
                            class="modal-body">



                        <form accept-charset="UTF-8" role="form" method="POST" id="add_level_form" action="{{ url('/admin/taux/store/') }}">
                            <input type="hidden" name="_token" value="{{ csrf_token() }}">

                            <fieldset>
                                <div class="form-group">
                                    <label>{{  trans('admin.personnel')  }} : </label>
                                    <select  name="id_personnel" form="add_level_form" class="form-control">
                                        @foreach($personnel_sans as $p)
                                            <option value="{{  $p->id  }}" @if($p->id == old('id_personnel')) selected @endif>{{  $p->nom }}</option>
                                        @endforeach
                                    </select>
                                </div>


                                <div class="form-group">
                                    <label >{{  trans('admin.taux')  }} : <span class="required">*</span></label>
                                    <div class="input-group">
                                        <input class="form-control"  name="taux" type="number"  value="{{ old('taux') }}">
                                        <span class="input-group-addon">{{ trans('inscription.devise') }}</span>

                                    </div>

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