@extends('skeleton')
@section('sub-content')
    @if(isset($success))

        <div class="alert alert-success">
            {{  trans('admin.succes')  }}
        </div>
        @if(!isset($inscription_edition))
            <div class="col-md-12">
                <a  class="btn btn-warning btn-lg" href="{{URL::to('/inscriptions/print/'.$success)}}"><span class="glyphicon glyphicon-print"></span> {{trans('inscription.print_receipe')}}</a>

            </div>
        @endif

    @endif

    @if(isset($custom_error))

        <div class="alert alert-danger">
            {{  $custom_error  }}
        </div>

    @endif

    @if(isset($old_record))
        <p style="font-size: 18px"> <strong>Matricule : {{$e->matricule}} </strong></p>
        <p style="font-size: 18px"> <strong>{{trans('admin.nom')}} : {{$e->nom.' '.$e->prenom}} </strong></p>

        <div class="col-md-8">
            <form class="form-horizontal" id="edit_inscription_form" method="post" action="{{URL::to('/inscriptions/update/')}}">
                <input type="hidden" name="_token" value="{{ csrf_token() }}"/>
                <input type="hidden" name="id_eleve" value="{{$e->id}}">
                <div class="col-md-6">
                    <div class="form-group">
                        <label>  @lang('admin.cycle'): </label>
                        <select required form="edit_inscription_form" id="edit_cycle"  name="cycle" class="form-control" onchange="filterEditInscriptionFields()">
                            @foreach($cycles as $c)
                                <option value="{{$c->id}}" @if($inscription->id_cycle==$c->id) selected @endif>{{$c->nom}}</option>
                            @endforeach
                        </select>

                    </div>
                    <div class="form-group">
                        <label>@lang('admin.filiere')  : </label>
                        <select form="edit_inscription_form" id="edit_filiere" name="id_niveau" class="form-control" onchange="filterEditInscriptionFields()" required>
                            <option value=""> -- Choisir --</option>
                            @foreach($niveaux as $n)
                                <option value="{{$n->id}}" @if($inscription && $inscription->classe && $inscription->classe->id_niveau==$n->id) selected @endif>{{$n->getNom()}}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label>@lang('admin.specialites')  : </label>

                        <select required form="edit_inscription_form" id="edit_classe" name="classe" class="form-control autocomplete">
                            <option value=""> -- @lang('admin.choix_classe') --</option>
                            @foreach($classes as $c)
                                <option value="{{$c->id}}" data-filiere="{{$c->id_niveau}}" @if($inscription->id_classe==$c->id) selected @endif>{{$c->getNom()}}</option>
                            @endforeach
                        </select>


                    </div>
                    <div class="form-group">
                        <label>  @lang('admin.nniveau') </label>
                        <select form="edit_inscription_form" id="edit_nniveau" name="nniveau" class="form-control" required>
                            @foreach($nniveaux as $nn)
                                <option value="{{$nn->id}}" data-cycle="{{$nn->id_cycle}}" @if($inscription->id_nniveau==$nn->id) selected @endif>{{$nn->nom}}</option>
                            @endforeach
                        </select>

                    </div>
                        <div class="form-group">
                            <label>{{trans('admin.matricule')}} : (@lang('admin.no_matricule'))</label>
                            <p class="form-control-static">Le matricule sera genere automatiquement si l'etudiant n'en a pas encore.</p>

                        </div>

                    <input type="submit" class="btn btn-success form-control" value="{{trans('admin.modifier')}}" >
                </div>
            </form>

        </div>

    @elseif(isset($e))
        <p style="font-size: 18px"> <strong>Matricule : {{$e->matricule}} </strong></p>
        <p style="font-size: 18px"> <strong>{{trans('admin.nom')}} : {{$e->nom.' '.$e->prenom}} </strong></p>

        <div class="col-md-8">
            <form class="form-horizontal" id="add_inscription_form" method="post" action="{{URL::to('/inscriptions/store/')}}">
                <input type="hidden" name="_token" value="{{ csrf_token() }}"/>
                <input type="hidden" name="id_eleve" value="{{$e->id}}">

                <div class="col-md-6">
                    <div class="form-group">
                        <label>  @lang('admin.cycle'): </label>
                        <select form="add_inscription_form" id="cycle" name="cycle" class="form-control" onchange="filterInscriptionFields(); getScolarite()" required>
                            <option value=""> -- @lang('admin.choose_cycle') --</option>

                        @foreach($cycles as $c)
                                <option value="{{$c->id}}" @if(old('cycle')==$c->id) selected @endif>{{$c->nom}}</option>
                            @endforeach
                        </select>

                    </div>
                    <div class="form-group">
                        <label>@lang('admin.filiere')  : </label>
                        <select form="add_inscription_form" id="filiere" name="id_niveau" class="form-control" onchange="filterInscriptionFields(); getScolarite()" required>
                            <option value=""> -- Choisir --</option>
                            @foreach($niveaux as $n)
                                <option value="{{$n->id}}" @if(old('id_niveau')==$n->id) selected @endif>{{$n->getNom()}}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label>@lang('admin.specialites')  : </label>

                        <select form="add_inscription_form" id="classe" name="classe" class="form-control autocomplete" onchange="getScolarite()" required>
                            <option value=""> -- @lang('admin.choix_classe') --</option>
                            @foreach($classes as $c)
                                <option id="{{$c->niveau->id}}" data-filiere="{{$c->id_niveau}}" value="{{$c->id}}" @if(old('classe')==$c->id) selected @endif>{{$c->getNom()}}</option>
                            @endforeach
                        </select>

                    </div>
                    <div class="form-group">
                        <label for="type_scolarite">{{  trans('admin.type_scolarite')  }} : </label>
                        <select form="add_inscription_form" id="type_scolarite" name="type_scolarite" class="form-control" onchange="getScolarite()" >
                            <option id="0" value="0" @if(strcmp(old('type_scolarite'),'0')==0) selected @endif>{{ trans('admin.ancien') }}</option>
                            <option id="1" value="1" @if(strcmp(old('type_scolarite'),'1')==0) selected @endif>{{ trans('admin.nouveau') }}</option>
                        </select>
                    </div>

                        <div class="form-group">
                        <label>  @lang('admin.nniveau') </label>
                        <select form="add_inscription_form" id="nniveau" name="nniveau" class="form-control" required>
                            @foreach($nniveaux as $nn)
                                <option value="{{$nn->id}}" data-cycle="{{$nn->id_cycle}}" @if(old('nniveau')==$nn->id) selected @endif>{{$nn->nom}}</option>
                            @endforeach
                        </select>

                    </div>




                </div>
                <div class="col-md-6" style="padding-left: 30px">
                    <div class="form-group">
                        <label>Campus : <span class="required">*</span></label>
                        @if(isset($campus_actif) && $campus_actif)
                            <input type="hidden" name="id_campus" value="{{ $campus_actif->id }}">
                            <p class="form-control-static">
                                <strong>{{ $campus_actif->nom }}</strong>
                                @if($campus_actif->sigle) - {{ $campus_actif->sigle }} @endif
                                @if($campus_actif->localisation) | {{ $campus_actif->localisation }} @endif
                            </p>
                        @else
                            <select name="id_campus" class="form-control" required>
                                <option value=""> -- Choisir --</option>
                                @if(isset($campus))
                                    @foreach($campus as $cp)
                                        <option value="{{ $cp->id }}" @if(old('id_campus')==$cp->id) selected @endif>{{ $cp->nom }}</option>
                                    @endforeach
                                @endif
                            </select>
                        @endif
                    </div>
                    @if($e->matricule==null)
                        <div class="form-group">
                            <label>{{trans('admin.matricule')}} : (@lang('admin.no_matricule'))</label>
                            <p class="form-control-static">Le matricule sera genere automatiquement apres l'inscription.</p>

                        </div>
                    @endif
                    <div class="form-group">
                        <div id="show_montant" class="alert alert-info hidden"  >
                            <span  style="font-size: 18px">

                                <span id="show_montant_value">25000</span>
                                @lang('inscription.devise')
                            </span>
                        </div>

                        <div id="montant-group" class="hidden">
                            <label for="montant">{{trans('inscription.montant')}} : </label>
                            <div class="input-group">
                                <input type="number" name="montant" id="montant" value="{{ old('montant') }}"  class="form-control disabled" required readonly>
                                <span class="input-group-addon"> {{trans('inscription.devise')}}</span>
                            </div>
                        </div>


                    </div>
                        <div class="form-group">
                            <label>Montant en lettre</label>
                            <input type="text" name="lettre"  class="form-control"  value="{{old('lettre')}}">

                        </div>
                        <div class="form-group">
                            <label for="mode_paye">{{trans('inscription.detail')}} : </label>
                            <select form="add_inscription_form" id="mode_paye" name="mode_paye" class="form-control">
                                <option value="bancaire">{{trans('inscription.bancaire') }}</option>
                                <option value="orange money">{{trans('inscription.om')}}</option>
                                <option value="espece">{{trans('inscription.espece')}}</option>

                            </select>

                        </div>
                        <div class="form-group">
                            <label>Type d'operation : <span class="required">*</span></label>
                            <select name="operation_source" id="operation_source" class="form-control" onchange="toggleOperationSource()" required>
                                <option value="caisse" @if(old('operation_source')=='caisse') selected @endif>Espece / Caisse</option>
                                <option value="banque" @if(old('operation_source')=='banque') selected @endif>Banque</option>
                            </select>
                        </div>
                        <div class="form-group" id="caisse_group">
                            <label>Caisse d'entree</label>
                            <select name="id_caisse" class="form-control">
                                @if(isset($caisses))
                                    @foreach($caisses as $caisse)
                                        <option value="{{ $caisse->id }}" @if(old('id_caisse')==$caisse->id) selected @endif>{{ $caisse->nom }}</option>
                                    @endforeach
                                @endif
                            </select>
                        </div>
                        <div id="banque_group" class="hidden">
                            <div class="form-group">
                                <label>Banque</label>
                                <select name="id_banque" class="form-control">
                                    @if(isset($banques))
                                        @foreach($banques as $banque)
                                            <option value="{{ $banque->id }}" @if(old('id_banque')==$banque->id) selected @endif>{{ $banque->nom }} {{ $banque->numero_compte }}</option>
                                        @endforeach
                                    @endif
                                </select>
                            </div>
                            <div class="form-group">
                                <label>Numero de versement</label>
                                <input type="text" name="numero_versement" class="form-control" value="{{ old('numero_versement') }}">
                            </div>
                            <div class="form-group">
                                <label>Reference</label>
                                <input type="text" name="reference_operation" class="form-control" value="{{ old('reference_operation') }}">
                            </div>
                            <div class="form-group">
                                <label>Demande</label>
                                <input type="text" name="demande_operation" class="form-control" value="{{ old('demande_operation') }}">
                            </div>
                        </div>
                        <div class="form-group">
                            <label>{{trans('inscription.ref')}} </label>
                            <input type="text" name="ref_versement"  class="form-control"  value="{{old('ref_versement')}}">

                        </div>
                </div>
                <input type="submit" class="btn btn-success form-control" value="{{trans('inscription.inscrire')}}" >

            </form>

        </div>

    @endif

    <script>
        function refreshChosen(selectId) {
            var el = $(selectId);
            if(el.hasClass('chosen-select') || el.hasClass('autocomplete')) {
                el.trigger('chosen:updated');
            }
        }

        function filterOptionsByData(selectId, dataName, value) {
            var select = $(selectId);
            select.find('option').each(function() {
                var option = $(this);
                if(option.val() === '') {
                    option.show().prop('disabled', false);
                    return;
                }
                var optionValue = option.data(dataName);
                var visible = !value || String(optionValue) === String(value);
                option.toggle(visible).prop('disabled', !visible);
            });

            var selected = select.find('option:selected');
            if(selected.length && selected.prop('disabled')) {
                select.val('');
            }
            refreshChosen(selectId);
        }

        function filterInscriptionFields() {
            filterOptionsByData('#nniveau', 'cycle', $('#cycle').val());
            filterOptionsByData('#classe', 'filiere', $('#filiere').val());
        }

        function filterEditInscriptionFields() {
            filterOptionsByData('#edit_nniveau', 'cycle', $('#edit_cycle').val());
            filterOptionsByData('#edit_classe', 'filiere', $('#edit_filiere').val());
        }

        function getScolarite() {

            var cycle=$('#cycle').val();
            var niveau= $('#filiere').val();
            var type_scolarite= $('#type_scolarite').val();
            var token='{{csrf_token()}}';

            if(cycle.length>0 && niveau.length>0)
            {
                $.ajax({
                    url:'{{route('get_scolarite')}}',
                    type:'POST',
                    dataType:'html',
                    data:'cycle=' + cycle + '&niveau=' + niveau + '&_token=' + token + '&type_scolarite=' + type_scolarite,
                    complete: function (res,stat) {
                        var obj=JSON.parse(res.responseText);
                        if(obj.inscription <= 0)
                        {
                            $('#show_montant').addClass('hidden');
                            $('#montant-group').removeClass('hidden');
                            var montant=$('#montant').removeClass('disabled');
                            montant.attr('readonly',false);
                            montant.val('');

                        }

                        else {
                            $('#show_montant').removeClass('hidden');
                            $('#show_montant_value').html(obj.inscription);
                            $('#montant-group').addClass('hidden');
                            var montant=$('#montant').addClass('disabled');
                            montant.val(obj.inscription);


                        }


                    }
                })
            }
            else {
                $('#show_montant').addClass('hidden');
                $('#montant-group').addClass('hidden');
                $('#montant').val('');

            }


        }

        function toggleOperationSource() {
            var source = $('#operation_source').val();
            if(source == 'banque') {
                $('#banque_group').removeClass('hidden');
                $('#caisse_group').addClass('hidden');
            } else {
                $('#banque_group').addClass('hidden');
                $('#caisse_group').removeClass('hidden');
            }
        }

        $(document).ready(function() {
            filterInscriptionFields();
            filterEditInscriptionFields();
            toggleOperationSource();
        });
    </script>
@endsection

@section('breadcrumb')
    <li><strong><a href="{{url('inscriptions')}}">@lang('menu.gestion_inscriptions')</a></strong></li>
    @if(!isset($old_record))
        <li><strong><a href="{{url('inscriptions/find')}}">@lang('admin.search')</a></strong></li>
    @endif
    <li class="active"><strong>{{$titre}}</strong></li>
@endsection
