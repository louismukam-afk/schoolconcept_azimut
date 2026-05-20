@extends('skeleton')
@section('sub-content')

    <style>
        .panel-success>.panel-heading {
            background-color: #3c763d;
            color:white;
            border:solid 1px #3c763d;
        }
        .panel-success {

            border:solid 1px #3c763d;
        }
    </style>
    <div id="monaccordeon" class="panel-group col-md-12">
        <h3>{{trans('inscription.choix_liste')}}</h3>

        <div class="panel panel-primary">
            <div class="panel-heading">
                <h3 class="panel-title">
                    <a class="accordion-toggle" href="#item1" dataparent="#monaccordeon"
                       data-toggle="collapse">
                        {{trans('inscription.eleves_inscrits')}}
                    </a>
                </h3>
            </div>

            <div id="item1"
                 class="panel-collapse collapsing">
                <div
                        class="panel-body">
                    <li class="col-md-12"><a  href={{URL::to('/inscriptions/listes/show?id=0')}}>{{trans('inscription.all_eleves')}}</a></li>


                    @foreach($niveaux as $n)
                        <div class="col-md-6">
                            <h4 class="page-header"> {{$n->getNom()}}</h4>

                            @foreach($classes as $c)
                                <div class="col-md-12">
                                    @if($c->id_niveau==$n->id)
                                        <li class="col-md-12"><a data-toggle="modal" data-backdrop="false" href="#choose_level" onclick="choose_level({{$c->id}}, '{{URL::to('/inscriptions/listes/show')}}', '')">{{$c->getNom()}}</a></li>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    @endforeach


                </div>

            </div>
        </div>


        <div class="panel panel-primary">
            <div class="panel-heading">
                <h3 class="panel-title">
                    <a class="accordion-toggle" href="#item2" dataparent="#monaccordeon"
                       data-toggle="collapse">
                        {{trans('admin.scolarite_pi')}}
                    </a>
                </h3>
            </div>

            <div id="item2"
                 class="panel-collapse collapsing">
                <div class="panel-body">
                    <form class="form-horizontal" id="check_list_pay_form" method="get" action="{{route('show_sco_liste')}}">
                        <div class="">

                            <div class="row">
                                <div class="col-md-5" >
                                    <div class="form-group" style="padding: 5px 10px">
                                        <label>@lang('admin.tranche'): </label>
                                        <select form="check_list_pay_form" id="option" name="option" class="form-control" required="required">
                                            <option value="" disabled>-- @lang('admin.selectionner') --</option>
                                            <option value="1"> @lang('admin.tranche1') </option>
                                            <option value="2"> @lang('admin.tranche2') </option>
                                            <option value="3"> @lang('admin.tranche3') </option>
                                            <option value="4"> @lang('admin.tranche4') </option>
                                            <option value="5"> @lang('admin.tranche5') </option>
                                            <option value="5"> @lang('admin.schoolfees') </option>
                                        </select>

                                    </div>
                                </div>

                                <div class="col-md-5" >
                                    <div class="form-group" style="padding: 5px 10px">
                                        <label>Type: </label>
                                        <select form="check_list_pay_form" id="type_pay" name="type" class="form-control" required>
                                            <option value="0">@lang('admin.impaye')</option>
                                            <option value="1">@lang('admin.paye')</option>
                                        </select>

                                    </div>
                                </div>
                            </div>

                            <div class="row">


                                <div class="col-md-5">
                                    <div class="form-group" style="padding: 5px 10px">
                                        <label>@lang('admin.specialites')  : </label>

                                        <select form="check_list_pay_form" id="select_classe_for_pay" name="id" class="form-control autocomplete" onchange="classForPayChange()" required>
                                            <?php $n=0;$i=0; ?>
                                            <option value="" disabled> -- @lang('admin.choix_classe') --</option>
                                            <option value="0"> @lang('admin.tous') </option>
                                            @foreach($classes_list as $c)
                                                @if($i=0)
                                                    <optgroup label="<{{$c->niveau->getNom()}}">
                                                        <?php  $i++; ?>
                                                        @endif
                                                        @if($c->id_niveau!=$n)
                                                            <?php $n = $c->id_niveau ?>
                                                    </optgroup>
                                                    <optgroup label="{{$c->niveau->getNom()}}">
                                                        @endif
                                                        <option id="{{$c->niveau->id}}" value="{{$c->id}}" @if(old('classe')==$c->id) selected @endif>{{$c->getNom()}}</option>
                                                        @endforeach
                                                    </optgroup>
                                        </select>

                                    </div>
                                </div>



                            </div>
                            <div class="row">
                                <div class="col-md-5">
                                    <div class="form-group" style="padding: 5px 10px">
                                        <label>  @lang('admin.nniveau') </label>
                                        <select form="check_list_pay_form"  name="nniveau" class="form-control" id="select_nniveau_for_pay">
                                            @foreach($nniveaux as $nn)
                                                <option value="{{$nn->id}}"  @if(old('nniveau')==$nn->id) selected @endif>{{$nn->nom}}</option>
                                            @endforeach
                                        </select>

                                    </div>
                                </div>
                            </div>






                        </div>

                        <div class="row">
                            <div class="col-md-6 col-md-offset-3">
                                <input type="submit" class="btn btn-success form-control" value="{{trans('admin.confirmer')}}" >

                            </div>
                        </div>
                    </form>
                </div>

            </div>
        </div>




        <div class="panel panel-info">
            <div class="panel-heading">
                <h3 class="panel-title">
                    <a class="accordion-toggle" href="#item3" dataparent="#monaccordeon"
                       data-toggle="collapse">
                        {{trans('admin.fiche_scolarite')}}
                    </a>
                </h3>
            </div>

            <div id="item3"
                 class="panel-collapse collapsing">
                <div class="panel-body">
                    @foreach($niveaux as $n)
                        <div class="col-md-6">
                            <h4 class="page-header"> {{$n->getNom()}}</h4>

                            @foreach($classes as $c)
                                <div class="col-md-12">
                                    @if($c->id_niveau==$n->id)
                                        <li class="col-md-12"><a data-toggle="modal" data-backdrop="false" href="#choose_level" onclick="choose_level({{$c->id}}, '{{URL::to('/inscriptions/fiche-scolarite')}}', '')">{{$c->getNom()}}</a></li>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    @endforeach


                </div>

            </div>
        </div>






        <div class="panel panel-success">
            <div class="panel-heading">
                <h3 class="panel-title">
                    <a class="accordion-toggle" href="#item8" dataparent="#monaccordeon"
                       data-toggle="collapse">
                        {{trans('admin.frais_pi')}}
                    </a>
                </h3>
            </div>



            <div id="item8"
                 class="panel-collapse collapsing">
                <div class="panel-body">
                    <form class="form-horizontal" id="check_list_form" method="get" action="{{route('show_frais_liste')}}">
                        <div class="">

                           <div class="row">
                                   <div class="col-md-5" >
                                       <div class="form-group" style="padding: 5px 10px">
                                           <label>@lang('admin.frais'): </label>
                                           <select form="check_list_form" id="frais" name="frais" class="form-control" required="required">
                                               <option value="">-- @lang('admin.selectionner') --</option>
                                               @foreach($frais as $f)
                                                   <option value="{{$f->id}}"> {{$f->nom}} </option>
                                               @endforeach
                                           </select>

                                       </div>
                                   </div>

                               <div class="col-md-5" >
                                   <div class="form-group" style="padding: 5px 10px">
                                       <label>Type: </label>
                                       <select form="check_list_form" id="type" name="type" class="form-control" required>
                                           <option value="0">@lang('admin.impaye')</option>
                                           <option value="1">@lang('admin.paye')</option>
                                       </select>

                                   </div>
                               </div>
                           </div>

                            <div class="row">


                                <div class="col-md-5">
                                    <div class="form-group" style="padding: 5px 10px">
                                        <label>@lang('admin.specialites')  : </label>

                                        <select form="check_list_form" id="select_classe_for_frais" name="classe" class="form-control autocomplete" onchange="classForFraisChange()" required>
                                            <?php $n=0;$i=0; ?>
                                            <option value="" disabled> -- @lang('admin.choix_classe') --</option>
                                                <option value="0"> @lang('admin.tous') </option>
                                            @foreach($classes_list as $c)
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
                                </div>



                            </div>
                            <div class="row">
                                  <div class="col-md-5">
                                      <div class="form-group" style="padding: 5px 10px">
                                          <label>  @lang('admin.nniveau') </label>
                                          <select form="check_list_form"  name="nniveau" class="form-control" id="select_nniveau_for_frais">
                                              @foreach($nniveaux as $nn)
                                                  <option value="{{$nn->id}}"  @if(old('nniveau')==$nn->id) selected @endif>{{$nn->nom}}</option>
                                              @endforeach
                                          </select>

                                      </div>
                                  </div>
                            </div>






                        </div>

                        <div class="row">
                            <div class="col-md-6 col-md-offset-3">
                                <input type="submit" class="btn btn-success form-control" value="{{trans('admin.confirmer')}}" >

                            </div>
                        </div>
                    </form>
                </div>

            </div>
        </div>
    </div>





    <script>

        $(function (){
            classForFraisChange()
            classForPayChange();
        })

        function classForFraisChange()
        {
            selectClass = $('#select_classe_for_frais');
            selectNNiveau = $('#select_nniveau_for_frais');

            if(selectClass.val() == 0) {
                selectNNiveau.attr('disabled','disabled');
            } else {
                selectNNiveau.removeAttr('disabled');

            }
        }

        function classForPayChange()
        {
            selectClass = $('#select_classe_for_pay');
            selectNNiveau = $('#select_nniveau_for_pay');

            if(selectClass.val() == 0) {
                selectNNiveau.attr('disabled','disabled');
            } else {
                selectNNiveau.removeAttr('disabled');

            }
        }
    </script>
@endsection

@section('breadcrumb')
    <li><strong><a href="{{url('inscriptions')}}">@lang('menu.gestion_inscriptions')</a></strong></li>
    <li class="active"><strong>{{$titre}}</strong></li>
@endsection