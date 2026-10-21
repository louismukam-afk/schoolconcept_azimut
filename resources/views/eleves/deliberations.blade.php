@extends('skeleton')

@section('sub-content')
    <style>
        .delib-toolbar { margin-bottom: 12px; }
        .delib-layout { display: table; width: 100%; table-layout: fixed; border-spacing: 12px 0; }
        .delib-side, .delib-main { display: table-cell; vertical-align: top; }
        .delib-side { width: 260px; }
        .delib-type {
            display: block;
            border: 1px solid #d9e5e8;
            border-radius: 6px;
            padding: 10px 12px;
            margin-bottom: 8px;
            color: #333;
            background: #fff;
            cursor: pointer;
            font-weight: normal;
        }
        .delib-type input { margin-right: 7px; }
        .delib-type .fa { color: #2296a8; width: 18px; text-align: center; margin-right: 4px; }
        .delib-type.active { border-color: #2296a8; background: #f0fbfd; }
        .delib-panel { border: 1px solid #ddd; border-radius: 6px; background: #fff; margin-bottom: 12px; }
        .delib-panel-heading { padding: 9px 12px; border-bottom: 1px solid #ddd; font-weight: bold; background: #f7f7f7; }
        .delib-panel-body { padding: 12px; }
        .delib-grid { display: table; width: 100%; table-layout: fixed; border-spacing: 10px 0; }
        .delib-grid > div { display: table-cell; vertical-align: top; }
        .delib-check-list { max-height: 210px; overflow: auto; border: 1px solid #ddd; padding: 8px; border-radius: 4px; }
        .delib-check-list label { display: block; font-weight: normal; margin-bottom: 6px; }
        .delib-target-box { border-left: 3px solid #2296a8; padding-left: 10px; }
        .delib-hidden { display: none !important; }
        .delib-small { color: #666; font-size: 12px; }
        .delib-summary { margin-top: 10px; }
        @media (max-width: 900px) {
            .delib-layout, .delib-side, .delib-main, .delib-grid, .delib-grid > div { display: block; width: 100%; }
            .delib-side { margin-bottom: 12px; }
        }
    </style>

    <div class="delib-toolbar">
        <a href="{{ url('/eleves/notes/show') }}" class="btn btn-default">
            <span class="glyphicon glyphicon-arrow-left"></span> Retour aux notes
        </a>
        @if($classe && $cycle && $nniveau)
            <a href="{{ url('/eleves/notes/matieres/show').'?id='.$classe->id.'&cycle='.$id_cycle.'&nniveau='.$id_nniveau }}" class="btn btn-info">
                <span class="glyphicon glyphicon-list"></span> PV / notes
            </a>
            <a href="{{ url('/eleves/notes/deliberations/'.$classe->id).'?semestre=1&cycle='.$id_cycle.'&nniveau='.$id_nniveau }}" class="btn btn-default">
                <span class="glyphicon glyphicon-user"></span> Ancienne deliberation etudiants
            </a>
        @endif
    </div>

    <h3>Deliberations</h3>

    @if(session('deliberation_done') !== null)
        <div class="alert alert-success">
            Deliberation appliquee : {{ session('deliberation_done') }} ligne(s) modifiee(s).
        </div>
    @endif

    <div class="delib-panel">
        <div class="delib-panel-heading">Choix de la specialite</div>
        <div class="delib-panel-body">
            @include('eleves.partials.cycle_niveau_filiere_filter', array(
                'selectionBaseUrl' => URL::to('/eleves/notes/deliberations'),
                'selectionTitle' => 'Cycle, niveau, filiere et specialite',
                'selectionEmptyMessage' => 'Selectionnez le cycle, le niveau et la filiere pour choisir une specialite.'
            ))

            <div class="liste-classe col-md-12">
                <ul id="specialite_list">
                    @foreach($niveaux as $n)
                        <div class="col-md-6 niveau-block" data-filiere="{{$n->id}}" style="display:none;">
                            <h4 class="page-header">{{$n->getNom()}}</h4>
                            @foreach($classes as $c)
                                @if($c->id_niveau == $n->id)
                                    <li class="col-md-12">
                                        <a class="special cycle-niveau-specialite-link" href="#" data-classe="{{$c->id}}">{{$c->getNom()}}</a>
                                    </li>
                                @endif
                            @endforeach
                        </div>
                    @endforeach
                </ul>
            </div>
        </div>
    </div>

    @if($classe && $cycle && $nniveau)
        <div class="alert alert-info">
            <strong>Selection :</strong>
            {{ $cycle->nom }} |
            {{ $nniveau->nom }} |
            {{ $classe->nom }} |
            Annee {{ session('year') }}
        </div>

        <form method="GET" action="{{ url('/eleves/notes/deliberations') }}">
            <input type="hidden" name="id" value="{{ $classe->id }}">
            <input type="hidden" name="cycle" value="{{ $id_cycle }}">
            <input type="hidden" name="nniveau" value="{{ $id_nniveau }}">

            <div class="delib-layout">
                <div class="delib-side">
                    <div class="delib-panel">
                        <div class="delib-panel-heading">Types</div>
                        <div class="delib-panel-body">
                            <?php $selectedType = isset($request_values['type_deliberation']) ? $request_values['type_deliberation'] : 'matiere_note'; ?>
                            <?php
                                $types = array(
                                    'matiere_note' => array('icon' => 'fa-book', 'label' => 'Par Matieres'),
                                    'ue_note' => array('icon' => 'fa-clone', 'label' => 'Par UE'),
                                    'credit' => array('icon' => 'fa-star-o', 'label' => 'Par Credits'),
                                    'matiere_credit' => array('icon' => 'fa-files-o', 'label' => 'Par Matieres & Credits'),
                                    'ue_credit' => array('icon' => 'fa-bookmark-o', 'label' => 'Par UE & Credits')
                                );
                            ?>
                            @foreach($types as $typeKey => $type)
                                <label class="delib-type {{ $selectedType == $typeKey ? 'active' : '' }}">
                                    <input type="radio" name="type_deliberation" value="{{ $typeKey }}" {{ $selectedType == $typeKey ? 'checked' : '' }}>
                                    <i class="fa {{ $type['icon'] }}"></i> {{ $type['label'] }}
                                </label>
                            @endforeach
                        </div>
                    </div>
                </div>

                <div class="delib-main">
                    <div class="delib-panel">
                        <div class="delib-panel-heading">Criteres de deliberation</div>
                        <div class="delib-panel-body">
                            <div class="delib-grid">
                                <div>
                                    <div class="form-group">
                                        <label>Semestre</label>
                                        <select name="semestre" id="semestre_deliberation" class="form-control">
                                            <option value="tous" {{ (int)$semestre == 0 ? 'selected' : '' }}>Tous les semestres</option>
                                            @for($s = 1; $s <= 6; $s++)
                                                <option value="{{ $s }}" {{ (int)$semestre == $s ? 'selected' : '' }}>Semestre {{ $s }}</option>
                                            @endfor
                                        </select>
                                    </div>
                                </div>
                                <div>
                                    <div class="form-group">
                                        <label>Portee</label>
                                        <?php $scope = isset($request_values['portee']) ? $request_values['portee'] : 'specialite'; ?>
                                        <select name="portee" class="form-control">
                                            <option value="specialite" {{ $scope == 'specialite' ? 'selected' : '' }}>Cette specialite</option>
                                            <option value="selection" {{ $scope == 'selection' ? 'selected' : '' }}>Selection de matieres / UE</option>
                                        </select>
                                    </div>
                                </div>
                                <div>
                                    <div class="form-group">
                                        <label>Base</label>
                                        <?php $base = isset($request_values['base']) ? $request_values['base'] : 'note20'; ?>
                                        <select name="base" id="base_deliberation" class="form-control">
                                            <option value="note20" {{ $base == 'note20' ? 'selected' : '' }}>Note /20</option>
                                            <option value="note100" {{ $base == 'note100' ? 'selected' : '' }}>Note /100</option>
                                            <option value="credit" {{ $base == 'credit' ? 'selected' : '' }}>Credit</option>
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div class="delib-grid">
                                <div>
                                    <div class="form-group">
                                        <label>Intervalle debut</label>
                                        <input type="number" step="0.01" name="interval_min" value="{{ isset($request_values['interval_min']) ? $request_values['interval_min'] : '' }}" class="form-control" placeholder="Ex: 5">
                                        <label class="checkbox-inline">
                                            <input type="checkbox" name="min_inclus" value="1" {{ !isset($request_values['operation']) || isset($request_values['min_inclus']) ? 'checked' : '' }}> inclus
                                        </label>
                                    </div>
                                </div>
                                <div>
                                    <div class="form-group">
                                        <label>Intervalle fin</label>
                                        <input type="number" step="0.01" name="interval_max" value="{{ isset($request_values['interval_max']) ? $request_values['interval_max'] : '' }}" class="form-control" placeholder="Ex: 8">
                                        <label class="checkbox-inline">
                                            <input type="checkbox" name="max_inclus" value="1" {{ !isset($request_values['operation']) || isset($request_values['max_inclus']) ? 'checked' : '' }}> inclus
                                        </label>
                                    </div>
                                </div>
                                <div class="delib-target-box">
                                    <div class="form-group">
                                        <label id="target_label">Valeur a obtenir apres deliberation</label>
                                        <input type="number" step="0.01" name="valeur_cible" value="{{ isset($request_values['valeur_cible']) ? $request_values['valeur_cible'] : '' }}" class="form-control" placeholder="Ex: 10 ou 60">
                                    </div>
                                </div>
                            </div>

                            <div class="delib-grid">
                                <div id="matieres_section">
                                    <label>Matieres / UV programmees</label>
                                    <div class="delib-check-list">
                                        <label><input type="checkbox" id="all_matieres" {{ empty($request_values['matieres']) ? 'checked' : '' }}> Toutes les matieres visibles</label>
                                        @foreach($matieres as $m)
                                            <label class="delib-matiere-row" data-semestre="{{ $m->semestre }}">
                                                <input type="checkbox" class="delib-matiere-input" name="matieres[]" value="{{ $m->id_classe_matiere }}" {{ in_array($m->id_classe_matiere, isset($request_values['matieres']) ? (array)$request_values['matieres'] : array()) ? 'checked' : '' }}>
                                                {{ $m->code_matiere ? $m->code_matiere.' - ' : '' }}{{ $m->nom }}
                                                <span class="delib-small">({{ number_format((float)$m->credit, 0) }} cr., S{{ $m->semestre }})</span>
                                            </label>
                                        @endforeach
                                    </div>
                                </div>
                                <div id="modules_section">
                                    <label>UE / modules programmes</label>
                                    <div class="delib-check-list">
                                        <label><input type="checkbox" id="all_modules" {{ empty($request_values['modules']) ? 'checked' : '' }}> Toutes les UE visibles</label>
                                        @foreach($modules as $module)
                                            <label class="delib-module-row" data-semestres="{{ implode(',', array_values($module['semestres'])) }}">
                                                <input type="checkbox" class="delib-module-input" name="modules[]" value="{{ $module['id_module'] }}" {{ in_array($module['id_module'], isset($request_values['modules']) ? (array)$request_values['modules'] : array()) ? 'checked' : '' }}>
                                                {{ $module['code_module'] ? $module['code_module'].' - ' : '' }}{{ $module['nom'] }}
                                                <span class="delib-small">({{ number_format((float)$module['credit'], 0) }} cr., S{{ implode('/', array_values($module['semestres'])) }})</span>
                                            </label>
                                        @endforeach
                                        @if(count($modules) == 0)
                                            <span class="delib-small">Aucune UE groupee trouvee pour cette selection.</span>
                                        @endif
                                    </div>
                                </div>
                                <div>
                                    <label>Resume du principe</label>
                                    <div class="alert alert-warning" id="type_help">
                                        Les notes ou credits dans l'intervalle seront proposes pour deliberation vers la valeur cible.
                                    </div>
                                    <button type="submit" name="operation" value="actualiser" class="btn btn-primary btn-block">
                                        <span class="glyphicon glyphicon-refresh"></span> Actualiser les criteres
                                    </button>
                                    <button type="submit" name="operation" value="deliberer" class="btn btn-success btn-block" style="margin-top:8px;">
                                        <span class="glyphicon glyphicon-ok"></span> Deliberer
                                    </button>
                                    <p class="delib-small" style="margin-top:6px;">
                                        Le bouton Deliberer conserve les criteres choisis pour l'etape d'application.
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="delib-panel delib-summary">
                        <div class="delib-panel-heading">Lecture des criteres</div>
                        <div class="delib-panel-body">
                            <p>
                                <strong>Exemple :</strong>
                                les notes ou credits compris dans l'intervalle choisi seront amenes a la valeur cible.
                                Les bornes peuvent etre incluses ou exclues selon les cases cochees.
                            </p>
                            <p class="delib-small">
                                Cliquez sur Deliberer pour generer l'apercu, puis confirmez les lignes a appliquer.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </form>

        @if(!empty($previewMessage))
            <div class="alert alert-warning">{{ $previewMessage }}</div>
        @endif

        @if(isset($previewRows) && count($previewRows) > 0)
            <div class="delib-panel">
                <div class="delib-panel-heading">Apercu avant application definitive</div>
                <div class="delib-panel-body">
                    <form method="POST" action="{{ url('/eleves/notes/deliberations/apply-criteres') }}">
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                        <input type="hidden" name="id_classe" value="{{ $classe->id }}">
                        <input type="hidden" name="cycle" value="{{ $id_cycle }}">
                        <input type="hidden" name="nniveau" value="{{ $id_nniveau }}">

                        <div class="table-responsive">
                            <table class="table table-bordered table-condensed">
                                <thead>
                                <tr>
                                    <th style="width:40px;"><input type="checkbox" id="preview_all" checked></th>
                                    <th>Matricule</th>
                                    <th>Nom & Prenoms</th>
                                    <th>Objet</th>
                                    <th>Semestre</th>
                                    <th>Reference actuelle</th>
                                    <th>Note UV actuelle /20</th>
                                    <th>Note UV cible /20</th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php $previewIndex = 0; ?>
                                @foreach($previewRows as $row)
                                    <tr>
                                        <td>
                                            <input type="checkbox" class="preview-check" name="apply[{{ $previewIndex }}]" value="1" checked>
                                            <input type="hidden" name="eleve_id[{{ $previewIndex }}]" value="{{ $row['eleve']->id }}">
                                            <input type="hidden" name="matiere_id[{{ $previewIndex }}]" value="{{ $row['matiere']->id_matiere }}">
                                            <input type="hidden" name="classe_matiere_id[{{ $previewIndex }}]" value="{{ $row['matiere']->id_classe_matiere }}">
                                            <input type="hidden" name="session_id[{{ $previewIndex }}]" value="{{ $row['matiere']->id_session }}">
                                            <input type="hidden" name="semestre_matiere[{{ $previewIndex }}]" value="{{ $row['semestre'] }}">
                                            <input type="hidden" name="target20[{{ $previewIndex }}]" value="{{ number_format($row['target20'], 2, '.', '') }}">
                                            <input type="hidden" name="action_type[{{ $previewIndex }}]" value="{{ isset($row['type_deliberation']) ? $row['type_deliberation'] : 'matiere_note' }}">
                                        </td>
                                        <td>{{ $row['eleve']->matricule }}</td>
                                        <td>{{ $row['eleve']->nom }} {{ $row['eleve']->prenom }}</td>
                                        <td>
                                            @if(!empty($row['object_label']))
                                                <strong>{{ $row['object_label'] }}</strong><br>
                                            @endif
                                            {{ $row['matiere']->code_matiere ? $row['matiere']->code_matiere.' - ' : '' }}{{ $row['matiere']->nom }}
                                        </td>
                                        <td>S{{ $row['semestre'] }}</td>
                                        <td>{{ isset($row['current_label']) ? $row['current_label'] : 'Note actuelle' }} : <strong>{{ number_format(isset($row['current_value']) ? $row['current_value'] : $row['note20'], 2) }}</strong></td>
                                        <td>{{ number_format($row['note20'], 2) }}</td>
                                        <td><strong>{{ number_format($row['target20'], 2) }}</strong></td>
                                    </tr>
                                    <?php $previewIndex++; ?>
                                @endforeach
                                </tbody>
                            </table>
                        </div>

                        <button type="submit" class="btn btn-success btn-lg">
                            <span class="glyphicon glyphicon-ok"></span> Appliquer la deliberation aux lignes cochees
                        </button>
                    </form>
                </div>
            </div>
        @endif
    @else
        <div class="alert alert-warning">
            Choisissez d'abord une specialite pour afficher les matieres, les UE et les criteres de deliberation.
        </div>
    @endif

    <script>
        (function () {
            var typeConfig = {
                matiere_note: {
                    matieres: true,
                    modules: false,
                    base: 'note20',
                    target: 'Note finale a obtenir',
                    help: 'Deliberation par matiere : les UV dont la note est dans l intervalle seront amenees a la note cible.'
                },
                ue_note: {
                    matieres: false,
                    modules: true,
                    base: 'note20',
                    target: 'Moyenne UE a obtenir',
                    help: 'Deliberation par UE : seules les matieres non validees du module, avec une note inferieure a 10/20 (50/100), sont augmentees pour atteindre la moyenne cible. Les matieres deja validees restent inchangees.'
                },
                credit: {
                    matieres: false,
                    modules: false,
                    base: 'credit',
                    target: 'Total credits a obtenir',
                    help: 'Deliberation par credits : seules les matieres non validees, avec une note inferieure a 10/20 (50/100), sont amenees a 10/20 pour atteindre les credits cibles. Les matieres deja validees restent inchangees.'
                },
                matiere_credit: {
                    matieres: true,
                    modules: false,
                    base: 'credit',
                    target: 'Credits matiere a obtenir',
                    help: 'Deliberation par matiere et credits : parmi les UV selectionnees, seules les matieres non validees, avec une note inferieure a 10/20 (50/100), sont amenees a 10/20 pour atteindre les credits cibles.'
                },
                ue_credit: {
                    matieres: false,
                    modules: true,
                    base: 'credit',
                    target: 'Credits UE a obtenir',
                    help: 'Deliberation par UE et credits : seules les matieres non validees, notees en dessous de 10/20 (50/100), sont modifiees pour valider les modules selectionnes. Les matieres deja validees restent inchangees.'
                }
            };

            function get(id) {
                return document.getElementById(id);
            }

            function selectedType() {
                var checked = document.querySelector('input[name="type_deliberation"]:checked');
                return checked ? checked.value : 'matiere_note';
            }

            function refreshTypeCards() {
                var labels = document.getElementsByClassName('delib-type');
                for (var i = 0; i < labels.length; i++) {
                    var input = labels[i].getElementsByTagName('input')[0];
                    labels[i].className = labels[i].className.replace(' active', '');
                    if (input && input.checked) {
                        labels[i].className += ' active';
                    }
                }
            }

            function setVisible(id, visible) {
                var el = get(id);
                if (!el) return;
                if (visible) {
                    el.className = el.className.replace(' delib-hidden', '');
                } else if (el.className.indexOf('delib-hidden') === -1) {
                    el.className += ' delib-hidden';
                }
            }

            function refreshTypeDisplay() {
                var config = typeConfig[selectedType()] || typeConfig.matiere_note;
                setVisible('matieres_section', !!config.matieres);
                setVisible('modules_section', !!config.modules);
                if (!config.matieres) clearGroup('matieres');
                if (!config.modules) clearGroup('modules');

                var base = get('base_deliberation');
                if (base) {
                    if (config.base === 'credit') base.value = 'credit';
                    else if (base.value === 'credit') base.value = 'note20';
                }

                var target = get('target_label');
                if (target) target.innerHTML = config.target;

                var help = get('type_help');
                if (help) help.innerHTML = config.help;
            }

            function clearGroup(group) {
                var config = groupConfig(group);
                if (config.master) config.master.checked = false;
                var inputs = document.querySelectorAll(config.inputSelector);
                for (var i = 0; i < inputs.length; i++) {
                    inputs[i].checked = false;
                }
            }

            function rowHasSemester(row, semestre) {
                if (!semestre || semestre === 'tous') return true;
                if (row.getAttribute('data-semestre')) {
                    return row.getAttribute('data-semestre') === semestre;
                }
                var semestres = row.getAttribute('data-semestres') || '';
                return (',' + semestres + ',').indexOf(',' + semestre + ',') !== -1;
            }

            function refreshSemesterRows() {
                var select = get('semestre_deliberation');
                var semestre = select ? select.value : 'tous';
                var rows = document.querySelectorAll('.delib-matiere-row, .delib-module-row');
                for (var i = 0; i < rows.length; i++) {
                    var visible = rowHasSemester(rows[i], semestre);
                    rows[i].style.display = visible ? '' : 'none';
                    if (!visible) {
                        var hiddenInput = rows[i].getElementsByTagName('input')[0];
                        if (hiddenInput) hiddenInput.checked = false;
                    }
                }
                if (get('all_matieres') && get('all_matieres').checked) syncGroupFromMaster('matieres');
                if (get('all_modules') && get('all_modules').checked) syncGroupFromMaster('modules');
            }

            function groupConfig(group) {
                if (group === 'matieres') {
                    return {
                        master: get('all_matieres'),
                        rowSelector: '.delib-matiere-row',
                        inputSelector: '.delib-matiere-input'
                    };
                }
                return {
                    master: get('all_modules'),
                    rowSelector: '.delib-module-row',
                    inputSelector: '.delib-module-input'
                };
            }

            function visibleRows(config) {
                var rows = document.querySelectorAll(config.rowSelector);
                var visibles = [];
                for (var i = 0; i < rows.length; i++) {
                    if (rows[i].style.display !== 'none') {
                        visibles.push(rows[i]);
                    }
                }
                return visibles;
            }

            function syncGroupFromMaster(group) {
                var config = groupConfig(group);
                if (!config.master) return;
                var rows = visibleRows(config);
                for (var i = 0; i < rows.length; i++) {
                    var input = rows[i].querySelector(config.inputSelector);
                    if (input) input.checked = config.master.checked;
                }
            }

            function syncMasterFromGroup(group) {
                var config = groupConfig(group);
                if (!config.master) return;
                var rows = visibleRows(config);
                var checkedCount = 0;
                for (var i = 0; i < rows.length; i++) {
                    var input = rows[i].querySelector(config.inputSelector);
                    if (input && input.checked) checkedCount++;
                }
                config.master.checked = rows.length > 0 && checkedCount === rows.length;
            }

            function refreshAll() {
                refreshTypeCards();
                refreshTypeDisplay();
                refreshSemesterRows();
            }

            document.addEventListener('change', function (event) {
                if (event.target && event.target.name === 'type_deliberation') {
                    refreshAll();
                }
                if (event.target && event.target.id === 'semestre_deliberation') {
                    refreshSemesterRows();
                }
                if (event.target && event.target.id === 'all_matieres') {
                    syncGroupFromMaster('matieres');
                }
                if (event.target && event.target.id === 'all_modules') {
                    syncGroupFromMaster('modules');
                }
                if (event.target && event.target.className && event.target.className.indexOf('delib-matiere-input') !== -1) {
                    syncMasterFromGroup('matieres');
                }
                if (event.target && event.target.className && event.target.className.indexOf('delib-module-input') !== -1) {
                    syncMasterFromGroup('modules');
                }
                if (event.target && event.target.id === 'preview_all') {
                    var checks = document.getElementsByClassName('preview-check');
                    for (var i = 0; i < checks.length; i++) {
                        checks[i].checked = event.target.checked;
                    }
                }
            });
            document.addEventListener('DOMContentLoaded', refreshAll);
        })();
    </script>
@endsection

@section('breadcrumb')
    <li><strong><a href="{{ url('eleves') }}">@lang('menu.gestion_notes')</a></strong></li>
    <li><strong><a href="{{ url('eleves/notes') }}">Notes</a></strong></li>
    <li class="active"><strong>Deliberations</strong></li>
@endsection
