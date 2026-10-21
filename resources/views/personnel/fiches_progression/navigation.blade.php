<div class="btn-group" style="margin-bottom:15px">
    <a href="{{route('fiches_progression_enseignants',array('annee_scolaire'=>$selectedYear))}}" class="btn btn-default"><span class="glyphicon glyphicon-user"></span> Enseignants</a>
    <a href="{{route('fiches_progression_par_specialite',array('annee_scolaire'=>$selectedYear))}}" class="btn btn-default"><span class="glyphicon glyphicon-ok"></span> Cours avec fiches</a>
    <a href="{{route('fiches_progression_manquantes',array('annee_scolaire'=>$selectedYear))}}" class="btn btn-default"><span class="glyphicon glyphicon-warning-sign"></span> Fiches manquantes</a>
</div>
