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
        $sNote=0;
        $subSnote=0;
        $subScoef1=0;
        $subScoef2=0;
        $subScoef3=0;
        $subScoef=0;
        $credits=0;
        ?>
        <div class="releve">
            @include('perso.header_bts')

            <div class="content">

                <div class="titre">
                    <p style="font-size: 18px;"><strong>LISTE DES MATIERES A RATTRAPPER DES EXAMENS FIN DE SEMESTRE {{$semestre}} </strong></p>
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
                    <th class="note-column"><strong>Crédits </strong>  </th>
                    <!-- <th class="note-column"><strong>Note /100</strong>  </th>-->
                    <th class="note-column"><strong>Note /20</strong>  </th>
                    <th class="note-column"><strong>Observations</strong></th>

                    </thead>
                    <tbody>
                    <tr>
                        <td colspan="8"><strong style="font-size: 12px;">Unités d'enseignement Fondamentales\<span class="big-traductio">Common courses</span></strong></td>
                    </tr>

                    @foreach($releve->matieres as $matiere)
                        @if(($matiere->type==0 && $matiere->note<50))
                            <?php $sNote+= ($matiere->note*$matiere->credit);$subSnote+=$matiere->note*$matiere->credit; $sCoef+=$matiere->credit; $subScoef+=$matiere->credit;$TMP++; ?>
                            @if($matiere->note>=50)
                                <?php $TMV++; ?>
                            @endif
                            <td> {{$matiere->id_code}}</td>

                            <td> {{$matiere->altName}}</td>
                            <td style="text-align: center">{{getSemestre($matiere->semestre)}}</td>
                            <td style="text-align: center">{{$matiere->credit}}</td>
                            <!--  <td>{{$matiere->note}}</td>-->
                            <td style="text-align: center">{{round($matiere->note/5,6)}} </td>

                            <td style="text-align: center">{{getMention($matiere->note,100)}}</td>

                            </tr>

                        @endif
                    @endforeach



                    <tr>
                        <td colspan="8"><strong style="font-size: 12px;">Unités d'enseignement Transversales\<span class="big-traductio">Common courses</span></strong></td>
                    </tr>
                    @foreach($releve->matieres as $matiere)
                        @if( ($matiere->type==1 && $matiere->note<50))
                            <?php $sNote+= ($matiere->note*$matiere->credit);$subSnote+=$matiere->note*$matiere->credit; $sCoef+=$matiere->credit;$subScoef+=$matiere->credit;$TMP++; ?>
                            @if($matiere->note>=50)
                                <?php $TMV++; ?>
                            @endif
                            <td> {{$matiere->id_code}}</td>

                            <td> {{$matiere->altName}}</td>
                            <td style="text-align: center">{{getSemestre($matiere->semestre)}}</td>
                            <td style="text-align: center">{{$matiere->credit}}</td>
                            <!-- <td>{{$matiere->note}}</td>-->
                            <td style="text-align: center">{{round($matiere->note/5,6)}} </td>
                            <td style="text-align: center">{{getMention($matiere->note,100)}}</td>
                            </tr>

                        @endif
                    @endforeach

                    <tr>
                        <td colspan="8"><strong style="font-size: 12px;">Unités d'enseignements Professionnelles\<span class="big-traductio">Professional courses</span></strong></td>

                    </tr>
                    <tr>
                    @foreach($releve->matieres as $matiere)
                        @if(($matiere->type==2 && $matiere->note<50))
                            <?php $sNote+= ($matiere->note*$matiere->credit);$subSnote+=$matiere->note*$matiere->credit; $sCoef+=$matiere->credit;$subScoef+=$matiere->credit;$TMP++; ?>
                            @if($matiere->note>=50)
                                <?php $TMV++; ?>
                            @endif
                            <td> {{$matiere->id_code}}</td>

                            <td> {{$matiere->altName}}</td>
                            <td style="text-align: center">{{getSemestre($matiere->semestre)}}</td>
                            <td style="text-align: center">{{$matiere->credit}}</td>
                            <!-- <td>{{$matiere->note}}</td>-->
                            <td style="text-align: center">{{round($matiere->note/5,6)}} </td>
                            <td style="text-align: center">{{getMention($matiere->note,100)}}</td>
                            </tr>



                        @endif

                    @endforeach

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