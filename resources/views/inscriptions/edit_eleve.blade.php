@extends('skeleton')
@section('sub-content')

    <div class="col-md-6">
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
                    <div class="col-md-5">
                        <div class="form-group">
                            <label for="date_naiss">{{  trans('inscription.date_naiss')  }} : <span class="required">*</span></label>
                            <input class="form-control" id="date_naiss" name="date_naiss" type="date"  value="{{ $eleve->date_naiss }}"  placeholder="10/07/2009">

                        </div>
                    </div>
                    <div class="col-md-5">
                        <div class="form-group">
                            <label for="lieu_naiss">{{  trans('inscription.lieu_naiss')  }} : <span class="required">*</span></label>
                            <input class="form-control" id="lieu_naiss" name="lieu_naiss" type="text"  value="{{ $eleve->lieu_naiss }}"  >

                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-5">
                        <div class="form-group">
                            <label for="sexe">{{  trans('inscription.sexe')  }} : </label>
                            <select form="enregistrer_eleve_form" id="sexe" name="sexe" class="form-control">
                                <option value="M" @if(strcmp($eleve->sexe,'M')==0) selected @endif>{{trans('inscription.homme') }}</option>
                                <option value="F" @if(strcmp($eleve->sexe,'F')==0) selected @endif>{{trans('inscription.femme')}}</option>
                            </select>

                        </div>
                    </div>
                    <div class="col-md-5">
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

                <div class="">
                    <div class="form-group">
                        <label for="email">{{  trans('inscription.email')  }} : </label>
                        <input class="form-control" id="email" name="email" type="text"  value="{{  $eleve->email }}"  >

                    </div>
                </div>

                <div class="">
                    <div class="form-group">
                        <label for="tel">{{  trans('inscription.tel')  }} : </label>
                        <input class="form-control" id="tel" name="tel" type="text"  value="{{  $eleve->tel }}"  >

                    </div>
                </div>

                <div class="">
                    <div class="form-group">
                        <label for="status_matrimonial">{{  trans('inscription.status_matrimonial')  }} : </label>
                        <input class="form-control" id="status_matrimonial" name="status_matrimonial" type="text"  value="{{  $eleve->status_matrimonial }}"  >

                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="region_origine">{{  trans('inscription.region_origine')  }} : </label>
                            <input class="form-control" id="region_origine" name="region_origine" type="text"  value="{{  $eleve->region_origine }}"  >

                        </div>

                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="departement_origine">{{  trans('inscription.departement_origine')  }} : </label>
                            <input class="form-control" id="departement_origine" name="departement_origine" type="text"  value="{{  $eleve->departement_origine }}"  >

                        </div>

                    </div>
                </div>
                <div>
                    <div class="form-group">
                        <label for="diplome_preinscription">{{  trans('inscription.diplome_preinscription')  }} : </label>
                        <input class="form-control" id="diplome_preinscription" name="diplome_preinscription" type="text"  value="{{  $eleve->diplome_preinscription }}"  >

                    </div>

                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="annee_obtention">{{  trans('inscription.annee_obtention')  }} : </label>
                            <input class="form-control" id="annee_obtention" name="annee_obtention" type="text"  value="{{  $eleve->annee_obtention }}"  >

                        </div>

                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="pays_obtention">{{  trans('inscription.pays_obtention')  }} : </label>
                            <input class="form-control" id="pays_obtention" name="pays_obtention" type="text"  value="{{  $eleve->pays_obtention }}"  >

                        </div>

                    </div>
                </div>
                <div>
                    <div class="form-group">
                        <label for="dernier_etablissement">{{  trans('inscription.dernier_etablissement')  }} : </label>
                        <input class="form-control" id="dernier_etablissement" name="dernier_etablissement" type="text"  value="{{  $eleve->dernier_etablissement }}"  >

                    </div>

                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="annee_sortie">{{  trans('inscription.annee_sortie')  }} : </label>
                            <input class="form-control" id="annee_sortie" name="annee_sortie" type="text"  value="{{  $eleve->annee_sortie }}"  >

                        </div>

                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="diplome_obtenu">{{  trans('inscription.diplome_obtenu')  }} : </label>
                            <input class="form-control" id="diplome_obtenu" name="diplome_obtenu" type="text"  value="{{  $eleve->diplome_obtenu }}"  >

                        </div>

                    </div>
                </div>
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="premiere_langue">{{  trans('inscription.premiere_langue')  }} : </label>
                            <input class="form-control" id="premiere_langue" name="premiere_langue" type="text"  value="{{  $eleve->premiere_langue }}"  >

                        </div>

                    </div> <div class="col-md-4">
                        <div class="form-group">
                            <label for="deuxieme_langue">{{  trans('inscription.deuxieme_langue')  }} : </label>
                            <input class="form-control" id="deuxieme_langue" name="deuxieme_langue" type="text"  value="{{  $eleve->deuxieme_langue }}"  >

                        </div>

                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="autre_langue">{{  trans('inscription.autre_langue')  }} : </label>
                            <input class="form-control" id="autre_langue" name="autre_langue" type="text"  value="{{  $eleve->autre_langue }}"  >

                        </div>

                    </div>
                </div>
                <div >
                    <div class="form-group">
                        <label for="profession">{{  trans('inscription.profession')  }} : </label>
                        <input class="form-control" id="profession" name="profession" type="text"  value="{{  $eleve->profession }}"  >

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
<div class="parent col-md-offset-1 col-md-5">
        <h3>{{trans('inscription.parent')}}</h3>
    @foreach($parents as $p)
        <div class="panel panel-info">
            <div class="panel-heading">
                <div class="panel-title">
                    <h4>
                        @if($p->type==0)
                            {{trans('inscription.mere')}}
                        @elseif($p->type==1)
                            {{trans('inscription.pere')}}
                        @else
                            {{trans('inscription.tuteur')}}
                        @endif
                    </h4>
                </div>

            </div>
            <div class="panel-body" >
                <div>{{trans('admin.nom')}} : <strong>{{$p->nom}}</strong></div>
                <div>{{trans('inscription.quartier')}} : <strong>{{$p->quartier}}</strong></div>
                <div>{{trans('inscription.ville')}} : <strong>{{$p->ville}}</strong></div>
                <div>Tel1 : <strong>{{$p->tel1}}</strong></div>
                <div>Tel2 : <strong>{{$p->tel2}}</strong></div>
            </div>
        </div>



    @endforeach
</div>

@endsection