@extends('skeleton')
@section('sub-content')
    @include('perso.functions')
    <div class="col-md-10">
        @if(isset($success))

            <div class="alert alert-success">
                {{  trans('admin.succes')  }}
            </div>

        @endif
        @if(isset($custom_error))

            <div class="alert alert-danger">
                {{  $custom_error  }}
            </div>

        @endif

        <button class="btn btn-primary" data-toggle="modal" data-backdrop="false" href="#add_frais"
        ><span class="glyphicon glyphicon-plus"></span>{{  trans('admin.frais_n')  }}</button>

        <table class="table table-bordered table-striped table-condensed table-inverse" style="margin-top: 15px;">
            <thead>
            <caption style="font-size: 22px">{{  trans('admin.frais_l')  }}</caption>
            <th>#</th>
            <th>@lang('admin.code')</th>
            <th>@lang('admin.nom')</th>
            <th>@lang('admin.type')</th>
            <th>@lang('admin.montant')</th>
            <th>@lang('admin.show_on_report')</th>
            <th>@lang('admin.numero_ordre')</th>
            </thead>
            <tbody>
            <?php $i=1; ?>
            @foreach($frais as $n)
                <tr>
                    <td>{{$i++}}</td>
                    <td>{{$n->code}} </td>
                    <td>{{$n->nom}} </td>
                    <td>{{ $n->isKind() ? trans('admin.nature') : trans('admin.espece') }}</td>
                    <td>
                        @if(!$n->isKind()) <strong>{{$n->montant}}</strong> @endif
                    </td>
                    <td>
                        @if($n->show_on_report)
                            @lang('inscription.oui')
                        @else
                            @lang('inscription.non')
                        @endif
                    </td>

                    <td>{{$n->ordre}} </td>

                    <td>
                        <div class="btn-group">
                            <a href="#" class=" btn btn-primary dropdown-toggle" data-toggle="dropdown">Actions <b class="caret"></b></a>
                            <ul class="dropdown-menu">
                                <li><a style="color: white" class="btn-primary"  data-toggle="modal" data-backdrop="false" href="#edit_frais" onclick="editer_frais({{$n->id}}, '{{sanitize($n->code)}}', '{{sanitize($n->nom)}}',{{$n->montant}}, {{$n->show_on_report == true ? 1 : 0}}, {{$n->ordre}}, {{$n->type}})"><span class="glyphicon glyphicon-pencil"  ></span> {{  trans('admin.edit')  }}</a></li>
                                <li>
                                    <a style="color: white" href="{{ URL::to('/admin/frais/destroy/').'/'.$n->id }}"   class="btn-danger"><span class="glyphicon glyphicon-trash"></span> @lang('admin.del')</a>
                                </li>
                            </ul>
                        </div>


                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>


        <div class="modal fade" id="add_frais">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">x</button>
                        <h4 class="modal-title">{{  trans('admin.remplir_champs')  }}</h4>
                    </div>
                    <div
                            class="modal-body">



                        <form accept-charset="UTF-8" role="form" method="POST" id="add_frais_form" action="{{ url('/admin/frais/store/') }}">
                            <input type="hidden" name="_token" value="{{ csrf_token() }}">
                            <fieldset>


                                <div class="form-group">
                                    <label for="code">{{  trans('admin.code')  }} : <span class="required">*</span></label>
                                    <input class="form-control" id="code" name="code" type="text"  value="{{ old('code') }}"  required autofocus>

                                </div>

                                <div class="form-group">
                                    <label for="nom">{{  trans('admin.nom')  }} : <span class="required">*</span></label>
                                    <input class="form-control" id="nom" name="nom" type="text"  value="{{ old('nom') }}" required>

                                </div>

                                <div class="alert alert-info"><input type="checkbox" name='type_frais' id="type_frais"/> @lang('admin.frais_nature')</div>

                                <div class="form-group" id="montant_form_group">
                                    <label>{{  trans('admin.montant')  }} : </label>
                                    <input class="form-control"  name="montant" type="number" id="montant_frais"  value="{{ old('montant') }}" required >

                                </div>

                                <div class="form-group">
                                    <label>{{  trans('admin.show_on_report')  }} : </label>
                                    <select name="show_on_report" id="show_on_report" class="form-control" form="add_frais_form">
                                        <option value="0" @if(old('show_on_report') == '0') selected @endif>@lang('inscription.non')</option>
                                        <option value="1" @if(old('show_on_report') == '1') selected @endif>@lang('inscription.oui')</option>
                                    </select>

                                </div>

                                <div class="form-group">
                                    <label>{{  trans('admin.numero_ordre')  }} : </label>
                                    <input class="form-control" id="ordre"  name="ordre" type="number"  value="{{ old('ordre') }}"  />

                                </div>
                                <input class="btn  btn-success " type="submit" value="{{  trans('admin.confirmer')  }}">


                            </fieldset>
                        </form>


                    </div>





                </div>


            </div>
        </div>



        <div class="modal fade" id="edit_frais">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">x</button>
                        <h4 class="modal-title">{{  trans('admin.remplir_champs')  }}</h4>
                    </div>
                    <div class="modal-body">



                        <form accept-charset="UTF-8" role="form" method="POST" id="edit_frais_form" action="{{ url('/admin/frais/update/') }}">
                            <input type="hidden" name="_token" value="{{ csrf_token() }}">
                            <input type="hidden" name="id" id="edit_id_frais" value=""/>
                            <fieldset>

                                <div class="form-group">
                                    <label for="edit_code">{{  trans('admin.code')  }} : <span class="required">*</span></label>
                                    <input class="form-control" id="edit_code" name="code" type="text"    required >

                                </div>

                                <div class="form-group">
                                    <label for="nom">{{  trans('admin.nom')  }} : <span class="required">*</span></label>
                                    <input class="form-control" id="edit_nom" name="nom" type="text"   required >

                                </div>

                                <div class="alert alert-info"><input type="checkbox" name='type_frais' id="edit-type_frais"/> @lang('admin.frais_nature')</div>

                                <div class="form-group" id="edit-montant_form_group">
                                    <label>{{  trans('admin.montant')  }} : </label>
                                    <input class="form-control" id="edit_montant" name="montant" type="number"  required >

                                </div>

                                <div class="form-group">
                                    <label>{{  trans('admin.show_on_report')  }} : </label>
                                    <select name="show_on_report" id="edit_show_on_report" class="form-control" form="edit_frais_form">
                                        <option value="0" >@lang('inscription.non')</option>
                                        <option value="1" >@lang('inscription.oui')</option>
                                    </select>

                                </div>

                                <div class="form-group">
                                    <label>{{  trans('admin.numero_ordre')  }} : </label>
                                    <input class="form-control" id="edit_ordre"  name="ordre" type="number"   />

                                </div>
                                <input class="btn  btn-success " type="submit" value="{{  trans('admin.confirmer')  }}">


                            </fieldset>
                        </form>



                    </div>





                </div>


            </div>
        </div>

        <div class="paginate" style="text-align: center;"> <?php echo(str_replace('/?', '?', $frais->render()) ); ?></div>
    </div>

    <script>
        const CASH_FEES = {{ \gesecole\Frais::CASH_FEES_TYPE }}
        const KIND_FEES = {{ \gesecole\Frais::KIND_FEES_TYPE }}
        $(function() {

                $('#type_frais').on('change', function(event) {
                    const el = event.target
                    if (el.checked) {
                        $('#montant_form_group').addClass('hidden')
                        $('#montant_frais').removeAttr('required')
                    } else {
                        $('#montant_form_group').removeClass('hidden')
                        $('#montant_frais').attr('required', 'required')
                    }
                })

                $('#edit-type_frais').on('change', function(event) {
                    const el = event.target
                    if (el.checked) {
                        $('#edit-montant_form_group').addClass('hidden')
                        $('#edit-montant').removeAttr('required')
                    } else {
                        $('#edit-montant_form_group').removeClass('hidden')
                        $('#edit-montant').attr('required', 'required')
                    }
                })
            })

        function editer_frais(id, code, nom, montant, show, ordre, type)
        {
            document.getElementById("edit_id_frais").value = id;
            document.getElementById("edit_code").value = code;
            document.getElementById("edit_nom").value = nom;
            document.getElementById("edit_montant").value = montant;
            document.getElementById("edit_show_on_report").value = show;
            document.getElementById("edit_ordre").value = ordre;

            const editFraisCheckbox = document.getElementById("edit-type_frais")
            if (type === KIND_FEES) {
                editFraisCheckbox.checked = true;
                $('#edit-montant_form_group').addClass('hidden')
                $('#edit-montant').removeAttr('required')
            } else {
                editFraisCheckbox.checked = false;
                $('#edit-montant_form_group').removeClass('hidden')
                $('#edit-montant').attr('required', 'required')
            }

        }
    </script>

@endsection

@section('breadcrumb')
    <li><strong><a href="{{url('admin')}}">@lang('admin.dashboard')</a></strong></li>
    <li class="active"><strong>{{$titre}}</strong></li>
@endsection