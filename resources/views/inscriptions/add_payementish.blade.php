@extends('skeleton')
@section('sub-content')
    @if(isset($success))

        <div class="alert alert-success">
            {{  trans('admin.succes')  }}

        </div>

    @endif

    @if(isset($custom_error))

        <div class="alert alert-danger">
            {{  $custom_error  }}
        </div>

    @endif

        <p style="font-size: 18px"> <strong>Matricule : {{$e->matricule}} </strong></p>
        <p style="font-size: 18px"> <strong>{{trans('admin.nom')}} : {{$e->nom.' '.$e->prenom}} </strong></p>

        <div class="col-md-8">
            <form class="form-horizontal" id="add_inscription_form" method="post" action="{{URL::to('/inscriptions/payement/store/'.$e->id)}}">
                <input type="hidden" name="_token" value="{{ csrf_token() }}"/>
                <input type="hidden" name="id_eleve" value="{{$e->id}}">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="montant">{{trans('inscription.montant')}} : </label>
                        <div class="input-group">
                            <input type="number" name="montant" id="montant" placeholder="EX: 15000" class="form-control" required value="{{old('montant')}}">
                            <span class="input-group-addon"> {{trans('inscription.devise')}}</span>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="motif">{{trans('inscription.motif')}} : </label>
                            <input type="text" name="motif" id="motif" placeholder="EX: 1ère tranche" class="form-control" required value="{{old('motif')}}">

                    </div>
                    <div class="form-group">
                        <label>Montant en lettre</label>
                        <input type="text" name="lettre"  class="form-control"  value="{{old('lettre')}}">

                    </div>
                    <div class="form-group">
                        <label for="mode_paye">{{trans('inscription.detail')}} : </label>
                        <select form="add_inscription_form" id="mode_paye" name="mode_paye" class="form-control">
                            <option value="bancaire">{{trans('inscription.bancaire') }}</option>
                            <option value="orange money">{{trans('inscription.om')}}</option>
                            <option value="espece">{{trans('inscription.espece')}}</option>

                        </select>

                    </div>
                    <div class="form-group">
                        <label>{{trans('inscription.ref')}} </label>
                        <input type="text" name="ref_versement"  class="form-control"  value="{{old('ref_versement')}}">

                    </div>
                    <input type="submit" class="btn btn-success form-control" value="{{trans('admin.confirmer')}}" >
                </div>
            </form>

        </div>
@endsection

@section('breadcrumb')
    <li><strong><a href="{{url('inscriptions')}}">@lang('menu.gestion_inscriptions')</a></strong></li>
    <li><strong><a href="{{url('inscriptions/payement/search')}}">@lang('inscription.title_search')</a></strong></li>
    <li class="active"><strong>{{$titre}}</strong></li>
@endsection

