@extends('skeleton')
@section('sub-content')
    @include('perso.functions')

    <style>
        .result-table thead,.result-table thead th
        {
            height: 5px !important;
            padding-top: 0 !important;
            padding-bottom: 0 !important;
        }
    </style>
    <a href="#" class="btn btn-lg btn-warning" onclick="window.print()"><span class="glyphicon glyphicon-print"></span> @lang('inscription.imprimer')</a>

    <link rel="stylesheet"  href="{{URL::asset('css/releves.css')}}">
    <?php $ne=0 ?>
    @foreach($PV as $releve)
        <?php
        $sCoef=0;
        $TMP=0;
        $TMV=0;
        $sNote=0;

        ?>
        <div class="releve">
            @include('perso.header')
           
            <div class="content">

                <div class="titre">
                    <p style="font-size: 22px;"><strong>RELEVE DE NOTES ANNUEL</strong></p>
                    <strong>
                        <p class="big-traduction">ANNUAL TRANSCRIPT </p>
                    </strong>
                </div>
                <div class="nom-matricule-container">
                    <div class="nom">
                        <p><strong>Nom et Prénoms : </strong>{{$releve->eleve->nom.' '.$releve->eleve->prenom}}</p>
                        <p><strong class="traduction">
                                Surname and name
                            </strong></p>
                    </div>
                    <div class="matricule">
                        <p><strong>Matricule : </strong>{{$releve->eleve->matricule}}</p>
                        <p><strong class="traduction">
                                Registration N°
                            </strong></p>
                    </div>
                </div>
                <div class="date-lieu-container">
                    <div class="date">
                        <p><strong>Née le: </strong> {{(new DateTime($releve->eleve->date_naiss))->format('d/m/Y')}}</p>
                        <p><strong class="traduction">Born on</strong></p>
                    </div>
                    <div class="lieu">
                        <p><strong>A :</strong> {{$releve->eleve->lieu_naiss}}</p>
                        <p><strong class="traduction">At </strong></p>
                    </div>
                </div>
                <div class="cycle-filiere">
                    <div class="cycle">
                        <p><strong>Cycle : </strong> {{$cycle->nom}}</p>
                        <p><strong class="traduction">Cycle : </strong></p>
                    </div>
                    <div class="filiere">
                        <p><strong>Filière : </strong> {{$niveau->nom}}</p>
                        <p class="traduction"><strong class="traduction">Discpline : </strong>{{$niveau->nom_anglais}}</p>
                    </div>

                </div>

                <div class="specialite-annee">
                    <div class="specialite">
                        <p><strong>Spécialité : </strong> {{$classe->nom}} <strong>- Niveau : </strong>{{$nniveau->nom}}</p>
                        <p class="traduction"><strong class="traduction">Spéciality : </strong> {{$classe->nom_anglais}}  <strong class="traduction">- Niveau : </strong>{{$nniveau->nom}}</p>
                    </div>
                    <div class="annee_a">
                        <p><strong>Année académique : </strong> {{session('year')}}</p>
                        <p><strong class="traduction">Academic year : </strong></p>
                    </div>
                </div>
                <table class="table  table-striped table-condensed table-inverse" style="margin-top: 15px; margin-bottom: 10px;">
                    <thead>
                    <th ><strong>Intitulé de l'UE</strong> <span class="traduction">UE title</span></th>
                    <th class="note-column"><strong>Semestre <span class="traduction">Semester</span></strong>  </th>
                  <th class="note-column"><strong>Crédit</strong></th>
                    <th class="note-column"><strong>Moy /100</strong>  </th>
                    <th class="note-column"><strong>Qualité /4</strong>  </th>
                    <th class="note-column"><strong>Mention</strong> <span class="traduction">Grade</span></th>

                    </thead>
                    <tbody>
                    <tr>
                        <td colspan="2"><strong style="font-size: 12px;margin-left: 80px;text-align: center;">Unités d'enseignements Fondamentales\<span class="traduction">Fundamental</span></strong></td>
                    </tr>
                    @foreach($releve->matieres as $matiere)
                            @if(($matiere->type==0))
                                <?php $sNote+= ($matiere->note*$matiere->credit) ;$sCoef+=$matiere->credit;$TMP++; ?>
                                @if($matiere->note>=50)
                                    <?php $TMV++; ?>
                                @endif
                            <td>
                                    {{$matiere->altName}}

                            </td>
                            <td>{{getSemestre($matiere->semestre)}}</td>
                          <td>{{$matiere->credit}}</td>
                            <td>{{$matiere->note}}</td>
                            <td>{{getGrade($matiere->note)[1]}}</td>
                            <td>{{getGrade($matiere->note)[0]}}</td>
                        </tr>
                            @endif
                    @endforeach

                    <tr>
                        <td colspan="2"><strong style="font-size: 12px;margin-left: 80px;text-align: center;">Unités d'enseignement Transversales\<span class="traduction">Transversal</span></strong></td>
                    </tr>
                    @foreach($releve->matieres as $matiere)
                            @if( ($matiere->type==1))
                                <?php $sNote+= ($matiere->note*$matiere->credit);$sCoef+=$matiere->credit;$TMP++; ?>
                                @if($matiere->note>=50)
                                    <?php $TMV++; ?>
                                @endif
                                <td>
                                    {{$matiere->altName}}
                                </td>
                                <td>{{getSemestre($matiere->semestre)}}</td>
                               <td>{{$matiere->credit}}</td>
                                <td>{{$matiere->note}}</td>
                                <td>{{getGrade($matiere->note)[1]}}</td>
                                <td>{{getGrade($matiere->note)[0]}}</td>
                                </tr>
                            @endif
                    @endforeach

                    <tr>
                        <td colspan="2"><strong style="font-size: 12px;margin-left: 80px;text-align: center;">Unités d'enseignements Professionnelles\<span class="traduction">Professional</span></strong></td>
                    </tr>
                    @foreach($releve->matieres as $matiere)
                        @if(($matiere->type==2))
                            <?php $sNote+= ($matiere->note*$matiere->credit) ;$sCoef+=$matiere->credit;$TMP++; ?>
                            @if($matiere->note>=50)
                                <?php $TMV++; ?>
                            @endif
                            <td>
                                {{$matiere->altName}}

                            </td>
                            <td>{{getSemestre($matiere->semestre)}}</td>
                            <td>{{$matiere->credit}}</td>
                            <td>{{$matiere->note}}</td>
                            <td>{{getGrade($matiere->note)[1]}}</td>
                            <td>{{getGrade($matiere->note)[0]}}</td>
                            </tr>
                        @endif
                    @endforeach


                    </tbody>
                </table>

               

<table class="table result-table table-striped table-condensed table-inverse" style="font-size: 11px;margin-top: 0;">
<thead>
    <th colspan="2">Travail annuel</th>
    <th>T.M.P</th>
    <th>T.M.V</th>
    <th>Moyenne</th>
    <th>MGP</th>
    <th>Cote</th>
    <th colspan="2">Mention</th>
</thead>
<tbody style="text-align: center">
<tr>
    <?php $moy=$sNote/$sCoef ?>
    <td rowspan="2">Moy.Annuelle/<span class="traduction">Annual Av.</span></td>
    <td>/ 100 </td>
    <td rowspan="2">{{$TMP}}</td>
    <td rowspan="2">{{$TMV}}</td>
    <td>{{round($moy,2)}}</td>
    <td rowspan="2">{{round($moy/25,2)}}</td>
    <td rowspan="2">{{getGrade($moy)[0]}}</td>
    <td colspan="2">{{getMention($moy,100)}}</td>

</tr>
<tr>
    <td>/ 20</td>
    <td>{{round($moy/5,2)}}</td>
    <td>Decision :</td>
    <td><strong style="font-size: 13px">{{getDecision(round($moy/25,2))}}</strong></td>
</tr>
<tr>
    <td colspan="9">
        <strong class="traduction">T.M.P: </strong> <em class="traduction">Total des modules prévus</em>
        &emsp;
        <strong class="traduction">T.M.V: </strong> <em class="traduction">Total des modules Validés</em>

    </td>


</tr>
    
</tbody>




             </table>

            </div>

 


            <div class="sign-footer">
                <div class="jury">
                  <p><strong class="traduction"></strong></p>
                  <P></P>
                    <p><strong><u>Le Responsable des Affaires Accadémiques</u></strong></p>
                  
                </div>
                <div class="dep">
                <p></p>
                    <p><strong>YAOUNDE Le ..........................</strong></p>
                    <p><u><strong>Le Directeur</strong></u></p>
                </div>
            </div>
            <div class="releve-fait-le" style="font-size: 10px;display: none">
                <p>Fait le {{date('d-m-Y')}}</p>
            </div>
            @if($ne>0)
            <div class="copy col-md-12" >
                &copy;  2017 SchoolConcept | by: <a href="http://youth-concept.com" style="color:#fff;" target="_blank">youth-concept</a>
            </div>
            @endif
        </div>
        <?php $ne++; ?>
    @endforeach

@endsection