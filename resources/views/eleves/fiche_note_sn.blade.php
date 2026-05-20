@extends('skeleton')
@section('sub-content')
    @include('perso.functions')

    <style>
        .result-table thead,.result-table thead th
        {
            height: auto;
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
        $creditsV=0;
        $creditsN=0;
        $sNote=0;
        $subSnote=0;
        $subScoef1=0;
        $subScoef2=0;
        $subScoef3=0;
        $subScoef=0;
        $credits=0;
        $sc=0;
        $sc1=0;
        $sc2=0;
        $sc3=0;
        $sc4=0;
        $sc5=0;
        $sc6=0;
        $sc7=0;
        ?>
        <div class="releve">
            @include('perso.header_bts')

            <div class="content">

                <div class="titre">
                    <p style="font-size: 18px;"><strong>RELEVE DE NOTES DES EXAMENS FIN DE SEMESTRE {{$semestre}} </strong></p>
                    <strong>
                        <p class="big-traduction"><strong> </strong></p>
                    </strong>
                </div>
                <div class="nom-matricule-container">
                    <div class="nom">
                        <p><strong>Nom et Prénoms / Surname and name : </strong>{{$releve->eleve->nom.' '.$releve->eleve->prenom}}</p>
                    </div>
                    <div class="matricule">
                        <p><strong>Matricule : </strong>{{$releve->eleve->matricule}}</p>

                    </div>
                </div>
                <div class="date-lieu-container">
                    <div class="date">
                        <p><strong>Né(e) le / Born on: </strong> {{(new DateTime($releve->eleve->date_naiss))->format('d/m/Y')}}</p>
                    </div>
                    <div class="lieu">
                        <p><strong>A / At :</strong> {{$releve->eleve->lieu_naiss}}</p>
                    </div>
                </div>
                <div class="cycle-filiere">
                    <div class="cycle">
                        <p><strong>Cycle : </strong> {{$cycle->nom}}</p>
                    </div>
                    <div class="filiere">
                        <p><strong>Filière/ Field : </strong> {{$niveau->nom}}</p>
                    </div>

                </div>

                <div class="specialite-annee">
                    <div class="specialite">
                        <p><strong>Spécialité/ Speciality : </strong> {{$classe->nom}} </p>
                        <p><strong>- Niveau/ level : </strong>{{$nniveau->nom}} </p>
                    </div>
                    <div class="annee_a">
                        <p><strong>Année académique/ Academic Year : </strong> {{session('year')}}</p>

                    </div>
                </div>
                <table class="table  table-striped table-condensed table-inverse" style="margin-top: auto; margin-bottom: 1px;">
                    <thead>
                    <th>Codes</th>
                    <th ><strong>Intitulé de l'UE/</strong> <span class="big-traductio">Course title</span></th>

                    <th class="note-column"><strong>Semestre <span class="big-traductio">Semester</span></strong>  </th>
                    <th class="note-column"><strong>Crédits prévus </strong>  </th>
                    <!-- <th class="note-column"><strong>Note /100</strong>  </th>-->
                    <th class="note-column"><strong>Note /20</strong>  </th>
                    <th class="note-column"><strong>Crédits validés</strong>  </th>
                    <th class="note-column"><strong>Notation</strong> <span class="big-traductio">Grade</span></th>
                    <th class="note-column"><strong>Observations</strong></th>

                    </thead>
                    <tbody>
                    <tr>
                        <td colspan="8"><strong style="font-size: 12px;">Unités d'enseignement Fondamentales\<span class="big-traductio">Common courses</span></strong></td>
                    </tr>

                    @foreach($releve->matieres as $matiere)
                        @if(($matiere->type==0))
                            <?php $sNote+= ($matiere->note*$matiere->credit);$subSnote+=$matiere->note*$matiere->credit; $sCoef+=$matiere->credit; $subScoef+=$matiere->credit;$TMP++; ?>
                            @if($matiere->note>=50)
                                <?php $TMV++;  $creditsV++; $subScoef1+=$matiere->credit ?>
                            @endif
                            @if($matiere->note<50)
                                <?php   $creditsN++; $subScoef2+=$matiere->credit ?>
                            @endif
                            <td> {{$matiere->id_code}}</td>

                            <td> {{$matiere->altName}}</td>
                            <td style="text-align: center">{{getSemestre($matiere->semestre)}}</td>
                            <td style="text-align: center">{{$matiere->credit}}</td>
                            <!--  <td>{{$matiere->note}}</td>-->
                            <td style="text-align: center">{{round($matiere->note/5,6)}} </td>
                            @if($matiere->note>=50)
                                <td style="text-align: center">{{$matiere->credit}}</td>
                            @else
                                <td style="text-align: center">0</td>
                            @endif
                            <td style="text-align: center">{{getGrade($matiere->note)[0]}}</td>

                            <td style="text-align: center">{{getMention($matiere->note,100)}}</td>

                            </tr>

                        @endif
                    @endforeach
                    <tr >
                        <td colspan="2"></td>
                        <td style="text-align: center;" >
                            <strong> Moy/Av </strong>
                        </td>
                        <td style="text-align: center;">{{$subScoef}}</td>
                        @if($subScoef!=null)
                            <td  style="text-align: center;">

                                <strong>{{ round(($subSnote/$subScoef)/5,6) }} </strong>
                            </td>
                            <td style="text-align: center"> {{$subScoef1}}</td>
                        <!-- <td style="text-align: center">{{getGrade($subSnote/$subScoef)[1]}}</td>-->
                            <td  style="text-align: center;">
                                <strong > {{getGrade(($subSnote/$subScoef))[0]}}  </strong>
                            </td>
                            <td style="text-align: center">{{getMention($subSnote/$subScoef,100)}}</td>
                        @else
                            <td> <strong>-</strong></td>
                        @endif
                    </tr>

                    <?php

                    $subSnote=0;
                    $subScoef=0;
                    $subScoef1=0;
                    $subScoef2=0;
                    ?>


                    <tr>
                        <td colspan="8"><strong style="font-size: 12px;">Unités d'enseignement Transversales\<span class="big-traductio">Common courses</span></strong></td>
                    </tr>
                    @foreach($releve->matieres as $matiere)
                        @if( ($matiere->type==1))
                            <?php $sNote+= ($matiere->note*$matiere->credit);$subSnote+=$matiere->note*$matiere->credit; $sCoef+=$matiere->credit;$subScoef+=$matiere->credit;$TMP++; ?>
                            @if($matiere->note>=50)
                                <?php $TMV++;  $creditsV++; $subScoef1+=$matiere->credit ?>
                            @endif
                            @if($matiere->note<50)
                                <?php   $creditsN++; $subScoef2+=$matiere->credit ?>
                            @endif
                            <td> {{$matiere->id_code}}</td>

                            <td> {{$matiere->altName}}</td>
                            <td style="text-align: center">{{getSemestre($matiere->semestre)}}</td>
                            <td style="text-align: center">{{$matiere->credit}}</td>
                            <!--  <td>{{$matiere->note}}</td>-->
                            <td style="text-align: center">{{round($matiere->note/5,6)}} </td>
                            @if($matiere->note>=50)
                                <td style="text-align: center">{{$matiere->credit}}</td>
                            @else
                                <td style="text-align: center">0</td>
                            @endif
                            <td style="text-align: center">{{getGrade($matiere->note)[0]}}</td>

                            <td style="text-align: center">{{getMention($matiere->note,100)}}</td>
                            </tr>

                        @endif
                    @endforeach
                    <tr >
                        <td colspan="2"></td>
                        <td style="text-align: center;" >
                            <strong> Moy/Av </strong>
                        </td>

                        <td style="text-align: center;">{{$subScoef}}</td>
                        @if($subScoef!=null)
                            <td  style="text-align: center;">
                                <strong>{{ round(($subSnote/$subScoef)/5,6) }} </strong>
                            </td>
                            < !--  <td style="text-align: center">{{getGrade($subSnote/$subScoef)[1]}}</td> -->
                            <td style="text-align: center"> {{$subScoef1}}</td>
                            <td  style="text-align: center;">
                                <strong > {{getGrade(($subSnote/$subScoef))[0]}}  </strong>
                            </td>
                            <td style="text-align: center">{{getMention($subSnote/$subScoef,100)}}</td>
                        @else
                            <td> <strong>-</strong></td>
                        @endif
                    </tr>


                    <?php $sc2+=$subScoef1; $sc5+=$subScoef2 ?>

                    <?php

                    $subSnote=0;
                    $subScoef=0;
                    $subScoef1=0;
                    $subScoef2=0;
                    ?>
                    <tr>
                        <td colspan="8"><strong style="font-size: 12px;">Unités d'enseignements Professionnelles\<span class="big-traductio">Professional courses</span></strong></td>

                    </tr>
                    @foreach($releve->matieres as $matiere)
                        @if(($matiere->type==2))
                            <?php $sNote+= ($matiere->note*$matiere->credit);$subSnote+=$matiere->note*$matiere->credit; $sCoef+=$matiere->credit;$subScoef+=$matiere->credit;$TMP++; ?>
                            @if($matiere->note>=50)
                                <?php $TMV++;  $creditsV++; $subScoef1+=$matiere->credit ?>
                            @endif
                            @if($matiere->note<50)
                                <?php   $creditsN++; $subScoef2+=$matiere->credit ?>
                            @endif
                            <td> {{$matiere->id_code}}</td>

                            <td> {{$matiere->altName}}</td>
                            <td style="text-align: center">{{getSemestre($matiere->semestre)}}</td>
                            <td style="text-align: center">{{$matiere->credit}}</td>
                            <!--  <td>{{$matiere->note}}</td>-->
                            <td style="text-align: center">{{round($matiere->note/5,6)}} </td>
                            @if($matiere->note>=50)
                                <td style="text-align: center">{{$matiere->credit}}</td>
                            @else
                                <td style="text-align: center">0</td>
                                <td style="text-align: center">{{getGrade($matiere->note)[0]}}</td>
                            @endif
                            <td style="text-align: center">{{getMention($matiere->note,100)}}</td>
                            </tr>



                        @endif

                    @endforeach
                    <tr >
                        <td colspan="2"></td>
                        <td style="text-align: center;" >
                            <strong> Moy/Av </strong>
                        </td>
                        <td style="text-align: center;">{{$subScoef}}</td>
                        @if($subScoef!=null)
                            <td  style="text-align: center;">
                                <strong>{{ round(($subSnote/$subScoef)/5,6) }} </strong>
                            </td>
                            <td style="text-align: center">{{$subScoef1}}</td>
                        <!-- <td style="text-align: center">{{getGrade($subSnote/$subScoef)[1]}}</td> -->
                            <td  style="text-align: center;">
                                <strong > {{getGrade(($subSnote/$subScoef))[0]}}  </strong>
                            </td>
                            <td style="text-align: center">{{getMention($subSnote/$subScoef,100)}}</td>
                        @else
                            <td><strong>-</strong></td>
                    @endif
                    <tr>



                    <tr>
                        <?php $sc3+=$subScoef1; $sc6+=$subScoef2;  ?>


                        <td colspan="1" style="text-align: center;"><strong>Total des crédits validés</strong></td>
                        <?php  $sc=$sc1+$sc2+$sc3; $sc7=$sc4+$sc5+$sc6;?>
                        <td colspan="2" style="text-align: center;">{{$sc}}</td>
                        <td colspan="3" style="text-align:center;"><strong>Total des crédits non validés</strong></td>
                        <td colspan="2" style="text-align: center;">{{$sc7}}</td>
                    </tr>

                    </tbody>
                </table>



                <table class="table result-table table-striped table-condensed table-inverse" style="font-size: 11px;margin-top: auto;">
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
                        <?php
                        if ($sCoef!=null)
                        {
                            $moy=$sNote/$sCoef;
                        }else
                            $moy="-";
                        ?>
                        <td rowspan="2">Moy.Annuelle/<span class="big-traductio">Annual Av.</span></td>
                        <td>/ 100 </td>
                        <td rowspan="2">{{$TMP}}</td>
                        <td rowspan="2">{{$TMV}}</td>
                        <td>{{round($moy,6)}}</td>
                        <td rowspan="2">{{round($moy/25,6)}}</td>
                        <td rowspan="2">{{getGrade($moy)[0]}}</td>
                        <td colspan="2">{{getMention($moy,100)}}</td>


                    </tr>
                    <tr>
                        <td>/ 20</td>
                        <td>{{round($moy/5,6)}}</td>
                        <td>Decision :</td>
                        @if($TMP==$TMV)
                            <td><strong style="font-size: 13px">Admis(e)</strong></td>
                        @else
                            <td><strong style="font-size: 13px">Echoué(e)</strong></td>
                        @endif
                    </tr>
                    <tr>
                        <td colspan="9">
                            <strong class="big-traductio">T.M.P: </strong> <em class="big-traductio">Total des modules prévus</em>
                            &emsp;
                            <strong class="big-traductio">T.M.V: </strong> <em class="big-traductio">Total des modules Validés</em>


                        </td>
                    </tr>
                    <tr> <td colspan="9" style="text-align: center;"><(Note: A+(90-100)=Excellent; A(80-89)=Très Bien; A-(75-79)=Bien; B(65-69)=Assez Bien; B-(60-64)=Passable; C+(55-59)=Passable; C(50-54)=Passable; C-(45-49)=Médiocre; D+(40-44)=Faible; D(35-39);E(30-34)=Echec; F(0-29)=Echec</td></tr>



                    </tbody>



                </table>

            </div>




            <div class="sign-footer">
                <p style=""><strong>YAOUNDE Le ..........................</strong></p>
                <p><u><strong>Le Directeur de l'ISH</strong></u></p>

            </div>
            {{-- <div class="releve-fait-le" style="font-size: 10px;display: none">
                 <p>Fait le {{date('d-m-Y')}}</p>
             </div>--}}
            @if($ne>0)
                <div class="copy col-md-12" >
                    &copy;  2017 SchoolConcept | by: <a href="http://youth-concept.com" style="color:#fff;" target="_blank">youth-concept</a>
                </div>
            @endif
        </div>
        <?php $ne++; ?>
    @endforeach


@endsection