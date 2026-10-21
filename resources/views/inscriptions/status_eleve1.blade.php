@extends('skeleton')
@section('sub-content')
    <style>
        .big-title{
            display: none;
        }
    </style>
    <input style="opacity: 0" type="text" id="qrcode-input" name="qrcode" autofocus>
    <div id="scan-error-message" class="hidden text-center" >
        <h3 style="color: red; max-width: 600px; margin-left: auto; margin-right: auto" class="">@lang('admin.bad_code')</h3>
    </div>
    <div id="status-container">
        @if(isset($eleve))
            @if($acces == null)
                <div class="alert alert-warning"  style="display: flex;justify-content: center;align-items: center">
                    <span class="fa fa-question-circle" style="color: gray; font-size: 9em"></span>
                    <h4  style="color: gray; font-size: 3em; max-width: 300px; margin-left: 10px"> @lang('admin.no_access_status')</h4>
                </div>
            @else
                @if($acces->decision == \gesecole\Vars::DECISION_ALLOW)
                    <div class="alert alert-success"  style="display: flex;justify-content: center;align-items: center">
                        <span class="fa fa-check-circle" style="color: green; font-size: 9em"></span>
                        <h4  style="color: green; font-size: 3em; max-width: 300px; margin-left: 10px"> @lang('admin.allow_access')</h4>
                    </div>
                @elseif($acces->decision == \gesecole\Vars::DECISION_DENY)
                    <div class="alert alert-danger"  style="display: flex;justify-content: center;align-items: center">
                        <span class="fa fa-ban" style="color: red; font-size: 9em"></span>
                        <h4  style="color: red; font-size: 3em; max-width: 300px; margin-left: 10px"> @lang('admin.deny_access')</h4>

                    </div>

                @else
                    <div class="alert alert-warning"  style="display: flex;justify-content: center;align-items: center">
                        <span class="fa fa-question-circle" style="color: gray; font-size: 9em"></span>
                        <h4  style="color: gray; font-size: 3em; max-width: 300px; margin-left: 10px"> @lang('admin.no_access_status')</h4>
                    </div>
                @endif
            @endif

            @if(\Auth::user()->canDoAction(\gesecole\Action::ACTION_ADMIN_STUDENT_REGISTRATION_STATUS_UPDATE))
                <div class="row">
                    <div class="col-md-12" style="margin-bottom: 10px">
                        <div class="btn-group">
                            <a href="{{url('/inscriptions/eleve/set-status?status=1&id='. $eleve->id)}}" class="btn btn-success"> @lang('admin.allow_student_access')</a>
                            <a href="{{url('/inscriptions/eleve/set-status?status=0&id='. $eleve->id)}}" class="btn btn-danger"> @lang('admin.deny_student_access')</a>
                        </div>
                    </div>
                </div>
            @endif

        @endif

        <div class="row">
            <div class="col-md-6">
                @if(isset($eleve))
                    <div class="alert alert-info">
                        <a href="{{url('/inscriptions/enregistrement/edit',$eleve->id)}}"><h3 class="text-uppercase"><span class="glyphicon glyphicon-user"></span> <strong>{{$eleve->nom . ' ' . $eleve->prenom}}</strong></h3></a>
                    </div>
                    <hr>
                @endif

                @if(isset($inscription))
                    <h4><strong>@lang('admin.filiere'): </strong> {{App::getLocale() == 'en' ? $inscription->classe->niveau->nom_anglais : $inscription->classe->niveau->nom}}</h4>
                    <h4><strong>@lang('admin.classe'):</strong> {{App::getLocale() == 'en' ? $inscription->classe->nom_anglais : $inscription->classe->nom}}</h4>
                    <h4><strong>@lang('admin.nniveau'):</strong> {{$inscription->nniveau->nom}}</h4>
                @else
                    <h3 style="color: red">@lang('inscription.pas_inscrit')</h3>
                @endif

            </div>

            <div class="col-md-6">
                @if(isset($inscription) && isset($scolarite) )
                    <div class="panel panel-primary">
                        <div class="panel-heading">
                            <div class="panel-title">  <h4><strong>@lang('admin.schoolfees')</strong></h4>
                            </div>

                        </div>
                        <div class="panel-body">
                            <table class="table table-bordered table-stripped">
                                <thead>
                                <th></th>
                                <th>@lang('admin.montant')</th>
                                <th>@lang('admin.status')</th>
                                </thead>
                                <tbody>
                                @if($scolarite->tranche1 > 0)
                                    <tr>
                                        <td><strong>@lang('admin.tranche1')</strong></td>
                                        <td>{{$scolarite->tranche1 . ' ' . trans('inscription.devise')}}</td>
                                        <td class="text-center">
                                            @if($solde - $scolarite->tranche1 >= 0)
                                                <span style="color: green; font-size: 22px" class="fa fa-check-circle"></span>
                                            @elseif($scolarite->tranche1 - $solde >= $scolarite->tranche1)
                                                <span style="color: red; font-size: 22px" class="fa fa-ban"></span>
                                            @else
                                                <span style="color: orangered">@lang('admin.reste') {{ ($scolarite->tranche1 - $solde . ' ' . trans('inscription.devise')) }}</span>
                                            @endif
                                        </td>
                                    </tr>
                                @endif
                                @if($scolarite->tranche2 > 0)
                                    <tr>
                                        <td><strong>@lang('admin.tranche2')</strong></td>
                                        <td>{{$scolarite->tranche2 . ' ' . trans('inscription.devise')}}</td>
                                        <td class="text-center">
                                            @if($solde - ($scolarite->tranche1 + $scolarite->tranche2) >= 0)
                                                <span style="color: green; font-size: 22px" class="fa fa-check-circle"></span>
                                            @elseif(($scolarite->tranche1 + $scolarite->tranche2) - $solde >= $scolarite->tranche2)
                                                <span style="color: red; font-size: 22px" class="fa fa-ban"></span>
                                            @else
                                                <span style="color: orangered">@lang('admin.reste') {{ (($scolarite->tranche1 + $scolarite->tranche2) - $solde . ' ' . trans('inscription.devise')) }}</span>
                                            @endif
                                        </td>
                                    </tr>
                                @endif
                                @if($scolarite->tranche3 > 0)
                                    <tr>
                                        <td><strong>@lang('admin.tranche3')</strong></td>
                                        <td>{{$scolarite->tranche3 . ' ' . trans('inscription.devise')}}</td>
                                        <td class="text-center">
                                            @if($solde - ($scolarite->tranche1 + $scolarite->tranche2 + $scolarite->tranche3) >= 0)
                                                <span style="color: green; font-size: 22px" class="fa fa-check-circle"></span>
                                            @elseif(($scolarite->tranche1 + $scolarite->tranche2 + $scolarite->tranche3) - $solde >= $scolarite->tranche3)
                                                <span style="color: red; font-size: 22px" class="fa fa-ban"></span>
                                            @else
                                                <span style="color: orangered">@lang('admin.reste') {{ (($scolarite->tranche1 + $scolarite->tranche2 + $scolarite->tranche3) - $solde . ' ' . trans('inscription.devise')) }}</span>
                                            @endif
                                        </td>
                                    </tr>
                                @endif
                                @if($scolarite->tranche4 > 0)
                                    <tr>
                                        <td><strong>@lang('admin.tranche4')</strong></td>
                                        <td>{{$scolarite->tranche4 . ' ' . trans('inscription.devise')}}</td>
                                        <td class="text-center">
                                            @if($solde - ($scolarite->tranche1 + $scolarite->tranche2 + $scolarite->tranche3 + $scolarite->tranche4) >= 0)
                                                <span style="color: green; font-size: 22px" class="fa fa-check-circle"></span>
                                            @elseif(($scolarite->tranche1 + $scolarite->tranche2 + $scolarite->tranche3 + $scolarite->tranche4) - $solde >= $scolarite->tranche4)
                                                <span style="color: red; font-size: 22px" class="fa fa-ban"></span>
                                            @else
                                                <span style="color: orangered">@lang('admin.reste') {{ (($scolarite->tranche1 + $scolarite->tranche2 + $scolarite->tranche3 + $scolarite->tranche4) - $solde . ' ' . trans('inscription.devise')) }}</span>
                                            @endif
                                        </td>
                                    </tr>
                                @endif
                                @if($scolarite->tranche5 > 0)
                                    <tr>
                                        <td><strong>@lang('admin.tranche5')</strong></td>
                                        <td>{{$scolarite->tranche5 . ' ' . trans('inscription.devise')}}</td>
                                        <td class="text-center">
                                            @if($solde - ($scolarite->tranche1 + $scolarite->tranche2 + $scolarite->tranche3 + $scolarite->tranche4 + $scolarite->tranche5) >= 0)
                                                <span style="color: green; font-size: 22px" class="fa fa-check-circle"></span>
                                            @elseif(($scolarite->tranche1 + $scolarite->tranche2 + $scolarite->tranche3 + $scolarite->tranche4 + $scolarite->tranche5) - $solde >= $scolarite->tranche5)
                                                <span style="color: red; font-size: 22px" class="fa fa-ban"></span>
                                            @else
                                                <span style="color: orangered">@lang('admin.reste') {{ (($scolarite->tranche1 + $scolarite->tranche2 + $scolarite->tranche3 + $scolarite->tranche4+ $scolarite->tranche5) - $solde . ' ' . trans('inscription.devise')) }}</span>
                                            @endif
                                        </td>
                                    </tr>
                                @endif
                                </tbody>
                                <tfoot>
                                <tr>
                                    <td><strong>@lang('admin.total')</strong></td>
                                    <td>{{ $scolarite->tranche1 + $scolarite->tranche2 + $scolarite->tranche3 + $scolarite->tranche4 + $scolarite->tranche5 }} {{trans('inscription.devise')}}</td>
                                    <td class="text-center" style="color: orangered">{{ $solde }} {{trans('inscription.devise')}}</td>
                                </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>

                @endif

            </div>
        </div>


    </div>

@endsection

@section('breadcrumb')
    <li><strong><a href="{{url('inscriptions')}}">@lang('menu.gestion_inscriptions')</a></strong></li>
    <li class="active"><strong>{{$titre}}</strong></li>
@endsection