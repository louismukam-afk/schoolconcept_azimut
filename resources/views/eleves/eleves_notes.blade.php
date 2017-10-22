@extends('skeleton')
@section('sub-content')
    <div class="add-container col-md-12" style="margin-top: 25px">
        <h3>{{trans('eleve.gerer_notes').' '.(session('locale')=='en')?$matiere->nom_anglais:$matiere->nom}}</h3>
        <hr>
        <form accept-charset="UTF-8" role="form" method="POST" id="add_level_form" action="{{URL::to('/eleves/notes/store/')}}">
            <input type="hidden" name="_token" value="{{ csrf_token() }}">
            <input type="hidden" name="id_matiere" value="{{ $matiere->id }}">
            <input type="hidden" name="sequence" value="{{ $sequence }}">
            <input type="hidden" name="id_classe" value="{{ $id_classe }}">
            <input type="hidden" name="cycle" value="{{ $id_cycle }}">


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
                                <input type="number" id="note{{$i}}" name="note[]" step="0.05" @if($sequence==5 || $sequence==6) disabled @endif  class="form-control"
                                       @foreach($notes as $n)
                                        @if($n->id_eleve==$e->id)
                                       value="{{$n->note}}"
                                            <?php break;?>
                                        @endif
                                       @endforeach
                                       required>
                                <span class="input-group-addon">/{{$max}}</span>
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