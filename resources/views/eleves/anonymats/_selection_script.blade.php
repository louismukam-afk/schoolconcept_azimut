<script>
(function () {
    var initial = {
        niveau: "{{(int)$selection['id_nniveau']}}",
        filiere: "{{(int)$selection['id_niveau']}}",
        classe: "{{(int)$selection['id_classe']}}",
        matiere: "{{(int)Request::get('id_matiere')}}",
        matieres: <?php echo json_encode(isset($selectedSubjectIds) ? array_map('intval', $selectedSubjectIds) : array()); ?>
    };
    var matieresUrl = "{{route('notes_anonymats_matieres')}}";
    var lockSemesterToExam = {{isset($lockSemesterToExam) && $lockSemesterToExam ? 'true' : 'false'}};

    function get(id) {
        return document.getElementById(id);
    }

    function update(select) {
        if (window.jQuery && select) {
            window.jQuery(select).trigger('chosen:updated');
        }
    }

    function sourceOptions(select) {
        var result = [];
        if (!select) return result;
        for (var i = 1; i < select.options.length; i++) {
            result.push(select.options[i].cloneNode(true));
        }
        return result;
    }

    function fill(select, source, predicate, placeholder, selected) {
        if (!select) return;
        select.options.length = 0;
        select.add(new Option(placeholder, ''));
        for (var i = 0; i < source.length; i++) {
            if (!predicate || predicate(source[i])) {
                select.add(source[i].cloneNode(true));
            }
        }
        select.disabled = select.options.length <= 1;
        select.value = selected || '';
        if (select.value !== String(selected || '')) select.value = '';
        update(select);
    }

    document.addEventListener('DOMContentLoaded', function () {
        var cycle = get('anonymat_cycle');
        var niveau = get('anonymat_nniveau');
        var filiere = get('anonymat_filiere');
        var classe = get('anonymat_classe');
        var semestre = get('anonymat_semestre');
        var typeExamen = get('anonymat_type_examen');
        var modeGeneration = get('anonymat_mode_generation');
        var matiere = get('anonymat_matiere');
        var matieresBlock = get('anonymat_matieres_block');
        var matieresCheckboxes = get('anonymat_matieres_checkboxes');
        var matieresStatus = get('anonymat_matieres_status');
        var matieresAll = get('anonymat_matieres_all');
        var matieresNone = get('anonymat_matieres_none');
        if (!cycle || !niveau || !filiere || !classe) return;

        var niveaux = sourceOptions(niveau);
        var filieres = sourceOptions(filiere);
        var classes = sourceOptions(classe);
        var matterRequest = 0;

        function hasSelectedSubject(subjectId, selected) {
            for (var i = 0; i < selected.length; i++) {
                if (String(selected[i]) === String(subjectId)) return true;
            }
            return false;
        }

        function renderMatiereCheckboxes(items, selected, placeholder) {
            if (!matieresCheckboxes) return;
            matieresCheckboxes.innerHTML = '';
            var selectedItems = selected || [];
            var checkAll = selectedItems.length === 0 && items.length > 0;
            for (var i = 0; i < items.length; i++) {
                var col = document.createElement('div');
                col.className = 'col-sm-6 col-md-4';
                var label = document.createElement('label');
                label.className = 'checkbox-inline';
                label.style.marginLeft = '0';
                label.style.marginBottom = '8px';
                var checkbox = document.createElement('input');
                checkbox.type = 'checkbox';
                checkbox.name = 'id_matieres[]';
                checkbox.value = items[i].id;
                checkbox.checked = checkAll || hasSelectedSubject(items[i].id, selectedItems);
                label.appendChild(checkbox);
                label.appendChild(document.createTextNode(' ' + items[i].nom));
                col.appendChild(label);
                matieresCheckboxes.appendChild(col);
            }
            if (matieresStatus) {
                matieresStatus.textContent = placeholder || (items.length ? items.length + ' matiere(s) disponible(s)' : 'Aucune matiere configuree');
            }
        }

        function toggleMatiereBlock() {
            if (!matieresBlock || !modeGeneration) return;
            matieresBlock.style.display = modeGeneration.value === 'matiere' ? '' : 'none';
        }

        function loadMatieres(selected, selectedSubjects) {
            if (!matiere && !matieresCheckboxes) return;
            var checkedSubjects = arguments.length > 1 ? selectedSubjects : initial.matieres;
            var requestId = ++matterRequest;
            var ready = cycle.value && niveau.value && classe.value && semestre.value;
            if (matiere) fill(matiere, [], null, ready ? 'Chargement...' : 'Completez d\'abord la selection', '');
            renderMatiereCheckboxes([], [], ready ? 'Chargement...' : 'Completez d\'abord la selection');
            if (!ready) return;

            var url = matieresUrl + '?id_cycle=' + encodeURIComponent(cycle.value)
                + '&id_nniveau=' + encodeURIComponent(niveau.value)
                + '&id_classe=' + encodeURIComponent(classe.value)
                + '&semestre=' + encodeURIComponent(semestre.value);
            var xhr = new XMLHttpRequest();
            xhr.open('GET', url, true);
            xhr.onreadystatechange = function () {
                if (xhr.readyState !== 4 || requestId !== matterRequest) return;
                if (xhr.status < 200 || xhr.status >= 300) {
                    if (matiere) fill(matiere, [], null, 'Impossible de charger les matieres', '');
                    renderMatiereCheckboxes([], [], 'Impossible de charger les matieres');
                    return;
                }
                var items;
                try {
                    items = JSON.parse(xhr.responseText);
                } catch (error) {
                    if (matiere) fill(matiere, [], null, 'Reponse invalide', '');
                    renderMatiereCheckboxes([], [], 'Reponse invalide');
                    return;
                }
                var options = [];
                for (var i = 0; i < items.length; i++) {
                    options.push(new Option(items[i].nom, items[i].id));
                }
                if (matiere) {
                    fill(matiere, options, null,
                        items.length ? 'Choisir une matiere' : 'Aucune matiere configuree', selected || '');
                }
                renderMatiereCheckboxes(items, checkedSubjects,
                    items.length ? items.length + ' matiere(s) disponible(s)' : 'Aucune matiere configuree');
            };
            xhr.send();
        }

        function refreshNiveaux(selected) {
            var cycleId = cycle.value;
            fill(niveau, niveaux, function (option) {
                return option.getAttribute('data-cycle') === cycleId;
            }, cycleId ? 'Choisir un niveau' : 'Selectionnez d\'abord un cycle', selected || '');
        }

        function refreshFilieres(selected) {
            fill(filiere, niveau.value ? filieres : [], null,
                niveau.value ? 'Choisir une filiere' : 'Selectionnez d\'abord un niveau', selected || '');
        }

        function refreshClasses(selected) {
            var filiereId = filiere.value;
            fill(classe, classes, function (option) {
                return option.getAttribute('data-filiere') === filiereId;
            }, filiereId ? 'Choisir une specialite' : 'Selectionnez d\'abord une filiere', selected || '');
        }

        function syncSemesterWithExam() {
            if (typeExamen.value === 'fin_semestre_1' || typeExamen.value === 'rattrapage_1') {
                semestre.value = 'S1';
            }
            if (typeExamen.value === 'fin_semestre_2' || typeExamen.value === 'rattrapage_2') {
                semestre.value = 'S2';
            }
            update(semestre);
        }

        cycle.addEventListener('change', function () {
            refreshNiveaux('');
            refreshFilieres('');
            refreshClasses('');
            loadMatieres('', []);
        });
        niveau.addEventListener('change', function () {
            refreshFilieres('');
            refreshClasses('');
            loadMatieres('', []);
        });
        filiere.addEventListener('change', function () {
            refreshClasses('');
            loadMatieres('', []);
        });
        classe.addEventListener('change', function () { loadMatieres('', []); });
        semestre.addEventListener('change', function () {
            if (lockSemesterToExam) syncSemesterWithExam();
            loadMatieres('', []);
        });
        typeExamen.addEventListener('change', function () {
            syncSemesterWithExam();
            loadMatieres('', []);
        });
        if (modeGeneration) {
            modeGeneration.addEventListener('change', toggleMatiereBlock);
        }
        if (matieresAll && matieresCheckboxes) {
            matieresAll.addEventListener('click', function () {
                var boxes = matieresCheckboxes.getElementsByTagName('input');
                for (var i = 0; i < boxes.length; i++) boxes[i].checked = true;
            });
        }
        if (matieresNone && matieresCheckboxes) {
            matieresNone.addEventListener('click', function () {
                var boxes = matieresCheckboxes.getElementsByTagName('input');
                for (var i = 0; i < boxes.length; i++) boxes[i].checked = false;
            });
        }

        syncSemesterWithExam();
        toggleMatiereBlock();
        refreshNiveaux(initial.niveau);
        refreshFilieres(initial.filiere);
        refreshClasses(initial.classe);
        loadMatieres(initial.matiere);
    });
})();
</script>
