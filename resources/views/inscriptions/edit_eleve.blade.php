@extends('skeleton')
@section('sub-content')

    <div class="col-md-8">
        @if(isset($success))

            <div class="alert alert-success">
                {{  trans('admin.succes')  }}
            </div>

        @endif




        <form accept-charset="UTF-8" role="form" method="POST" id="enregistrer_eleve_form" action="{{URL::to('/inscriptions/enregistrement/update/'.$eleve->id)}}">
            <input type="hidden" name="_token" value="{{ csrf_token() }}"/>

            <fieldset>
                <legend style="margin-top:20px;margin-bottom:30px;"><strong> {{trans('inscription.eleve')}}</strong></legend>

                <div class="form-group">
                    <label for="nom">{{  trans('inscription.nom')  }} : <span class="required">*</span></label>
                    <input class="form-control" id="nom" name="nom" type="text"  value="{{ $eleve->nom}}"  autofocus>

                </div>
                <div class="form-group">
                    <label for="prenom">{{  trans('inscription.prenom')  }} : </label>
                    <input class="form-control" id="prenom" name="prenom" type="text"  value="{{ $eleve->prenom }}"  >

                </div>
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="date_naiss">{{  trans('inscription.date_naiss')  }} : <span class="required">*</span></label>
                            <input class="form-control" id="date_naiss" name="date_naiss" type="date"  value="{{ $eleve->date_naiss }}"  placeholder="10/07/2009">

                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="lieu_naiss">{{  trans('inscription.lieu_naiss')  }} : <span class="required">*</span></label>
                            <input class="form-control" id="lieu_naiss" name="lieu_naiss" type="text"  value="{{ $eleve->lieu_naiss }}"  >

                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="sexe">{{  trans('inscription.sexe')  }} : </label>
                            <select form="enregistrer_eleve_form" id="sexe" name="sexe" class="form-control">
                                <option value="M" @if(strcmp($eleve->sexe,'M')==0) selected @endif>{{trans('inscription.homme') }}</option>
                                <option value="F" @if(strcmp($eleve->sexe,'F')==0) selected @endif>{{trans('inscription.femme')}}</option>
                            </select>

                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="nationalite">{{  trans('inscription.nationalite')  }} : <span class="required">*</span></label>
                            <input class="form-control" id="nationalite" name="nationalite" type="text"  value="{{ $eleve->nationalite }}"  >

                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label for="quartier">{{  trans('inscription.residence')  }} : <span class="required">*</span></label>
                    <input class="form-control" id="quartier" name="quartier" type="text"  value="{{ $eleve->quartier }}"  required>

                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="transport">{{  trans('inscription.deplacement')  }} : </label>
                        <select form="enregistrer_eleve_form" id="transport" name="transport" class="form-control">
                            <option value="0" @if($eleve->transport==0) selected @endif>{{trans('inscription.pieds') }}</option>
                            <option value="1" @if($eleve->transport==1) selected @endif>{{trans('inscription.voiture')}}</option>
                        </select>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="form-group">
                        <label for="parents_ens">{{  trans('inscription.parents_ens')  }} : </label>
                        <select form="enregistrer_eleve_form" id="parents_ens" name="parents_ens" class="form-control">
                            <option value="0" @if($eleve->parent_ens==0) selected @endif>{{trans('inscription.non') }}</option>
                            <option value="1" @if($eleve->parent_ens==1) selected @endif>{{trans('inscription.oui')}}</option>
                        </select>
                    </div>
                </div>

                <div class="col-md-5">
                    <div class="form-group">
                        <label for="lien_tuteur">{{  trans('inscription.lien_tuteur')  }} : </label>
                        <input class="form-control" id="lien_tuteur" name="lien_tuteur" type="text"  value="{{ $eleve->lien_tuteur }}"  >

                    </div>
                </div>


            </fieldset>
            <input class="btn  btn-success " type="submit" value="{{  trans('admin.confirmer')  }}">

        </form>
    </div>
@endsection