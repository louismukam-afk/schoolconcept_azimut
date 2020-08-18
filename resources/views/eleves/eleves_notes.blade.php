@extends('skeleton')
@section('sub-content')
    <div class="add-container col-md-12" style="margin-top: 25px">
        <div class="row">
            <h3 class="col-md-10">{{((App::getLocale()=='en')?$matiere->nom_anglais:$matiere->nom)}}    :     @if($sequence==1 || $sequence==2) CC @elseif($sequence==3 || $sequence==4) @lang('admin.session_normale') @elseif($sequence==5 || $sequence==6) @lang('admin.rattrapage') @endif</h3>

            <div class="col-md-2" style="display: none">
                <a href="#" class="btn-xs btn btn-danger"><span class="glyphicon glyphicon-trash"></span> @lang('admin.del')</a>

            </div>
        </div>

        <hr>
        <form accept-charset="UTF-8" role="form" method="POST" id="add_level_form" action="{{URL::to('/eleves/notes/store/')}}">
            <input type="hidden" name="_token" value="{{ csrf_token() }}">
            <input type="hidden" name="id_matiere" value="{{ $matiere->id }}">
            <input type="hidden" name="sequence" value="{{ $sequence }}">
            <input type="hidden" name="id_classe" value="{{ $id_classe }}">
            <input type="hidden" name="cycle" value="{{ $id_cycle }}">
            <input type="hidden" name="nniveau" value="{{ $id_nniveau }}">



            <fieldset>
                <?php $i=0; ?>
                @foreach($eleves as $e)
                    <div class="row" col-md-10>

                        <div class="form-group col-md-6 ">
                            <input type="checkbox" name="eleve[]" value="{{$e->id}}" onchange="onCheckedChange(this,'note{{$i}}')" @if($sequence!=5 && $sequence!=6) checked @endif tabindex="-1">
                            <label >{{ $e->nom.' '.$e->prenom  }} : </label>
                        </div>
                        <div class="form-group col-md-4 row">
                            <div class="input-group">
                                <input type="number" id="note{{$i}}" name="note[]" step="0.01" @if($sequence==5 || $sequence==6) disabled @endif  class="form-control"
                                       @foreach($notes as $n)
                                        @if($n->id_eleve==$e->id)
                                       value="@if($sequence==1 || $sequence==2){{round($n->note/1.5,2)}}@elseif($sequence==3 || $sequence==4){{round($n->note/3.5,2)}}@elseif($sequence==5 || $sequence==6){{round($n->note/3.5,2)}}@endif"

                                            <?php break;?>
                                        @endif
                                       @endforeach
                                       required>
                                <span class="input-group-addon">/@if($sequence==1 || $sequence==2) 20 @elseif($sequence==3 || $sequence==4) 20 @elseif($sequence==5 || $sequence==6) 20 @endif</span>
                            </div>

                        </div>

                    </div>
                    <?php $i++; ?>
                @endforeach

            </fieldset>
            @if($i!=0)
            <div class="col-md-7">

                <input type="submit" value="{{trans('admin.confirmer')}}" class="btn btn-success center-block btn-lg">
            </div>
                @else
                <div class="alert alert-info">
                    {{trans('eleve.none')}}
                </div>
            @endif

        </form>
    </div>
    <script>
        function onCheckedChange(checkElt,input1) {
            if(checkElt.checked){

                document.getElementById(input1).disabled=false;

            }else{

                document.getElementById(input1).disabled=true;
            }
        }
    </script>
@endsection

@section('breadcrumb')
    <li><strong><a href="{{url('eleves')}}">@lang('menu.gestion_notes')</a></strong></li>
    <li><strong><a href="{{url('eleves/notes')}}">Notes</a></strong></li>

    <li><a href="{{url('eleves/notes/edit')}}"><strong>@lang('admin.choix_classe')</strong></a></li>
    <li><a href="{{url('eleves/notes/matieres').'?id='.$id_classe.'&cycle='.$id_cycle.'&nniveau='.$id_nniveau}}"><strong>@lang('admin.titre_matiere')</strong></a></li>

    <li class="active"><strong>{{$titre}}</strong></li>
@endsection