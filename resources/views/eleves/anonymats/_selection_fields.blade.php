<div class="row">
    <div class="form-group col-md-4"><label>Campus</label><select name="id_campus" class="form-control" required>
        @foreach($campuses as $item)<option value="{{$item->id}}" @if($selection['id_campus']==$item->id) selected @endif>{{$item->sigle?'['.$item->sigle.'] ':''}}{{$item->nom}}</option>@endforeach
    </select></div>
    <div class="form-group col-md-4"><label>Cycle</label><select name="id_cycle" id="anonymat_cycle" class="form-control" required><option value="">Choisir</option>
        @foreach($cycles as $item)<option value="{{$item->id}}" @if($selection['id_cycle']==$item->id) selected @endif>{{$item->nom}}</option>@endforeach
    </select></div>
    <div class="form-group col-md-4"><label>Niveau</label><select name="id_nniveau" id="anonymat_nniveau" class="form-control" required @if(!$selection['id_cycle']) disabled @endif><option value="">Selectionnez d'abord un cycle</option>
        @foreach($nniveaux as $item)<option value="{{$item->id}}" data-cycle="{{$item->id_cycle}}" @if($selection['id_nniveau']==$item->id) selected @endif>{{$item->nom}}</option>@endforeach
    </select></div>
</div>
<div class="row">
    <div class="form-group col-md-4"><label>Filiere</label><select name="id_niveau" id="anonymat_filiere" class="form-control" required @if(!$selection['id_nniveau']) disabled @endif><option value="">Selectionnez d'abord un niveau</option>
        @foreach($filieres as $item)<option value="{{$item->id}}" @if($selection['id_niveau']==$item->id) selected @endif>{{$item->nom}}</option>@endforeach
    </select></div>
    <div class="form-group col-md-4"><label>Specialite / sigle</label><select name="id_classe" id="anonymat_classe" class="form-control" required @if(!$selection['id_niveau']) disabled @endif><option value="">Selectionnez d'abord une filiere</option>
        @foreach($classes as $item)<option value="{{$item->id}}" data-filiere="{{$item->id_niveau}}" @if($selection['id_classe']==$item->id) selected @endif>{{$item->code?'['.$item->code.'] ':''}}{{$item->nom}}</option>@endforeach
    </select></div>
    <div class="form-group col-md-4"><label>Tutelle d'epreuve</label><select name="id_tutelle" class="form-control"><option value="0">Toutes / aucune</option>
        @foreach($tutelles as $item)<option value="{{$item->id}}" @if($selection['id_tutelle']==$item->id) selected @endif>{{$item->nom}}</option>@endforeach
    </select></div>
</div>
<div class="row">
    <div class="form-group col-md-2"><label>Semestre</label><select name="semestre" id="anonymat_semestre" class="form-control" required>
        <option value="S1" @if($selection['semestre']=='S1') selected @endif>S1</option><option value="S2" @if($selection['semestre']=='S2') selected @endif>S2</option>
    </select></div>
    <div class="form-group col-md-2"><label>Mode</label><select name="mode_generation" id="anonymat_mode_generation" class="form-control" required>
        <option value="examen" @if($selection['mode_generation']=='examen') selected @endif>Par examen</option><option value="matiere" @if($selection['mode_generation']=='matiere') selected @endif>Par matiere</option>
    </select></div>
</div>
<div class="row">
    <div class="form-group col-md-6"><label>Type d'examen</label><select name="type_examen" id="anonymat_type_examen" class="form-control" required>
        @foreach($examLabels as $value=>$label)<option value="{{$value}}" @if($selection['type_examen']==$value) selected @endif>{{$label}}</option>@endforeach
    </select></div>
    @if(isset($showMatiere) && $showMatiere)
        <div class="form-group col-md-6"><label>Matiere</label><select name="id_matiere" id="anonymat_matiere" class="form-control" required @if(!$selection['id_classe']) disabled @endif><option value="">Completez d'abord la selection</option>
            @foreach($matieres as $item)<option value="{{$item->id}}" @if(Request::get('id_matiere')==$item->id) selected @endif>{{$item->nom}}</option>@endforeach
        </select></div>
    @endif
</div>
@if(isset($showSubjectCheckboxes) && $showSubjectCheckboxes)
<div class="row" id="anonymat_matieres_block">
    <div class="form-group col-md-12">
        <label>Matieres a generer</label>
        <input type="hidden" name="selection_matieres_active" value="1">
        <div class="well well-sm" style="margin-bottom:10px">
            <button type="button" class="btn btn-xs btn-default" id="anonymat_matieres_all"><i class="fa fa-check-square-o"></i> Tout cocher</button>
            <button type="button" class="btn btn-xs btn-default" id="anonymat_matieres_none"><i class="fa fa-square-o"></i> Tout decocher</button>
            <span class="text-muted" id="anonymat_matieres_status" style="margin-left:8px">Completez d'abord la selection</span>
        </div>
        <div id="anonymat_matieres_checkboxes" class="row">
            @foreach($matieres as $item)
                <div class="col-sm-6 col-md-4">
                    <label class="checkbox-inline" style="margin-left:0;margin-bottom:8px">
                        <input type="checkbox" name="id_matieres[]" value="{{$item->id}}" @if(!count($selectedSubjectIds) || in_array((int)$item->id, $selectedSubjectIds)) checked @endif> {{$item->nom}}
                    </label>
                </div>
            @endforeach
        </div>
    </div>
</div>
@endif
@include('eleves.anonymats._selection_script')
