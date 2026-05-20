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
            <caption style="font-size: 22px">{{  trans('admin.titre_table_programme').' '.$classe->nom.' '.trans('admin.nniveau').' '.\gesecole\NNiveau::find($id_nniveau)->nom  }}</caption>
            <th>#</th>
            <th>Code matière</th>
            <th>{{  trans('admin.titre_matiere')  }}</th>
            <th>Type</th>
            <th>{{  trans('admin.coef')  }}</th>
            <th>Max</th>
            <th>Semestre</th>

            </thead>
            <tbody>
            <?php $i=1; ?>
            @foreach($programmes as $p)
                <tr>
                    <td>{{$i++}}</td>
                    <td>{{$p->id_code}} </td>
                    <td><strong >{{(App::getLocale()=='en')?$p->nom_anglais:$p->nom_matiere}}</strong></td>
                    <td>@if($p->type==0)@lang('admin.fondamental')@elseif($p->type==1) @lang('admin.transversal') @elseif($p->type==2) @lang('admin.professionnel') @endif</td>
                    <td>{{$p->coef}} </td>
                    <td>{{$p->max}}</td>
                    <td>{{getSemestre($p->semestre)}}</td>
                    <td>
                        <div class="btn-group">
                            <a href="#" class=" btn btn-primary dropdown-toggle" data-toggle="dropdown">Actions <b class="caret"></b></a>
                            <ul class="dropdown-menu">
                                <li><a style="color: white"  class="btn-primary"  data-toggle="modal" data-backdrop="false" href="#edit_programme" onclick="editer_programme({{$p->id}},{{$p->id_cycle}},{{$p->coef}},{{$p->max}},{{$p->semestre}},{{$p->type}},'{{$p->id_code}}')"><span class="glyphicon glyphicon-pencil"  ></span> {{  trans('admin.edit')  }}</a></li>
                                <li><a style="color: white" href="{{URL::to('/admin/programmes/destroy/'.$p->id.'?id='.$classe->id.'&cycle='.$id_cycle.'&nniveau='.$id_nniveau)}}"   class="btn-danger"><span class="glyphicon glyphicon-trash"></span> {{  trans('admin.del')  }}</a></li>
                                <li><a style="color: white" href="{{URL::to('/admin/programmes/groupe/'.$p->id)}}"   class="btn-warning"><span class="glyphicon glyphicon-folder-open"></span> {{  trans('admin.groupe')  }}</a></li>
                                <li>
                                    <a style="color: white"
                                       href="{{ URL::to('/admin/sessions/'.$p->id.'?id_classe='.$classe->id.'&id_matiere='.$p->id_matiere.'&id_classe_matiere='.$p->id) }}"
                                       class="btn-info">
                                        <span class="glyphicon glyphicon-time"></span> {{ trans('admin.sessions') }}
                                    </a>
                                </li>

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
                                <input type="hidden" name="id_nniveau" id="id_nniveau" value="{{$id_nniveau}}"/>

                                <fieldset>
                                    <div class="form-group">
                                        <label >Type : </label>
                                        <select name="type" id="edit_type" class="form-control">
                                            <option value="0">@lang('admin.fondamental')</option>
                                            <option value="1">@lang('admin.transversal')</option>
                                            <option value="2">@lang('admin.professionnel')</option>

                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label for="id_code">Code : </label>
                                        <input class="form-control" id="edit_id_code" name="id_code" type="text"  value="{{ old('id_code') }}"  >

                                    </div>
                                    <div class="form-group">
                                        <label>Coef : </label>
                                        <input class="form-control" id="edit_coef" name="coef" type="text"  value="{{ old('coef') }}"  autofocus>

                                    </div>
                                    <div class="form-group">
                                        <label for="nom">Max : </label>
                                        <input class="form-control" id="edit_max" name="max" type="text"  value="{{ old('max') }}"  >

                                    </div>
                                    <div class="form-group">
                                        <label >Semestre : </label>
                                        <select name="semestre" id="edit_semestre" class="form-control">
                                            <option value="1">{{getSemestre(1)}}</option>
                                            <option value="2">{{getSemestre(2)}}</option>
                                            <option value="0">{{getSemestre(0)}}</option>
                                             <option value="3">{{getSemestre(3)}}</option>
                                              <option value="4">{{getSemestre(4)}}</option>
                                               <option value="5">{{getSemestre(5)}}</option>
                                                <option value="6">{{getSemestre(6)}}</option>
                                        </select>
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
        <div class="row">
            <div class="col-md-8">
                <form class="form-horizontal"  method="get" action="{{URL::to('/admin/programmes/selection-matieres')}}">
                    <input type="hidden" name="id" value="{{ $classe->id }}">
                    <input type="hidden" name="cycle" value="{{ $id_cycle }}">
                    <input type="hidden" name="nniveau" id="id_nniveau" value="{{$id_nniveau}}"/>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="search">{{  trans('inscription.recherche')  }} : </label>
                            <div class="input-group">
                                <input id="search" type="search" name="search" placeholder="{{trans('inscription.nom')}}" class="form-control" autofocus>
                                <div class="input-group-btn">
                                    <button type="submit" class="btn btn-primary" ><span class="fa fa-search"> </span>{{trans('inscription.search')}}</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>

            </div>

        </div>

        <div class="row">
            <div class="col-md-12">
                @if(isset($search_term))
                    <h4 style="color: gray">{{trans('admin.s_result_containing', ['terms' => $search_term])}}</h4>
                @endif
            </div>
        </div>
        <form accept-charset="UTF-8" role="form" method="POST" id="add_level_form" action="{{ url('/admin/programmes/store/') }}">
            <input type="hidden" name="_token" value="{{ csrf_token() }}">
            <input type="hidden" name="id" value="{{ $classe->id }}">
            <input type="hidden" name="id_cycle" value="{{ $id_cycle }}">
            <input type="hidden" name="id_nniveau" id="id_nniveau" value="{{$id_nniveau}}"/>

            <fieldset>
                <?php $i=0; ?>
                    <table id="table-matiere" class="table-condensed">
                        <thead><td></td></thead>
                        <tbody>
                        @foreach($matieres as $m)
                            <tr>
                                <td>
                                <div class="row" col-md-12>

                                    <div class="form-group col-md-3">
                                        <input type="checkbox" name="matiere[]" value="{{$m->id}}" onchange="onCheckedChange(this,'coef{{$i}}','max{{$i}}','semestre{{$i}}','type{{$i}}','id_code{{$i}}')">
                                        <label >{{(App::getLocale()=='en')?$m->nom_anglais:$m->nom}} : </label>
                                    </div>
                                    <div class="form-group col-md-2">
                                        <label >Type : </label>
                                        <select name="type[]" id="type{{$i}}" class="form-control" disabled>
                                            <option value="0">@lang('admin.fondamental')</option>
                                            <option value="1">@lang('admin.transversal')</option>
                                            <option value="2">@lang('admin.professionnel')</option>

                                        </select>
                                    </div>
                                    <div class="form-group col-md-2">
                                        <label for="id_code">Code : </label>
                                        <input class="form-control" id="id_code" name="id_code[]" PLACEHOLDER="CGE000" type="text"  value="{{ old('id_code') }}">

                                    </div>
                                    <div class="form-group col-md-2">
                                        <label for="coef{{$i}}">Coef : </label>
                                        <input type="number" id="coef{{$i}}" name="coef[]" placeholder="1" class="form-control" disabled>
                                    </div>
                                    <div class="form-group col-md-2">
                                        <label for="max{{$i}}">Max : </label>
                                        <input type="number" id="max{{$i}}" name="max[]" placeholder="100" class="form-control" disabled>
                                    </div>
                                    <div class="form-group col-md-2">
                                        <label >Semestre : </label>
                                        <select name="semestre[]" id="semestre{{$i}}" class="form-control" disabled>
                                            <option value="1">{{getSemestre(1)}}</option>
                                            <option value="2">{{getSemestre(2)}}</option>
                                            <option value="0">{{getSemestre(0)}}</option>
                                        </select>
                                    </div>
                                </div>
                                </td>
                            </tr>
                            <?php $i++; ?>
                            @endforeach
                        </tbody>
                    </table>


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
        function onCheckedChange(checkElt,input1,input2,input3,input4,input5) {
            if(checkElt.checked){

               document.getElementById(input1).disabled=false;
                document.getElementById(input2).disabled=false;
                document.getElementById(input3).disabled=false;
                document.getElementById(input4).disabled=false;
                document.getElementById(input5).disabled=false;
                document.getElementById(input1).value=1;
                document.getElementById(input2).value=100;
//                document.getElementById(input5).value="CGE000";
            }else{
                document.getElementById(input1).value="";
                document.getElementById(input2).value="";
                document.getElementById(input1).disabled=true;
                document.getElementById(input2).disabled=true;
                document.getElementById(input3).disabled=true;
                document.getElementById(input4).disabled=true;
                document.getElementById(input5).disabled="";
            }
        }
        function editer_programme(id,id_cycle,coef,max,semestre,type,id_code)
        {

            document.getElementById("edit_id_programme").value = id;
            document.getElementById("id_cycle_programme").value = id_cycle;
            document.getElementById("edit_coef").value = coef;
            document.getElementById("edit_max").value = max;
            document.getElementById("edit_semestre").value = semestre;
            document.getElementById("edit_type").value = type;
            document.getElementById("edit_id_code").value = id_code;

        }

        /*$('#table-matiere').dataTable( {
            dom: 'Bfrtip',
           pageLength : 50,
            buttons:[]
        } );*/
    </script>
@endsection

@section('breadcrumb')
    <li><strong><a href="{{url('admin')}}">@lang('admin.dashboard')</a></strong></li>
    <li><strong><a href="{{url('admin/programmes')}}">@lang('admin.selectionner_classe')</a></strong></li>

    <li class="active"><strong>{{$titre}}</strong></li>
@endsection