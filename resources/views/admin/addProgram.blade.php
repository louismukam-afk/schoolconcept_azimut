@extends('skeleton')
@section('sub-content')

    <div class="col-md-6">
        @if(isset($success))

            <div class="alert alert-success">
                {{  trans('admin.succes')  }}
            </div>

        @endif

        <table class="table table-bordered table-striped table-condensed table-inverse" style="margin-top: 15px;">
            <thead>
            <caption style="font-size: 22px">{{  trans('admin.titre_table_programme').' '.$classe->nom  }}</caption>
            <th>#</th>
            <th>{{  trans('admin.titre_matiere')  }}</th>
            <th>{{  trans('admin.coef')  }}</th>
            <th>Max</th>
            </thead>
            <tbody>
            <?php $i=1; ?>
            @foreach($programmes as $p)
                <tr>
                    <td>{{$i++}}</td>
                    <td><strong >{{$p->nom_matiere.'/'.$p->nom_anglais}}</strong></td>
                    <td>{{$p->coef}} </td>
                    <td>{{$p->max}}</td>
                    <td>
                        <a style="color: white" href="{{URL::to('/admin/programmes/destroy/'.$p->id.'?id='.$classe->id.'?cycle='.$id_cycle)}}"   class="btn btn-danger"><span class="glyphicon glyphicon-trash"></span> {{  trans('admin.del')  }}</a>
                    </td>
                </tr>

            @endforeach
            </tbody>
        </table>

    </div>
    <div class="add-container col-md-12" style="margin-top: 25px">
        <h3>{{trans('admin.ajouter_matiere')}}</h3>
        <form accept-charset="UTF-8" role="form" method="POST" id="add_level_form" action="{{ url('/admin/programmes/store/') }}">
            <input type="hidden" name="_token" value="{{ csrf_token() }}">
            <input type="hidden" name="id" value="{{ $classe->id }}">
            <input type="hidden" name="id_cycle" value="{{ $id_cycle }}">
            <fieldset>
                <?php $i=0; ?>
                @foreach($matieres as $m)
                    <div class="row" col-md-10>

                        <div class="form-group col-md-3">
                            <input type="checkbox" name="matiere[]" value="{{$m->id}}" onchange="onCheckedChange(this,'coef{{$i}}','max{{$i}}')">
                            <label >{{ $m->nom.'/'.$m->nom_anglais  }} : </label>
                        </div>
                        <div class="form-group col-md-3">
                            <label for="coef{{$i}}">Coef : </label>
                            <input type="number" id="coef{{$i}}" name="coef[]" placeholder="1" class="form-control" disabled>
                        </div>
                        <div class="form-group col-md-3">
                            <label for="max{{$i}}">Max : </label>
                            <input type="number" id="max{{$i}}" name="max[]" placeholder="20" class="form-control" disabled>
                        </div>
                    </div>
                    <?php $i++; ?>
                @endforeach

            </fieldset>
            @if($i==0)
            <p class="alert alert-info  col-md-5">{{trans('admin.toutes_matieres')}}</p>
            @endif
            <div class="col-md-10">
                <input type="submit" value="{{trans('admin.confirmer')}}" class="btn btn-success  center-block form-control">
            </div>

        </form>
    </div>
    <script>
        function onCheckedChange(checkElt,input1,input2) {
            if(checkElt.checked){

               document.getElementById(input1).disabled=false;
                document.getElementById(input2).disabled=false;
                document.getElementById(input1).value=1;
                document.getElementById(input2).value=20;
            }else{
                document.getElementById(input1).value="";
                document.getElementById(input2).value="";
                document.getElementById(input1).disabled=true;
                document.getElementById(input2).disabled=true;
            }
        }
    </script>
@endsection