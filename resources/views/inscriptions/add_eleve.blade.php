@extends('skeleton')
@section('sub-content')

    <div class="col-md-8">
        @if(isset($success))

            <div class="alert alert-success">
                {{  trans('admin.succes')  }}
            </div>
            <a class="btn btn-info btn-lg" href="{{ URL::to('/inscriptions/create/').'/'.$success }}">{{trans('inscription.inscrire_nouveau')}} </a>

        @endif
            @if(isset($error_parent))

                <div class="alert alert-danger">
                    {{ $error_parent  }}
                </div>

            @endif



            <form accept-charset="UTF-8" role="form" method="POST" id="enregistrer_eleve_form" action="{{URL::to('/inscriptions/enregistrement/store/')}}">
                <input type="hidden" name="_token" value="{{ csrf_token() }}"/>
                <legend style="margin-top:20px;margin-bottom:30px;"><strong> {{trans('inscription.eleve')}}</strong></legend>

                <fieldset style="padding-left: 40px">

                    <div class="form-group">
                        <label for="nom">{{  trans('inscription.nom')  }} : <span class="required">*</span></label>
                        <input class="form-control" id="nom" name="nom" type="text"  value="{{ old('nom') }}"  autofocus>

                    </div>
                    <div class="form-group">
                        <label for="prenom">{{  trans('inscription.prenom')  }} : </label>
                        <input class="form-control" id="prenom" name="prenom" type="text"  value="{{ old('prenom') }}"  >

                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="date_naiss">{{  trans('inscription.date_naiss')  }} : <span class="required">*</span></label>
                                <input class="form-control" id="date_naiss" name="date_naiss" type="date"  value="{{ old('date_naiss') }}"  placeholder="10/07/2009">

                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="lieu_naiss">{{  trans('inscription.lieu_naiss')  }} : <span class="required">*</span></label>
                                <input class="form-control" id="lieu_naiss" name="lieu_naiss" type="text"  value="{{ old('lieu_naiss') }}"  >

                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="sexe">{{  trans('inscription.sexe')  }} : </label>
                               <select form="enregistrer_eleve_form" id="sexe" name="sexe" class="form-control">
                                   <option value="M" @if(strcmp(old('sexe'),'M')==0) selected @endif>{{trans('inscription.homme') }}</option>
                                   <option value="F" @if(strcmp(old('sexe'),'F')==0) selected @endif>{{trans('inscription.femme')}}</option>
                               </select>

                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="nationalite">{{  trans('inscription.nationalite')  }} : <span class="required">*</span></label>
                                <input class="form-control" id="nationalite" name="nationalite" type="text"  value="{{ old('nationalite') }}"  >

                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="quartier">{{  trans('inscription.residence')  }} : <span class="required">*</span></label>
                        <input class="form-control" id="quartier" name="quartier" type="text"  value="{{ old('quartier') }}"  required>

                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="transport">{{  trans('inscription.deplacement')  }} : </label>
                            <select form="enregistrer_eleve_form" id="transport" name="transport" class="form-control">
                                <option value="0" @if(old('transport')==0) selected @endif>{{trans('inscription.pieds') }}</option>
                                <option value="1" @if(old('transport')==1) selected @endif>{{trans('inscription.voiture')}}</option>
                            </select>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="parents_ens">{{  trans('inscription.parents_ens')  }} : </label>
                            <select form="enregistrer_eleve_form" id="parents_ens" name="parents_ens" class="form-control">
                                <option value="0" @if(old('parents_ens')==0) selected @endif>{{trans('inscription.non') }}</option>
                                <option value="1" @if(old('parents_ens')==1) selected @endif>{{trans('inscription.oui')}}</option>
                            </select>
                        </div>
                    </div>

                    <div class="col-md-5">
                        <div class="form-group">
                            <label for="lien_tuteur">{{  trans('inscription.lien_tuteur')  }} : </label>
                            <input class="form-control" id="lien_tuteur" name="lien_tuteur" type="text"  value="{{ old('lien_tuteur') }}"  >

                        </div>
                    </div>


                </fieldset>








                <legend style="margin-top:20px;margin-bottom:30px;"><strong>{{trans('inscription.pere')}}</strong></legend>

                <fieldset style="padding-left: 40px">
                    <div class="form-group">
                        <label for="nom_pere">{{  trans('inscription.nom')  }} : </label>
                        <input class="form-control" id="nom_pere" name="nom_pere" type="text"  value="{{ old('nom_pere') }}">
                    </div>

                    <div class="col-md-9">
                        <div class="form-group">
                            <label for="profession_pere">{{  trans('inscription.profession')  }} :</label>
                            <input class="form-control" id="profession_pere" name="profession_pere" type="text"  value="{{ old('profession_pere') }}"  >
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="ville_pere">{{  trans('inscription.ville')  }} : </label>
                                <input class="form-control" id="ville_pere" name="ville_pere" type="text"  value="{{ old('ville_pere') }}" >

                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="quartier_pere">{{  trans('inscription.quartier')  }} : </label>
                                <input class="form-control" id="quartier_pere" name="quartier_pere" type="text"  value="{{ old('quartier_pere') }}"  >

                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="tel1_pere">Tel1 : </label>
                                <input class="form-control" id="tel1_pere" name="tel1_pere" type="text"  value="{{ old('tel1_pere') }}" >

                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="tel2_pere">Tel2 : </label>
                                <input class="form-control" id="tel2_pere" name="tel2_pere" type="text"  value="{{ old('tel2_pere') }}"  >

                            </div>
                        </div>
                    </div>
                </fieldset>


                <legend style="margin-top:20px;margin-bottom:30px;"><strong>{{trans('inscription.mere')}}</strong></legend>

                <fieldset style="padding-left: 40px">
                    <div class="form-group">
                        <label for="nom_mere">{{  trans('inscription.nom')  }} : </label>
                        <input class="form-control" id="nom_mere" name="nom_mere" type="text"  value="{{ old('nom_mere') }}">
                    </div>

                    <div class="col-md-9">
                        <div class="form-group">
                            <label for="profession_mere">{{  trans('inscription.profession')  }} :</label>
                            <input class="form-control" id="profession_mere" name="profession_mere" type="text"  value="{{ old('profession_mere') }}"  >
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="ville_mere">{{  trans('inscription.ville')  }} : </label>
                                <input class="form-control" id="ville_mere" name="ville_mere" type="text"  value="{{ old('ville_mere') }}" >

                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="quartier_mere">{{  trans('inscription.quartier')  }} : </label>
                                <input class="form-control" id="quartier_mere" name="quartier_mere" type="text"  value="{{ old('quartier_mere') }}"  >

                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="tel1_mere">Tel1 : </label>
                                <input class="form-control" id="tel1_mere" name="tel1_mere" type="text"  value="{{ old('tel1_mere') }}" >

                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="tel2_mere">Tel2 : </label>
                                <input class="form-control" id="tel2_mere" name="tel2_mere" type="text"  value="{{ old('tel2_mere') }}"  >

                            </div>
                        </div>
                    </div>
                </fieldset>
                <legend style="margin-top:20px;margin-bottom:30px;"><strong>{{trans('inscription.tuteur')}}</strong></legend>

                <fieldset style="padding-left: 40px">
                    <div class="form-group">
                        <label for="nom_tuteur">{{  trans('inscription.nom')  }} : </label>
                        <input class="form-control" id="nom_tuteur" name="nom_tuteur" type="text"  value="{{ old('nom_tuteur') }}">
                    </div>

                    <div class="col-md-9">
                        <div class="form-group">
                            <label for="profession_tuteur">{{  trans('inscription.profession')  }} :</label>
                            <input class="form-control" id="profession_tuteur" name="profession_tuteur" type="text"  value="{{ old('profession_tuteur') }}"  >
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="ville_tuteur">{{  trans('inscription.ville')  }} : </label>
                                <input class="form-control" id="ville_tuteur" name="ville_tuteur" type="text"  value="{{ old('ville_tuteur') }}" >

                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="quartier_tuteur">{{  trans('inscription.quartier')  }} : </label>
                                <input class="form-control" id="quartier_tuteur" name="quartier_tuteur" type="text"  value="{{ old('quartier_tuteur') }}"  >

                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="tel1_tuteur">Tel1 : </label>
                                <input class="form-control" id="tel1_tuteur" name="tel1_tuteur" type="text"  value="{{ old('tel1_tuteur') }}" >

                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="tel2_tuteur">Tel2 : </label>
                                <input class="form-control" id="tel2_tuteur" name="tel2_tuteur" type="text"  value="{{ old('tel2_tuteur') }}"  >

                            </div>
                        </div>
                    </div>
                </fieldset>

                <input class="btn  btn-success form-control" type="submit" value="{{  trans('admin.confirmer')  }}" style="margin-top: 20px">
            </form>
    </div>

@endsection