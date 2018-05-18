@extends('skeleton')
@section('sub-content')
    @include('perso.functions')
    <div class="col-md-8">
        @if(isset($success))

            <div class="alert alert-success">
                {{  trans('admin.succes')  }}
            </div>

        @endif

        <table class="table table-bordered table-striped table-condensed table-inverse" style="margin-top: 15px;">
            <thead>
            <caption style="font-size: 22px">{{  trans('admin.matiere').' : '.((session('locale')=='en')?$matiere_mere->nom_anglais:$matiere_mere->nom)}}</caption>
            <th>#</th>
            <th>{{  trans('admin.titre_matiere')  }}</th>
            <th>Semestre</th>


            </thead>
            <tbody>
            <?php $i=1; ?>
            @foreach($groupes as $p)
                <tr>
                    <td>{{$i++}}</td>
                    <td><strong >{{(session('locale')=='en')?$p->nom_anglais:$p->nom_matiere}}</strong></td>
                    <td>{{getSemestre($p->semestre)}}</td>

                    <td>
                        <a href="{{URL::to('/admin/programmes/groupe/destroy/'.$p->id_groupe.'?id_classe_matiere='.$classeMatiere->id)}}"   class="btn btn-danger btn-xs"><span class="glyphicon glyphicon-trash"></span> {{  trans('admin.del')  }}</a>

                    </td>
                </tr>

            @endforeach
            </tbody>
        </table>









    </div>
    <div class="add-container col-md-12" style="margin-top: 25px">
        <h3>{{trans('admin.ajouter_matiere')}}</h3>
        <form accept-charset="UTF-8" role="form" method="POST" id="add_level_form" action="{{ url('/admin/programmes/groupe/store/') }}">
            <input type="hidden" name="_token" value="{{ csrf_token() }}">
            <input type="hidden" name="id" value="{{ $classeMatiere->id }}">
            <fieldset>
                <?php $i=0; ?>
                @foreach($matieres as $m)
                    <div class="row" col-md-12>

                        <div class="form-group col-md-5">
                            <input type="checkbox" name="matiere[]" value="{{$m->id}}" onchange="onCheckedChange(this,'semestre{{$i}}')">
                            <label >{{(session('locale')=='en')?$m->nom_anglais:$m->nom_matiere}} : </label>
                        </div>

                        <div class="form-group col-md-3">
                            <label >Semestre : </label>
                            <select name="semestre[]" id="semestre{{$i}}" class="form-control" disabled>
                                <option value="1">{{getSemestre(1)}}</option>
                                <option value="2">{{getSemestre(2)}}</option>
                                <option value="0">{{getSemestre(0)}}</option>
                            </select>
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
        function onCheckedChange(checkElt,input1) {
            if(checkElt.checked){

                document.getElementById(input1).disabled=false;

            }else{

                document.getElementById(input1).disabled=true;

            }
        }

    </script>
@endsection