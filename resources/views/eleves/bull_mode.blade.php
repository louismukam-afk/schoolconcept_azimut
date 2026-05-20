@extends('index')

@section('content')
    @if(isset($success))

        <div class="alert alert-success">
            {{  trans('admin.succes')  }}
        </div>

    @endif
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

    <section style="margin-top: 80px">
        <h4  style="color: #c9302c;font-size: 19px" >@lang('admin.info_gen')</h4>
        <a class="btn btn-danger" data-toggle="modal" data-backdrop="false" href="#choose_classe_regen">@lang('admin.regen_bull')</a>
    </section>

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
                        <fieldset> <div class="form-group">
                                <label>  @lang('admin.cycle'): </label>
                                <select form="choix_classe_form" id="cycle" name="id_cy" class="form-control"  autofocus>
                                    <option value=""> -- @lang('admin.choose_cycle') --</option>

                                    @foreach($cycles as $c)
                                        <option value="{{$c->id}}" @if(old('cycle')==$c->id) selected @endif>{{$c->nom}}</option>
                                    @endforeach
                                </select>

                            </div>
                            <div class="form-group">
                                <label>@lang('admin.specialites')  : </label>

                                <select form="choix_classe_form" id="classe" name="id_c"  form="choix_classe_form" class="form-control " autofocus>
                                    <?php $n=0;$i=0; ?>
                                    <option value=""> -- @lang('admin.choix_classe') --</option>
                                    @foreach($classes as $c)
                                        @if($i=0)
                                            <optgroup label="<{{$c->niveau->getNom()}}">
                                                <?php  $i++; ?>
                                                @endif
                                                @if($c->id_niveau!=$n)
                                                    <?php $n=$c->id_niveau ?>
                                            </optgroup>
                                            <optgroup label="{{$c->niveau->getNom()}}">
                                                @endif
                                                <option id="{{$c->niveau->id}}" value="{{$c->id}}" @if(old('classe')==$c->id) selected @endif>{{$c->getNom()}}</option>
                                                @endforeach
                                            </optgroup>
                                </select>

                            </div>


                            <div class="form-group">
                                <label>  @lang('admin.nniveau') </label>
                                <select form="choix_classe_form"  id="niveau" name="id_nn" class="form-control" autofocus>
                                    @foreach($nniveaux as $nn)
                                        <option value="{{$nn->id}}"  @if(old('nniveau')==$nn->id) selected @endif>{{$nn->nom}}</option>
                                    @endforeach
                                </select>

                            </div>
                            <div class="form-group">
                                <label for="sequence">{{  trans('eleve.choix_seq')  }} : </label>
                                <select id="sequence" type="numeric" name="sequence" form="choix_classe_form" class="form-control" >
                                    <option value="1">Séquence 1</option>
                                    <option value="2">Séquence 2</option>
                                    <option value="3">Séquence 3</option>
                                    <option value="4">Séquence 4</option>
                                    <option value="5">Séquence 5</option>
                                    <option value="6">Séquence 6</option>
                                    <option value="7">Séquence 7</option>
                                    <option value="8">Séquence 8</option>
                                    <option value="9">{{trans('eleve.trimestre')}} 1</option>
                                    <option value="10">{{trans('eleve.trimestre')}} 2</option>
                                    <option value="11">{{trans('eleve.trimestre')}} 3</option>
                                    <option value="12">@lang('admin.annuel')</option>
                                </select>
                            </div>


                            <input class="btn  btn-success " type="submit" value="{{  trans('admin.confirmer')  }}" >


                        </fieldset>
                    </form>


                </div>





            </div>


        </div>
    </div>


    <div class="modal fade" id="choose_classe_regen">
        <div class="modal-dialog" style="width: 30%">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">x</button>
                    <h4 class="modal-title">{{  trans('admin.remplir_champs')  }}</h4>
                </div>
                <div
                        class="modal-body">



                    <form accept-charset="UTF-8" role="form" method="POST" id="choix_classe_regen" action="{{ url('/eleves/bulletins/regenerate') }}">
                        <input type="hidden" name="_token" value="{{ csrf_token() }}"/>
                        <fieldset>
                            <div class="form-group">
                            <label>  @lang('admin.cycle'): </label>
                            <select form="choix_classe_regen" id="cycle" name="cycle" class="form-control"  required>
                                <option value=""> -- @lang('admin.choose_cycle') --</option>

                                @foreach($cycles as $c)
                                    <option value="{{$c->id}}" @if(old('cycle')==$c->id) selected @endif>{{$c->nom}}</option>
                                @endforeach
                            </select>

                </div>
                <div class="form-group">
                    <label>@lang('admin.specialites')  : </label>

                    <select form="choix_classe_regen" id="classe" name="classe" class="form-control autocomplete" required>
                        <?php $n=0;$i=0; ?>
                        <option value=""> -- @lang('admin.choix_classe') --</option>
                        @foreach($classes as $c)
                            @if($i=0)
                                <optgroup label="<{{$c->niveau->getNom()}}">
                                    <?php  $i++; ?>
                                    @endif
                                    @if($c->id_niveau!=$n)
                                        <?php $n=$c->id_niveau ?>
                                </optgroup>
                                <optgroup label="{{$c->niveau->getNom()}}">
                                    @endif
                                    <option id="{{$c->niveau->id}}" value="{{$c->id}}" @if(old('classe')==$c->id) selected @endif>{{$c->getNom()}}</option>
                                    @endforeach
                                </optgroup>
                    </select>

                </div>


                <div class="form-group">
                    <label>  @lang('admin.nniveau') </label>
                    <select form="choix_classe_regen"  name="nniveau" class="form-control" required>
                        @foreach($nniveaux as $nn)
                            <option value="{{$nn->id}}"  @if(old('nniveau')==$nn->id) selected @endif>{{$nn->nom}}</option>
                        @endforeach
                    </select>

                </div>


                            <div class="form-group">
                                <label for="sequence">{{  trans('eleve.choix_seq')  }} : </label>
                                <select id="sequence" name="sequence" form="choix_classe_regen" class="form-control" >
                                    <option value="1">Séquence 1</option>
                                    <option value="2">Séquence 2</option>
                                    <option value="3">Séquence 3</option>
                                    <option value="4">Séquence 4</option>
                                    <option value="5">Séquence 5</option>
                                    <option value="6">Séquence 6</option>
                                    <option value="7">Séquence 7</option>
                                    <option value="8">Séquence 8</option>
                                    <option value="9">{{trans('eleve.trimestre')}} 1</option>
                                    <option value="10">{{trans('eleve.trimestre')}} 2</option>
                                    <option value="11">{{trans('eleve.trimestre')}} 3</option>
                                    <option value="12">@lang('admin.annuel')</option>
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

@section('breadcrumb')
    <li><strong><a href="{{url('eleves')}}">@lang('menu.gestion_notes')</a></strong></li>

    <li class="active"><strong>{{trans('admin.bull_mode')}}</strong></li>
@endsection