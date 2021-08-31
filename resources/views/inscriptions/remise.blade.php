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



    <div class="col-md-12">
        <form class="form-horizontal" id="add_inscription_form" method="post" action="{{URL::to('/inscriptions/remise/reduction')}}">
            <input type="hidden" name="_token" value="{{ csrf_token() }}"/>
            <input type="hidden" name="id_eleve" value="{{$inscription->id_eleve}}">


            <div class="">
                <div class="row">
                    <div class="col-md-10">
                        <div class="form-group">
                            <label for="date">Date de la remise</label>
                            <input type="date" name="date_remise" class="form-control">
                        </div>
                    </div>
                    <div class="col-md-10">
                        <div class="form-group" style="padding: 5px 10px">
                            <label for="lettre">Montant: </label>
                            <input type="text" name="montant" id="montant" placeholder="EX: 15000" class="form-control"  value="{{old('montant')}}" >

                        </div>
                    </div>
                    <div class="col-md-10">
                        <div class="form-group" style="padding: 5px 10px">
                            <label for="lettre">Montant en lettre : </label>
                            <input type="text" name="montant_lettre" id="lettre" placeholder="EX: dix mille fcfa" class="form-control"  value="{{old('montant_lettre')}}" >

                        </div>
                    </div>
                </div>



        <div class="row">
            <div class="col-md-10 col-md-10">
                <input type="submit" class="btn btn-success form-control" value="{{trans('admin.confirmer')}}" >
            </div>
        </div>

    </div>
        </form>
    </div>


    <script>
    </script>
@endsection

@section('breadcrumb')
    <li><strong><a href="{{url('inscriptions')}}">@lang('menu.gestion_inscriptions')</a></strong></li>
    <li><strong><a href="{{url('inscriptions/payement')}}">@lang('inscription.title_search')</a></strong></li>
    <li class="active"><strong>{{$titre}}</strong></li>
@endsection