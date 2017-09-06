@extends('index')

@section('content')
    <div class="row text-center pad-top">
        <div class="col-lg-3 col-md-3 col-sm-3 col-xs-6">
            <div class="div-square">
                <a href="{{URL::to('/eleves/bulletins/search/')}}" >
                    <i class="fa fa-user fa-5x"></i>
                    <h4>{{trans('inscription.eleve')}}</h4>
                </a>
            </div>


        </div>
        <div class="col-lg-3 col-md-3 col-sm-3 col-xs-6">
            <div class="div-square">
                <a data-toggle="modal" data-backdrop="false" href="#choose_classe" >
                    <i class="fa fa-group fa-5x"></i>
                    <h4>Classe</h4>
                </a>
            </div>


        </div>


    </div>

    <div class="modal fade" id="choose_classe">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">x</button>
                    <h4 class="modal-title">{{  trans('admin.remplir_champs')  }}</h4>
                </div>
                <div
                        class="modal-body">



                    <form accept-charset="UTF-8" role="form" method="POST" id="choix_classe_form" action="{{ url('/eleves/bulletins/print-single-seq') }}">
                        <input type="hidden" name="_token" value="{{ csrf_token() }}"/>
                        <fieldset>
                            <div class="form-group">
                                <label for="classe">{{  trans('admin.selectionner_classe')  }} : </label>
                                <select id="classe" name="id_c" form="choix_classe_form" class="form-control" autofocus>
                                    @foreach($classes as $c)
                                        <option value="{{$c->id}}">{{$c->nom}}</option>
                                    @endforeach

                                </select>
                            </div>
                            <div class="form-group">
                                <label for="sequence">{{  trans('eleve.choix_seq')  }} : </label>
                                <select id="sequence" name="sequence" form="choix_classe_form" class="form-control" >
                                    <option value="1">Séquence 1</option>
                                    <option value="2">Séquence 2</option>
                                    <option value="3">Séquence 3</option>
                                    <option value="4">Séquence 4</option>
                                    <option value="5">Séquence 5</option>
                                    <option value="6">Séquence 6</option>
                                    <option value="7">{{trans('eleve.trimestre')}} 1</option>
                                    <option value="8">{{trans('eleve.trimestre')}} 2</option>
                                    <option value="9">{{trans('eleve.trimestre')}} 3</option>
                                </select>
                            </div>


                            <input class="btn  btn-success " type="submit" value="{{  trans('admin.confirmer')  }}" >


                        </fieldset>
                    </form>


                </div>





            </div>


        </div>
    </div>
    </div>


@endsection