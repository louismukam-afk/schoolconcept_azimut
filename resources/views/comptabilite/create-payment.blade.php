@extends('skeleton')
@section('sub-content')

    <div class="col-md-10">
        @if(isset($success))

            <div class="alert alert-success">
                {{  trans('admin.succes')  }}
            </div>

        @endif
    </div>
    @if($salaire!=null)
    <div class="col-md-8">
        <div class="panel panel-success">
            <div class="panel-heading">
                <div class="panel-title">
                   <h3>{{trans('admin.resume')}}</h3>
                </div>
            </div>
            <div class="panel-body">
                <h4><strong>Nom :</strong> {{$personnel->nom}}</h4>
                @if(isset($dernier_payement))
                    <h4>{{trans('admin.date_dernier_payement').': '.$dernier_payement->date_p}}</h4>
                @endif
                <h5 class="alert alert-info"><strong>{{trans('admin.depuis_dernier_payement')}}</strong></h5>
                <p><strong>{{trans('admin.abscences')}} :</strong>

                    <?php $s=0; ?>
                    @foreach($discipline as $d)
                        @if($d->type==0)
                            <?php $s+=$d->nombre; ?>
                        @endif
                    @endforeach
                    {{$s.' '.trans('admin.heure')}}
                </p>
                <p><strong>{{trans('admin.retards')}} :</strong>  {{$discipline->where('type',1,false)->count()}}</p>

                <div class="alert alert-warning"><strong>{{trans('admin.salaire_base')}}</strong>
                    <p><strong>{{$salaire->montant.' '.trans('inscription.devise')}}</strong></p>

                </div>
                <div class="alert alert-info"><strong>{{trans('admin.compte_heure_')}}</strong>
                    <p><strong>{{$heure_realisee.' '.trans('admin.heure')}}</strong></p>

                </div>

                <div class="alert alert-warning"><strong>{{trans('admin.taux')}}</strong>
                    <p><strong>{{$taux?$taux->taux.' '.trans('inscription.devise').' / '.trans('admin.heure'):'0 '.trans('inscription.devise').' / '.trans('admin.heure')}}</strong></p>

                </div>
                <?php
                    $taux?$t=$taux->taux:$t=0;

                ?>
                <div class="alert alert-success"><strong>Total</strong>
                    <p><strong>{{$salaire->montant+$heure_realisee*$t.' '.trans('inscription.devise')}}</strong></p>

                </div>
                <div class="col-md-6">
                    <form accept-charset="UTF-8" role="form" method="POST" id="pay_staff" action="{{ url('/comptabilite/payement-salaire/store/') }}">
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                        <input type="hidden" name="id" value="{{$personnel->id }}">
                        <input type="hidden" name="heure_realisee" value="{{$heure_realisee}}">
                        <input type="hidden" name="taux" value="{{$t}}">
                        <input type="hidden" name="total" value="{{$salaire->montant+$heure_realisee*$t}}">

                        <fieldset>
                            <div class="form-group">
                                <label>{{  trans('admin.mois')  }} : </label>
                                <select form="pay_staff" name="mois" class="form-control">
                                    <option value="1" @if(date('m')==1) selected @endif>@lang('admin.mois1')</option>
                                    <option value="2" @if(date('m')==2) selected @endif>@lang('admin.mois2')</option>
                                    <option value="3" @if(date('m')==3) selected @endif>@lang('admin.mois3')</option>
                                    <option value="4" @if(date('m')==4) selected @endif>@lang('admin.mois4')</option>
                                    <option value="5" @if(date('m')==5) selected @endif>@lang('admin.mois5')</option>
                                    <option value="6" @if(date('m')==6) selected @endif>@lang('admin.mois6')</option>
                                    <option value="7" @if(date('m')==7) selected @endif>@lang('admin.mois7')</option>
                                    <option value="8" @if(date('m')==8) selected @endif>@lang('admin.mois8')</option>
                                    <option value="9" @if(date('m')==9) selected @endif>@lang('admin.mois9')</option>
                                    <option value="10" @if(date('m')==10) selected @endif>@lang('admin.mois10')</option>
                                    <option value="11" @if(date('m')==11) selected @endif>@lang('admin.mois11')</option>
                                    <option value="12" @if(date('m')==12) selected @endif>@lang('admin.mois12')</option>
                                </select>
                            </div>

                            <div class="form-group">
                                <label>{{  trans('admin.primes')  }} : </label>
                                <div class="input-group">
                                    <input class="form-control" name="primes" type="text"  value="{{ old('primes') }}">
                                    <span class="input-group-addon"> {{trans('inscription.devise')}}</span>
                                </div>

                            </div>
                            <div class="form-group">
                                <label>{{  trans('admin.acomptes')  }} : </label>
                                <div class="input-group">
                                    <input class="form-control" name="acomptes" type="text"  value="{{ old('acomptes') }}">
                                    <span class="input-group-addon"> {{trans('inscription.devise')}}</span>
                                </div>

                            </div>
                            <div class="form-group">
                                <label>CNSS : </label>
                                <div class="input-group">
                                    <input class="form-control" name="CNSS" type="text"  value="{{ old('CNSS') }}">
                                    <span class="input-group-addon"> {{trans('inscription.devise')}}</span>
                                </div>

                            </div>
                            <div class="form-group">
                                <label>{{  trans('admin.cas_socials')  }} : </label>
                                <div class="input-group">
                                    <input class="form-control" name="cas_socials" type="text"  value="{{ old('cas_socials') }}">
                                    <span class="input-group-addon"> {{trans('inscription.devise')}}</span>
                                </div>

                            </div>

                            <div class="form-group">
                                <label>{{  trans('admin.assurances')  }} : </label>
                                <div class="input-group">
                                    <input class="form-control" name="assurances" type="text"  value="{{ old('assurances') }}">
                                    <span class="input-group-addon"> {{trans('inscription.devise')}}</span>
                                </div>

                            </div>
                            <div class="form-group">
                                <label>{{  trans('admin.autre_retenues')  }} : </label>
                                <div class="input-group">
                                    <input class="form-control" name="autre_retenues" type="text"  value="{{ old('autre_retenues') }}">
                                    <span class="input-group-addon"> {{trans('inscription.devise')}}</span>
                                </div>

                            </div>

                            <input class="btn  btn-success " type="submit" value="{{  trans('admin.confirmer')  }}">


                        </fieldset>
                    </form>
                </div>

            </div>

        </div>
    </div>
    @else
        <div class="alert alert-danger">{{trans('admin.salaire_non_defini')}}</div>
    @endif


@endsection