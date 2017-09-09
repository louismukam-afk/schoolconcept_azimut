@extends('skeleton')
@section('sub-content')

<div class="page-header">
    <span><h4>{{$personnel->nom}}</h4></span>
</div>
<div class="col-md-8">


    <form accept-charset="UTF-8" role="form" id="select_sexe" method="POST" action="{{ url('/personnel/update/'.$personnel->id) }}" disabled="true">
        <input type="hidden" name="_token" value="{{ csrf_token() }}">

        <fieldset>

            <div class="form-group">
                <label for="nom">{{  trans('admin.nom')  }} : <span class="required">*</span></label>
                <input class="form-control" id="nom" name="nom" type="text"  value="{{ $personnel->nom }}" required>
                <div class="row">
                    <div class="col-md-6">
                        <label for="date">{{  trans('inscription.date_naiss')  }} : <span class="required">*</span></label>
                        <input class="form-control" id="date_naiss" name="date_naiss" type="date"  value="{{ $personnel->date_naiss }}" placeholder="jj/mm/aaaa" required>
                    </div>
                    <div class="col-md-6">
                        <label for="lieu_naiss">{{  trans('inscription.lieu_naiss')  }} : <span class="required">*</span></label>
                        <input class="form-control" id="lieu_naiss" name="lieu_naiss" type="text"  value="{{ $personnel->lieu_naiss }}" required>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <label for="sexe">{{  trans('inscription.sexe')  }} : <span class="required">*</span></label>
                        <select name="sexe" form="select_sexe" class="form-control" id="sexe">
                            <option value="H">{{  trans('inscription.homme')  }}</option>
                            <option value="F">{{  trans('inscription.femme')  }}</option>
                        </select>
                    </div>

                    <div class="col-md-6">
                        <label for="diplome">{{  trans('admin.diplome')  }} : <span class="required">*</span></label>
                        <input class="form-control" id="diplome" name="diplome" type="text"  value="{{ $personnel->diplome }}" required >
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <label for="tel1">{{  trans('admin.tel1')  }} : <span class="required">*</span></label>
                        <input class="form-control" id="tel1" name="tel1" type="text"  value="{{ $personnel->tel1 }}" required >
                    </div>

                    <div class="col-md-6">
                        <label for="tel2">{{  trans('admin.tel2')  }} :</label>
                        <input class="form-control" id="tel2" name="tel2" type="text"  value="{{ $personnel->tel2 }}">
                    </div>
                </div>





                <label for="adresse">{{  trans('admin.adresse')  }} : <span class="required">*</span></label>
                <input class="form-control" id="adresse" name="adresse" type="text"  value="{{ $personnel->adresse }}" required>
                <label for="email">{{  trans('admin.email')  }} :</label>
                <input class="form-control" id="email" name="email" type="text"  value="{{ $personnel->email }}" >

            </div>
            <input class="btn  btn-primary pull-right" type="submit" value="{{  trans('admin.modifier')  }}" id="submit">


        </fieldset>
    </form>
</div>

@endsection