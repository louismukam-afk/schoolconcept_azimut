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
                    <td><strong >{{(session('locale')=='en')?$p->nom_anglais:$p->nom_matiere}}</strong></td>
                    <td>{{$p->coef}} </td>
                    <td>{{$p->max}}</td>
                    <td>
                        <div class="btn-group">
                            <a href="#" class=" btn btn-primary dropdown-toggle" data-toggle="dropdown">Actions <b class="caret"></b></a>
                            <ul class="dropdown-menu">
                                <li><a style="color: white"  class="btn-primary"  data-toggle="modal" data-backdrop="false" href="#edit_programme" onclick="editer_programme({{$p->id}},{{$p->id_cycle}},{{$p->coef}},{{$p->max}},{{$p->semestre}},{{$p->type}})"><span class="glyphicon glyphicon-pencil"  ></span> {{  trans('admin.edit')  }}</a></li>
                                <li><a style="color: white" href="{{URL::to('/admin/programmes/destroy/'.$p->id.'?id='.$classe->id.'?cycle='.$id_cycle)}}"   class="btn-danger"><span class="glyphicon glyphicon-trash"></span> {{  trans('admin.del')  }}</a></li>
                                <li><a style="color: white" href="{{URL::to('/admin/programmes/groupe/'.$p->id)}}"   class="btn-warning"><span class="glyphicon glyphicon-folder-open"></span> {{  trans('admin.groupe')  }}</a></li>
                            </ul>
                        </div>

                    </td>
                </tr>

            @endforeach
            </tbody>
        </table>






            <div class="modal fade" id="edit_programme">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal">x</button>
                            <h4 class="modal-title">{{  trans('admin.remplir_champs')  }}</h4>
                        </div>
                        <div
                                class="modal-body">



                            <form accept-charset="UTF-8" role="form" method="POST" id="edit_programme_form" action="{{ url('/admin/programmes/update/') }}">
                                <input type="hidden" name="_token" value="{{ csrf_token() }}"/>
                                <input type="hidden" name="id" id="edit_id_programme" value=""/>
                                <input type="hidden" name="id_cycle" id="id_cycle_programme" value=""/>
                                <fieldset>

                                    <div class="form-group">
                                        <label>Coef : </label>
                                        <input class="form-control" id="edit_coef" name="coef" type="text"  value="{{ old('coef') }}"  autofocus>

                                    </div>
                                    <div class="form-group">
                                        <label for="nom">Max : </label>
                                        <input class="form-control" id="edit_max" name="max" type="text"  value="{{ old('max') }}"  >

                                    </div>



                                    <input class="btn  btn-success" type="submit" value="{{  trans('admin.confirmer')  }}">


                                </fieldset>
                            </form>


                        </div>





                    </div>


                </div>
            </div>



















    </div>
    <div class="add-container col-md-12" style="margin-top: 25px">
        <h3>{{trans('admin.ajouter_matiere')}}</h3>
        <form accept-charset="UTF-8" role="form" method="POST" id="add_level_form" action="{{ url('/admin/programmes/store/') }}">
            <input type="hidden" name="_token" value="{{ csrf_token() }}">
            <input type="hidden" name="id" value="{{ $classe->id }}">
            <fieldset>
                <?php $i=0; ?>
                @foreach($matieres as $m)
                    <div class="row" col-md-12>

                        <div class="form-group col-md-3">
                            <input type="checkbox" name="matiere[]" value="{{$m->id}}" onchange="onCheckedChange(this,'coef{{$i}}','max{{$i}}','semestre{{$i}}','type{{$i}}')">
                            <label >{{(session('locale')=='en')?$m->nom_anglais:$m->nom}} : </label>
                        </div>

                        <div class="form-group col-md-4">
                            <label for="coef{{$i}}">Coef : </label>
                            <input type="number" id="coef{{$i}}" name="coef[]" placeholder="1" class="form-control" disabled>
                        </div>
                        <div class="form-group col-md-4">
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
        function editer_programme(id,coef,max)
        {

            document.getElementById("edit_id_programme").value = id;
            document.getElementById("edit_coef").value = coef;
            document.getElementById("edit_max").value = max;

        }
    </script>
@endsection