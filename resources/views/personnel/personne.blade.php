@extends('skeleton')
@section('sub-content')
    <div class="col-md-10">
        @if(isset($success))

            <div class="alert alert-success">
                {{  trans('admin.succes')  }}
            </div>

        @endif

            <button class="btn btn-primary" data-toggle="modal" data-backdrop="false" href="#add_cycle"
            ><span class="glyphicon glyphicon-plus"></span> {{  trans('admin.ajouter_personnel')  }}</button>
            <span class="col-md-12"><strong>Total : {{count($personnel)}}</strong></span>
        <table class="table table-bordered table-striped table-condensed table-inverse" style="margin-top: 5px;">
            <thead>
            <caption style="font-size: 22px">{{  trans('admin.personnels')  }}</caption>
            <th>#</th>
            <th>Nom</th>
            <th>Lieu de naissance</th>
            <th>{{trans('inscription.sexe')}}</th>
            <th>Tel</th>
            </thead>
            <tbody>
            <?php $i=1; ?>
            @foreach($personnel as $c)
                <tr>
                    <td>{{$i++}}</td>
                    <td><strong >{{$c->nom}}</strong></td>
                    <td><strong >{{$c->lieu_naiss}}</strong></td>
                    <td><strong >{{$c->sexe}}</strong></td>
                    <td><strong >{{$c->tel1}}</strong></td>

                    <td><a href="{{ URL::to('/personnel/edit/').'/'.$c->id }}"   class="btn btn-primary btn-xs pull-right"><span class="glyphicon glyphicon-edit"></span> {{trans('admin.see_edit')}}</a></td>
                </tr>
            @endforeach
            </tbody>
        </table>

            <div class="modal fade" id="add_cycle">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal">x</button>
                            <h4 class="modal-title">{{  trans('admin.remplir_champs')  }}</h4>
                        </div>
                        <div class="modal-body">


                        <form accept-charset="UTF-8" role="form" id="select_sexe" method="POST" action="{{ url('/personnel/store/') }}">
                <input type="hidden" name="_token" value="{{ csrf_token() }}">

                <fieldset>

                    <div class="form-group">
                        <label for="nom">{{  trans('admin.nom')  }} : <span class="required">*</span></label>
                        <input class="form-control" id="nom" name="nom" type="text"  value="{{ old('nom') }}" required autofocus>
                        <div class="row">
                            <div class="col-md-6">
                                <label for="date">{{  trans('inscription.date_naiss')  }} : <span class="required">*</span></label>
                                <input class="form-control" id="nom" name="date_naiss" type="date"  value="{{ old('date_naiss') }}" placeholder="jj/mm/aaaa" required>
                            </div>
                            <div class="col-md-6">
                                <label for="lieu_naiss">{{  trans('inscription.lieu_naiss')  }} : <span class="required">*</span></label>
                                <input class="form-control" id="nom" name="lieu_naiss" type="text"  value="{{ old('lieu_naiss') }}" required>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <label for="sexe">{{  trans('inscription.sexe')  }} : <span class="required">*</span></label>
                                <select name="sexe" form="select_sexe" class="form-control">
                                    <option value="H">{{  trans('inscription.homme')  }}</option>
                                    <option value="F">{{  trans('inscription.femme')  }}</option>
                                </select>
                            </div>

                            <div class="col-md-6">
                                <label for="diplome">{{  trans('admin.diplome')  }} : <span class="required">*</span></label>
                                <input class="form-control" id="diplome" name="diplome" type="text"  value="{{ old('diplome') }}" required >
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <label for="tel1">{{  trans('admin.tel1')  }} : <span class="required">*</span></label>
                                <input class="form-control" id="tel1" name="tel1" type="text"  value="{{ old('tel1') }}" required >
                            </div>

                            <div class="col-md-6">
                                <label for="tel2">{{  trans('admin.tel2')  }} :</label>
                                <input class="form-control" id="nom" name="tel2" type="text"  value="{{ old('tel2') }}">
                            </div>
                        </div>





                        <label for="adresse">{{  trans('admin.adresse')  }} : <span class="required">*</span></label>
                        <input class="form-control" id="adresse" name="adresse" type="text"  value="{{ old('adresse') }}" required>
                        <label for="email">{{  trans('admin.email')  }} :</label>
                        <input class="form-control" id="email" name="email" type="text"  value="{{ old('email') }}" >

                        <label >Type :</label>
                        <select name="type" form="select_sexe" class="form-control">
                            <option value="0">@lang('admin.enseignant')</option>
                            <option value="1">@lang('admin.autre')</option>
                        </select>

                        <label for="matieres">{{  trans('admin.titre_matiere')  }} :</label>
                        <textarea  class="form-control" id="matieres" name="matieres">{{ old('matieres') }}</textarea>

                        <label for="autre_information">{{  trans('admin.autre')  }} informations:</label>
                        <textarea  class="form-control" id="autre_information" name="autre_information">{{ old('autre_information') }}</textarea>

                    </div>
                    <input class="btn  btn-success pull-right" type="submit" value="{{  trans('admin.confirmer')  }}">


                </fieldset>
            </form>

        <div class="paginate" style="text-align: center;"> <?php echo(str_replace('/?', '?', $personnel->render()) ); ?></div>
    </div>
</div>
</div>
    </div>
    </div>
@endsection