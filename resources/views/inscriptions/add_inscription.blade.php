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
                        <select required form="edit_inscription_form" id="edit_cycle"  name="cycle" class="form-control">
                            @foreach($cycles as $c)
                                <option value="{{$c->id}}" @if($inscription->id_cycle==$c->id) selected @endif>{{$c->nom}}</option>
                            @endforeach
                        </select>

                    </div>
                    <div class="form-group">
                        <label>@lang('admin.specialites')  : </label>

                        <select required form="edit_inscription_form" id="edit_classe" name="classe" class="form-control autocomplete">
                            <?php $n=0;$i=0; ?>

                            @foreach($classes as $c)
                                @if($i=0)
                                    <optgroup label="{{$c->niveau->getNom()}}">
                                        <?php  $i++; ?>
                                        @endif
                                        @if($c->id_niveau!=$n)
                                            <?php $n=$c->id_niveau ?>
                                    </optgroup>
                                    <optgroup label="{{$c->niveau->getNom()}}">
                                        @endif
                                        <option value="{{$c->id}}" @if($inscription->id_classe==$c->id) selected @endif>{{$c->getNom()}}</option>
                                        @endforeach
                                    </optgroup>
                        </select>


                    </div>
                    <div class="form-group">
                        <label>  @lang('admin.nniveau') </label>
                        <select form="edit_inscription_form"  name="nniveau" class="form-control" required>
                            @foreach($nniveaux as $nn)
                                <option value="{{$nn->id}}" @if($inscription->id_nniveau==$nn->id) selected @endif>{{$nn->nom}}</option>
                            @endforeach
                        </select>

                    </div>
                        <div class="form-group">
                            <label>{{trans('admin.matricule')}} : (@lang('admin.no_matricule'))</label>
                            <input type="text" name="matricule"  class="form-control"  value="{{old('matricule')}}">

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
                        <select form="add_inscription_form" id="cycle" name="cycle" class="form-control" onchange="getScolarite()" required>
                            <option value=""> -- @lang('admin.choose_cycle') --</option>

                        @foreach($cycles as $c)
                                <option value="{{$c->id}}" @if(old('cycle')==$c->id) selected @endif>{{$c->nom}}</option>
                            @endforeach
                        </select>

                    </div>
                    <div class="form-group">
                        <label>@lang('admin.specialites')  : </label>

                        <select form="add_inscription_form" id="classe" name="classe" class="form-control autocomplete" onchange="getScolarite()" required>
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
                        <label for="type_scolarite">{{  trans('admin.type_scolarite')  }} : </label>
                        <select form="add_inscription_form" id="type_scolarite" name="type_scolarite" class="form-control" onchange="getScolarite()" >
                            <option id="0" value="0" @if(strcmp(old('type_scolarite'),'0')==0) selected @endif>{{ trans('admin.ancien') }}</option>
                            <option id="1" value="1" @if(strcmp(old('type_scolarite'),'1')==0) selected @endif>{{ trans('admin.nouveau') }}</option>
                        </select>
                    </div>

                        <div class="form-group">
                        <label>  @lang('admin.nniveau') </label>
                        <select form="add_inscription_form"  name="nniveau" class="form-control" required>
                            @foreach($nniveaux as $nn)
                                <option value="{{$nn->id}}"  @if(old('nniveau')==$nn->id) selected @endif>{{$nn->nom}}</option>
                            @endforeach
                        </select>

                    </div>




                </div>
                <div class="col-md-6" style="padding-left: 30px">
                    @if($e->matricule==null)
                        <div class="form-group">
                            <label>{{trans('admin.matricule')}} : (@lang('admin.no_matricule'))</label>
                            <input type="text" name="matricule"  class="form-control"  value="{{old('matricule')}}">

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
                            <label>{{trans('inscription.ref')}} </label>
                            <input type="text" name="ref_versement"  class="form-control"  value="{{old('ref_versement')}}">

                        </div>
                </div>
                <input type="submit" class="btn btn-success form-control" value="{{trans('inscription.inscrire')}}" >

            </form>

        </div>

    @endif

    <script>
        function getScolarite() {

            var cycle=$('#cycle').val();
            var el=$('#classe')[0];
            var el_type_scolarite=$('#type_scolarite')[0];

            var niveau= el.options[el.selectedIndex].id;
            var type_scolarite= el_type_scolarite.options[el_type_scolarite.selectedIndex].id;
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
    </script>
@endsection

@section('breadcrumb')
    <li><strong><a href="{{url('inscriptions')}}">@lang('menu.gestion_inscriptions')</a></strong></li>
    @if(!isset($old_record))
        <li><strong><a href="{{url('inscriptions/find')}}">@lang('admin.search')</a></strong></li>
    @endif
    <li class="active"><strong>{{$titre}}</strong></li>
@endsection