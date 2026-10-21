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
         $sNote1=0;
         $sCoef1=0;

        ?>
        <div class="releve">
            @include('perso.header1')
           
            <div class="content">

                <div class="titre">
                    <p style="font-size: 22px;"><strong> ANNUAL TRANSCRIPT  </strong></p>
                    <strong>
                        <p class="big-traduction"><strong>RELEVE DE NOTES ANNUEL</strong></p>
                    </strong>
                </div>
                <div class="nom-matricule-container">
                    <div class="nom">
                        <p><strong>  Surname and name  : </strong>{{$releve->eleve->nom.' '.$releve->eleve->prenom}}</p>
                        <p><strong class="traduction" style="color: red;">
                               Nom et Prénoms
                            </strong></p>
                    </div>
                    <div class="matricule">
                        <p><strong>  Registration N° : </strong>{{$releve->eleve->matricule}}</p>
                        <p><strong class="traduction">
                              Matricule 
                            </strong></p>
                    </div>
                </div>
                <div class="date-lieu-container">
                    <div class="date">
                        <p><strong> Born on : </strong> {{(new DateTime($releve->eleve->date_naiss))->format('d/m/Y')}}</p>
                        <p><strong class="traduction">Né(e) le</strong></p>
                    </div>
                    <div class="lieu">
                        <p><strong> At:</strong> {{$releve->eleve->lieu_naiss}}</p>
                        <p><strong class="traduction">A  </strong></p>
                    </div>
                </div>
                <div class="cycle-filiere">
                    <div class="cycle">
                        <p><strong>Cycle : </strong> {{$cycle->nom}}</p>
                        <p><strong class="traduction">Cycle : </strong></p>
                    </div>
                    <div class="filiere">
                        <p><strong>Field : </strong> {{$niveau->nom}}</p>
                        <p class="traduction"><strong class="traduction">Filière   : </strong></p>
                    </div>

                </div>

                <div class="specialite-annee">
                    <div class="specialite">
                        <p><strong>Spéciality : </strong> {{$classe->nom}} <strong style="margin-left:220px">-Level  : </strong>{{$nniveau->nom}}</p>
                        <p class="traduction"><strong class="traduction"> Spécialité :  <strong class="traduction" style="margin-left:220px"> Niveau                            </strong> </p>
                    </div>
                    <div class="annee_a">
                        <p><strong> Academic year : </strong> {{session('year')}}</p>
                        <p><strong class="traduction">Année académique: </strong></p>
                    </div>
                </div>
               <table class="table  table-striped table-condensed table-inverse" style="margin-top: 15px; margin-bottom: 10px;">
                    <thead style="text-align: center;">
                   
                    <th ><strong> Course title /</strong> <span class="traduction"></span>Intitulé de l'UE</th>
                     <th>Codes</th>
                    <th class="note-column"><strong>Semester  <span class="traduction">Semestre</span></strong>  </th>
                
                    <th class="note-column"><strong>Note /20</strong>  </th>
                  <!--  <th class="note-column"><strong>Qualité /4</strong>  </th>-->  <th class="note-column"><strong>Notation</strong> <span class="traduction">Grade</span></th>
                      <th class="note-column"><strong>Crédit</strong></th>

                    </thead>
                    <tbody>
                    <tr>
                        <td colspan="2"><strong style="font-size: 12px;margin-left: 80px;text-align: center;"> Fundamental courses \<span class="traduction">Unités d'enseignements Fondamentales</span></strong></td>
                    </tr>
                    @foreach($releve->matieres as $matiere)
                            @if(($matiere->type==0))
                                <?php $sNote+= ($matiere->note*$matiere->credit) ;$sCoef+=$matiere->credit;$TMP++; ?>
                                @if($matiere->note>=50)
                                    <?php $TMV++; ?>
                                @endif
                                 <td> {{$matiere->nom_code}}</td>
                                
                            <td style="text-align: center">
                                    {{$matiere->altName}}

                            </td>
                             
                            <td style="text-align: center";>{{getSemestre($matiere->semestre)}}</td>
                             <td style="text-align: center">{{round($matiere->note/5,2)}} </td>
                          
                            <td style="text-align: center">{{getGrade($matiere->note)[0]}}</td>
                             <td style="text-align: center">{{$matiere->credit}}</td>

                        </tr>
                        
                            @endif
                          

                    @endforeach
                        <?php $moy=$sNote/$sCoef ?>
                        <tr>
                         <td colspan="3">Average/Moyenne :</td> <td colspan="3">{{round($moy/5,2)}}</td>
                       
                        </tr>

                    <tr>
                        <td colspan="2"><strong style="font-size: 12px;margin-left: 80px;text-align: center;">Unités d'enseignement Transversales\<span class="traduction">Common courses</span></strong></td>
                    </tr>
                    @foreach($releve->matieres as $matiere)
                            @if( ($matiere->type==1))
                                <?php $sNote+= ($matiere->note*$matiere->credit);$sCoef+=$matiere->credit;$TMP++; ?>
                                @if($matiere->note>=50)
                                    <?php $TMV++; ?>
                                @endif
                                   <td> {{$matiere->nom_code}}</td>
                                <td style="text-align: center">
                                    {{$matiere->altName}}
                                </td>
                                <td style="text-align: center">{{getSemestre($matiere->semestre)}}</td>
                              
                                 <td style="text-align: center">{{round($matiere->note/5,2)}} </td>
                               <!-- <td>{{$matiere->note}}</td>-->
                                <td style="text-align: center">{{getGrade($matiere->note)[1]}}</td>
                                 <td style="text-align: center">{{$matiere->credit}}</td>
                               <!-- <td style="text-align: center">{{getGrade($matiere->note)[0]}}</td>-->
                                </tr>
                            @endif
                           

                    @endforeach
                      <?php $moy=$sNote/$sCoef ?>
                        <tr>
                         <td colspan="3"> Moyenne/Average :</td> <td colspan="3">{{round($moy/5,2)}}</td>
                       
                        </tr>

                    <tr>
                        <td colspan="2"><strong style="font-size: 12px;margin-left: 80px;text-align: center;">Professional courses  \<span class="traduction">Unités d'enseignements Professionnelles</span></strong></td>
                    </tr>
                    @foreach($releve->matieres as $matiere)
                        @if(($matiere->type==2))
                            <?php $sNote+= ($matiere->note*$matiere->credit) ;$sCoef+=$matiere->credit;$TMP++;  ?>
                            @if($matiere->note>=50)
                                <?php $TMV++; ?>
                            @endif
                               <td> {{$matiere->nom_code}}</td>
                            <td>
                                {{$matiere->altName}}

                            </td>
                            <td style="text-align: center">{{getSemestre($matiere->semestre)}}</td>
                            <td style="text-align: center">{{$matiere->credit}}</td>
                            <td style="text-align: center">{{round($matiere->note/5,2)}} </td>
                            <td style="text-align: center">{{getGrade($matiere->note)[1]}}</td>
                           <!-- <td style="text-align: center">{{getGrade($matiere->note)[0]}}</td>-->
                            </tr>
                        @endif
                        

                    @endforeach
                     <?php $moy=$sNote/$sCoef ?>
                        <tr>
                         <td colspan="3">Average /Moyenne :</td> <td colspan="3">{{round($moy/5,2)}}</td>
                       
                        </tr>


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
    <td rowspan="2">Annual Av./<span class="traduction">Moy.Annuelle </span></td>
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