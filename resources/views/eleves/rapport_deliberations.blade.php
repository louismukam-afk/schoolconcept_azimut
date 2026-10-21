@extends('skeleton')
@section('sub-content')
    <style>
        .report-actions { margin-bottom: 15px; }
        .report-selection { border: 0; padding: 0; min-width: 0; }
        .report-specialities { padding: 0 15px 15px; }
        .report-specialities label { display: block; font-weight: normal; padding: 6px 0; }
        .report-group { margin-top: 22px; }
        .report-type { margin: 15px 0; }
        .report-subject { margin: 12px 0; }
        .report-table th, .report-table td { vertical-align: middle !important; }
        .report-metrics { display: flex; flex-wrap: wrap; gap: 12px; margin: 15px 0; }
        .report-metrics .well { flex: 1; margin: 0; min-width: 150px; }
        .report-metrics strong { display: block; font-size: 24px; }
        #deliberation-print-root { display: none; }
        @media print {
            @page { size: A4 landscape; margin: 0; }
            body > * { display: none !important; }
            body > #deliberation-print-root { display: block !important; padding: 10mm; }
            html, body { margin: 0 !important; padding: 0 !important; background: white !important; }
            body::before, body::after { display: none !important; }
            #deliberation-print-root .btn, #deliberation-print-root button { display: none !important; }
            .report-group { border: 0; }
            tr { page-break-inside: avoid; }
            h4, h5 { page-break-after: avoid; }
            thead { display: table-header-group; }
            .table-responsive { overflow: visible !important; }
            a[href]:after { content: none !important; }
            body { font-size: 10px; }
        }
    </style>
    <div class="report-actions btn-toolbar">
        <a href="{{ url('/eleves/notes/show') }}" class="btn btn-default">Retour aux notes</a>
        <a href="{{ url('/eleves/notes/deliberations') }}" class="btn btn-success">Délibérations</a>
        @if($report !== null)
            <button type="button" class="btn btn-primary" onclick="printDeliberationReport();"><span class="glyphicon glyphicon-print"></span> Imprimer le rapport / PDF</button>
        @endif
    </div>
    <h3>{{ $titre }}</h3>
    @if($errors->any())
        <div class="alert alert-danger"><ul>@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul></div>
    @endif
    <form method="GET" action="{{ url('/eleves/notes/deliberations/rapport') }}" class="report-filters">
        <div class="panel panel-default">
            <div class="panel-heading"><strong>Choix du rapport</strong></div>
            <div class="panel-body">
                <div class="row">
                    <div class="col-md-3 form-group">
                        <label for="annee_academique">Année académique</label>
                        <select name="annee_academique" id="annee_academique" class="form-control" required>
                            @foreach($annees as $annee)
                                <option value="{{ $annee }}" {{ $annee_selectionnee == $annee ? 'selected' : '' }}>{{ $annee }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3 form-group">
                        <label for="report_scope">Portée</label>
                        <select name="portee" id="report_scope" class="form-control">
                            <option value="globale" {{ $portee == 'globale' ? 'selected' : '' }}>Globale — toutes les spécialités</option>
                            <option value="selection" {{ $portee == 'selection' ? 'selected' : '' }}>Par cycle, niveau et filière / spécialité</option>
                        </select>
                    </div>
                    <div class="col-md-3 form-group">
                        <label for="report_format">Présentation</label>
                        <select name="format" id="report_format" class="form-control">
                            <option value="synthetique" {{ $format == 'synthetique' ? 'selected' : '' }}>Rapport synthétique</option>
                            <option value="detaille" {{ $format == 'detaille' ? 'selected' : '' }}>Rapport détaillé</option>
                        </select>
                    </div>
                    <div class="col-md-3 form-group">
                        <label for="type_deliberation">Type de délibération</label>
                        <select name="type_deliberation" id="type_deliberation" class="form-control">
                            <option value="">Tous les types</option>
                            @foreach($types as $key => $label)
                                <option value="{{ $key }}" {{ $type_deliberation == $key ? 'selected' : '' }}>{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-4 form-group">
                        <label for="report_semester">Semestre du programme</label>
                        <select name="semestre" id="report_semester" class="form-control">
                            <option value="tous" {{ $semestre === 'tous' ? 'selected' : '' }}>Tous les semestres</option>
                            @for($s = 1; $s <= 6; $s++)
                                <option value="{{ $s }}" {{ (string)$semestre === (string)$s ? 'selected' : '' }}>Semestre {{ $s }}</option>
                            @endfor
                        </select>
                    </div>
                    <div class="col-md-4 form-group">
                        <label for="report_exam">Examen modifié</label>
                        <select name="type_examen" id="report_exam" class="form-control">
                            @foreach($exam_options as $examKey => $examLabel)
                                <option value="{{ $examKey }}" {{ $type_examen == $examKey ? 'selected' : '' }}>{{ $examLabel }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <p class="text-muted">Synthétique : effectifs, matières et lignes délibérées par type. Détaillé : matières et listes nominatives des étudiants.</p>
            </div>
        </div>
        <fieldset id="report_selection" class="report-selection" {{ $portee == 'globale' ? 'disabled' : '' }} style="{{ $portee == 'globale' ? 'display:none;' : '' }}">
            @include('eleves.partials.cycle_niveau_filiere_filter', array(
                'selectionSubmitFilters' => true, 'selectionCycleValue' => $id_cycle,
                'selectionLevelValue' => $id_nniveau, 'selectionFiliereValue' => $id_filiere,
                'selectionTitle' => 'Cycle, niveau, filière et spécialité', 'selectionBaseUrl' => ''
            ))
            <div class="panel panel-default report-specialities">
                <h4>Spécialité</h4>
                <label><input type="radio" name="id" value="" {{ !$id_classe ? 'checked' : '' }}> Toutes les spécialités de la filière sélectionnée</label>
                <div class="liste-classe"><ul id="specialite_list" class="list-unstyled">
                    @foreach($niveaux as $n)
                        <li class="niveau-block" data-filiere="{{ $n->id }}" style="display:none;">
                            <h4>{{ $n->getNom() }}</h4>
                            @foreach($classes as $c)
                                @if($c->id_niveau == $n->id)
                                    <label><input type="radio" name="id" value="{{ $c->id }}" {{ $id_classe == $c->id ? 'checked' : '' }}> {{ $c->getNom() }}</label>
                                @endif
                            @endforeach
                        </li>
                    @endforeach
                </ul></div>
            </div>
        </fieldset>
        <div class="report-actions"><button type="submit" name="consulter" value="1" class="btn btn-primary"><span class="glyphicon glyphicon-search"></span> Consulter le rapport</button></div>
    </form>
    @if($report !== null)
        <div id="deliberation-report">
            <h3 class="text-center">Rapport {{ $format == 'detaille' ? 'détaillé' : 'synthétique' }} des délibérations</h3>
            <p class="text-center">Année {{ $annee_selectionnee }} · {{ $portee == 'globale' ? 'Toutes les spécialités du campus' : 'Sélection par cycle, niveau et filière' }} · {{ $semestre == 'tous' ? 'Tous les semestres' : 'Semestre '.$semestre }} · {{ $exam_options[$type_examen] }} · {{ $type_deliberation ? $types[$type_deliberation] : 'Tous les types de délibération' }}</p>
            <p class="text-muted">Seules les notes portant une trace de délibération sont comptées. Chaque ligne correspond à une note d'examen ; la note cible est celle de la dernière délibération conservée. Les modifications antérieures non archivées ne sont pas reconstituées.</p>
            @if($format == 'detaille')
                <p class="text-muted">« Non conservée » signifie que la note avant délibération n'a pas été enregistrée dans l'ancienne trace.</p>
            @endif
            <div class="report-metrics">
                <div class="well well-sm"><strong>{{ count($report['groups']) }}</strong> Groupes spécialité / niveau</div>
                <div class="well well-sm"><strong>{{ $report['subjects'] }}</strong> Matières programmées concernées</div>
                <div class="well well-sm"><strong>{{ $report['students'] }}</strong> Étudiants distincts</div>
                <div class="well well-sm"><strong>{{ $report['count'] }}</strong> Lignes délibérées</div>
            </div>
            @if(!$report['count'])
                <div class="alert alert-info">Aucune délibération enregistrée pour cette sélection.</div>
            @endif
            @foreach($report['groups'] as $group)
                <section class="panel panel-default report-group">
                    <div class="panel-heading"><strong>{{ $group['filiere'] }} / {{ $group['specialite'] }} / {{ $group['niveau'] }}</strong> — {{ $group['cycle'] }}</div>
                    <div class="panel-body">
                        <p>{{ count($group['students']) }} étudiant(s) distinct(s) · {{ count($group['subjects']) }} matière(s) · {{ $group['count'] }} ligne(s) délibérée(s)</p>
                        @if($format == 'synthetique')
                            <div class="table-responsive"><table class="table table-bordered report-table">
                                <thead><tr><th>Type de délibération</th><th>Matières délibérées</th><th>Étudiants distincts</th><th>Lignes délibérées</th></tr></thead>
                                <tbody>
                                @foreach($types as $typeKey => $typeLabel)
                                    @if(isset($group['types'][$typeKey]))
                                        <?php $typeGroup = $group['types'][$typeKey]; ?>
                                        <tr><td>{{ $typeLabel }}</td><td>
                                            @foreach($typeGroup['subjects'] as $subject)
                                                <div>{{ $subject['nom'] }} (S{{ $subject['semestre'] }}) — {{ count($subject['rows']) }} ligne(s)</div>
                                            @endforeach
                                        </td><td>{{ count($typeGroup['students']) }}</td><td>{{ $typeGroup['count'] }}</td></tr>
                                    @endif
                                @endforeach
                                </tbody>
                            </table></div>
                            <p class="text-muted">Un étudiant présent dans plusieurs types est compté une seule fois dans le total du groupe.</p>
                        @else
                            @foreach($types as $typeKey => $typeLabel)
                                @if(isset($group['types'][$typeKey]))
                                    <div class="report-type">
                                        <h4>{{ $typeLabel }}</h4>
                                        @foreach($group['types'][$typeKey]['subjects'] as $subject)
                                            <div class="report-subject">
                                                <h5><strong>{{ $subject['nom'] }}</strong> — Semestre {{ $subject['semestre'] }}</h5>
                                                <div class="table-responsive"><table class="table table-striped table-bordered table-condensed report-table">
                                                    <thead><tr><th>Matricule</th><th>Étudiant</th><th>Examen</th><th>Note avant délibération /20</th><th>Note cible /20</th><th>Note cible /100</th><th>Dernière modification</th></tr></thead>
                                                    <tbody>
                                                    @foreach($subject['rows'] as $row)
                                                        <tr><td>{{ $row->matricule }}</td><td>{{ $row->nom }} {{ $row->prenom }}</td><td>{{ $row->exam_label }}</td><td>{{ $row->before20 === null ? 'Non conservée' : number_format($row->before20, 2, ',', ' ') }}</td><td>{{ number_format($row->target20, 2, ',', ' ') }}</td><td>{{ number_format($row->target20 * 5, 2, ',', ' ') }}</td><td>{{ $row->updated_at }}</td></tr>
                                                    @endforeach
                                                    </tbody>
                                                </table></div>
                                            </div>
                                        @endforeach
                                    </div>
                                @endif
                            @endforeach
                        @endif
                    </div>
                </section>
            @endforeach
        </div>
    @else
        <div class="alert alert-info">Choisissez la portée et la présentation, puis cliquez sur « Consulter le rapport ».</div>
    @endif
    <script>
        (function () {
            function prepareReportPrint() {
                var report = document.getElementById('deliberation-report');
                if (!report) return;
                var previous = document.getElementById('deliberation-print-root');
                if (previous) previous.parentNode.removeChild(previous);
                var printable = report.cloneNode(true);
                printable.id = 'deliberation-print-root';
                document.body.appendChild(printable);
            }
            window.printDeliberationReport = function () {
                prepareReportPrint();
                window.print();
            };
            window.addEventListener('beforeprint', prepareReportPrint);
            window.addEventListener('afterprint', function () {
                var printable = document.getElementById('deliberation-print-root');
                if (printable) printable.parentNode.removeChild(printable);
            });
        })();
        document.addEventListener('DOMContentLoaded', function () {
            var scope = document.getElementById('report_scope');
            var selection = document.getElementById('report_selection');
            function refreshScope() {
                selection.disabled = scope.value === 'globale';
                selection.style.display = selection.disabled ? 'none' : '';
            }
            scope.addEventListener('change', refreshScope);
            refreshScope();
            ['cycle_filter', 'nniveau_filter', 'filiere_filter'].forEach(function (id) {
                document.getElementById(id).addEventListener('change', function () {
                    var all = selection.querySelector('input[name="id"][value=""]');
                    if (all) all.checked = true;
                });
            });
        });
    </script>
@endsection
@section('breadcrumb')
    <li><strong><a href="{{ url('eleves') }}">@lang('menu.gestion_notes')</a></strong></li>
    <li><strong><a href="{{ url('eleves/notes/show') }}">Notes</a></strong></li>
    <li class="active"><strong>Rapports des délibérations</strong></li>
@endsection
