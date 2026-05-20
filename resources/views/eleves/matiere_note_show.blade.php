@extends('skeleton')

@section('sub-content')
    <div class="col-md-12">
        {{-- Messages flash --}}
        @if(isset($success))
            <div class="alert alert-success">
                {{ trans('admin.succes') }}
            </div>
        @endif

        @if(isset($erreur))
            <div class="alert alert-danger">
                {{ $erreur }}
            </div>
        @endif

        {{-- Contexte général --}}
        <p>
            <strong>Année académique :</strong>
            {{ isset($annee) ? $annee : session('year') }}
        </p>

        {{-- Liens de PV globaux (classe entière) --}}
        <ul>
            <li>
                <a href="{{ url('/eleves/notes/print_note/'.$id.'?sequence=1&cycle='.$id_cycle.'&nniveau='.$id_nniveau) }}">
                    <h4>Procès-verbaux des notes de contrôle continu semestre 1</h4>
                </a>
            </li>
            <li>
                <a href="{{ url('/eleves/notes/print_note/'.$id.'?sequence=2&cycle='.$id_cycle.'&nniveau='.$id_nniveau) }}">
                    <h4>Procès-verbaux des notes de contrôle continu semestre 2</h4>
                </a>
            </li>
            <li>
                <a href="{{ url('/eleves/notes/print_note_rat/'.$id.'?sequence=7&cycle='.$id_cycle.'&nniveau='.$id_nniveau) }}">
                    <h4>Procès-verbaux des notes de rattrapages semestre 1</h4>
                </a>
            </li>
            <li>
                <a href="{{ url('/eleves/notes/print_note_rat/'.$id.'?sequence=8&cycle='.$id_cycle.'&nniveau='.$id_nniveau) }}">
                    <h4>Procès-verbaux des notes de rattrapages semestre 2</h4>
                </a>
            </li>
            <li>
                <a href="{{ url('/eleves/notes/print_note/'.$id.'?sequence=3&cycle='.$id_cycle.'&nniveau='.$id_nniveau) }}">
                    <h4>{{ trans('admin.PV').' '.trans('admin.semestre').' 1' }}</h4>
                </a>
            </li>
            <li>
                <a href="{{ url('/eleves/notes/print_note/'.$id.'?sequence=4&cycle='.$id_cycle.'&nniveau='.$id_nniveau) }}">
                    <h4>{{ trans('admin.PV').' '.trans('admin.semestre').' 2' }}</h4>
                </a>
            </li>
            <li>
                <a href="{{ url('/eleves/notes/print_note/'.$id.'?sequence=9&cycle='.$id_cycle.'&nniveau='.$id_nniveau) }}">
                    <h4>@lang('admin.bilan_annuel')</h4>
                </a>
            </li>

            {{-- Délibérations des notes --}}
            <li>
                <a href="{{ url('/eleves/notes/print_note_deliberer/'.$id.'?sequence=7&cycle='.$id_cycle.'&nniveau='.$id_nniveau) }}">
                    <h4>Délibérer les notes semestre 1</h4>
                </a>
            </li>
            <li>
                <a href="{{ url('/eleves/notes/print_note_deliberer/'.$id.'?sequence=8&cycle='.$id_cycle.'&nniveau='.$id_nniveau) }}">
                    <h4>Délibérer les notes semestre 2</h4>
                </a>
            </li>

            <li>
                <a href="{{ url('/eleves/notes/print_note_deliberer_rattrapage/'.$id.'?sequence=10&cycle='.$id_cycle.'&nniveau='.$id_nniveau) }}">
                    <h4>Délibérer les notes rattrapage 1</h4>
                </a>
            </li>
            <li>
                <a href="{{ url('/eleves/notes/print_note_deliberer_rattrapage/'.$id.'?sequence=11&cycle='.$id_cycle.'&nniveau='.$id_nniveau) }}">
                    <h4>Délibérer les notes rattrapage 2</h4>
                </a>
            </li>
        </ul>

        {{-- Choix de la matière --}}
        <h4>{{ trans('eleve.choix_mat') }}</h4>
        <hr>

        <div class="liste-classe col-md-9">
            <ul>
                @foreach($matieres as $m)
                    <li>
                        @if(!empty($m->id_session_exam))
                            {{-- Matière avec session configurée --}}
                            <a data-toggle="modal"
                               data-backdrop="false"
                               href="#choose_seq2"
                               onclick="choix_seq({{ $m->id }})">
                                {{ (App::getLocale()=='en') ? $m->nom_anglais : $m->nom }}
                            </a>
                        @else
                            {{-- Matière sans session d’examen pour l’année --}}
                            <span style="color: #999;">
                                {{ (App::getLocale()=='en') ? $m->nom_anglais : $m->nom }}
                                (aucune session configurée)
                            </span>
                        @endif
                    </li>
                @endforeach
            </ul>
        </div>

        {{-- Modal choix séquence --}}
        <div class="modal fade" id="choose_seq2">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button"
                                class="close"
                                data-dismiss="modal">x</button>
                        <h4 class="modal-title">{{ trans('admin.remplir_champs') }}</h4>
                    </div>

                    <div class="modal-body">
                        <form accept-charset="UTF-8"
                              role="form"
                              method="POST"
                              id="choix_seq_form2"
                              action="{{ url('/eleves/notes/print_note/'.$id) }}">
                            <input type="hidden" name="_token" value="{{ csrf_token() }}"/>
                            <input type="hidden" name="id" id="id" value=""/>
                            <input type="hidden" name="cycle" id="id_cycle" value="{{ $id_cycle }}"/>
                            <input type="hidden" name="nniveau" id="id_nniveau" value="{{ $id_nniveau }}"/>

                            <fieldset>
                                <div class="form-group">
                                    <label for="sequence">{{ trans('eleve.choix_seq') }} : </label>
                                    <select name="sequence"
                                            form="choix_seq_form2"
                                            class="form-control">
                                        <optgroup label="@lang('admin.semestre') 1">
                                            <option value="1">CC 1</option>
                                            <option value="3">@lang('admin.session_normale') 1</option>
                                            <option value="5">@lang('admin.rattrapage') 1</option>
                                            <option value="7">TP / TPE 1</option>
                                        </optgroup>
                                        <optgroup label="@lang('admin.semestre') 2">
                                            <option value="2">CC 2</option>
                                            <option value="4">@lang('admin.session_normale') 2</option>
                                            <option value="6">@lang('admin.rattrapage') 2</option>
                                            <option value="8">TP / TPE 2</option>
                                        </optgroup>
                                    </select>
                                </div>

                                <input class="btn btn-success"
                                       type="submit"
                                       value="{{ trans('admin.confirmer') }}"
                                       autofocus>
                            </fieldset>
                        </form>
                    </div>
                </div>
            </div>
        </div> {{-- /modal --}}
    </div>

    <script>
        function choix_seq(id) {
            document.getElementById("id").value = id;
        }
    </script>
@endsection

@section('breadcrumb')
    <li>
        <strong><a href="{{ url('eleves') }}">@lang('menu.gestion_notes')</a></strong>
    </li>
    <li>
        <strong><a href="{{ url('eleves/notes') }}">Notes</a></strong>
    </li>
    <li>
        <a href="{{ url('eleves/notes/show') }}">
            <strong>@lang('admin.choix_classe')</strong>
        </a>
    </li>
    <li class="active">
        <strong>@lang('admin.matiere')</strong>
    </li>
@endsection
