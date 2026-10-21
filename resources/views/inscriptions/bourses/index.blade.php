@extends('skeleton')
@section('sub-content')
    <div class="col-md-10">
        @include('perso.functions')
        <?php
            $schoolYears = getSchoolYears();
        ?>
    @if(isset($success))

            <div class="alert alert-success">
                {{  trans('admin.succes')  }}
            </div>

        @endif
        <div>
            <button class="btn btn-primary" data-toggle="modal" data-backdrop="false" href="#add_bourse"
        ><span class="glyphicon glyphicon-plus"></span>{{  trans('admin.create')  }}</button>
        </div>
        <table class="table table-striped table-condensed table-inverse" style="margin-top: 15px;">
            <thead>
            <caption style="font-size: 22px">{{  trans('admin.bourse')  }}</caption>
            <th>#</th>
            <th>{{  trans('inscription.eleve')  }}</th>
            <th>{{  trans('admin.year')  }}</th>
            <th>{{  trans('admin.montant')  }}</th>
            <th></th>
            </thead>
            <tbody>
            <?php $i=1; ?>
            @foreach($bourses as $b)
                <tr>
                    <td>{{$i++}}</td>
                    <td>{{getStudentFullName($b->eleve)}}</td>
                    <td><strong >{{$b->annee_scolaire}}</strong></td>
                    <td>{{$b->montant}}</td>
                    <td>
                        <div class="btn-group">
                            <a href="#" class=" btn btn-primary dropdown-toggle" data-toggle="dropdown">Actions <b class="caret"></b></a>
                            <ul class="dropdown-menu">
                                <li><a style="color: white" title="Modifier les informations" class="btn-primary"  data-toggle="modal" data-backdrop="false" href="#edit_bourse" onclick="edit_bourse({{$b->id}},'{{sanitize($b->annee_scolaire)}}',{{$b->montant}})"><span class="glyphicon glyphicon-pencil"  ></span> {{  trans('admin.edit')  }}</a></li>
                                <li><a style="color: white" href="{{ route('delete_bourse', $b->id) }}"   class="btn-danger"><span class="glyphicon glyphicon-trash"></span> {{  trans('admin.del')  }}</a></li>
                            </ul>
                        </div>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>


        <div class="modal fade" id="add_bourse">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">x</button>
                        <h4 class="modal-title">{{  trans('admin.remplir_champs')  }}</h4>
                    </div>
                    <div class="modal-body">
                        <form accept-charset="UTF-8" role="form" method="POST" id="add_bourse_form" action="{{ route('store_bourse') }}">
                            <input type="hidden" name="_token" value="{{ csrf_token() }}">
                            <fieldset>

                                <div class="form-group">
                                    <label for="eleve">{{  trans('admin.selectionner_eleve')  }} : <span class="required">*</span></label>
                                    <select id="eleve" name="id_eleve" form="add_bourse_form" class="form-control autocomplete" required>
                                        <option value="">-------</option>
                                        @foreach($eleves as $e)
                                            <option value="{{  $e->id  }}" @if($e->id == old('id_eleve')) selected @endif>{{  $e->getFullName() }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="form-group">
                                    <label for="annee_scolaire">{{  trans('admin.year')  }} : <span class="required">*</span></label>
                                    <select id="annee_scolaire" name="annee_scolaire" form="add_bourse_form" class="form-control autocomplete" required>
                                        <option value="">-------</option>
                                        @foreach($schoolYears as $y)
                                            <option value="{{ $y }}" @if($y == old('annee_scolaire')) selected @endif>{{ $y}}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="form-group">
                                    <label>@lang('admin.montant') : <span class="required">*</span></label>
                                    <input class="form-control" id="montant"  name="montant" type="number"  value="{{ old('montant') }}" required autofocus>
                                </div>

                                <input class="btn  btn-success " type="submit" value="{{  trans('admin.confirmer')  }}">

                            </fieldset>
                        </form>
                    </div>
                </div>


            </div>
        </div>

        <div class="modal fade" id="edit_bourse">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">x</button>
                        <h4 class="modal-title">{{  trans('admin.remplir_champs')  }}</h4>
                    </div>
                    <div class="modal-body">
                        <form accept-charset="UTF-8" role="form" method="POST" id="edit_bourse_form" action="{{ route('update_bourse') }}">
                            <input type="hidden" name="_token" value="{{ csrf_token() }}">
                            <input type="hidden" id="id_bourse" name="id" required>
                            <fieldset>

                                <div class="form-group">
                                    <label for="edit-annee_scolaire">{{  trans('admin.year')  }} : <span class="required">*</span></label>
                                    <select id="edit-annee_scolaire" name="annee_scolaire" form="edit_bourse_form" class="form-control autocomplete" required>
                                        <option value="">-------</option>
                                        @foreach($schoolYears as $y)
                                            <option value="{{ $y }}">{{ $y}}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="form-group">
                                    <label>@lang('admin.montant') : <span class="required">*</span></label>
                                    <input class="form-control" id="edit-montant"  name="montant" type="number" required>
                                </div>

                                <input class="btn  btn-success " type="submit" value="{{  trans('admin.confirmer')  }}">

                            </fieldset>
                        </form>
                    </div>
                </div>


            </div>
        </div>

        <div class="paginate" style="text-align: center;"> <?php echo(str_replace('/?', '?', $bourses->render()) ); ?></div>
    </div>

    <script>
        function edit_bourse(id, annee_scolaire, montant)
        {
            document.getElementById("id_bourse").value = id;
            document.getElementById("edit-montant").value = montant;
            document.getElementById("edit-annee_scolaire").value = annee_scolaire;
            $("#edit-annee_scolaire").trigger("chosen:updated");
        }
    </script>
    <!--    -->
@endsection

@section('breadcrumb')
<li><strong><a href="{{url('inscriptions')}}">@lang('menu.gestion_inscriptions')</a></strong></li>
<li class="active"><strong>{{$titre}}</strong></li>
@endsection