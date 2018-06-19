@extends('skeleton')
@section('sub-content')
    <h4>{{trans('eleve.choix_classe')}}</h4>
    <hr>
    <div class="liste-classe col-md-12">
        <ul>
           

                @foreach($cycles as $cy)
                    <h3 class="page-header col-md-12">{{$cy->nom}}</h3>

                    @foreach($niveaux as $n)
                        <div class="col-md-6">
                            <h4 class="page-header"> {{(App::getLocale()=='en')?$n->nom_anglais:$n->nom}}</h4>

                            @foreach($classes as $c)
                                <div class="col-md-12">
                                    @if($c->id_niveau==$n->id)
                                        <li class="col-md-12"><a data-toggle="modal" data-backdrop="false" href="#choose_level" onclick="getNniveau({{$cy->id}});choix_lvl({{$c->id}},{{$cy->id}})">{{(App::getLocale()=='en')?$c->nom_anglais:$c->nom}}</a></li>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    @endforeach

                @endforeach


        </ul>
    </div>

    <div class="modal fade" id="choose_level">
        <div class="modal-dialog" style="width: 20%">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">x</button>
                    <h4 class="modal-title">{{  trans('admin.remplir_champs')  }}</h4>
                </div>
                <div
                        class="modal-body">



                    <form accept-charset="UTF-8" role="form" method="GET" id="choix_level_form" action="{{URL::to('/eleves/notes/matieres/show/')}}">
                        <input type="hidden" name="_token" value="{{ csrf_token() }}"/>
                        <input type="hidden" name="id" id="id" value=""/>
                        <input type="hidden" name="cycle" id="id_cycle" value=""/>

                        <fieldset>
                            <div class="form-group">
                                <label for="niveau">{{  trans('admin.nniveau')  }} : </label>
                                <select  name="nniveau" id="nniveau" form="choix_level_form" class="form-control" required>
                                    @foreach($nniveaux as $nn)
                                        <option value="{{$nn->id}}">{{$nn->nom}}</option>
                                    @endforeach
                                </select>
                            </div>


                            <input class="btn  btn-success " type="submit" value="{{  trans('admin.confirmer')  }}" autofocus>


                        </fieldset>
                    </form>


                </div>





            </div>


        </div>
    </div>

    <script>
       
        function choix_lvl(id,id_cycle)
        {
            document.getElementById("id").value = id;
            document.getElementById("id_cycle").value = id_cycle;
        }
    </script>

@endsection