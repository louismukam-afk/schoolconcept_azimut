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
                    <input type="submit" class="btn btn-success form-control" value="{{trans('admin.confirmer')}}" >
                </div>
            </form>

        </div>
@endsection