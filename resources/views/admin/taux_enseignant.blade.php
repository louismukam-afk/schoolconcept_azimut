@extends('skeleton')
@section('sub-content')


     <div class="col-md-10">
        @if(isset($success))

            <div class="alert alert-success">
                {{  trans('admin.succes')  }}
            </div>

        @endif
         <h3>{{$personnel->nom}}</h3>

        <table class="table  table-striped table-condensed table-inverse" style="margin-top: 15px;">
            <thead>
            <th>{{  trans('admin.matiere')  }}</th>
            <th>{{  trans('admin.classe')  }}</th>
            <th>{{  trans('admin.nniveau')  }}</th>

            <th>{{  trans('admin.taux')  }}</th>
            </thead>
            <tbody>
            <?php $i=0; ?>
            @foreach($taux as $t)
                <tr>
                    <td>
                        @if(($t->id_matiere==0) && ($t->id_nniveau==0) && ($t->id_classe==0))
                            <strong>@lang('admin.taux_defaut')</strong>
                            @else
                        @foreach($matieres as $m)
                            @if($m->id == $t->id_matiere)
                                {{  App::getLocale()=='en'?$m->nom_anglais:$m->nom  }}
                                <?php break; ?>
                            @endif
                        @endforeach
                            @endif
                    </td>
                    <td>
                        @foreach($classes as $c)
                            @if($c->id == $t->id_classe)
                                {{  App::getLocale()=='en'?$c->nom_anglais:$c->nom  }}
                                <?php break; ?>
                            @endif
                        @endforeach
                    </td>
                    <td>
                        @foreach($nniveaux as $nn)
                            @if($nn->id == $t->id_nniveau)
                                {{  $nn->nom  }}
                                <?php break; ?>
                            @endif
                        @endforeach
                    </td>

                    <td>{{$t->taux.' '.trans('inscription.devise').'/ H' }}</td>
                    <td>
                        @if(($t->id_matiere==0) && ($t->id_nniveau==0) && ($t->id_classe==0))
                        @else
                        <a style="color: white" href="{{ URL::to('/admin/taux/destroy/').'/'.$t->id }}"   class="btn btn-danger btn-xs"><span class="glyphicon glyphicon-trash"></span> {{  trans('admin.del')  }}</a>
                        @endif
                </tr>
            @endforeach
            </tbody>
        </table>


            <div class="add-container col-md-12" style="margin-top: 25px">
                <h3>{{trans('admin.ajouter_taux')}}</h3>
                <form accept-charset="UTF-8" role="form" method="POST" id="add_level_form" action="{{ url('/admin/taux/store/') }}">
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                    <input type="hidden" name="id_personnel" value="{{ $personnel->id }}">

                    <fieldset>
                        <div class="row" >


                            <div class="form-group col-sm-8 col-md-10">
                                <input type="checkbox" name="matiere[]" value="0" onchange="onCheckedChange(this,'classe0','nniveau0','taux0')" >
                                <strong> @lang('admin.taux_defaut')</strong>

                                <input type="hidden" id="classe0" name="classe[]" value="0" disabled>
                                <input type="hidden" id="nniveau0" name="nniveau[]" value="0" disabled>
                            </div>
                            <div class="form-group col-sm-4 col-md-2">
                                <label for="taux0">@lang('admin.taux')</label>
                                <input type="number" step="0.05" id="taux0" name="taux[]"  class="form-control" required disabled>
                            </div>

                        </div>
                        <?php $i=1;?>

                        @foreach($dispenses as $d)
                                @foreach($matieres as $m)
                                    @if($m->id == $d->id_matiere)
                                        <?php $matiere=$m; break; ?>
                                    @endif
                                @endforeach
                                    @foreach($classes as $c)
                                        @if($c->id == $d->id_classe)
                                            <?php $classe=$c; break; ?>
                                        @endif
                                    @endforeach
                                    @foreach($nniveaux as $nn)
                                        @if($nn->id == $d->id_nniveau)
                                            <?php $nniveau=$nn; break; ?>
                                        @endif
                                    @endforeach
                            <div class="row" >


                                <div class="form-group col-sm-8 col-md-10">
                                    <input type="checkbox" name="matiere[]" value="{{$matiere->id}}" onchange="onCheckedChange(this,'classe{{$i}}','nniveau{{$i}}','taux{{$i}}')" >
                                   <strong> {{(App::getLocale()=='en')?$matiere->nom_anglais:$matiere->nom}} <br> {{(App::getLocale()=='en')?$classe->nom_anglais:$classe->nom}}
                                    <br>{{$nniveau->nom}}</strong>

                                    <input type="hidden" id="classe{{$i}}" name="classe[]" value="{{ $classe->id }}" disabled>
                                    <input type="hidden" id="nniveau{{$i}}" name="nniveau[]" value="{{ $nniveau->id }}" disabled>
                                </div>
                                <div class="form-group col-sm-4 col-md-2">
                                    <label for="taux{{$i}}">@lang('admin.taux')</label>
                                    <input type="number" step="0.05" id="taux{{$i}}" name="taux[]"  class="form-control" required disabled>
                                </div>

                            </div>
                            <?php $i++; ?>
                        @endforeach

                    </fieldset>
                    @if($i<0)
                        <p class="alert alert-info  col-md-5">{{trans('admin.toutes_matieres')}}</p>
                    @endif
                    <div class="col-md-10">
                        <input type="submit" value="{{trans('admin.confirmer')}}" class="btn btn-success  center-block form-control">
                    </div>

                </form>
            </div>





























        <div class="modal fade" id="add_cycle">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">x</button>
                        <h4 class="modal-title">{{  trans('admin.remplir_champs')  }}</h4>
                    </div>
                    <div
                            class="modal-body">






                    </div>





                </div>


            </div>
        </div>



    </div>
     <script>
         function onCheckedChange(checkElt,input1,input2,input3) {
             if(checkElt.checked){

                 document.getElementById(input1).disabled=false;
                 document.getElementById(input2).disabled=false;
                 document.getElementById(input3).disabled=false;

             }else{

                 document.getElementById(input1).disabled=true;
                 document.getElementById(input2).disabled=true;
                 document.getElementById(input3).disabled=true;

             }
         }
     </script>

@endsection

@section('breadcrumb')
    <li><strong><a href="{{url('admin')}}">@lang('admin.dashboard')</a></strong></li>
    <li><strong><a href="{{url('admin/taux')}}">@lang('admin.taux')</a></strong></li>

    <li class="active"><strong>{{$titre}}</strong></li>
@endsection